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

mix.setPublicPath('public/')
    .js('resources/js/app.js', 'js/app.js')
    .extract([      //Extract these to vendor.js
        'jquery',
        'jquery-ui/ui/widgets/tooltip',
        'vue',
        'vue-router',
        'vuex',
        'vue-analytics',
        'uikit'
        // 'vegas', //Causes Errors if here!
    ])
    .scripts('resources/js/legacy/*', 'public/js/legacy.js') //Combine all the legacy files into one
    .sass('resources/sass/app.scss', 'css')
    .sass('resources/sass/vendor.scss', 'css')
    .options({
        postCss: [
            //This lets us use @import on urls
            require('postcss-import-url')({
                modernBrowser: true
            }),
            require('postcss-url')([
                //Preprocessing so we accurately grab url('~packageName/...')
                {
                    filter: (asset) => asset.url.startsWith('~'),
                    url: (asset) => process.cwd() + '/node_modules/' + asset.url.substring(1),
                    multi: true,
                },
                // This lets us inline most CSS images
                {
                    url: 'inline',
                    maxSize: 10,
                },
                //Handle everything pointing to the public folder
                {
                    filter: (asset) => !asset.url.startsWith('~'),
                    url: 'inline',
                    maxSize: 10,
                    ignoreFragmentWarning: true,
                    basePath: process.cwd() + '/' + mix.config.publicPath,
                }
            ]),
        ]
    });

//Do Hot model reloading on port 3030, so we can test the site on port 8080
mix.options({
    hmrOptions: {
        port: '3030'
    }
});
