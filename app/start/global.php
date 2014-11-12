<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',
    app_path().'/handlers',

));

/* 404 error page */

App::missing(function($exception)
{
    if (Request::ajax() || Request::is('api/*'))
    {
        return Response::make('Invalid route', 404);
    }
    else
    {
        return Response::view('errors.404', [], 404);
    }
});

App::error(function(ModelNotFoundException $e)
{
    if (Request::ajax() || Request::server('HTTP_HOST') == 'api.strimoid.pl' || Request::is('api/*'))
    {
        return Response::make('Not found', 404);
    }
    else
    {
        return Response::view('errors.404', [], 404);
    }
});

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a rotating log file setup which creates a new file each day.
|
*/

$logFile = 'log-'.php_sapi_name().'.txt';

Log::useDailyFiles(storage_path().'/logs/'.$logFile);

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
    if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException)
    {
        return;
    }

    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
    {
        return;
    }

    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException)
    {
        return;
    }

	Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenace mode is in effect for this application.
|
*/

App::down(function()
{
    if (Auth::check() && Auth::user()->type == 'admin')
    {
        return;
    }

	return Response::make('<html><head>Strimoid - Przerwa techniczna</head><link href="http://fonts.googleapis.com/css?family=Hammersmith+One" rel="stylesheet" type="text/css><body><div style="margin: 50px auto; width: 960px; ">Przerwa techniczna, przepraszamy za utrudnienia. <br><br><iframe width="960" height="720" src="//www.youtube.com/embed/oHg5SJYRHA0" frameborder="0" allowfullscreen></iframe></div></body></html>', 503);
});

App::before(function($request) {
    setlocale(LC_ALL, 'pl_PL.utf8');
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/


require app_path().'/binds.php';
require app_path().'/composers.php';
require app_path().'/events.php';
require app_path().'/filters.php';
require app_path().'/validators.php';
require app_path().'/utils.php';
