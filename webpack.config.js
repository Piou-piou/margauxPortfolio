var Encore = require('@symfony/webpack-encore');
const path = require('path');
var glob = require('glob');

Encore
.setOutputPath('public/build/')
.setPublicPath('/build')
.cleanupOutputBeforeBuild()

.addEntry('js/vendor', [
  './assets/js/index.js',
  './node_modules/jquery/dist/jquery.min.js',
])

.addEntry('js/index', [
  './node_modules/ribs-popup/dist/js/ribs-popup.js',
])

.addEntry('js/upload', [
  './assets/js/upload.js',
])

.addStyleEntry('css/vendor', [
  './node_modules/ribs-popup/dist/css/style.css',
])

.addStyleEntry('css/style', [
  './assets/scss/style.scss'
])

.enableBuildNotifications()
.enableSourceMaps(!Encore.isProduction())
.enableVersioning(Encore.isProduction())

.addLoader({
  test: /\.js$/,
  exclude: /node_modules/,
  include: [
    path.join(__dirname, 'assets/'),
  ],
  loader: 'babel-loader',
})

.enableSassLoader()

.enableSourceMaps(!Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();
