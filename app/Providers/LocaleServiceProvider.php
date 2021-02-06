<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: LocaleServiceProvider.php
 */
namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Class LocaleServiceProvider.
 */
class LocaleServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function boot()
    {
        setAllLocale(config('app.locale'));

        $this->registerBladeExtensions();
    }

    /**
     * Register the locale blade extensions.
     */
    protected function registerBladeExtensions(): void
    {
        /*
         * The block of code inside this directive indicates
         * the chosen language requests RTL support.
         */
        Blade::if('langrtl', function () {
            return session()->has('lang-rtl');
        });
    }
}
