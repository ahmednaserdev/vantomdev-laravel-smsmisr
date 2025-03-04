# Laravel SmsMisr 🚀

A powerful Laravel package for integrating **SmsMisr** OTP and SMS APIs. This package supports multiple Laravel versions up to **12.x**, allowing easy and efficient management of SMS and OTP functionalities.

![Build Status](https://img.shields.io/github/actions/workflow/status/ahmednaserdev/vantomdev-laravel-smsmisr/test.yml?branch=main)
![Coverage](https://img.shields.io/codecov/c/github/ahmednaserdev/vantomdev-laravel-smsmisr)
![Packagist Version](https://img.shields.io/packagist/v/vantomdev/smsmisr)
![License](https://img.shields.io/packagist/l/vantomdev/smsmisr)

---

## 🌟 Features

- 🔑 **Send OTPs** to single mobile numbers using predefined templates.
- 📲 **Send SMS** to single or multiple mobile numbers.
- 🌐 **Multiple languages** support (English, Arabic, Unicode).
- 🔒 **Rate Limiting** to prevent abuse and reduce costs (Max 3 messages per minute, block for 5 minutes).
- 📘 **Detailed Localization Support** for multilingual websites.
- 🔄 **Flexible configuration** for Live and Testing environments.
- 🔧 **Customizable API URLs** for OTP and SMS requests.
- 💎 **Seamless integration** with Laravel 8.x to 12.x.

---

## ⚙️ Requirements

- PHP >= 8.1
- Laravel >= 8.x
- GuzzleHttp >= 7.5

---

## 🚀 Installation

### Step 1: Install via Composer

```bash
composer require vantomdev/smsmisr
```

### Step 2: Publish the Configuration

```bash
php artisan vendor:publish --tag=config --provider="VantomDev\SmsMisr\SmsMisrServiceProvider"
```

### Step 3: Publish Language Files

```bash
php artisan vendor:publish --tag=lang --provider="VantomDev\SmsMisr\SmsMisrServiceProvider"
```

### Step 4: Add Environment Variables

Add the following variables to your `.env` file:

```env
SMSMISR_USERNAME=your_username
SMSMISR_PASSWORD=your_password
SMSMISR_SENDER=your_sender
SMSMISR_ENV=2  # 1 for Live, 2 for Test

# API Base URLs
SMSMISR_BASE_URL_OTP=https://smsmisr.com/api/OTP/
SMSMISR_BASE_URL_SMS=https://smsmisr.com/api/SMS/

# Default Template Token
SMSMISR_TEMPLATE_TOKEN=your_default_template_token

# Rate Limiting (Optional)
SMSMISR_RATE_LIMIT=true
SMSMISR_MAX_REQUESTS=3
SMSMISR_BLOCK_DURATION=5
```

---

## ⏰ Rate Limiting

To prevent abuse and excessive costs, this package has built-in rate limiting.

- Maximum of **3 messages per minute** per mobile number.
- If exceeded, the user is **blocked for 5 minutes**.
- Error message displayed using localization:

```php
'Too many requests. Please try again after 5 minutes.'
```

You can customize this message in your language files or disable rate limiting by setting:

```php
'enable_rate_limit' => false,
```

in `config/smsmisr.php`.

---

## 🌐 Localization Support

This package supports multiple languages for error messages and notifications. Default languages included:

- English (`resources/lang/vendor/smsmisr/en/messages.php`)
- Arabic (`resources/lang/vendor/smsmisr/ar/messages.php`)

You can add more languages by creating new files under `resources/lang/vendor/smsmisr/{lang_code}/messages.php`.

Example:

```php
return [
    'rate_limited' => 'Too many requests. Please try again after 5 minutes.',
    'success' => 'OTP sent successfully!',
];
```

---

## 🔥 Usage Examples

### 1. Send OTP

```php
use VantomDev\SmsMisr\Facades\SmsMisr;
use VantomDev\SmsMisr\Exceptions\SmsMisrException;

try {
    $response = SmsMisr::sendOtp('2011XXXXXXX', '123456');
    return back()->with('success', __('smsmisr::messages.success'));
} catch (SmsMisrException $e) {
    return back()->with('error', $e->getMessage());
}
```

### 2. Send SMS

```php
use VantomDev\SmsMisr\Facades\SmsMisr;
use VantomDev\SmsMisr\Exceptions\SmsMisrException;

try {
    $response = SmsMisr::sendSms('2011XXXXXXX', 'This is a test message.', 1); # 1 For English , 2 For Arabic , 3 For Unicode
    return back()->with('success', __('smsmisr::messages.success'));
} catch (SmsMisrException $e) {
    return back()->with('error', $e->getMessage());
}
```

---

## 🎨 Changelog

### v1.2.0
- **Added Configurable API URLs**: `base_url_otp` and `base_url_sms` are now customizable.
- **Refactored ServiceProvider**: Improved Dependency Injection for better testability.
- **Improved Rate Limiting**: Now configurable from `config/smsmisr.php` or `.env`.

### v1.1.0
- **Added Rate Limiting**: Max 3 messages per minute, block for 5 minutes.
- **Enhanced Localization Support**: Multi-language support for error messages.
- **Refactored ServiceProvider**: Improved Dependency Injection and Interface Binding.
- **Custom Exceptions and Logging**: Enhanced error handling and logging mechanism.

### v1.0.0
- Initial release with OTP and SMS support.

---

## 🧑‍💻 Contributing

1. Fork the repository.
2. Create a new branch (`feature/your-feature-name`).
3. Make your changes and commit them (`git commit -m 'Add some feature'`).
4. Push to the branch (`git push origin feature/your-feature-name`).
5. Open a pull request.

---

## 📧 Support

For any issues, please create an issue on the [GitHub Repository](https://github.com/ahmednaserdev/vantomdev-laravel-smsmisr/issues).

---

## ❤️ Contributors

Thanks to all contributors for helping make this package better!

---

## ⚖️ License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🚀 Happy Coding!
