/**
 * WPReserve Gulpfile.js
 */

/**=====================================================================================================================
 * REQUIRE
 *====================================================================================================================*/

var gulp = require('gulp');
var gulpUtil = require('gulp-util');
var plumber = require('gulp-plumber');
var runSeq = require('run-sequence');
var sort = require('gulp-sort');
var fs = require('fs');
var git = require('gulp-git');

var uglify = require('gulp-uglify');
var stripDebug = require('gulp-strip-debug');

var sass = require('gulp-sass');
var minCss = require('gulp-minify-css');

var pot = require('gulp-wp-pot');

var zip = require('gulp-zip');

/**=====================================================================================================================
 * BOWER
 *====================================================================================================================*/

/**
 * Move src/lib packages to assets/lib.
 */
gulp.task('bower', function () {

    return gulp.src('./src/lib/**/*')
        .pipe(gulp.dest('./assets/lib'));

});

/**=====================================================================================================================
 * JS
 *====================================================================================================================*/

/**
 * Minify JavaScripts from src to assets.
 */
gulp.task('js', function () {

    return gulp.src('./src/js/**/*.js')
        .pipe(plumber(function (error) {
            gulpUtil.log(gulpUtil.colors.red('Error (' + error.plugin + '): ' + error.message));
            this.emit('end');
        }))
        //.pipe(stripDebug())
        .pipe(uglify())
        .pipe(gulp.dest('./assets/js'));

});

/**=====================================================================================================================
 * CSS
 *====================================================================================================================*/

/**
 * Compile and minify Sass to assets.
 */
gulp.task('sass', function () {

    return gulp.src('./src/sass/**/*.scss')
        .pipe(plumber(function (error) {
            gulpUtil.log(gulpUtil.colors.red('Error (' + error.plugin + '): ' + error.message));
            this.emit('end');
        }))
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./src/css'));

});

gulp.task('css', function() {

    return gulp.src('./src/css/**/*.css')
        .pipe(plumber(function (error) {
            gulpUtil.log(gulpUtil.colors.red('Error (' + error.plugin + '): ' + error.message));
            this.emit('end');
        }))
        .pipe(minCss())
        .pipe(gulp.dest('./assets/css'));

});

/**=====================================================================================================================
 * GENERAL
 *====================================================================================================================*/

/**
 * POT file generator.
 */
gulp.task('pot', function () {

    var src = [
        './**/*.php',
        '!./vendor/**/*'
    ];

    return gulp.src(src)
        .pipe(sort())
        .pipe(pot({
            domain: 'wprsrv',
            destFile:'wprsrv.pot',
            lastTranslator: 'Otto Rask <ojrask@gmail.com>'
        }))
        .pipe(gulp.dest('./languages'));

});

/**
 * Compiler.
 */
gulp.task('compile', ['bower', 'js', 'sass', 'css', 'pot'], function (cb) {

    runSeq(['bower', 'js', 'pot'], 'sass', 'css', cb);

});

/**
 * Watcher.
 */
gulp.task('watch', ['compile'], function () {

    var potSrc = ['./**/*.php', '!./vendor/**/*'];

    gulp.watch('./src/js/**/*.js', ['js']);
    gulp.watch('./src/sass/**/*.scss', ['sass']);
    gulp.watch('./src/css/**/*.css', ['css']);
    gulp.watch('./src/lib/**/*', ['bower']);
    gulp.watch(potSrc, ['pot']);

    console.log('Watching for changes, Ctrl-C to quit...');

});

/**
 * Build a release.
 */
gulp.task('build', function () {

    var stream = null;

    var pluginSrc = [
        './index.php',
        './wprsrv.php',
        './functions.php',
        './README.md',
        './LICENSE.md',
        './uninstall.php',
        './assets',
        './languages',
        './config',
        './classes',
        './vendor',
        './includes'
    ];

    var getPluginVersion = function (file) {
        var contents = fs.readFileSync(file, 'utf8');

        var matched = contents.match(/Version: *([0-9]+?\.[0-9]+?\.[0-9]+?)/gi);

        if (matched === undefined || !matched[0] || !matched[0].length) {
            return false;
        }

        var versLine = matched[0].trim();
        var version = versLine.replace(/[^0-9\.]/gi, '').trim();

        return version;
    };

    git.exec({args: 'rev-parse --abbrev-ref HEAD', quiet: true}, function (err, stdout) {
        if (err) {
            throw err;
        }

        var branch = stdout.trim();

        if (branch !== 'master') {
            gulpUtil.log(gulpUtil.colors.red('Error: ' + 'Cannot build plugin package, must be built on master branch.'));
            return false;
        }

        var versionNumber = getPluginVersion('./wprsrv.php');

        if (!versionNumber) {
            console.log('Build failed, could not read version number from `wprsrv.php`!');
            return false;
        }

        var zipName = 'wprsrv-' + versionNumber + '.zip';

        var str = gulp.src(pluginSrc)
            .pipe(plumber(function (error) {
                gulpUtil.log(gulpUtil.colors.red('Error (' + error.plugin + '): ' + error.message));
                this.emit('end');
            }))
            .pipe(zip(zipName))
            .pipe(gulp.dest('./builds'));

        gulpUtil.log(gulpUtil.colors.green('Built distributable plugin archive `./builds/wprsrv-' + versionNumber + '.zip`'));

        stream = str;

    });

    return stream;

});

/**
 * Default to watching.
 */
gulp.task('default', ['watch'], function() {});
