<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | You can find your API key on your Bugsnag dashboard.
    |
    | This api key points the Bugsnag notifier to the project in your account
    | which should receive your application's uncaught exceptions.
    |
    */

    'api_key' => env('BUGSNAG_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | App Type
    |--------------------------------------------------------------------------
    |
    | Set the type of application executing the current code.
    |
    */

    'app_type' => env('BUGSNAG_APP_TYPE'),

    /*
    |--------------------------------------------------------------------------
    | App Version
    |--------------------------------------------------------------------------
    |
    | Set the version of application executing the current code.
    |
    */

    'app_version' => env('BUGSNAG_APP_VERSION'),

    /*
    |--------------------------------------------------------------------------
    | Batch Sending
    |--------------------------------------------------------------------------
    |
    | Set to true to send the errors through to Bugsnag when the PHP process
    | shuts down, in order to prevent your app waiting on HTTP requests.
    |
    | Setting this to false will send an HTTP request straight away for each
    | error.
    |
    */

    'batch_sending' => env('BUGSNAG_BATCH_SENDING'),

    /*
    |--------------------------------------------------------------------------
    | Endpoint
    |--------------------------------------------------------------------------
    |
    | Set what server the Bugsnag notifier should send errors to. By default
    | this is set to 'https://notify.bugsnag.com', but for Bugsnag Enterprise
    | this should be the URL to your Bugsnag instance.
    |
    */

    'endpoint' => env('BUGSNAG_ENDPOINT'),

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    |
    | Use this if you want to ensure you don't send sensitive data such as
    | passwords, and credit card numbers to our servers. Any keys which
    | contain these strings will be filtered.
    |
    */

    'filters' => empty(env('BUGSNAG_FILTERS')) ? ['password'] : explode(',', str_replace(' ', '', env('BUGSNAG_FILTERS'))),

    /*
    |--------------------------------------------------------------------------
    | Hostname
    |--------------------------------------------------------------------------
    |
    | You can set the hostname of your server to something specific for you to
    | identify it by if needed.
    |
    */

    'hostname' => env('BUGSNAG_HOSTNAME'),

    /*
    |--------------------------------------------------------------------------
    | Proxy
    |--------------------------------------------------------------------------
    |
    | This is where you can set the proxy settings you'd like us to use when
    | communicating with Bugsnag when reporting errors.
    |
    */

    'proxy' => array_filter([
        'http' => env('HTTP_PROXY'),
        'https' => env('HTTPS_PROXY'),
        'no' => empty(env('NO_PROXY')) ? null : explode(',', str_replace(' ', '', env('NO_PROXY'))),
    ]),

    /*
    |--------------------------------------------------------------------------
    | Project Root
    |--------------------------------------------------------------------------
    |
    | Bugsnag marks stacktrace lines as in-project if they come from files
    | inside your “project root”. You can set this here.
    |
    | If this is not set, we will automatically try to detect it.
    |
    */

    'project_root' => env('BUGSNAG_PROJECT_ROOT'),

    /*
    |--------------------------------------------------------------------------
    | Strip Path
    |--------------------------------------------------------------------------
    |
    | You can set a strip path to have it also trimmed from the start of any
    | filepath in your stacktraces.
    |
    | If this is not set, we will automatically try to detect it.
    |
    */

    'strip_path' => env('BUGSNAG_STRIP_PATH'),

    /*
    |--------------------------------------------------------------------------
    | Query
    |--------------------------------------------------------------------------
    |
    | Enable this if you'd like us to automatically record all queries executed
    | as breadcrumbs.
    |
    */

    'query' => env('BUGSNAG_QUERY', true),

    /*
    |--------------------------------------------------------------------------
    | Bindings
    |--------------------------------------------------------------------------
    |
    | Enable this if you'd like us to include the query bindings in our query
    | breadcrumbs.
    |
    */

    'bindings' => env('BUGSNAG_QUERY_BINDINGS', false),

    /*
    |--------------------------------------------------------------------------
    | Release Stage
    |--------------------------------------------------------------------------
    |
    | Set the release stage to use when sending notifications to Bugsnag.
    |
    | Leaving this unset will default to using the application environment.
    |
    */

    'release_stage' => env('BUGSNAG_RELEASE_STAGE'),

    /*
    |--------------------------------------------------------------------------
    | Notify Release Stages
    |--------------------------------------------------------------------------
    |
    | Set which release stages should send notifications to Bugsnag.
    |
    */

    'notify_release_stages' => empty(env('BUGSNAG_NOTIFY_RELEASE_STAGES')) ? null : explode(',', str_replace(' ', '', env('BUGSNAG_NOTIFY_RELEASE_STAGES'))),

    /*
    |--------------------------------------------------------------------------
    | Send Code
    |--------------------------------------------------------------------------
    |
    | Bugsnag automatically sends a small snippet of the code that crashed to
    | help you diagnose even faster from within your dashboard. If you don’t
    | want to send this snippet, then set this to false.
    |
    */

    'send_code' => env('BUGSNAG_SEND_CODE', true),

    /*
    |--------------------------------------------------------------------------
    | Callbacks
    |--------------------------------------------------------------------------
    |
    | Enable this if you'd like us to enable our default set of notification
    | callbacks. These add things like the cookie information and session
    | details to the error to be sent to Bugsnag.
    |
    | If you'd like to add your own callbacks, you can call the
    | Bugsnag::registerCallback method from the boot method of your app
    | service provider.
    |
    */

    'callbacks' => env('BUGSNAG_CALLBACKS', true),

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    |
    | Enable this if you'd like us to set the current user logged in via
    | Laravel's authentication system.
    |
    | If you'd like to add your own user resolver, you can do this by using
    | callbacks via Bugsnag::registerCallback.
    |
    */

    'user' => env('BUGSNAG_USER', true),

    /*
    |--------------------------------------------------------------------------
    | Logger Notify Level
    |--------------------------------------------------------------------------
    |
    | This sets the level at which a logged message will trigger a notification
    | to Bugsnag.  By default this level will be 'notice'.
    |
    | Must be one of the Psr\Log\LogLevel levels from the Psr specification.
    |
    */

    'logger_notify_level' => env('BUGSNAG_LOGGER_LEVEL'),

    /*
    |--------------------------------------------------------------------------
    | Auto Capture Sessions
    |--------------------------------------------------------------------------
    |
    | Enable this to start tracking sessions and deliver them to Bugsnag.
    |
    */

    'auto_capture_sessions' => env('BUGSNAG_CAPTURE_SESSIONS', false),

    /*
    |--------------------------------------------------------------------------
    | Sessions Endpoint
    |--------------------------------------------------------------------------
    |
    | Sets a url to send tracked sessions to.
    |
    */

    'session_endpoint' => env('BUGSNAG_SESSION_ENDPOINT'),

    /*
    |--------------------------------------------------------------------------
    | Builds Endpoint
    |--------------------------------------------------------------------------
    |
    | Sets a url to send build reports to.
    |
    */

    'build_endpoint' => env('BUGSNAG_BUILD_ENDPOINT'),

];
