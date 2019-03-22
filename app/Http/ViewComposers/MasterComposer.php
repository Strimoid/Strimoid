<?php

namespace Strimoid\Http\ViewComposers;

use Auth;
use Cache;
use Illuminate\Contracts\View\View;
use Setting;
use Strimoid\Models\Group;

class MasterComposer
{
    public function compose(View $view): void
    {
        $data = $view->getData();

        if (Auth::check()) {
            $notifications = Auth::user()->notifications()
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->take(15)->get();
            $view->with('notifications', $notifications);

            $unreadCount = Auth::user()->notifications()
                ->wherePivot('read', false)
                ->count();
            $view->with('newNotificationsCount', $unreadCount);
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
        $popularGroups = Cache::remember('popularGroups', now()->addHour(), function () {
            return Group::orderBy('subscribers_count', 'desc', true)
                ->take(30)->get(['id', 'name', 'urlname']);
        });
        $view->with('popularGroups', $popularGroups);
    }
}
