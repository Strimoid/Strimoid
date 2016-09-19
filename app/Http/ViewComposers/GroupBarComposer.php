<?php namespace Strimoid\Http\ViewComposers;

use Illuminate\View\View;

class GroupBarComposer
{
    public function compose(View $view)
    {
        if (auth()->check()) {
            $subscriptions = user()->subscribedGroups()
                ->pluck('urlname')
                ->sortBy(null, SORT_NATURAL | SORT_FLAG_CASE);

            $moderatedGroups = user()->moderatedGroups()
                ->pluck('urlname')
                ->sortBy(null, SORT_NATURAL | SORT_FLAG_CASE);

            $observedUsers = user()->followedUsers()
                ->pluck('name')
                ->sortBy(null, SORT_NATURAL | SORT_FLAG_CASE);

            $data = compact('subscriptions', 'moderatedGroups', 'observedUsers');
            $view->with($data);
        }
    }
}
