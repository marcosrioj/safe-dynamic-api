<?php

namespace App\Domains\Auth\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

/**
 * Class ForgotPasswordApiController.
 */
class ForgotPasswordApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Api Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email', 'returnUrl' => 'required']);

            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $response = $this->broker()->sendResetLink(
                $request->only('email'),
                function ($user, $token) use ($request) {
                    $user->sendPasswordResetNotification($token, $request->get("returnUrl"));
                }
            );

            if ($response == Password::RESET_LINK_SENT) {
                return response()->json([
                    'message' =>  __('We have e-mailed your password reset link!')
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
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return $request->wantsJson()
            ? new JsonResponse(['message' => trans($response)], 200)
            : back()->with('status', trans($response));
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }
}
