<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: TimezoneHelper.php
 */
use JamesMills\LaravelTimezone\Timezone;

if (! function_exists('timezone')) {
    /**
     * Access the timezone helper.
     */
    function timezone()
    {
        return resolve(Timezone::class);
    }
}
