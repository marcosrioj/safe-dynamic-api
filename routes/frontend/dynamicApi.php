<?php

use App\Domains\DymamicApi\GenericController;
use Psr\Http\Message\ServerRequestInterface;

$routeBase = empty(env('ROUTE_BASE')) ?  'dynamicapi' : env('ROUTE_BASE');
$routeBase = '/' . str_replace('/', '', strtolower($routeBase));

Route::any($routeBase . '/{any}', function (ServerRequestInterface $request) {
    return GenericController::
        Default($request);
})->where('any', '.*');
