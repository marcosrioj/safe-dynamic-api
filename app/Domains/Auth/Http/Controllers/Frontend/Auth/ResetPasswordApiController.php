<?php

namespace App\Domains\Auth\Http\Controllers\Frontend\Auth;

use App\Domains\Auth\Rules\UnusedPassword;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use GuzzleHttp\Psr7\Response;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Class ResetPasswordAPiController.
 */
class ResetPasswordApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Api Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        try {
            $request->validate($this->rules(), []);

            $credentials = $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            );

            // Here we will attempt to reset the user's password. If it is successful we
            // will update the password on an actual user model and persist it to the
            // database. Otherwise we will parse the error and return the response.
            $response = Password::broker()->reset(
                $credentials,
                function ($user, $password) {
                    $this->resetPassword($user, $password);
                }
            );

            // If the password was successfully reset, we will redirect the user back to
            // the application's home authenticated view. If there is an error we can
            // redirect them back to where they came from with their error message.

            if ($response == Password::PASSWORD_RESET) {
                return response()->json([
                    'message' =>  __('Your password has been updated successfully!')
                ]);
            } else {
                throw ValidationException::withMessages([
                    'email' => [trans($response)],
                ]);
            }
        } catch (\Exception $e) {
            $statusCode = $e->getCode() == 0 ? 500 : $e->getCode();
            $body = new \StdClass();
            $body->code = 9999;
            $body->message = $e->getMessage();
            $response = new Response($statusCode, ['content-type' => 'application/json'], json_encode($body));
            return $response;
        }
    }

    /**
     * Reset the given user's password.
     * @return void
     */
    public function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        Auth::guard()->login($user);
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'max:255', 'email'],
            'password' => array_merge(
                [
                    'max:100',
                    new UnusedPassword(request('email')),
                ],
                PasswordRules::changePassword(request('email'))
            ),
        ];
    }
}
