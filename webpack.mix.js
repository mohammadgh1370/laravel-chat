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

mix.js('resources/js/app.js', 'public/js/app.js')
    .sass('resources/sass/app.scss', 'public/css/app.css')
    .copy('resources/fonts', 'public/fonts')
    .styles([
        'node_modules/bootstrap-rtl/dist/css/bootstrap-rtl.css',
        'resources/css/adminlte.css',
        'resources/css/custom-style.css',
    ],'public/css/chat.css')
    .scripts([
        'resources/js/adminlte.js',
        'resources/js/demo.js',
    ],'public/js/chat.js');
