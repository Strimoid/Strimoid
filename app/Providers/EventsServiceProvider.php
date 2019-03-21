<?php

namespace Strimoid\Providers;

use Auth;
use Carbon;
use Config;
use Guzzle;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Request;
use Strimoid\Handlers\Events\NewActionHandler;
use Strimoid\Handlers\Events\NotificationsHandler;
use Strimoid\Handlers\Events\PubSubHandler;
use Strimoid\Models\Entry;
use Strimoid\Models\User;

class EventsServiceProvider extends ServiceProvider
{
    public function boot(Dispatcher $events)
    {
        $this->app->booted(function () {
            if (Request::getUser() && Request::getPassword()) {
                return Auth::onceBasic('name');
            }
        });

        $events->listen('auth.login', function ($user) {
            $user->last_login = new Carbon();
            $user->last_ip = Request::getClientIp();
            $user->save();
        });

        /* IRC Notification */

        $events->listen(
            'eloquent.created: Strimoid\\Models\\User',
            function (User $user) {
                $url = config('app.hubot_url');

                if (!$url) {
                    return;
                }

                try {
                    Guzzle::post($url, ['json' => [
                        'room' => '#strimoid',
                        'text' => 'Mamy nowego użytkownika ' . $user->name . '!',
                    ]]);
                } catch (Exception $e) {
                }
            }
        );

        $events->listen(
            'eloquent.created: Strimoid\\Models\\Entry',
            function (Entry $entry) {
                $url = config('app.hubot_url');

                if (!$url) {
                    return;
                }

                try {
                    $text = strip_tags($entry->text);
                    $text = trim(preg_replace('/\s+/', ' ', $text));
                    $text = Str::limit($text, 100);

                    Guzzle::post($url, ['json' => [
                        'room' => '#strimoid-entries',
                        'text' => '[' . $entry->group->name . '] '
                            . $entry->user->name . ': ' . $text,
                    ]]);
                } catch (Exception $e) {
                }
            }
        );

        $events->subscribe(NewActionHandler::class);
        $events->subscribe(NotificationsHandler::class);
        $events->subscribe(PubSubHandler::class);
    }

    public function register()
    {
    }
}
