var elixir = require('laravel-elixir')

require('laravel-elixir-bower')
require('laravel-elixir-stylus')
require('laravel-elixir-riot')

elixir(function(mix) {
    mix.bower('vendor.css', 'public/assets/css', 'vendor.js', 'public/assets/js')
       .stylus(null, 'public/assets/stylus')
       .stylesIn('public/assets/stylus', 'public/assets/css')
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
});
