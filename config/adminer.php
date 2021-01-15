<?php

$adminerRoutePrefix = env('SQL_MANAGER_ROUTE_PREFIX') ?: 'sqlmanager';

return [

    /*
    |--------------------------------------------------------------------------
    | Auto Login
    |--------------------------------------------------------------------------
    |
    | Enable autologin to database
    |
    | ATTENSION: Please only enable autologin with authenticated protection
    |
    */
    'autologin' => true,

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | You may customize route prefix. (default: 'sqlmanager')
    |
    */
    'route_prefix' => $adminerRoutePrefix,
];
