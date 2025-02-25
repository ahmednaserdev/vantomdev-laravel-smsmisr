
# Laravel SmsMisr

A powerful Laravel package for integrating **SmsMisr** OTP and SMS APIs. This package supports multiple Laravel versions up to **12.x**, allowing easy and efficient management of SMS and OTP functionalities.

---

## Features

- **Send OTPs** to single mobile numbers using predefined templates.
- **Send SMS** to single or multiple mobile numbers.
- Support for **multiple languages** (English, Arabic, Unicode).
- Flexible configuration for **Live** and **Testing** environments.
- Compatible with **Laravel 8.x**, **9.x**, **10.x**, **11.x**, and **12.x**.

---

## Requirements

- PHP >= 8.1
- Laravel >= 8.x
- GuzzleHttp >= 7.5

---

## Installation

### Step 1: Install via Composer

```bash
composer require vantomdev/smsmisr
```

### Step 2: Publish the Configuration

```bash
php artisan vendor:publish --tag=config --provider="VantomDev\SmsMisr\SmsMisrServiceProvider"
```

### Step 3: Add Environment Variables

Add the following variables to your `.env` file:

```env
SMSMISR_USERNAME=your_username
SMSMISR_PASSWORD=your_password
SMSMISR_SENDER=your_sender
SMSMISR_ENV=2  # 1 for Live, 2 for Test
```

---

## Configuration

The configuration file will be published to:

```
config/smsmisr.php
```

You can customize the following options:

```php
return [
    'username' => env('SMSMISR_USERNAME', 'your_username'),
    'password' => env('SMSMISR_PASSWORD', 'your_password'),
    'sender' => env('SMSMISR_SENDER', 'your_sender'),
    'environment' => env('SMSMISR_ENV', '2'), // 1 for live, 2 for test
];
```

---

## Usage

### 1. Sending OTP

```php
use VantomDev\SmsMisr\Facades\SmsMisr;

$response = SmsMisr::sendOtp('2011XXXXXXX', 'template_token', '123456');
dd($response);
```

### 2. Sending SMS

You can send SMS to one or multiple numbers. To send SMS in a specific language:

- `1`: English
- `2`: Arabic
- `3`: Unicode

Example:

```php
$response = SmsMisr::sendSms('2011XXXXXXX', 'This is a test message.', 1);
dd($response);
```

### 3. Response Format

All responses are returned as an array:

```php
[
    "code" => "1901",
    "SMSID" => "12345",
    "Cost" => "1"
]
```

Or in case of an error:

```php
[
    "error" => "Error message here"
]
```

---

## Available Templates

1. **Your OTP is `XXXXXXXXXX`**
2. **Your One Time Password (OTP) is `XXXXXXXXXX`**
3. **OTP: `XXXXXXXXXX` Please use OTP to login to your account**
4. **OTP: `XXXXXXXXXX` Please use OTP to activate your account**
5. **Please don't share this (OTP) with anyone. `XXXXXXXXXX` is your OTP**

You can use the corresponding template token while sending OTPs.

---

## Response Codes

- `4901`: Success, Message Submitted Successfully
- `4903`: Invalid value in username or password field
- `4904`: Invalid value in "sender" field
- `4905`: Invalid value in "mobile" field
- `4906`: Insufficient Credit
- `4907`: Server under updating
- `4908`: Invalid OTP
- `4909`: Invalid Template Token
- `4912`: Invalid Environment

---

## Error Handling

All errors are caught and returned as an array:

```php
[
    "error" => "Detailed error message here"
]
```

You can handle them in your controller as follows:

```php
$response = SmsMisr::sendOtp('2011XXXXXXX', 'template_token', '123456');

if (isset($response['error'])) {
    // Handle error
    return back()->with('error', $response['error']);
} else {
    // Success
    return back()->with('success', 'OTP sent successfully!');
}
```

---

## Laravel Compatibility

- Laravel 8.x
- Laravel 9.x
- Laravel 10.x
- Laravel 11.x
- Laravel 12.x

This package is tested and verified to work seamlessly with all of these versions.

---

## Changelog

- **v1.0.0**: Initial release with support for OTP and SMS functionalities.

---

## Contributing

1. Fork the repository.
2. Create a new branch (`feature/your-feature-name`).
3. Make your changes and commit them (`git commit -m 'Add some feature'`).
4. Push to the branch (`git push origin feature/your-feature-name`).
5. Open a pull request.

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## Support

For any issues, please create an issue on the [GitHub Repository](https://github.com/vantomdev/laravel-smsmisr/issues).

---

## Author

Developed and maintained by **Ahmed Naser**.

Feel free to contribute, suggest enhancements, or report bugs!

---

## Acknowledgments

- Thanks to **SmsMisr** for providing the API services.
- Thanks to **Laravel** community for continuous support and improvements.

---

Happy Coding! 🚀
