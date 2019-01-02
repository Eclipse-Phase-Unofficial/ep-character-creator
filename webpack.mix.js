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

mix.js('resources/assets/js/app.js', 'public/js/app.js')
    .extract([      //Extract these to vendor.js
        'jquery',
        'jquery-ui/ui/widgets/tooltip',
        // 'vegas', //Causes Errors if here!
    ])
    .scripts('resources/assets/js/legacy/*', 'public/js/legacy.js') //Combine all the legacy files into one
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sass('resources/assets/sass/vendor.scss', 'public/css')
    .options({
        postCss: [
            //This lets us use @import on urls
            require('postcss-import-url')({
                modernBrowser: true
            }),
            // This lets us inline most CSS images
            require('postcss-url')({
                url: 'inline',
                maxSize: 10,
            }),
            //And this handles almost all the rest
            require('postcss-url')({
                url: 'inline',
                maxSize: 10,
                ignoreFragmentWarning: true,
                basePath: process.cwd() + '/' + mix.config.publicPath,
            }),
        ]
    });

//Do Hot model reloading on port 3030, so we can test the site on port 8080
mix.options({
    hmrOptions: {
        port: '3030'
    }
});
