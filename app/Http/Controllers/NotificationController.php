<?php

namespace Strimoid\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Response;
use Strimoid\Models\Notification;
use Strimoid\Models\NotificationTarget;

class NotificationController extends BaseController
{
    public function showJSONList($count)
    {
        if ($count > 50 || $count < 0) {
            $count = 50;
        }

        $notifications = Notification::target(['user_id' => Auth::id()])
            ->orderBy('created_at', 'desc')
            ->take($count)->get();

        $list = [];

        foreach ($notifications as $notification) {
            $list[] = [
                'id' => $notification->hashId(),
                'title' => $notification->title,
                'time' => $notification->getLocalTime(),
                'time_ago' => $notification->created_at->diffForHumans(),
                'type' => $notification->getTypeDescription(),
                'url' => $notification->getURL(),
                'img' => $notification->getThumbnailPath(),
            ];
        }

        return Response::json(['notifications' => $list]);
    }

    public function showJSONCount()
    {
        $newNotificationsCount = Notification::target(['user_id' => Auth::id(), 'read' => false]);

        $results['new'] = (int) $newNotificationsCount;

        return Response::json($results);
    }

    public function showList()
    {
        $notifications = Notification::target(['user_id' => Auth::id()])
            ->orderBy('created_at', 'desc')->paginate(30);

        return view('notifications.list', ['notifications' => $notifications]);
    }

    public function markAllAsRead()
    {
        NotificationTarget::where('user_id', Auth::id())
            ->where('read', false)
            ->update(['read' => true]);

        return Response::json(['status' => 'ok']);
    }

    public function registerGCM(Request $request)
    {
        $validator = Validator::make($request->all(), ['gcm_regid' => 'required|max:200']);

        if ($validator->fails()) {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

        Auth::user()->update($request->only('gcm_regid'));

        return Response::json(['status' => 'ok']);
    }

    public function listNotifications()
    {
        return Notification::with('user')
            ->target(['user_id' => Auth::id()])
            ->orderBy('created_at', 'desc')
            ->paginate(50);
    }

    public function edit(Request $request, Notification $notification)
    {
        if (!in_array(Auth::id(), array_column($notification->_targets, 'user_id'))) {
            App::abort(403, 'Access denied');
        }

        $notification->target(['user_id' => Auth::id()])
            ->update($request->only('read'));

        return Response::json(['status' => 'ok']);
    }
}
