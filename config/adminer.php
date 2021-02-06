<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: adminer.php
 */
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
