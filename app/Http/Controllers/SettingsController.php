<?php namespace Strimoid\Http\Controllers;

use Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Input;
use Redirect;
use Strimoid\Models\GroupBan;
use Strimoid\Models\GroupBlock;
use Strimoid\Models\GroupModerator;
use Strimoid\Models\GroupSubscriber;
use Strimoid\Models\UserBlocked;

class SettingsController extends BaseController
{
    use ValidatesRequests;

    public function showSettings()
    {
        $user = Auth::user();

        $subscribedGroups = $user->subscribedGroups();
        $blockedGroups    = $user->blockedGroups();
        $moderatedGroups  = $user->moderatedGroups();
        $blockedUsers     = $user->blockedUsers();
        $bans             = $user->bannedGroups();

        return view('user.settings', compact(
            'user', 'subscribedGroups', 'blockedGroups',
            'moderatedGroups', 'blockedUsers', 'bans'
        ));
    }

    public function saveSettings(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            'css_style'         => 'url|safe_url|max:250',
            'contents_per_page' => 'integer|min:1|max:100',
            'entries_per_page'  => 'integer|min:1|max:100',
            'timezone'          => 'timezone',
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
        $settings['notifications']['auto_read'] = Input::get('notifications.auto_read') == 'on' ? true : false;

        $user->settings = $settings;
        $user->save();

        return Redirect::route('user_settings')
            ->with('success_msg', 'Ustawienia zostały zapisane.');
    }
}
