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

    protected function sendNotifications($text, Closure $callback, User $sourceUser = null)
    {
        $sourceUser = $sourceUser ?: Auth::user();

        preg_match_all('/@([a-z0-9_-]+)/i', $text, $mentionedUsers, PREG_SET_ORDER);

        $uniqueUsers = array();

        foreach ($mentionedUsers as $mentionedUser)
        {
            if (!isset($mentionedUser[1]))
            {
                break;
            }

            if (!in_array(Str::lower($mentionedUser[1]), $uniqueUsers))
            {
                $uniqueUsers[] = Str::lower($mentionedUser[1]);
            }
        }

        $targets = array();

        foreach ($uniqueUsers as $uniqueUser)
        {
            $target = User::shadow($uniqueUser)->first();

            if ($target && $target->_id != Auth::id() && !$target->isBlocking($sourceUser))
            {
                $targets[] = ['_id' => $target->_id, 'read' => false];
            }
        }

        $notification = new Notification();

        $notification->sourceUser()->associate($sourceUser);
        $notification->users = $targets;

        $callback($notification);

        $notification->save();
    }

    protected function cachedPaginate($builder, $perPage, $minutes, $columns = ['*'], Closure $filter = null)
    {
        $total = $builder->remember($minutes)->count();
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