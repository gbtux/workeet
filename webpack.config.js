var Encore = require('@symfony/webpack-encore');
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin')

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addEntry('app', './assets/js/app.js')
    //.addEntry('page1', './assets/js/page1.js')
    //.addEntry('page2', './assets/js/page2.js')

    .addStyleEntry('security', [
        './assets/security/vendor/bootstrap/css/bootstrap.min.css',
        './assets/security/fonts/font-awesome-4.7.0/css/font-awesome.min.css',
        './assets/security/fonts/iconic/css/material-design-iconic-font.min.css',
        './assets/security/css/util.css',
        './assets/security/css/main.css'
    ])
    .addStyleEntry('roboto','./assets/css/roboto.css')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables Sass/SCSS support
    .enableSassLoader()
    .enableStylusLoader()
    .enableVueLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()

    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')
    .addPlugin(new VuetifyLoaderPlugin())
    .configureFilenames({
        images: '[path][name].[ext]',
    })
    .configureBabel(function(babelConfig) {
        //babelConfig.presets = ['es2015','stage-2']
        babelConfig.plugins = ["@babel/plugin-proposal-object-rest-spread","@babel/transform-runtime","@babel/plugin-syntax-dynamic-import"]
    })
;

module.exports = Encore.getWebpackConfig();
