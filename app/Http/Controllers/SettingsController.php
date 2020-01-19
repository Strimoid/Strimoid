<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends BaseController
{
    public function showSettings()
    {
        $user = user();

        $subscribedGroups = $user->subscribedGroups()->get();
        $blockedGroups = $user->blockedGroups()->get();
        $moderatedGroups = $user->moderatedGroups()->get();
        $blockedUsers = $user->blockedUsers()->get();
        $bans = $user->bannedGroups()->get();

        return view('user.settings', compact(
            'user',
            'subscribedGroups',
            'blockedGroups',
            'moderatedGroups',
            'blockedUsers',
            'bans'
        ));
    }

    public function saveSettings(Request $request)
    {
        $this->validate($request, [
            'css_style' => 'url|safe_url|max:250',
            'contents_per_page' => 'integer|min:1|max:100',
            'entries_per_page' => 'integer|min:1|max:100',
            'timezone' => 'timezone',
        ]);

        $settings['enter_send'] = $request->get('enter_send') == 'on' ? true : false;
        $settings['pin_navbar'] = $request->get('pin_navbar') == 'on' ? true : false;
        $settings['notifications_sound'] = $request->get('notifications_sound') == 'on' ? true : false;
        $settings['homepage_subscribed'] = $request->get('homepage_subscribed') == 'on' ? true : false;
        $settings['disable_groupstyles'] = $request->get('disable_groupstyles') == 'on' ? true : false;
        $settings['css_style'] = $request->get('css_style');
        $settings['contents_per_page'] = (int) $request->get('contents_per_page');
        $settings['entries_per_page'] = (int) $request->get('entries_per_page');
        $settings['timezone'] = $request->get('timezone');
        $settings['notifications.auto_read'] = $request->get('notifications.auto_read') == 'on' ? true : false;

        foreach ($settings as $key => $value) {
            setting()->set($key, $value);
        }

        \Cache::tags(['user.settings', 'u.' . auth()->id()])->flush();

        return redirect()->route('user_settings')->with('success_msg', 'Ustawienia zostały zapisane.');
    }
}
