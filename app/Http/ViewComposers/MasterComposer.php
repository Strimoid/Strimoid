<?php

namespace Strimoid\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Strimoid\Models\Group;
use Strimoid\Settings\Facades\Setting;

class MasterComposer
{
    private const DEFAULT_TITLE = 'Strm';
    public function __construct(private readonly \Illuminate\Auth\AuthManager $authManager, private readonly \Illuminate\Cache\CacheManager $cacheManager)
    {
    }

    public function compose(View $view): void
    {
        $data = $view->getData();

        if ($this->authManager->check()) {
            $notifications = $this->authManager->user()->notifications()
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->take(15)->get();
            $view->with('notifications', $notifications);

            $unreadCount = $this->authManager->user()->notifications()
                ->wherePivot('read', false)
                ->count();
            $view->with('newNotificationsCount', $unreadCount);
        }

        // Get object from which we can extract name to use as page title
        $currentGroup = head(Arr::only($data, ['group', 'folder', 'fakeGroup']));

        $view->with('currentGroup', $currentGroup);

        if (isset($currentGroup, $currentGroup->name)) {
            $pageTitle = $currentGroup->name;

            if ($currentGroup->urlname === 'all' && !Setting::get('homepage_subscribed')) {
                $pageTitle = self::DEFAULT_TITLE;
            }

            if ($currentGroup->urlname === 'subscribed' && Setting::get('homepage_subscribed')) {
                $pageTitle = self::DEFAULT_TITLE;
            }
        } else {
            $pageTitle = self::DEFAULT_TITLE;
        }

        $view->with('pageTitle', $pageTitle);

        // Needed by top bar with groups
        $popularGroups = $this->cacheManager->remember('popularGroups', now()->addHour(), fn () => Group::orderBy('subscribers_count', 'desc')
            ->take(30)->get(['id', 'name', 'urlname']));
        $view->with('popularGroups', $popularGroups);
    }
}
