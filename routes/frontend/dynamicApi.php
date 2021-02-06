<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: dynamicApi.php
 */
use App\Domains\DymamicApi\GenericController;
use Psr\Http\Message\ServerRequestInterface;

$routeBase = empty(env('ROUTE_BASE')) ?  'dynamicapi' : env('ROUTE_BASE');
$routeBase = str_replace('/', '', strtolower($routeBase));

Route::group([

    'middleware' => 'api',
    'prefix' => $routeBase

], function ($router) {

    Route::any('/{any}', function (ServerRequestInterface $request) {
        return GenericController::Default($request);
    })->where('any', '.*');
});
