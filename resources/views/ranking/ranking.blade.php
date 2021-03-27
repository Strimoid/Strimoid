@extends('global.master')

@section('content')
<table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>Nazwa użytkownika</th>
        <th style="width: 80px">Treści</th>
        <th style="width: 100px">Komentarze</th>
        <th style="width: 80px">Wpisy</th>
        <th style="width: 60px">UV</th>
        <th style="width: 60px">DV</th>
    </tr>
    </thead>
    <tbody>

    @foreach ($users as $index => $user)
    <tr @if(Auth::check() && $user->user_id == Auth::id()) class="warning" @endif>
        <td>{!! ( $index + $users->firstItem() ) !!}</td>
        <?php
            $day = Carbon::now()->diffInDays(Carbon::create(2013, 1, 1));

            $query = \Strimoid\Models\DailyAction::where('user_id', $user->user_id)
                ->select(DB::raw('SUM(points) as points, day'))
                ->groupBy('day')
                ->orderBy('day', 'asc');

            if (isset($group)) $query->where('group_id', $group->getKey());

            $results = $query->pluck('points', 'day');

            $chartData = [];

            for ($i = $day; $i >= ($day - 30); $i--) {
                $chartData[$i] = $results[$i] ?? 0;
            }

        ?>
        <td>
            <img src="{!! $user->user->getAvatarPath(20, 20) !!}" style="width: 20px; height: 20px">
            <a href="{!! route('user_profile', $user->user) !!}">{!! $user->user->name !!}</a>
            <img src="https://chart.googleapis.com/chart?chs=100x20&cht=ls&chco=0077CC&chf=bg,s,FFFFFF00&chds=a&chd=t:{!! implode(',', $chartData) !!}" class="pull-right">
        </td>
        <td>{!! $user->contents !!}</td>
        <td>{!! $user->comments !!}</td>
        <td>{!! $user->entries !!}</td>
        <td>{!! $user->uv !!}</td>
        <td>{!! $user->dv !!}</td>
    </tr>
    @endforeach

    </tbody>

</table>

{!! $users->links() !!}

@stop

@section('sidebar')

@if (isset($group))
@include('group.sidebar.description', ['group' => $group])
@include('group.sidebar.stats', ['group' => $group])
@endif

@stop
