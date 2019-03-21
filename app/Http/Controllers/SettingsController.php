<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\Request;
use Input;

class SettingsController extends BaseController
{
    public function showSettings()
    {
        $user = user();

        $subscribedGroups = $user->subscribedGroups();
        $blockedGroups = $user->blockedGroups();
        $moderatedGroups = $user->moderatedGroups();
        $blockedUsers = $user->blockedUsers()->get();
        $bans = $user->bannedGroups();

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

        $settings['enter_send'] = Input::get('enter_send') == 'on' ? true : false;
        $settings['pin_navbar'] = Input::get('pin_navbar') == 'on' ? true : false;
        $settings['notifications_sound'] = Input::get('notifications_sound') == 'on' ? true : false;
        $settings['homepage_subscribed'] = Input::get('homepage_subscribed') == 'on' ? true : false;
        $settings['disable_groupstyles'] = Input::get('disable_groupstyles') == 'on' ? true : false;
        $settings['css_style'] = Input::get('css_style');
        $settings['contents_per_page'] = (int) Input::get('contents_per_page');
        $settings['entries_per_page'] = (int) Input::get('entries_per_page');
        $settings['timezone'] = Input::get('timezone');
        $settings['notifications.auto_read'] = Input::get('notifications.auto_read') == 'on' ? true : false;

        foreach ($settings as $key => $value) {
            setting()->set($key, $value);
        }

        \Cache::tags(['user.settings', 'u.' . auth()->id()])->flush();

        return redirect()->route('user_settings')->with('success_msg', 'Ustawienia zostały zapisane.');
    }
}
