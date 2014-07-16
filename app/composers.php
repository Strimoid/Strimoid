<?php

View::composer('global.master', function($view)
{
    $data = $view->getData();

    $assetsHost = (Request::secure()) ? '' : 'http://static.strimoid.pl';

    $view->with('cssFilename', $assetsHost . Config::get('assets.style.css'));
    $view->with('jsFilename', $assetsHost . Config::get('assets.app.js'));

    if (Auth::check())
    {
        // Load last 15 notifications
        $notifications = Notification::with(['sourceUser' => function($q) { $q->select('avatar')->remember(3); }])
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

    if (isset($currentGroup) && isset($currentGroup->name))
    {
        $pageTitle = $currentGroup->name;

        // Homepage title shall always be Strimoid.pl
        if ($currentGroup->urlname == 'all' && !Settings::get('homepage_subscribed'))
        {
            $pageTitle = 'Strimoid';
        }

        if ($currentGroup->urlname == 'subscribed' && Settings::get('homepage_subscribed'))
        {
            $pageTitle = 'Strimoid';
        }
    }
    else
    {
        $pageTitle = 'Strimoid';
    }

    $view->with('pageTitle', $pageTitle);

    // Needed by top bar with groups
    $popularGroups = Group::remember(600)
        ->orderBy('subscribers', 'desc')
        ->take(30)->get();

    $view->with('popularGroups', $popularGroups);
});
