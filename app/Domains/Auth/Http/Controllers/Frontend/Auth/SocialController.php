<?php

namespace App\Domains\Auth\Http\Controllers\Frontend\Auth;

use App\Domains\Auth\Events\User\UserLoggedIn;
use App\Domains\Auth\Services\UserService;
use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Response;
use Laravel\Socialite\Facades\Socialite;

/**
 * Class SocialController.
 */
class SocialController extends Controller
{
    /**
     * @param $provider
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect($provider)
    {
        $redirectTo = request()->get('redirectTo');
        $driver = Socialite::driver($provider);

        if (!empty($redirectTo)) {
            $driver->with(['redirect' => $redirectTo]);
        }

        return $driver->redirect();
    }

    /**
     * @param $provider
     * @param  UserService  $userService
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Exceptions\GeneralException
     */
    public function callback($provider, UserService $userService)
    {
        $redirectTo = '';
        $parsedUrl = parse_url(url()->previous());
        if (array_key_exists('query', $parsedUrl)) {
            parse_str($parsedUrl['query'], $queryParameters);
            if (array_key_exists('redirectTo', $queryParameters)) {
                $redirectTo = $queryParameters['redirectTo'];
            }
        }

        if (!empty($redirectTo)) { //API
            try {
                $defaultRoleId = env('SOCIAL_USER_ROLE_ID_DEFAULT', 2); //user
                $user = $userService->registerProvider(Socialite::driver($provider)->user(), $provider, $defaultRoleId);

                if (!$user->isActive()) {
                    auth()->logout();

                    throw new \Exception(__('Your account has been deactivated.'), 403);
                }

                $token = auth()->guard('api')->login($user);
                event(new UserLoggedIn($user));
                $redirectTo = addParamToUrl($redirectTo, 'token', $token);

                return redirect($redirectTo);
            } catch (\Exception $e) {
                $statusCode = $e->getCode() == 0 ? 500 : $e->getCode();
                $body = new \StdClass();
                switch ($statusCode) {
                    case 403:
                        $body->code = 1012;
                        break;

                    default:
                        $body->code = 9999;
                        break;
                }
                $body->message = $e->getMessage();
                $response = new Response($statusCode, ['content-type' => 'application/json'], json_encode($body));
                return $response;
            }
        } else {
            $user = $userService->registerProvider(Socialite::driver($provider)->user(), $provider);

            if (!$user->isActive()) {
                auth()->logout();

                return redirect()->route('frontend.auth.login')->withFlashDanger(__('Your account has been deactivated.'));
            }

            auth()->login($user);

            event(new UserLoggedIn($user));

            return redirect()->route(homeRoute());
        }
    }
}
