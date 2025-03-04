<?php

namespace VantomDev\SmsMisr\Contracts;

interface SmsServiceInterface
{
    /**
     * Send OTP
     *
     * @param string $mobile
     * @param string $otp
     * @return array
     */
    public function sendOtp(string $mobile, string $otp): array;

    /**
     * Send SMS
     *
     * @param string $mobile
     * @param string $message
     * @param int $language
     * @return array
     */
    public function sendSms(string $mobile, string $message, int $language = 1): array;
}
