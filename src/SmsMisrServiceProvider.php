<?php

namespace VantomDev\SmsMisr;

use Illuminate\Support\ServiceProvider;
use VantomDev\SmsMisr\Services\SmsMisrService;

class SmsMisrServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/smsmisr.php', 'smsmisr');

        $this->app->singleton('smsmisr', function ($app) {
            return new SmsMisrService(
                config('smsmisr.username'),
                config('smsmisr.password'),
                config('smsmisr.sender'),
                config('smsmisr.environment')
            );
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/smsmisr.php' => config_path('smsmisr.php'),
        ], 'config');
    }
}
