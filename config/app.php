<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Site name
    |--------------------------------------------------------------------------
    */

    'name' => env('APP_NAME', 'Strm.pl'),
    'domain' => env('APP_DOMAIN', 'strm.pl'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('BASE_URL', 'http://strimoid.dev'),

    /*
    |--------------------------------------------------------------------------
    | CDN host
    |--------------------------------------------------------------------------
    */

    'cdn_host' => env('CDN_URL', '/i'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Europe/Warsaw',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY', 'iovahh7OoG6Phahrei7jouVufaemooF9'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Application Service Providers...
         */
        Strimoid\Providers\AppServiceProvider::class,
        Strimoid\Providers\AuthServiceProvider::class,
        Strimoid\Providers\BroadcastServiceProvider::class,
        Strimoid\Providers\ComposerServiceProvider::class,
        Strimoid\Providers\EventsServiceProvider::class,
        Strimoid\Providers\MarkdownServiceProvider::class,
        Strimoid\Providers\RepositoriesServiceProvider::class,
        Strimoid\Providers\RouteServiceProvider::class,
        Strimoid\Providers\ValidatorServiceProvider::class,
        Strimoid\Settings\SettingsServiceProvider::class,

        /*
         * Third party Service Providers...
         */
        Bugsnag\BugsnagLaravel\BugsnagServiceProvider::class,
        Collective\Html\HtmlServiceProvider::class,
        GrahamCampbell\Throttle\ThrottleServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,
        Jenssegers\Agent\AgentServiceProvider::class,
        Laracasts\Flash\FlashServiceProvider::class,
        Laracasts\Utilities\JavaScript\JavaScriptServiceProvider::class,
        Laravel\Passport\PassportServiceProvider::class,
        Laravel\Socialite\SocialiteServiceProvider::class,
        Msurguy\Honeypot\HoneypotServiceProvider::class,
        Tightenco\Ziggy\ZiggyServiceProvider::class,
        TwigBridge\ServiceProvider::class,
        Vinkla\Hashids\HashidsServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [
        'Arr'                => Illuminate\Support\Arr::class,
        'Auth'               => Illuminate\Support\Facades\Auth::class,
        'Input'              => Illuminate\Support\Facades\Request::class,
        'Lang'               => Illuminate\Support\Facades\Lang::class,
        'Request'            => Illuminate\Support\Facades\Request::class,
        'Response'           => Illuminate\Support\Facades\Response::class,
        'Route'              => Illuminate\Support\Facades\Route::class,
        'Session'            => Illuminate\Support\Facades\Session::class,
        'Storage'            => Illuminate\Support\Facades\Storage::class,
        'URL'                => Illuminate\Support\Facades\URL::class,
        'Form'               => Collective\Html\FormFacade::class,
        'Html'               => Collective\Html\HtmlFacade::class,
        'Str'                => Illuminate\Support\Str::class,
    ],

];
