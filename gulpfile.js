var gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    sourcemaps = require('gulp-sourcemaps'),
    csso = require('gulp-csso'),
    watch = require('gulp-watch'),
    less = require('gulp-less');

var config = {
    projectDir: __dirname+'/src/Application/Networking/InitCmsBundle/Resources/public',
    mopa: __dirname+'/vendor/mopa/bootstrap-bundle/Mopa/Bundle/BootstrapBundle/Resources/public'
};

gulp.task('less', function () {
    return gulp.src([
        config.projectDir+ '/less/styles.less'
    ])
        .pipe(sourcemaps.init())
        .pipe(less())
        .pipe(csso())
        .pipe(concat('style.css'))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(config.projectDir + '/css'));
});
gulp.task('app', function () {
    return gulp.src([
        config.projectDir + '/vendor/jquery/dist/jquery.min.js',
        config.mopa + '/bootstrap/js/tooltip.js',
        config.mopa + '/bootstrap/js/*.js',
        config.mopa + '/js/mopabootstrap-collection.js',
        config.mopa + '/js/mopabootstrap-subnav.js'
    ])
        .pipe(sourcemaps.init())
        .pipe(uglify())
        .pipe(concat('app.js'))
        .pipe(sourcemaps.write('./maps/'))
        .pipe(gulp.dest(config.projectDir + '/js'));
});

gulp.task('default', gulp.parallel('less',  'app'));