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
    <tr @if(Auth::check() && $user->user_id == Auth::user()->_id) class="warning" @endif>
        <td>{!! (($index + 1) + (($users->getCurrentPage()-1) * $users->getPerPage())) !!}</td>
        <?php
            $day = Carbon::now()->diffInDays(Carbon::create(2013, 1, 1));

            $query = DailyAction::remember(rand(720, 1500))
                ->where('user_id', $user->user_id)
                ->groupBy('day')
                ->orderBy('day', 'asc');

            if (isset($group))
                $query->where('group_id', $group->_id);

            $results = $query->lists('points', 'day');

            $chartData = [];

            for ($i = $day; $i >= ($day - 30); $i--) {
                $chartData[$i] = isset($results[$i]) ? $results[$i] : 0;
            }

        ?>
        <td>
            <img src="{!! $user->user->getAvatarPath() !!}" style="width: 20px; height: 20px">
            <a href="{!! route('user_profile', $user->user_id) !!}">{!! $user->user_id !!}</a>
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
@include('group.sidebar.description', array('group' => $group))
@include('group.sidebar.stats', array('group' => $group))
@endif

@stop
