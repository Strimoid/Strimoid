<?php namespace Strimoid\Http\ViewComposers;

use Illuminate\View\View;

class GroupBarComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        if (auth()->check()) {
            $subscriptions = user()->subscribedGroups()
                ->lists('urlname')
                ->sortBy(null, SORT_NATURAL | SORT_FLAG_CASE);

            $moderatedGroups = user()->moderatedGroups()
                ->lists('urlname')
                ->sortBy(null, SORT_NATURAL | SORT_FLAG_CASE);

            $observedUsers = user()->followedUsers()
                ->lists('name')
                ->sortBy(null, SORT_NATURAL | SORT_FLAG_CASE);

            $data = compact('subscriptions', 'moderatedGroups', 'observedUsers');
            $view->with($data);
        }
    }
}
