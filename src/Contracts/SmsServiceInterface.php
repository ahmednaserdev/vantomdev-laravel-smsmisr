<?php

namespace VantomDev\SmsMisr\Contracts;

interface SmsServiceInterface
{
    public function sendOtp($mobile, $template, $otp);
    public function sendSms($mobile, $message, $language = 1);
}
