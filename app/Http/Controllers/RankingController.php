<?php namespace Strimoid\Http\Controllers;

use Carbon;
use DB;
use Input;
use Strimoid\Models\DailyAction;
use Strimoid\Models\Group;
use Strimoid\Models\User;

class RankingController extends BaseController
{
    public function showRanking($group = null)
    {
        $query = DailyAction::select(DB::raw('user_id, Sum(points) as points, Sum(contents) as contents,
                Sum(comments) as comments, Sum(entries) as entries, Sum(uv) as uv, Sum(dv) as dv'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('points', 'desc');

        if ($group) {
            $query->where('group_id', $group->getKey());
            $data['group'] = $group;
        }

        if (Input::has('user')) {
            $user = User::name(Input::get('user'))->firstOrFail();
        }

        // Time filter
        $time = intval(Input::get('time')) ?: 90;
        $fromDay = Carbon::now()->diffInDays(Carbon::create(2013, 1, 1)) - $time;
        $query->where('day', '>', $fromDay);

        $data['users'] = $query->paginate(50);

        return view('ranking.ranking', $data);
    }

    public function getIndex()
    {
        $query = DailyAction::select(DB::raw('user_id, Sum(points) as points, Sum(contents) as contents,
                Sum(comments) as comments, Sum(entries) as entries, Sum(uv) as uv, Sum(dv) as dv'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('points', 'desc');

        if (Input::has('group')) {
            $group = Group::name(Input::get('group'))->firstOrFail();
            $query->where('group_id', $group->getKey());

            $data['group'] = $group;
        }

        // Time filter
        if (Input::has('time')) {
            $fromDay = Carbon::now()->diffInDays(Carbon::create(2013, 1, 1)) - Input::get('time');
            $query->where('day', '>', $fromDay);
        }

        return $query->paginate(50);
    }
}
