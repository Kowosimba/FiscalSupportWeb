<?php

namespace App\Providers;

use App\Services\RecaptchaService;
use Illuminate\Support\ServiceProvider;

class RecaptchaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(RecaptchaService::class, function ($app) {
            return new RecaptchaService();
        });
    }
}