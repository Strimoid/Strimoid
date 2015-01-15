<?php namespace Strimoid\Providers;

use Carbon, Config, Guzzle, Request, Event;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Strimoid\Handlers\Events\NewActionHandler;
use Strimoid\Models\User;
use Strimoid\Models\Entry;

class EventsServiceProvider extends ServiceProvider {

    /**
     * Register bindings in the container.
     *
     * @param  Dispatcher  $events
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        $events->listen('auth.login', function($user)
        {
            $user->last_login = new Carbon;
            $user->last_ip = Request::getClientIp();
            $user->save();
        });

        /* IRC Notification */

        User::created(function(User $user)
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

        Entry::created(function(Entry $entry)
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
