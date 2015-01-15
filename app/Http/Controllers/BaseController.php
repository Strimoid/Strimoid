<?php namespace Strimoid\Http\Controllers;

use Closure;
use Illuminate\Routing\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;

class BaseController extends Controller {

    public function __construct(Manager $fractal)
    {
        $this->fractal = $fractal;
    }

    protected function sendNotifications($targets, Closure $callback, User $sourceUser = null)
    {
        $sourceUser = $sourceUser ?: Auth::user();

        if (is_array($targets))
        {
            $uniqueUsers = $targets;
        }
        else
        {
            preg_match_all('/@([a-z0-9_-]+)/i', $targets, $mentionedUsers, PREG_SET_ORDER);

            $uniqueUsers = array();

            foreach ($mentionedUsers as $mentionedUser)
            {
                if (!isset($mentionedUser[1]) || in_array(Str::lower($mentionedUser[1]), $uniqueUsers))
                {
                    break;
                }

                $uniqueUsers[] = Str::lower($mentionedUser[1]);
            }
        }

        if (!$uniqueUsers)
        {
            return;
        }

        $notification = new Notification();
        $notification->sourceUser()->associate($sourceUser);

        foreach ($uniqueUsers as $uniqueUser)
        {
            $user = User::shadow($uniqueUser)->first();

            if ($user && $user->_id != Auth::id() && !$user->isBlockingUser($sourceUser))
            {
                $target = new NotificationTarget();
                $target->user()->associate($user);

                $notification->targets()->associate($target);
            }
        }

        $callback($notification);

        $notification->save();
    }

    protected function cachedPaginate($builder, $perPage, $minutes, $columns = ['*'], Closure $filter = null)
    {
        $page = Paginator::resolveCurrentPage();
        $total = $builder->count();
        $query = $builder->forPage($page, $perPage);

        $results = $query->get($columns);

        if ($filter)
        {
            $filter($results);
        }

        return new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath()
        ]);
    }

    protected function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);
        $rootScope = $this->fractal->createData($resource);

        return Response::json($rootScope->toArray());
    }

    protected function respondWithCollection($collection, $callback)
    {
        $resource = new Collection($collection, $callback);
        $rootScope = $this->fractal->createData($resource);

        return Response::json($rootScope->toArray());
    }

}