<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: PasswordExpiredController.php
 */
namespace App\Domains\Auth\Http\Controllers\Frontend\Auth;

use App\Domains\Auth\Http\Requests\Frontend\Auth\UpdatePasswordRequest;
use App\Domains\Auth\Services\UserService;
use App\Http\Controllers\Controller;

/**
 * Class PasswordExpiredController.
 */
class PasswordExpiredController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function expired()
    {
        abort_unless(config('boilerplate.access.user.password_expires_days'), 404);

        return view('frontend.auth.passwords.expired');
    }

    /**
     * @param  UpdatePasswordRequest  $request
     * @param  UserService  $userService
     *
     * @return mixed
     * @throws \Throwable
     */
    public function update(UpdatePasswordRequest $request, UserService $userService)
    {
        abort_unless(config('boilerplate.access.user.password_expires_days'), 404);

        $userService->updatePassword($request->user(), $request->only('old_password', 'password'), true);

        return redirect()->route('frontend.user.account')
            ->withFlashSuccess(__('Password successfully updated.'));
    }
}
