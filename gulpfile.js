var gulp = require('gulp')
var elixir = require('laravel-elixir')
var bower = require('main-bower-files')
var shell = require('gulp-shell')

require('laravel-elixir-lodash')
require('laravel-elixir-riot')

elixir.config.css.outputFolder = 'assets/css'
elixir.config.js.outputFolder = 'assets/js'

elixir.extend('laroute', function() {
    new elixir.Task('laroute', function() {
        return gulp.src('').pipe(shell('php artisan laroute:generate'));
    }).watch('./app/Http/routes.php')
})

elixir(function(mix) {
    mix.laroute()
       .styles(bower('**/*.css'), 'public/assets/css/vendor.css', '/')
       .scripts(bower('**/*.js'), 'public/assets/js/vendor.js', '/')
       .sass('app.sass', 'public/assets/css/all.css')
       .riot('**/*.tag', 'public/assets/js/riot.js')
       .lodash('**/*.html', null, { namespace: '_.tpl', name: function(file) {
           return file.relative.replace('.html', '').replace('/', '-')
       } })
       .babel([
            'plugins/*.js',
            'modules/*.js',
            'lara.js'
       ])
       .copy('bower_components/font-awesome/fonts', 'public/assets/fonts')
       .copy('bower_components/font-awesome/fonts', 'public/build/assets/fonts')
       .version([
            'assets/css/all.css',
            'assets/css/vendor.css',
            'assets/js/all.js',
            'assets/js/riot.js',
            'assets/js/vendor.js',
            'assets/js/laroute.js',
            'assets/js/lodash.js'
       ])
})
