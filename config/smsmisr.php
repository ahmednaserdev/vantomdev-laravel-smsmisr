<?php

return [
    // Base URLs for different services
    'base_url_otp' => env('SMSMISR_BASE_URL_OTP', 'https://smsmisr.com/api/OTP/'),
    'base_url_sms' => env('SMSMISR_BASE_URL_SMS', 'https://smsmisr.com/api/SMS/'),
    'username' => env('SMSMISR_USERNAME', 'your_username'),
    'password' => env('SMSMISR_PASSWORD', 'your_password'),
    'sender' => env('SMSMISR_SENDER', 'your_sender'),
    'environment' => env('SMSMISR_ENV', '2'), // 1 for live, 2 for test

    // Default OTP template token (can be overridden in .env)
    'template_token' => env('SMSMISR_TEMPLATE_TOKEN', 'your_default_template_token'),

    // Enable or disable rate limiting (default: true)
    'enable_rate_limit' => env('SMSMISR_RATE_LIMIT', true),
    // Maximum number of messages per minute before blocking
    'max_requests_per_minute' => env('SMSMISR_MAX_REQUESTS', 3),
    // Block duration (in minutes) if the limit is exceeded
    'block_duration' => env('SMSMISR_BLOCK_DURATION', 5),

];
