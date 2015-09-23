var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss')
        .copy(
                'vendor/bower_components/sweetalert/dist/sweetalert.min.js',
                'public/js/vendor/sweetalert.min.js'
            )
        .copy(
                'vendor/bower_components/sweetalert/dist/sweetalert.css',
                'public/css/sweetalert.css'
            )
        .copy(
                'vendor/bower_components/jquery/dist/jquery.min.js',
                'public/js/vendor/jquery.min.js'
            )
        .copy(
                'vendor/bower_components/amazeui/dist/js/amazeui.min.js',
                'public/js/vendor/amazeui.min.js'
            )
        .copy(
                'vendor/bower_components/amazeui/dist/css/amazeui.min.css',
                'public/css/amazeui.min.css'
            )
        .copy(
                'vendor/bower_components/jquery-validation/dist/jquery.validate.min.js',
                'public/js/vendor/jquery.validate.min.js'
            )
        .copy(
                'vendor/bower_components/zepto-full/zepto.min.js',
                'public/js/vendor/zepto.min.js'
            )
        .version('public/css/app.css');    
});
