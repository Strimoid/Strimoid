var gulp = require('gulp');
var plugins = require('gulp-load-plugins')();
var mainBowerFiles = require('main-bower-files');

gulp.task('default', ['angular', 'js', 'css'], function() {
});

gulp.task('angular', function() {
    return gulp.src([
            'resources/assets/js/app.js',
            'resources/assets/js/controllers/*.js'
        ])
        //.pipe(plugins.sourcemaps.init())
        .pipe(plugins.ngAnnotate({ single_quotes: true }))
        .pipe(plugins.concat('angular.js'))
        .pipe(plugins.uglify())
        //.pipe(plugins.sourcemaps.write())
        .pipe(gulp.dest('public/static/js/'));
});

gulp.task('js', function() {
    var src = mainBowerFiles({ filter: /\.js$/ }).concat([
        'resources/assets/js/libs/*.js',
        'resources/assets/js/plugins/*.js',
        'resources/assets/js/modules/*.js',
        'resources/assets/js/lara.js'
    ]);

    return gulp.src(src)
        .pipe(plugins.sourcemaps.init())
        .pipe(plugins.concat('app.js'))
        .pipe(plugins.uglify())
        .pipe(plugins.sourcemaps.write('.'))
        .pipe(gulp.dest('public/static/js/'));
});

gulp.task('css', function() {
    return gulp.src('resources/assets/css/*.css')
        .pipe(plugins.autoprefixer('last 2 versions'))
        .pipe(plugins.concat('style.css'))
        .pipe(plugins.minifyCss())
        .pipe(gulp.dest('public/static/css/'));
});
