<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

Artisan::add(new ChangePassword);
Artisan::add(new CreateUser());
Artisan::add(new TwitterPost());
Artisan::add(new FacebookPost());
Artisan::add(new DeleteAvatar());
Artisan::add(new DeleteUser());
Artisan::add(new BlockUser());
Artisan::add(new AddModerator());
Artisan::add(new GenerateSitemap());
Artisan::add(new UpdateStats());
Artisan::add(new UpdateThresholds());
Artisan::add(new UpdateUserPoints());

$providers = array(
    'Illuminate\Foundation\Providers\ArtisanServiceProvider',
    'Illuminate\Session\CommandsServiceProvider',
    'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
);

foreach ($providers as $provider)
{
    App::register($provider);
}

