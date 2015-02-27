var elixir = require('laravel-elixir');

require('laravel-elixir-bower');

elixir(function(mix) {
    mix.bower('vendor.css', 'public/assets/css', 'vendor.js', 'public/assets/js')
       .stylesIn('resources/assets/css', 'public/assets/css')
       .scriptsIn('resources/assets/js', 'public/assets/js')
       .version([
            'public/assets/css/all.css',
            'public/assets/css/vendor.css',
            'public/assets/js/all.js',
            'public/assets/js/vendor.js'
        ]);
});

elixir(function(mix) {
    mix.copy('bower_components/bootstrap/dist/fonts', 'public/assets/fonts')
       .copy('bower_components/bootstrap/dist/fonts', 'public/build/assets/fonts');
});
