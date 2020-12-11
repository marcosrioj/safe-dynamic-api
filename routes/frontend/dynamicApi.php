<?php

use App\Domains\DymamicApi\GenericController;
use Psr\Http\Message\ServerRequestInterface;

Route::any('/dynamicapi/{any}', function (ServerRequestInterface $request) {
    return GenericController::
        Default($request);
})->where('any', '.*');
