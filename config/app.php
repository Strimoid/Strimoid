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

    'site_name' => 'Strimoid.pl',

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
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Settings: "single", "daily", "syslog"
    |
    */

    'log' => 'daily',

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
        Strimoid\Providers\ApiServiceProvider::class,
        Strimoid\Providers\AppServiceProvider::class,
        Strimoid\Providers\ComposerServiceProvider::class,
        Strimoid\Providers\EventsServiceProvider::class,
        Strimoid\Providers\RepositoriesServiceProvider::class,
        Strimoid\Providers\RouteServiceProvider::class,
        Strimoid\Providers\ValidatorServiceProvider::class,
        Strimoid\Settings\SettingsServiceProvider::class,

        /*
         * Third party Service Providers...
         */
        Barryvdh\Cors\ServiceProvider::class,
        Collective\Html\HtmlServiceProvider::class,
        Dingo\Api\Provider\LaravelServiceProvider::class,
        GrahamCampbell\Markdown\MarkdownServiceProvider::class,
        GrahamCampbell\Throttle\ThrottleServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,
        Jenssegers\Agent\AgentServiceProvider::class,
        Laracasts\Flash\FlashServiceProvider::class,
        Laracasts\Utilities\JavaScript\JavaScriptServiceProvider::class,
        Laravel\Socialite\SocialiteServiceProvider::class,
        Lord\Laroute\LarouteServiceProvider::class,
        LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider::class,
        LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider::class,
        Msurguy\Honeypot\HoneypotServiceProvider::class,
        Thomaswelton\LaravelGravatar\LaravelGravatarServiceProvider::class,
        TwigBridge\ServiceProvider::class,
        Vinkla\Algolia\AlgoliaServiceProvider::class,
        Vinkla\Hashids\HashidsServiceProvider::class,
        Vinkla\Pusher\PusherServiceProvider::class,

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

        'App'                => Illuminate\Support\Facades\App::class,
        'Artisan'            => Illuminate\Support\Facades\Artisan::class,
        'Auth'               => Illuminate\Support\Facades\Auth::class,
        'Blade'              => Illuminate\Support\Facades\Blade::class,
        'Cache'              => Illuminate\Support\Facades\Cache::class,
        'Config'             => Illuminate\Support\Facades\Config::class,
        'Cookie'             => Illuminate\Support\Facades\Cookie::class,
        'Crypt'              => Illuminate\Support\Facades\Crypt::class,
        'DB'                 => Illuminate\Support\Facades\DB::class,
        'Eloquent'           => Illuminate\Database\Eloquent\Model::class,
        'Event'              => Illuminate\Support\Facades\Event::class,
        'File'               => Illuminate\Support\Facades\File::class,
        'Hash'               => Illuminate\Support\Facades\Hash::class,
        'Input'              => Illuminate\Support\Facades\Input::class,
        'Lang'               => Illuminate\Support\Facades\Lang::class,
        'Log'                => Illuminate\Support\Facades\Log::class,
        'Mail'               => Illuminate\Support\Facades\Mail::class,
        'Password'           => Illuminate\Support\Facades\Password::class,
        'Queue'              => Illuminate\Support\Facades\Queue::class,
        'Redirect'           => Illuminate\Support\Facades\Redirect::class,
        'Redis'              => Illuminate\Support\Facades\Redis::class,
        'Request'            => Illuminate\Support\Facades\Request::class,
        'Response'           => Illuminate\Support\Facades\Response::class,
        'Route'              => Illuminate\Support\Facades\Route::class,
        'Schema'             => Illuminate\Support\Facades\Schema::class,
        'Session'            => Illuminate\Support\Facades\Session::class,
        'Storage'            => Illuminate\Support\Facades\Storage::class,
        'URL'                => Illuminate\Support\Facades\URL::class,
        'Validator'          => Illuminate\Support\Facades\Validator::class,
        'View'               => Illuminate\Support\Facades\View::class,
        'Form'               => Collective\Html\FormFacade::class,
        'Html'               => Collective\Html\HtmlFacade::class,
        'Str'                => Illuminate\Support\Str::class,

        'Agent'      => Jenssegers\Agent\Facades\Agent::class,
        'Algolia'    => Vinkla\Algolia\Facades\Algolia::class,
        'Carbon'     => Carbon\Carbon::class,
        'Date'       => Carbon\Carbon::class,
        'Debugbar'   => Barryvdh\Debugbar\Facade::class,
        'Flash'      => Laracasts\Flash\Flash::class,
        'Gravatar'   => Thomaswelton\LaravelGravatar\Facades\Gravatar::class,
        'Hashids'    => Vinkla\Hashids\Facades\Hashids::class,
        'Image'      => Intervention\Image\Facades\Image::class,
        'Markdown'   => GrahamCampbell\Markdown\Facades\Markdown::class,
        'Setting'    => Strimoid\Settings\Facades\Setting::class,
        'Settings'   => Strimoid\Settings\Facades\Setting::class,
        'Socialite'  => Laravel\Socialite\Facades\Socialite::class,
        'Throttle'   => GrahamCampbell\Throttle\Facades\Throttle::class,
        'Twig'       => TwigBridge\Facade\Twig::class,

        'PDP'       => Strimoid\Facades\PDP::class,
        'Guzzle'    => Strimoid\Facades\Guzzle::class,
        'OEmbed'    => Strimoid\Facades\OEmbed::class,

    ],

];
