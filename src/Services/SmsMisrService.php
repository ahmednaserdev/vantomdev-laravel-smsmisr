<?php
namespace VantomDev\SmsMisr\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use VantomDev\SmsMisr\Contracts\SmsServiceInterface;
use VantomDev\SmsMisr\Exceptions\SmsMisrException;
use VantomDev\SmsMisr\Handlers\ResponseHandler;

class SmsMisrService implements SmsServiceInterface
{
    protected string $baseUrlOtp;
    protected string $baseUrlSms;
    protected string $username;
    protected string $password;
    protected string $sender;
    protected string $template;
    protected string $environment;
    protected Client $client;

    /**
     * SmsMisrService Constructor
     *
     * @param Client $client
     * @param string $baseUrlOtp
     * @param string $baseUrlSms
     * @param string $username
     * @param string $password
     * @param string $sender
     * @param string $template
     * @param string $environment
     */
    public function __construct(Client $client, string $baseUrlOtp, string $baseUrlSms, string $username, string $password, string $sender, string $template, string $environment)
    {
        $this->client      = $client;
        $this->baseUrlOtp  = $baseUrlOtp;
        $this->baseUrlSms  = $baseUrlSms;
        $this->username    = $username;
        $this->password    = $password;
        $this->sender      = $sender;
        $this->template    = $template;
        $this->environment = $environment;
    }

    /**
     * Send OTP
     */
    public function sendOtp(string $mobile, string $otp): array
    {
        if ($this->isRateLimited($mobile)) {
            throw new SmsMisrException('Too many requests. Please try again after 5 minutes.');
        }

        $response = $this->sendRequest($this->baseUrlOtp, [
            'mobile'   => $mobile,
            'template' => $this->template,
            'otp'      => $otp,
        ]);

        $this->incrementRequestCount($mobile);

        return $response;
    }

    /**
     * Send SMS
     */
    public function sendSms(string $mobile, string $message, int $language = 1): array
    {
        if ($this->isRateLimited($mobile)) {
            throw new SmsMisrException('Too many requests. Please try again after 5 minutes.');
        }

        $response = $this->sendRequest($this->baseUrlSms, [
            'mobile'   => $mobile,
            'language' => $language,
            'message'  => $message,
        ]);

        $this->incrementRequestCount($mobile);

        return $response;
    }

    /**
     * General function to send requests
     */
    private function sendRequest(string $url, array $extraParams): array
    {
        try {
            $params = array_merge([
                'environment' => $this->environment,
                'username'    => $this->username,
                'password'    => $this->password,
                'sender'      => $this->sender,
            ], $extraParams);

            $response = $this->client->post($url, [
                'form_params' => $params,
            ]);

            return $this->processResponse($response);
        } catch (RequestException $e) {
            Log::error("SmsMisr Request Failed: " . $e->getMessage());
            throw new SmsMisrException("Failed to send request: " . $e->getMessage());
        }
    }

    /**
     * Process API Response
     */
    private function processResponse(ResponseInterface $response): array
    {
        $content = $response->getBody()->getContents();
        $result  = ResponseHandler::handle($content);

        if (isset($result['error']) && $result['error']) {
            Log::warning('SmsMisr API Error: ' . json_encode($result));
            throw new SmsMisrException('API Error: ' . $result['message']);
        }

        return $result;
    }

    /**
     * Check if the mobile number is rate limited
     */
    private function isRateLimited(string $mobile): bool
    {
        if (! config('smsmisr.enable_rate_limit', true)) {
            return false;
        }

        $key           = 'smsmisr_rate_limit_' . $mobile;
        $requestCount  = Cache::get($key, 0);
        $maxRequests   = config('smsmisr.max_requests_per_minute', 3);
        $blockDuration = config('smsmisr.block_duration', 5);

        if ($requestCount >= $maxRequests) {
            Cache::put($key, $requestCount, now()->addMinutes($blockDuration));
            throw new SmsMisrException(__('smsmisr::messages.rate_limited'));
        }

        return false;
    }

    /**
     * Increment the request counter for the mobile number
     */
    private function incrementRequestCount(string $mobile): void
    {
        if (! config('smsmisr.enable_rate_limit', true)) {
            return;
        }

        $key           = 'smsmisr_rate_limit_' . $mobile;
        $requestCount  = Cache::get($key, 0);
        $cacheDuration = now()->addMinute();

        Cache::put($key, $requestCount + 1, $cacheDuration);
    }
}
