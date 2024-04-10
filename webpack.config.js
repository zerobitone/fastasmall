const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableSassLoader()
    .addEntry('app', './assets/app.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .enableVersioning()
;

module.exports = Encore.getWebpackConfig();