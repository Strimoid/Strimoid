var elixir = require('laravel-elixir')

require('laravel-elixir-bower')
require('laravel-elixir-riot')

elixir(function(mix) {
    mix.bower('vendor.css', 'public/assets/css', 'vendor.js', 'public/assets/js')
       .sass(null, 'public/assets/sass')
       .stylesIn('public/assets/sass', 'public/assets/css')
       .riot(null, 'public/assets/riot')
       .scripts([
            'plugins/*.js',
            'modules/*.js',
            'lara.js'
       ], 'public/assets/js', 'resources/assets/js')
       .scriptsIn('public/assets/riot', 'public/assets/js/riot.js')
       .version([
            'assets/css/all.css',
            'assets/css/vendor.css',
            'assets/js/all.js',
            'assets/js/riot.js',
            'assets/js/vendor.js'
       ])
});

elixir(function(mix) {
    mix.copy('bower_components/bootstrap/dist/fonts', 'public/assets/fonts')
       .copy('bower_components/bootstrap/dist/fonts', 'public/build/assets/fonts')
       .copy('bower_components/font-awesome/fonts', 'public/assets/fonts')
       .copy('bower_components/font-awesome/fonts', 'public/build/assets/fonts')
});
