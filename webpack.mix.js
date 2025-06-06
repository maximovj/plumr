const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
    ])
    .version()
    .browserSync({
        proxy: 'http://plumr.local', // Cambia por tu dominio local
        open: false,
        files: [
            'app/**/*.php',
            'resources/views/**/*.blade.php',
            'public/js/**/*.js',
            'public/css/**/*.css'
        ]
    });

mix.copy('node_modules/bootstrap-icons/font/bootstrap-icons.css', 'public/css/bootstrap-icons.min.css');
mix.copyDirectory('node_modules/bootstrap-icons/font/fonts', 'public/fonts');
