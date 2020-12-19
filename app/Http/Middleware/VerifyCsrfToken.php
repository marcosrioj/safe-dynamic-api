<?php

namespace App\Http\Middleware;

use App\Domains\Auth\Http\Controllers\Frontend\Auth\LoginJwtController;
use Illuminate\Encryption\Encrypter;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * Class VerifyCsrfToken.
 */
class VerifyCsrfToken extends Middleware
{
    public function __construct(Application $app, Encrypter $encrypter)
    {
        $dynamicApiExpect = empty(env('ROUTE_BASE')) ?  'dynamicapi' : env('ROUTE_BASE');
        $dynamicApiExpect = '/' . str_replace('/', '', strtolower($dynamicApiExpect)) . '/*';

        $this->except = [
            '/auth/login',
            '/auth/logout',
            '/auth/refresh',
            '/auth/me',
            'api/register',
            'api/password/email',
            'api/password/reset',
            "$dynamicApiExpect"
        ];

        parent::__construct($app, $encrypter);
    }

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
    protected $except = [];
}
