<?php

namespace Strimoid\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Strimoid\Models\NotificationTarget;

class NotificationMarkRead
{
    protected Guard $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($request->query->has('ntf_read') && $this->auth->check()) {
            $id = $request->query->get('ntf_read');
            $id = hashids_decode($id);

            NotificationTarget::where('notification_id', $id)
                ->where('user_id', $this->auth->id())
                ->update(['read' => true]);
        }

        return $next($request);
    }
}
