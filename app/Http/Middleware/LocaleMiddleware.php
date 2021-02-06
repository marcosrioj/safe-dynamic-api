<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: LocaleMiddleware.php
 */
namespace App\Http\Middleware;

use Closure;

/**
 * Class LocaleMiddleware.
 */
class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Locale is enabled and allowed to be changed
        if (config('boilerplate.locale.status') && session()->has('locale')) {
            setAllLocale(session()->get('locale'));
        }

        return $next($request);
    }
}
