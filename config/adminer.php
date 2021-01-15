<?php

$adminerRoutePrefix = env('SQL_MANAGER_ROUTE_PREFIX') ?: 'adminer';

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
    | You may customize route prefix. (default: 'adminer')
    |
    */
    'route_prefix' => $adminerRoutePrefix,
];
