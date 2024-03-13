<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends BaseController
{
    public function __construct(private readonly \Illuminate\Contracts\View\Factory $viewFactory, private readonly \Illuminate\Cache\CacheManager $cacheManager, private readonly \Illuminate\Contracts\Auth\Guard $guard, private readonly \Illuminate\Routing\Redirector $redirector)
    {
    }
    public function showSettings()
    {
        $user = user();

        $subscribedGroups = $user->subscribedGroups()->get();
        $blockedGroups = $user->blockedGroups()->get();
        $moderatedGroups = $user->moderatedGroups()->get();
        $blockedUsers = $user->blockedUsers()->get();
        $bans = $user->bannedGroups()->get();

        return $this->viewFactory->make('user.settings', compact(
            'user',
            'subscribedGroups',
            'blockedGroups',
            'moderatedGroups',
            'blockedUsers',
            'bans'
        ));
    }

    public function saveSettings(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'css_style' => 'nullable|url|safe_url|max:250',
            'contents_per_page' => 'integer|min:1|max:100',
            'entries_per_page' => 'integer|min:1|max:100',
            'language' => 'in:auto,en,pl',
            'timezone' => 'timezone',
        ]);

        $settings['enter_send'] = $request->get('enter_send') === 'on';
        $settings['pin_navbar'] = $request->get('pin_navbar') === 'on';
        $settings['notifications_sound'] = $request->get('notifications_sound') === 'on';
        $settings['homepage_subscribed'] = $request->get('homepage_subscribed') === 'on';
        $settings['disable_groupstyles'] = $request->get('disable_groupstyles') === 'on';
        $settings['css_style'] = $request->get('css_style');
        $settings['contents_per_page'] = (int) $request->get('contents_per_page');
        $settings['entries_per_page'] = (int) $request->get('entries_per_page');
        $settings['language'] = $request->get('language');
        $settings['timezone'] = $request->get('timezone');
        $settings['notifications.auto_read'] = $request->get('notifications.auto_read') === 'on';

        foreach ($settings as $key => $value) {
            setting()->set($key, $value);
        }

        $this->cacheManager->tags(['user.settings', 'u.' . $this->guard->id()])->flush();

        return $this->redirector->route('user_settings')->with('success_msg', 'Ustawienia zostały zapisane.');
    }
}
