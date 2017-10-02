let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css').sourceMaps();

mix.less('bower_components/startbootstrap-sb-admin-2/less/sb-admin-2.less', 'public/css')
    .less('bower_components/bootstrap/less/bootstrap.less', 'public/css');

mix.js('bower_components/startbootstrap-sb-admin-2/dist/js/sb-admin-2.js', 'public/js')
    .js('bower_components/bootstrap/dist/js/bootstrap.js', 'public/js')
    .js('bower_components/metisMenu/dist/metisMenu.js', 'public/js');

mix.copy('bower_components/metisMenu/dist/metisMenu.min.css', 'public/css/metisMenu.min.css');