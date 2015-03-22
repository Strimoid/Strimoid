<?php namespace Strimoid\Http\Controllers;

use Auth;
use Closure;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Settings;
use Str;
use Strimoid\Models\Notification;
use Strimoid\Models\User;

class BaseController extends Controller
{
    use ValidatesRequests;

    protected function sendNotifications($targets,
        Closure $callback, User $sourceUser = null)
    {
        $sourceUser = $sourceUser ?: Auth::user();

        if (is_array($targets)) {
            $uniqueUsers = $targets;
        } else {
            preg_match_all('/@([a-z0-9_-]+)/i', $targets, $mentionedUsers, PREG_SET_ORDER);

            $uniqueUsers = [];

            foreach ($mentionedUsers as $mentionedUser) {
                if (! isset($mentionedUser[1])
                    || in_array(Str::lower($mentionedUser[1]), $uniqueUsers)
                ) {
                    break;
                }

                $uniqueUsers[] = Str::lower($mentionedUser[1]);
            }
        }

        if (! $uniqueUsers) {
            return;
        }

        $notification = new Notification();
        $notification->sourceUser()->associate($sourceUser);

        foreach ($uniqueUsers as $uniqueUser) {
            $user = User::name($uniqueUser)->first();

            if ($user && $user->getKey() != Auth::id()
                && !$user->isBlockingUser($sourceUser)) {
                $notification->addTarget($user);
            }
        }

        $callback($notification);
        $notification->save();
    }

    /**
     * Return homepage group name.
     *
     * @return string
     */
    protected function homepageGroup()
    {
        $groupName = 'all';

        // Show popular instead of all as homepage for guests
        $groupName = Auth::guest() ? 'popular' : $groupName;

        // Maybe user is having subscribed set as his homepage?
        $subscribedEnabled = Settings::get('homepage_subscribed');
        $groupName = $subscribedEnabled ? 'subscribed' : $groupName;

        return $groupName;
    }
}
