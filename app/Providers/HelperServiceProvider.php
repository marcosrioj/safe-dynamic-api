<?php
/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: HelperServiceProvider.php
 */
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class HelperServiceProvider.
 */
class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function boot()
    {
        $rdi = new RecursiveDirectoryIterator(app_path('Helpers'.DIRECTORY_SEPARATOR.'Global'));
        $it = new RecursiveIteratorIterator($rdi);

        while ($it->valid()) {
            if (
                ! $it->isDot() &&
                $it->isFile() &&
                $it->isReadable() &&
                $it->current()->getExtension() === 'php' &&
                strpos($it->current()->getFilename(), 'Helper')
            ) {
                require $it->key();
            }

            $it->next();
        }
    }
}
