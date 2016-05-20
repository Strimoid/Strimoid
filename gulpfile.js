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
       .scripts(bower('**/*.js'), 'public/assets/js/vendor.js', '/')
       .sass('app.sass', 'public/assets/css/all.css')
       .riot('**/*.tag', 'public/assets/js/riot.js')
       .lodash('**/*.html', null, { namespace: '_.tpl', name: function(file) {
           return file.relative.replace('.html', '').replace('/', '-')
       }, templateSettings: {
           interpolate: /{!!([\s\S]+?)!!}/g,
           escape: /{{([\s\S]+?)}}/g
       }})
       .babel([
           'plugins/*.js',
           'modules/*.js',
           'lara.js'
       ])
       .copy('bower_components/font-awesome/fonts', 'public/assets/fonts')
       .copy('bower_components/font-awesome/fonts', 'public/build/assets/fonts')
       .version([
           'assets/css/*.css',
           'assets/js/*.js'
       ])
})
