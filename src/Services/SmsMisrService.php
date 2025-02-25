<?php

namespace VantomDev\SmsMisr\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SmsMisrService
{
    protected $username;
    protected $password;
    protected $sender;
    protected $environment;
    protected $client;

    public function __construct($username, $password, $sender, $environment)
    {
        $this->username = $username;
        $this->password = $password;
        $this->sender = $sender;
        $this->environment = $environment;
        $this->client = new Client([
            'base_uri' => 'https://smsmisr.com/api/',
        ]);
    }

    public function sendOtp($mobile, $template, $otp)
    {
        try {
            $response = $this->client->post('OTP/', [
                'form_params' => [
                    'environment' => $this->environment,
                    'username' => $this->username,
                    'password' => $this->password,
                    'sender' => $this->sender,
                    'mobile' => $mobile,
                    'template' => $template,
                    'otp' => $otp
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public function sendSms($mobile, $message, $language = 1)
    {
        try {
            $response = $this->client->post('SMS/', [
                'form_params' => [
                    'environment' => $this->environment,
                    'username' => $this->username,
                    'password' => $this->password,
                    'sender' => $this->sender,
                    'mobile' => $mobile,
                    'language' => $language,
                    'message' => $message
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }
}
