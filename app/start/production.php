<?php

/*
 * Send exception reports to Rollbar (without 404 errors)
 * But only when running on production environment,
 * to save limit of logged items
 */

App::error(function(Exception $exception, $code)
{
    if (App::runningInConsole())
        return;

    if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException)
        return;

    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
        return;

    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException)
        return;

    $config = array(
        'access_token' => '52e3063ca4cd4a07bc28be643144fc1f',
        'environment' => App::environment(),
        'root' => base_path(),
        'max_errno' => E_USER_NOTICE
    );

    Rollbar::init($config, false, false);
    Rollbar::report_exception($exception);
});