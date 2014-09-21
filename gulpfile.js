var gulp = require('gulp');
var plugins = require('gulp-load-plugins')();

gulp.task('default', ['angular', 'js', 'css'], function() {
});

gulp.task('angular', function() {
    return gulp.src(['app/assets/js/app.js', 'app/assets/js/controllers/*.js'])
        //.pipe(plugins.sourcemaps.init())
        .pipe(plugins.ngAnnotate({ single_quotes: true }))
        .pipe(plugins.concat('angular.js'))
        .pipe(plugins.uglify())
        //.pipe(plugins.sourcemaps.write())
        .pipe(gulp.dest('public/static/js/'));
});

gulp.task('js', function() {
    return gulp.src(['app/assets/js/libs/*.js', 'app/assets/js/plugins/*.js',
        'app/assets/js/modules/*.js', 'app/assets/js/lara.js'])
        .pipe(plugins.sourcemaps.init())
        .pipe(plugins.concat('app.js'))
        .pipe(plugins.uglify())
        .pipe(plugins.sourcemaps.write())
        .pipe(gulp.dest('public/static/js/'));
});

gulp.task('css', function() {
    return gulp.src('app/assets/css/*.css')
        .pipe(plugins.autoprefixer('last 2 versions'))
        .pipe(plugins.concat('style.css'))
        .pipe(plugins.minifyCss())
        .pipe(gulp.dest('public/static/css/'));
});
