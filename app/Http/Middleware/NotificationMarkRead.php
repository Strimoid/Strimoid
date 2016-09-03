<?php namespace Strimoid\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Strimoid\Models\NotificationTarget;

class NotificationMarkRead
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
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
