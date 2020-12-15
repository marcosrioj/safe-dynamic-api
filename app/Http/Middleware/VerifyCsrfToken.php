<?php

namespace App\Http\Middleware;

use App\Domains\Auth\Http\Controllers\Frontend\Auth\LoginJwtController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * Class VerifyCsrfToken.
 */
class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/auth/login',
        '/auth/logout',
        '/auth/refresh',
        '/auth/me',
        '/dynamicapi/*'
        //
    ];
}
