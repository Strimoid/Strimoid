<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    protected function parseUsernames($body)
    {
        $body = preg_replace_callback('/@([a-z0-9_-]+)/i', function($matches) {
            $target = User::where('shadow_name', Str::lower($matches[1]))->first();

            if ($target)
            {
                return '[@' . str_replace('_', '\_', $target->name) . '](' . route('user_profile', $target->name) . ')';
            }
            else
            {
                return '@'. $matches[1];
            }
        }, $body);

        $body = preg_replace_callback('/(?<=\s|^)g\/([a-z0-9_żźćńółęąśŻŹĆĄŚĘŁÓŃ]+)/i', function($matches) {
            $target = Group::where('shadow_urlname', shadow($matches[1]))->first();

            if ($target)
            {
                return '[g/' . str_replace('_', '\_', $target->urlname) . '](' . route('group_contents', $target->urlname) . ')';
            }
            else
            {
                return 'g/'. $matches[1];
            }
        }, $body);

        return $body;
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
        $safeBuilder = $builder;
        $safeBuilder->projections = null;

        $total = $safeBuilder->remember($minutes)->count();
        $page = Paginator::getCurrentPage($total);
        $query = $builder->remember(null)->forPage($page, $perPage);

        $results = $query->get($columns);

        if ($filter)
        {
            $filter($results);
        }

        return Paginator::make($results->all(), $total, $perPage);
    }

}