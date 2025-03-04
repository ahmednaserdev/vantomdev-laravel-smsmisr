<?php

namespace VantomDev\SmsMisr;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use VantomDev\SmsMisr\Contracts\SmsServiceInterface;
use VantomDev\SmsMisr\Services\SmsMisrService;

class SmsMisrServiceProvider extends ServiceProvider
{
    /**
     * Register services in the container.
     *
     * This method binds the SmsServiceInterface to SmsMisrService,
     * allowing easy swapping of the implementation if needed.
     * It also merges the configuration file for customizability.
     *
     * @return void
     */
    public function register(): void
    {
        // Merge package configuration with the application's configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/smsmisr.php', 'smsmisr');

        // Bind SmsServiceInterface to SmsMisrService for Dependency Injection
        $this->app->bind(SmsServiceInterface::class, function ($app) {
            return new SmsMisrService(
                new Client([
                    'timeout' => 5.0, // Set request timeout to 5 seconds
                ]),
                config('smsmisr.base_url_otp'),
                config('smsmisr.base_url_sms'),
                config('smsmisr.username'),
                config('smsmisr.password'),
                config('smsmisr.sender'),
                config('smsmisr.template_token'),
                config('smsmisr.environment')
            );
        });

        // Register the service as a singleton to use the same instance throughout the application
        $this->app->singleton('smsmisr', function ($app) {
            return $app->make(SmsServiceInterface::class);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Ensure the package translation files are loaded
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'smsmisr');
    
        if ($this->app->runningInConsole()) {
            // Publish configuration file
            $this->publishes([
                __DIR__ . '/../config/smsmisr.php' => config_path('smsmisr.php'),
            ], 'config');
    
            // Publish translation files
            $this->publishes([
                __DIR__ . '/../resources/lang' => resource_path('lang/vendor/smsmisr'),
            ], 'lang');
        }
    }    
}
