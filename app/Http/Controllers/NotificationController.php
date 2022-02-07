<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Strimoid\Models\Notification;
use Strimoid\Models\NotificationTarget;

class NotificationController extends BaseController
{
    public function __construct(private \Illuminate\Auth\AuthManager $authManager, private \Illuminate\Contracts\Routing\ResponseFactory $responseFactory, private \Illuminate\Contracts\View\Factory $viewFactory, private \Illuminate\Validation\Factory $validationFactory, private \Illuminate\Foundation\Application $application)
    {
    }
    public function showJSONList($count)
    {
        if ($count > 50 || $count < 0) {
            $count = 50;
        }

        $notifications = Notification::target(['user_id' => $this->authManager->id()])
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

        return $this->responseFactory->json(['notifications' => $list]);
    }

    public function showJSONCount()
    {
        $newNotificationsCount = Notification::target(['user_id' => $this->authManager->id(), 'read' => false]);

        $results['new'] = (int) $newNotificationsCount;

        return $this->responseFactory->json($results);
    }

    public function showList()
    {
        $notifications = Notification::target(['user_id' => $this->authManager->id()])
            ->orderBy('created_at', 'desc')->paginate(30);

        return $this->viewFactory->make('notifications.list', ['notifications' => $notifications]);
    }

    public function markAllAsRead()
    {
        NotificationTarget::where('user_id', $this->authManager->id())
            ->where('read', false)
            ->update(['read' => true]);

        return $this->responseFactory->json(['status' => 'ok']);
    }

    public function registerGCM(Request $request)
    {
        $validator = $this->validationFactory->make($request->all(), ['gcm_regid' => 'required|max:200']);

        if ($validator->fails()) {
            return $this->responseFactory->json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

        $this->authManager->user()->update($request->only('gcm_regid'));

        return $this->responseFactory->json(['status' => 'ok']);
    }

    public function listNotifications()
    {
        return Notification::with('user')
            ->target(['user_id' => $this->authManager->id()])
            ->orderBy('created_at', 'desc')
            ->paginate(50);
    }

    public function edit(Request $request, Notification $notification)
    {
        if (!in_array($this->authManager->id(), array_column($notification->_targets, 'user_id'))) {
            $this->application->abort(403, 'Access denied');
        }

        $notification->target(['user_id' => $this->authManager->id()])
            ->update($request->only('read'));

        return $this->responseFactory->json(['status' => 'ok']);
    }
}
