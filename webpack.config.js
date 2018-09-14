var Encore = require('@symfony/webpack-encore');
const path = require('path');
var glob = require('glob');

Encore
// directory where compiled assets will be stored
.setOutputPath('public/build/')
// public path used by the web server to access the output path
.setPublicPath('/build')

.cleanupOutputBeforeBuild()
.enableBuildNotifications()
.enableSourceMaps(!Encore.isProduction())
// enables hashed filenames (e.g. app.abc123.css)
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
