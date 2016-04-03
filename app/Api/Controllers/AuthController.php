<?php namespace Strimoid\Api\Controllers;

use Auth;
use Illuminate\Http\Request;
use Response;
use Str;
use Strimoid\Http\Controllers\BaseController;
use Strimoid\Models\Group;
use Strimoid\Models\Notification;
use Vinkla\Pusher\Facades\Pusher;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $remember = $request->input('remember') == 'true' ? true : false;

        if (Auth::attempt(['name' => $request->input('username'),
            'password' => $request->input('password'), 'is_activated' => true], $remember)) {
            if (Auth::user()->removed_at || Auth::user()->blocked_at) {
                Auth::logout();
                return Response::json(['error' => 'Account blocked or removed'], 400);
            }

            $data = $this->getUserData();

            return Response::json($data);
        }

        return Response::json(['error' => 'Invalid login or password'], 400);
    }

    public function logout()
    {
        Auth::logout();
    }

    public function sync()
    {
        return $this->getUserData();
    }

    private function getUserData()
    {
        $notifications = Notification::with([
                'user' => function ($q) { $q->select('avatar'); }
            ])
            ->target(Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(15)->get();

        $data['user'] = array_merge(Auth::user()->toArray(), [
            'subscribed_groups' => Auth::user()->subscribedGroups(),
            'blocked_groups'    => Auth::user()->blockedGroups(),
            'moderated_groups'  => Auth::user()->moderatedGroups(),
            'folders'           => Auth::user()->folders(),
            'notifications'     => $notifications,
        ]);

        return $data;
    }
}
