var gulp = require('gulp')
var elixir = require('laravel-elixir')
var bower = require('main-bower-files')
var shell = require('gulp-shell')
require('laravel-elixir-riot')

elixir.extend('laroute', function(message) {
    new elixir.Task('laroute', function() {
        return gulp.src('').pipe(shell('php artisan laroute:generate'));
    }).watch('./app/Http/routes.php')
});

elixir(function(mix) {
    mix.laroute()
       .styles(bower('**/*.css'), 'public/assets/js/vendor.css', '/')
       .scripts(bower('**/*.js'), 'public/assets/js/vendor.js', '/')
       .sass('**/*.(sass|scss)', 'public/assets/css/all.css')
       .riot('**/*.tag', 'public/assets/js/riot.js')
       .babel([
            'plugins/*.js',
            'modules/*.js',
            'lara.js'
       ], 'public/assets/js', 'resources/assets/js')
       .copy('bower_components/font-awesome/fonts', 'public/assets/fonts')
       .copy('bower_components/font-awesome/fonts', 'public/build/assets/fonts')
       .version([
            'assets/css/all.css',
            'assets/css/vendor.css',
            'assets/js/all.js',
            'assets/js/riot.js',
            'assets/js/vendor.js'
       ])
});
