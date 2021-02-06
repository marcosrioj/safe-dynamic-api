<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: UserTypeCheck.php
 */
namespace App\Domains\Auth\Http\Middleware;

use Closure;

/**
 * Class UserTypeCheck.
 */
class UserTypeCheck
{
    /**
     * @param $request
     * @param  Closure  $next
     * @param $type
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $type)
    {
        if ($request->user()) {
            if (strpos($type, '|') !== false) {
                $types = explode('|', $type);

                foreach ($types as $t) {
                    if ($request->user()->isType($t)) {
                        return $next($request);
                    }
                }
            } elseif ($request->user()->isType($type)) {
                return $next($request);
            }
        }

        return redirect()->route('frontend.index')->withFlashDanger(__('You do not have access to do that.'));
    }
}
