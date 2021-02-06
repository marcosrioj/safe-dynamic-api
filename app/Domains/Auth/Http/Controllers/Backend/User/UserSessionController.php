<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: UserSessionController.php
 */
namespace App\Domains\Auth\Http\Controllers\Backend\User;

use App\Domains\Auth\Http\Requests\Backend\User\ClearUserSessionRequest;
use App\Domains\Auth\Models\User;
use App\Http\Controllers\Controller;

/**
 * Class UserSessionController.
 */
class UserSessionController extends Controller
{
    /**
     * @param  ClearUserSessionRequest  $request
     * @param  User  $user
     *
     * @return mixed
     */
    public function update(ClearUserSessionRequest $request, User $user)
    {
        $user->update(['to_be_logged_out' => true]);

        return redirect()->back()->withFlashSuccess(__('The user\'s session was successfully cleared.'));
    }
}
