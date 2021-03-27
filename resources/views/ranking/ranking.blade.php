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
        <td>
            <img src="{!! $user->user->getAvatarPath(20, 20) !!}" style="width: 20px; height: 20px">
            <a href="{!! route('user_profile', $user->user) !!}">{!! $user->user->name !!}</a>
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
