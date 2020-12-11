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
            $body = new \StdClass();
            $body->error = true;
            $body->errorMessage = $e->getMessage();
            $statusCode = $e->getCode() == 0 ? 500 : $e->getCode();
            $response = new Response($statusCode, [], json_encode($body));
            return $response;
        }
    }

    private static function getConfig(): Config
    {
        return new Config([
            'driver' => env('DB_CONNECTION'),
            'address' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'username' =>  env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'database' => env('DB_DATABASE'),
            'basePath' => '/dynamicapi',
            'middlewares' => 'cors,errors,joinLimits,pageLimits,multiTenancy',
            'joinLimits.depth' => 3,
            'joinLimits.tables' => 6,
            'joinLimits.records' => 25,
            'pageLimits.pages' => 10,
            'pageLimits.records' => 25,
            'multiTenancy.handler' => function ($operation, $tableName) {
                // User Auto Filter
                $user = auth()->guard('api')->user();
                if (!empty($user)) {
                    if (!$user->isAdmin()) {
                        $autoUserFilterCol = env("DYNAMIC_API_COL_AUTO_USER_FILTER");
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
        $basePermission = "dynamic_api." . strtolower($requestMethod);
        $permission = $basePermission . "." . strtolower($tableName);

        if (count($joinparams) > 0) {
            $withJoinPermissions = [$permission];
            $joinPermission = str_replace($basePermission, $basePermission . ".join", $permission);

            foreach ($joinparams as $joinparam) {
                $joimParamExplode = explode(",", $joinparam);
                $joinPosPermission = "";

                foreach ($joimParamExplode as $item) {
                    $joinPosPermission = $joinPosPermission . ".$item";
                }

                $withJoinPermissions[] = $joinPermission . $joinPosPermission;
            }

            return $withJoinPermissions;
        } else {
            return [$permission];
        }
    }

    private static function guestHasPermission($tableName, $requestMethod, $joinparams): bool
    {
        $role = Role::findOrCreate(DynamicApiGuest::ROLE_TYPE);
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
