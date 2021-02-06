<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: SuperAdminCheck.php
 */
namespace App\Domains\Auth\Http\Middleware;

use Closure;

/**
 * Class SuperAdminCheck.
 */
class SuperAdminCheck
{
    /**
     * @param $request
     * @param  Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->hasAllAccess()) {
            return $next($request);
        }

        return redirect()->route('frontend.index')->withFlashDanger(__('You do not have access to do that.'));
    }
}
