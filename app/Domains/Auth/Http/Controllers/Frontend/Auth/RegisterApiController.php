<?php

namespace App\Domains\Auth\Http\Controllers\Frontend\Auth;

use App\Domains\Auth\Services\RoleService;
use App\Domains\Auth\Services\UserService;
use App\Http\Controllers\Controller;
use App\Rules\Captcha;
use GuzzleHttp\Psr7\Response;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;

/**
 * Class RegisterApiController.
 */
class RegisterApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register API Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var RoleService
     */
    protected $roleService;

    /**
     * RegisterController constructor.
     *
     * @param  UserService  $userService
     */
    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'role_id' => ['required', 'numeric', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password' => array_merge(['max:100'], PasswordRules::register($data['email'] ?? null)),
            'terms' => ['required', 'in:1'],
        ], [
            'terms.required' => __('You must accept the Terms & Conditions.'),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     *
     * @return \App\Domains\Auth\Models\User|mixed
     * @throws \App\Domains\Auth\Exceptions\RegisterException
     */
    protected function create(array $data)
    {
        $role = $this->roleService->getById($data['role_id']);
        $user =  $this->userService->registerUser($data);
        $user->syncRoles([$role]);
        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $this->validator($request->all())->validate();

            event(new Registered($user = $this->create($request->all())));

            $this->guard('api')->login($user);

            $credentials = ['email' => $user->email, 'password' => $request->get('password')];

            $token = auth()->guard('api')->attempt($credentials);

            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->guard('api')->factory()->getTTL() * 60
            ]);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() == 0 ? 500 : $e->getCode();
            $body = new \StdClass();
            $body->code = 9999;
            $body->message = $e->getMessage();
            $response = new Response($statusCode, ['content-type' => 'application/json'], json_encode($body));
            return $response;
        }
    }
}
