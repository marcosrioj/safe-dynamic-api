<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: UpdatePasswordRequest.php
 */
namespace App\Domains\Auth\Http\Requests\Frontend\Auth;

use App\Domains\Auth\Rules\UnusedPassword;
use Illuminate\Foundation\Http\FormRequest;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;

/**
 * Class UpdatePasswordRequest.
 */
class UpdatePasswordRequest extends FormRequest
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
            'current_password' => ['required', 'max:100'],
            'password' => array_merge(
                [
                    'max:100',
                    new UnusedPassword($this->user()),
                ],
                PasswordRules::changePassword(
                    $this->email,
                    config('boilerplate.access.user.password_history') ? 'current_password' : null
                )
            ),
        ];
    }
}
