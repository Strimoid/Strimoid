<?php

namespace Strimoid\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Strimoid\Models\DailyAction;
use Strimoid\Models\Group;

class RankingController extends BaseController
{
    public function showRanking(Request $request, string $group = null)
    {
        $query = DailyAction::select(DB::raw('user_id, Sum(points) as points, Sum(contents) as contents,
                Sum(comments) as comments, Sum(entries) as entries, Sum(uv) as uv, Sum(dv) as dv'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('points', 'desc');

        if ($group) {
            $group = Group::name($group)->firstOrFail();
            $query->where('group_id', $group->getKey());
            $data['group'] = $group;
        }

        // Time filter
        $time = (int) $request->get('time') ?: 90;
        $fromDay = Carbon::now()->diffInDays(Carbon::create(2013, 1, 1)) - $time;
        $query->where('day', '>', $fromDay);

        $data['users'] = $query->paginate(50);

        return view('ranking.ranking', $data);
    }

    public function getIndex(Request $request)
    {
        $query = DailyAction::select(DB::raw('user_id, Sum(points) as points, Sum(contents) as contents,
                Sum(comments) as comments, Sum(entries) as entries, Sum(uv) as uv, Sum(dv) as dv'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('points', 'desc');

        if ($request->has('group')) {
            $group = Group::name($request->get('group'))->firstOrFail();
            $query->where('group_id', $group->getKey());

            $data['group'] = $group;
        }

        // Time filter
        if ($request->has('time')) {
            $fromDay = Carbon::now()->diffInDays(Carbon::create(2013, 1, 1)) - $request->get('time');
            $query->where('day', '>', $fromDay);
        }

        return $query->paginate(50);
    }
}
