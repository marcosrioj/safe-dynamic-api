<?php

namespace App\Domains\DymamicApi;

use App\Domains\Auth\Models\DynamicApiGuest;
use App\Domains\Auth\Models\Role;
use Psr\Http\Message\ServerRequestInterface;
use Tqdev\PhpCrudApi\Api;
use Tqdev\PhpCrudApi\Config;
use Nyholm\Psr7\Response;

class GenericController
{
    public static function
    Default(ServerRequestInterface $request)
    {
        try {
            $api = new Api(self::getConfig());
            $requestMethod = $request->getServerParams()["REQUEST_METHOD"];
            $tableName = strtolower(explode("/", $request->getUri()->getPath())[3]);
            $joinparams = self::getJoinParams($request);

            if (self::guestHasPermission($tableName, $requestMethod, $joinparams)) {
                $response = $api->handle($request);
                return $response;
            } else {
                $user = auth()->guard('api')->user();
                if (!empty($user)) {
                    $permissions =  self::getPermissions($tableName, $requestMethod, $joinparams);

                    if ($user->can($permissions)) {
                        $response = $api->handle($request);
                        return $response;
                    }

                    $permissionsError =  join(", ", $permissions);
                    throw new \Exception("Missing permission. You need to have permission or all permissions if there is a join to access this feature. Permissions: $permissionsError", 403);
                } else {
                    throw new \Exception("Missing authentication. Please try again.", 401);
                }
            }
        } catch (\Exception $e) {
            $statusCode = $e->getCode() == 0 ? 500 : $e->getCode();
            $body = new \StdClass();
            switch ($statusCode) {
                case 401:
                    $body->code = 1011;
                    break;

                case 403:
                    $body->code = 1014;
                    break;

                default:
                    $body->code = 9999;
                    break;
            }
            $body->message = $e->getMessage();
            $response = new Response($statusCode, ['content-type' => 'application/json'], json_encode($body));
            return $response;
        }
    }

    private static function getConfig(): Config
    {
        $routeBase = empty(env('ROUTE_BASE')) ?  'dynamicapi' : env('ROUTE_BASE');
        $routeBase = '/' . str_replace('/', '', strtolower($routeBase));

        if ($routeBase == '/api') {
            throw new \Exception("The base route cannot be called 'API'. This name is used for API's that are created manually.");
        }

        return new Config([
            'driver' => env('DB_CONNECTION'),
            'address' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'username' =>  env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'database' => env('DB_DATABASE'),
            'basePath' => $routeBase,
            'middlewares' => 'cors,errors,joinLimits,pageLimits,multiTenancy',
            'joinLimits.depth' => 3,
            'joinLimits.tables' => 6,
            'joinLimits.records' => 25,
            'pageLimits.pages' => 10,
            'pageLimits.records' => 25,
            "cors.allowedOrigins" => env('CORS_ALLOWED_ORIGINS', '*'),
            "cors.allowHeaders" => env('CORS_ALLOWED_HEADERS', 'Content-Type, X-XSRF-TOKEN, Authorization'),
            "cors.allowMethods" => env('CORS_ALLOWED_METHODS', 'OPTIONS, GET, PUT, POST, DELETE, PATCH'),
            "cors.allowCredentials" => env('CORS_ALLOW_CREDENTIALS', 'false'),
            "cors.exposeHeaders" => env('CORS_EXPOSE_HEADERS', ''),
            "cors.maxAge" => env('CORS_MAX_AGE', '1728000'),
            'multiTenancy.handler' => function ($operation, $tableName) {
                // User Auto Filter
                $user = auth()->guard('api')->user();
                if (!empty($user)) {
                    if (!$user->isAdmin()) {
                        $autoUserFilterCol = env("COL_AUTO_USER_FILTER");
                        if (!empty($autoUserFilterCol)) {
                            return [$autoUserFilterCol => $user["id"]];
                        }
                    }
                }
            },
        ]);
    }

    private static function getJoinParams(ServerRequestInterface $request)
    {
        $query  = explode('&', $request->getServerParams()['QUERY_STRING']);
        $joinparams = [];

        foreach ($query as $param) {

            if (empty($param)) continue;

            // prevent notice on explode() if $param has no '='
            if (strpos($param, '=') === false) $param += '=';

            list($name, $value) = explode('=', $param, 2);
            if ($name == "join" || $name == "join[]") {
                $joinparams[] = urldecode($value);
            }
        }

        return $joinparams;
    }

    private static function getPermissions($tableName, $requestMethod, $joinparams = []): array
    {
        $basePermission = strtolower($requestMethod);
        $permission = $basePermission . "." . strtolower($tableName);
        $permissionsResult = [$permission];

        if (count($joinparams) > 0) {
            $joinPermission = str_replace($basePermission, $basePermission . ".join", $permission);

            foreach ($joinparams as $joinparam) {
                $joimParamExplode = explode(",", $joinparam);
                $joinPosPermission = "";

                foreach ($joimParamExplode as $item) {
                    $joinPosPermission = $joinPosPermission . ".$item";
                }

                $permissionsResult[] = $joinPermission . $joinPosPermission;
            }
        }

        return $permissionsResult;
    }

    private static function guestHasPermission($tableName, $requestMethod, $joinparams): bool
    {
        $role = Role::findByName(DynamicApiGuest::ROLE_TYPE);
        $permissions = self::getPermissions($tableName, $requestMethod, $joinparams);
        $hasPermission = false;

        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission)) {
                return false;
            }
            $hasPermission = true;
        }

        return $hasPermission;
    }
}
