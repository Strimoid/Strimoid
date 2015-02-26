<?php namespace Strimoid\Http\Controllers;

use Auth;
use Response;
use Strimoid\Models\Notification;

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
                '_id'      => $notification->_id,
                'title'    => $notification->title,
                'time'     => $notification->getLocalTime(),
                'time_ago' => $notification->created_at->diffForHumans(),
                'type'     => $notification->getTypeDescription(),
                'url'      => $notification->getURL(),
                'img'      => $notification->getThumbnailPath(),
            ];
        }

        return Response::json(['notifications' => $list]);
    }

    public function showJSONCount()
    {
        $newNotificationsCount = Notification::target(['user_id' => Auth::id(), 'read' => false]);

        $results['new'] = intval($newNotificationsCount);

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
        Notification::target(['user_id' => Auth::id(), 'read' => false])->update(['_targets.$.read' => true]);

        /*
        WS::send(json_encode([
            'topic' => 'u.'. Auth::id(),
            'tag' => Input::get('ntf_read'),
            'type' => 'notification_read_all'
        ]));
        */

        return Response::json(['status' => 'ok']);
    }

    public function registerGCM()
    {
        $validator = Validator::make(Input::all(), ['gcm_regid' => 'required|max:200']);

        if ($validator->fails()) {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

        Auth::user()->update(Input::only('gcm_regid'));

        return Response::json(['status' => 'ok']);
    }

    public function listNotifications()
    {
        $notifications = Notification::with('sourceUser')
            ->target(['user_id' => Auth::id()])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return $notifications;
    }

    public function edit(Notification $notification)
    {
        if (!in_array(Auth::id(), array_column($notification->_targets, 'user_id'))) {
            App::abort(403, 'Access denied');
        }

        $notification->target(['user_id' => Auth::id()])
            ->update(Input::only('read'));

        return Response::json(['status' => 'ok']);
    }
}
