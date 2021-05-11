const mix = require('laravel-mix');

mix
    .webpackConfig(require('./webpack.config'))
    .js('resources/js/app.js', 'public/dist/js')
    .postCss('resources/css/app.css', 'public/dist/css', [
        //
    ])
    .vue()
