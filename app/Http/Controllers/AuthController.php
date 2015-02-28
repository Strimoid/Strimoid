<?php namespace Strimoid\Http\Controllers;

use Auth;
use Response;
use Illuminate\Http\Request;
use Str;
use Strimoid\Models\Group;
use Strimoid\Models\Notification;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $remember = $request->input('remember') == 'true' ? true : false;

        if (Auth::attempt(['shadow_name' => Str::lower(Input::get('username')),
            'password' => $request->input('password'), 'is_activated' => true, ], $remember)) {
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
        $folders = Auth::user()->folders->toArray();

        foreach ($folders as &$folder) {
            $folder['groups'] = Group::whereIn('_id', $folder['groups'])->get()->toArray();
        }

        $notifications = Notification::with(['sourceUser' => function ($q) { $q->select('avatar'); }])
            ->target(['user_id' => Auth::id()])
            ->orderBy('created_at', 'desc')
            ->take(15)->get();

        $data['user'] = array_merge(Auth::user()->toArray(), [
            'subscribed_groups' => Group::whereIn('_id', Auth::user()->subscribedGroups())->get()->toArray(),
            'blocked_groups'    => Group::whereIn('_id', Auth::user()->blockedGroups())->get()->toArray(),
            'moderated_groups'  => Group::whereIn('_id', Auth::user()->moderatedGroups())->get()->toArray(),
            'folders'           => $folders,
            'notifications'     => $notifications,
        ]);

        return $data;
    }
}
