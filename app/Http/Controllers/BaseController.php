<?php namespace Strimoid\Http\Controllers;

use Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Setting;

class BaseController extends Controller
{
    use ValidatesRequests;

    /**
     * Return homepage group name.
     *
     * @return string
     */
    protected function homepageGroup()
    {
        $groupName = 'all';

        // Show popular instead of all as homepage for guests
        $groupName = Auth::guest() ? 'popular' : $groupName;

        // Maybe user is having subscribed set as his homepage?
        $subscribedEnabled = Setting::get('homepage_subscribed', false);
        $groupName = $subscribedEnabled ? 'subscribed' : $groupName;

        return $groupName;
    }
}
