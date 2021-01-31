<?php

namespace Strimoid\Http\ViewComposers;

use Illuminate\Support\Facades\Auth;
use Cache;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Strimoid\Settings\Facades\Setting;
use Strimoid\Models\Group;

class MasterComposer
{
    private const DEFAULT_TITLE = 'Strm';

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
        $currentGroup = head(Arr::only($data, ['group', 'folder', 'fakeGroup']));

        $view->with('currentGroup', $currentGroup);

        if (isset($currentGroup) && isset($currentGroup->name)) {
            $pageTitle = $currentGroup->name;

            if ($currentGroup->urlname == 'all' && !Setting::get('homepage_subscribed', false)) {
                $pageTitle = self::DEFAULT_TITLE;
            }

            if ($currentGroup->urlname == 'subscribed' && Setting::get('homepage_subscribed', false)) {
                $pageTitle = self::DEFAULT_TITLE;
            }
        } else {
            $pageTitle = self::DEFAULT_TITLE;
        }

        $view->with('pageTitle', $pageTitle);

        // Needed by top bar with groups
        $popularGroups = Cache::remember('popularGroups', now()->addHour(), fn () => Group::orderBy('subscribers_count', 'desc', true)
            ->take(30)->get(['id', 'name', 'urlname']));
        $view->with('popularGroups', $popularGroups);
    }
}
