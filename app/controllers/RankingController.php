<?php

class RankingController extends BaseController
{

    public function showRanking($group = null)
    {
        $conn = DB::connection('stats');

        $query = DailyAction::select(DB::raw('user_id, Sum(points) as points, Sum(contents) as contents,
                Sum(comments) as comments, Sum(entries) as entries, Sum(uv) as uv, Sum(dv) as dv'))
            ->with(['user' => function($q) { $q->select(['name', 'avatar']); }])
            ->groupBy('user_id')
            ->orderBy('points', 'desc');

        if ($group)
        {
            $group = Group::where('shadow_urlname', shadow($group))->firstOrFail();
            $query->where('group_id', $group->_id);

            $data['group'] = $group;
        }

        if (Input::has('user'))
        {
            $user = User::where('shadow_name', Str::lower(Input::get('user')))->firstOrFail();
        }

        $data['users'] = $query->paginate(50);

        return View::make('ranking.ranking', $data);
    }

    public function getIndex()
    {
        $conn = DB::connection('stats');

        $query = DailyAction::select(DB::raw('user_id, Sum(points) as points, Sum(contents) as contents,
                Sum(comments) as comments, Sum(entries) as entries, Sum(uv) as uv, Sum(dv) as dv'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('points', 'desc');

        if (Input::has('group'))
        {
            $group = Group::where('shadow_urlname', Str::lower(Input::get('group')))->firstOrFail();
            $query->where('group_id', $group->_id);

            $data['group'] = $group;
        }

        // Time filter
        if (Input::has('time'))
        {
            $fromDay = Carbon::now()->diffInDays(Carbon::create(2013, 1, 1)) - Input::get('time');
            $query->where('day', '>', $fromDay);
        }

        return $query->paginate(50);
    }

}