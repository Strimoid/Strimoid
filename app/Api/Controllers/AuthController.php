<?php

namespace Strimoid\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Strimoid\Models\Notification;

class AuthController extends BaseController
{
    public function __construct(private \Illuminate\Contracts\Auth\Guard $guard, private \Illuminate\Contracts\Routing\ResponseFactory $responseFactory)
    {
    }
    public function login(Request $request): JsonResponse
    {
        $remember = $request->input('remember') === 'true';

        if ($this->guard->attempt(['name' => $request->input('username'),
            'password' => $request->input('password'), 'is_activated' => true, ], $remember)) {
            if (user()->removed_at || user()->blocked_at) {
                $this->guard->logout();

                return $this->responseFactory->json(['error' => 'Account blocked or removed'], 400);
            }

            $data = $this->getUserData();

            return $this->responseFactory->json($data);
        }

        return $this->responseFactory->json(['error' => 'Invalid login or password'], 400);
    }

    public function logout(): void
    {
        $this->guard->logout();
    }

    public function sync(): array
    {
        return $this->getUserData();
    }

    private function getUserData(): array
    {
        $notifications = Notification::with([
                'user' => function ($q): void {
                    $q->select('avatar');
                },
            ])
            ->target($this->guard->id())
            ->orderBy('created_at', 'desc')
            ->take(15)->get();

        $data = array_merge(user()->toArray(), [
            'subscribed_groups' => user()->subscribedGroups(),
            'blocked_groups' => user()->blockedGroups(),
            'moderated_groups' => user()->moderatedGroups(),
            'folders' => user()->folders(),
            'notifications' => $notifications,
        ]);

        return ['user' => $data];
    }
}
