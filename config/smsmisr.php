<?php

return [
    'username' => env('SMSMISR_USERNAME', 'your_username'),
    'password' => env('SMSMISR_PASSWORD', 'your_password'),
    'sender' => env('SMSMISR_SENDER', 'your_sender'),
    'environment' => env('SMSMISR_ENV', '2'), // 1 for live, 2 for test
];
