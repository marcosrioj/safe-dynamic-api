/* 
 *  Copyright (C) SafeDynamiAPI - All Rights Reserved
 *  Unauthorized copying of this file, via any medium is strictly prohibited
 *  Proprietary and confidential
 *  Written by Marcos Lima <marcoslima@marcoslima.com>, Sat Feb 06 2021
 *  File: webpack.mix.js
 */

const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath('public')
    .setResourceRoot('../') // Turns assets paths in css relative to css file
    .sass('resources/sass/frontend/app.scss', 'css/frontend.css')
    .sass('resources/sass/backend/app.scss', 'css/backend.css')
    .js('resources/js/frontend/app.js', 'js/frontend.js')
    .js('resources/js/backend/app.js', 'js/backend.js')
    .extract([
        'alpinejs',
        'jquery',
        'bootstrap',
        'popper.js',
        'axios',
        'sweetalert2',
        'lodash'
    ])
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
} else {
    // Uses inline source-maps on development
    mix.webpackConfig({
        devtool: 'inline-source-map'
    });
}
