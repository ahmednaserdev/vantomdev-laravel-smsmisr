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
                    'base_uri' => 'https://smsmisr.com/api/',
                    'timeout'  => 5.0, // Set request timeout to 5 seconds
                ]),
                config('smsmisr.username'),
                config('smsmisr.password'),
                config('smsmisr.sender'),
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
     * This method publishes the package configuration file
     * to the application's config directory.
     * It ensures the config is only published when running in the console.
     *
     * @return void
     */
/**
 * Bootstrap services.
 *
 * @return void
 */
    public function boot(): void
    {
        // Publish configuration file when running artisan commands
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/smsmisr.php' => config_path('smsmisr.php'),
            ], 'config');

            // Publish translation files
            $this->publishes([
                __DIR__ . '/../resources/lang' => resource_path('lang/vendor/smsmisr'),
            ], 'lang');
        }

        // Load package translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'smsmisr');
    }

}
