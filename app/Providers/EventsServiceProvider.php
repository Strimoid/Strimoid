<?php namespace Strimoid\Providers;

use Auth, Carbon, Config, Guzzle, Request, Input;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Strimoid\Handlers\Events\NewActionHandler;
use Strimoid\Models\User;
use Strimoid\Models\Entry;
use Strimoid\Models\Notification;

class EventsServiceProvider extends ServiceProvider {

    /**
     * Register bindings in the container.
     *
     * @param  Dispatcher  $events
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        $this->app->booted(function()
        {
            if (Request::getUser() && Request::getPassword())
            {
                return Auth::onceBasic('_id');
            }
        });

        $events->listen('auth.login', function($user)
        {
            $user->last_login = new Carbon;
            $user->last_ip = Request::getClientIp();
            $user->save();
        });

        /* IRC Notification */

        $events->listen('eloquent.created: Strimoid\\Models\\User',
            function(User $user)
            {
                $url = Config::get('app.hubot_url');

                if (!$url) return;

                try {
                    Guzzle::post($url, ['json' => [
                        'room' => '#strimoid',
                        'text' => 'Mamy nowego użytkownika '. $user->name .'!',
                    ]]);
                } catch(Exception $e) {}
            });

        $events->listen('eloquent.created: Strimoid\\Models\\Entry',
            function(Entry $entry)
            {
                $url = Config::get('app.hubot_url');

                if (!$url) return;

                try {
                    $text = strip_tags($entry->text);
                    $text = trim(preg_replace('/\s+/', ' ', $text));
                    $text = Str::limit($text, 100);

                    Guzzle::post($url, ['json' => [
                        'room' => '#strimoid-entries',
                        'text' => '['. $entry->group->name .'] '
                            . $entry->user->name .': '. $text,
                    ]]);
                } catch(Exception $e) {}
            });

        $events->subscribe(new NewActionHandler);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
    }

}
