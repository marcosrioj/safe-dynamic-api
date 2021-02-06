<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: DisableTwoFactorAuthenticationRequest.php
 */
namespace App\Domains\Auth\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DisableTwoFactorAuthenticationRequest.
 */
class DisableTwoFactorAuthenticationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => ['required', 'max:10', 'totp_code'],
        ];
    }
}
