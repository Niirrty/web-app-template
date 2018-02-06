var gulp = require('gulp');
var uglify = require('gulp-uglify');
var less = require('gulp-less');
var concat = require('gulp-concat');
var minifyCSS = require('gulp-minify-css');
var pump = require('pump');

var documentRoot                 = './app';

var jsInternalSrc                = './src/js/int/*.js';
var jsExternalSrc                = [ './src/js/ext/*.js', './src/js/ext/**/*.js' ];
var lessBootstrapFile            = './src/less/bootstrap.less';
var unminifiedCssOutFileName     = 'app.css';
var minifiedCssOutFileName       = 'app.min.css';
var unminifiedJsOutFileName      = 'app.js';
var minifiedJsOutFileName        = 'app.min.js';
var unminifiedExtJsOutFileName   = 'externals.js';
var minifiedExtJsOutFileName     = 'externals.min.js';
var cssOutFolder                 = documentRoot + '/css';
var jsOutFolder                  = documentRoot + '/js';
var lessWatchSource              = [ './src/less/*.less', './src/less/**/*.less' ];
var jsWatchSource                = [ './src/js/*.js', './src/js/**/*.js' ];


gulp.task( 'scripts-internals', function()
{
   return pump( [
      gulp.src ( jsInternalSrc ),
      concat( unminifiedJsOutFileName ),
      gulp.dest( jsOutFolder ),
      uglify(),
      concat( minifiedJsOutFileName ),
      gulp.dest( jsOutFolder )
   ] );
});
 
gulp.task( 'scripts-externals', function()
{
   return pump( [
      gulp.src ( jsExternalSrc ),
      concat( unminifiedExtJsOutFileName ),
      gulp.dest( jsOutFolder ),
      uglify(),
      concat( minifiedExtJsOutFileName ),
      gulp.dest( jsOutFolder )
   ] );
});
 
gulp.task( 'compile-less', function( cb )
{
   return pump( [
      gulp.src( lessBootstrapFile ),  // Get LESS content from defined source(s)
      less(),                               // Compile LESS to CSS
      concat( unminifiedCssOutFileName ),                // unminified CSS file
      gulp.dest( cssOutFolder ),             // Save the CSS file
      minifyCSS(),                          // Minify the CSS
      concat( minifiedCssOutFileName ),            // Concat all to a minified CSS file
      gulp.dest( cssOutFolder )              // Save the minified CSS file
   ] );
});
 
gulp.task( 'auto-compile-less', function()
{
   gulp.watch( lessWatchSource, [ 'compile-less' ] );
});

gulp.task( 'build-all-scripts', [ 'scripts-internals', 'scripts-externals' ] );

gulp.task( 'auto-build-js', function()
{
   gulp.watch( jsWatchSource, [ 'build-all-scripts' ] );
});

gulp.task( 'default', [ 'compile-less', 'auto-compile-less', 'build-all-scripts', 'auto-build-js' ] );
