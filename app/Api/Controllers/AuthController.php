<?php namespace Strimoid\Api\Controllers;

use Illuminate\Http\Request;
use Strimoid\Models\Notification;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $remember = $request->input('remember') == 'true' ? true : false;

        if (auth()->attempt(['name' => $request->input('username'),
            'password' => $request->input('password'), 'is_activated' => true], $remember)) {
            if (user()->removed_at || user()->blocked_at) {
                auth()->logout();
                return response()->json(['error' => 'Account blocked or removed'], 400);
            }

            $data = $this->getUserData();

            return response()->json($data);
        }

        return response()->json(['error' => 'Invalid login or password'], 400);
    }

    public function logout()
    {
        auth()->logout();
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
            ->target(auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(15)->get();

        $data = array_merge(user()->toArray(), [
            'subscribed_groups' => user()->subscribedGroups(),
            'blocked_groups'    => user()->blockedGroups(),
            'moderated_groups'  => user()->moderatedGroups(),
            'folders'           => user()->folders(),
            'notifications'     => $notifications,
        ]);

        return ['user' => $data];
    }
}
