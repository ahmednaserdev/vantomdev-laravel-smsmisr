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
    protected string $username;
    protected string $password;
    protected string $sender;
    protected string $environment;
    protected Client $client;

    /**
     * SmsMisrService Constructor
     *
     * @param Client $client
     * @param string $username
     * @param string $password
     * @param string $sender
     * @param string $environment
     */
    public function __construct(Client $client, string $username, string $password, string $sender, string $environment)
    {
        $this->username    = $username;
        $this->password    = $password;
        $this->sender      = $sender;
        $this->environment = $environment;
        $this->client      = $client;
    }

    /**
     * Send OTP
     *
     * @param string $mobile
     * @param string $template
     * @param string $otp
     * @return array
     * @throws SmsMisrException
     */
    public function sendOtp(string $mobile, string $template, string $otp): array
    {
        // Check for request limit
        if ($this->isRateLimited($mobile)) {
            throw new SmsMisrException('Too many requests. Please try again after 5 minutes.');
        }

        try {
            $response = $this->client->post('OTP/', [
                'form_params' => [
                    'environment' => $this->environment,
                    'username'    => $this->username,
                    'password'    => $this->password,
                    'sender'      => $this->sender,
                    'mobile'      => $mobile,
                    'template'    => $template,
                    'otp'         => $otp,
                ],
            ]);

            // Increment request counter
            $this->incrementRequestCount($mobile);

            return $this->processResponse($response);
        } catch (RequestException $e) {
            Log::error('SmsMisr OTP Request Failed: ' . $e->getMessage());
            throw new SmsMisrException('Failed to send OTP: ' . $e->getMessage());
        }
    }

    /**
     * Send SMS
     *
     * @param string $mobile
     * @param string $message
     * @param int $language
     * @return array
     * @throws SmsMisrException
     */
    public function sendSms(string $mobile, string $message, int $language = 1): array
    {
        // Check for request limit
        if ($this->isRateLimited($mobile)) {
            throw new SmsMisrException('Too many requests. Please try again after 5 minutes.');
        }

        try {
            $response = $this->client->post('SMS/', [
                'form_params' => [
                    'environment' => $this->environment,
                    'username'    => $this->username,
                    'password'    => $this->password,
                    'sender'      => $this->sender,
                    'mobile'      => $mobile,
                    'language'    => $language,
                    'message'     => $message,
                ],
            ]);

            // Increment request counter
            $this->incrementRequestCount($mobile);

            return $this->processResponse($response);
        } catch (RequestException $e) {
            Log::error('SmsMisr SMS Request Failed: ' . $e->getMessage());
            throw new SmsMisrException('Failed to send SMS: ' . $e->getMessage());
        }
    }

    /**
     * Process API Response
     *
     * @param ResponseInterface $response
     * @return array
     * @throws SmsMisrException
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
 *
 * @param string $mobile
 * @return bool
 * @throws SmsMisrException
 */
    private function isRateLimited(string $mobile): bool
    {
        $key          = 'smsmisr_rate_limit_' . $mobile;
        $requestCount = Cache::get($key, 0);

        // If more than 3 requests in a minute, block for 5 minutes
        if ($requestCount >= 3) {
            Cache::put($key, $requestCount, now()->addMinutes(5));

            // Throw Exception with translated message
            throw new SmsMisrException(__('smsmisr::messages.rate_limited'));
        }

        return false;
    }

    /**
     * Increment the request counter for the mobile number
     *
     * @param string $mobile
     * @return void
     */
    private function incrementRequestCount(string $mobile): void
    {
        $key          = 'smsmisr_rate_limit_' . $mobile;
        $requestCount = Cache::get($key, 0);

        // Increment counter and reset after 1 minute
        Cache::put($key, $requestCount + 1, now()->addMinute());
    }
}
