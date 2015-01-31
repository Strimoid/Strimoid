<?php namespace Strimoid\Http\Middleware; 

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Strimoid\Models\Notification;

class NotificationMarkRead {


    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * @var Notification
     */
    protected $notification;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     * @param Notification $notification
     */
    public function __construct(Guard $auth, Notification $notification)
    {
        $this->auth = $auth;
        $this->notification = $notification;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->query->has('ntf_read')
            && $this->auth->check())
        {
            $id = $request->query->get('ntf_read');
            $id = b58_to_mid($id);

            $this->notification->where('_id', $id)
                ->target(['user_id' => $this->auth->id()])
                ->update(['_targets.$.read' => true]);
        }

        return $next($request);
    }


}
