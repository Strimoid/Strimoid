<?php namespace Strimoid\Http\ViewComposers;

use Auth;
use Cache;
use Illuminate\Contracts\View\View;
use Setting;
use Strimoid\Models\Group;
use Strimoid\Models\Notification;

class MasterComposer
{
    /**
     * Create a new profile composer.
     */
    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $data = $view->getData();

        if (Auth::check()) {
            // Load last 15 notifications
            $notifications = Notification::with('sourceUser')
                ->target(['user_id' => Auth::id()])
                ->orderBy('created_at', 'desc')
                ->take(15)->get();

            $view->with('notifications', $notifications);

            // And check how much unread notifications user has
            $elemMatch = ['user_id' => Auth::id(), 'read' => false];

            $newNotificationsCount = Notification::target($elemMatch)->count();

            $view->with('newNotificationsCount', $newNotificationsCount);
        }

        // Get object from which we can extract name to use as page title
        $currentGroup = head(array_only($data, ['group', 'folder', 'fakeGroup']));

        $view->with('currentGroup', $currentGroup);

        if (isset($currentGroup) && isset($currentGroup->name)) {
            $pageTitle = $currentGroup->name;

            // Homepage title shall always be Strimoid.pl
            if ($currentGroup->urlname == 'all' && !Setting::get('homepage_subscribed', false)) {
                $pageTitle = 'Strimoid';
            }

            if ($currentGroup->urlname == 'subscribed' && Setting::get('homepage_subscribed', false)) {
                $pageTitle = 'Strimoid';
            }
        } else {
            $pageTitle = 'Strimoid';
        }

        $view->with('pageTitle', $pageTitle);

        // Needed by top bar with groups
        $popularGroups = Cache::remember('popularGroups', 60, function () {
            return Group::orderBy('subscribers_count', 'desc', true)
                ->take(30)->get(['id', 'name', 'urlname']);
        });
        $view->with('popularGroups', $popularGroups);
    }
}
