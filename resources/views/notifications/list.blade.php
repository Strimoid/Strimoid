@extends('global.master')

@section('content_class') col-xs-12 @stop

@section('sidebar_class') hide @stop

@section('content')
<table class="table pull-left">
    <thead>
    <tr>
        <th></th>
        <th><i class="fa fa-envelope"></i> Treść</th>
        <th><i class="fa fa-user"></i> @ucFirstLang('common.author')</th>
        <th><i class="fa fa-clock-o"></i> @ucFirstLang('common.date')</th>
    </tr>
    </thead>
    <tbody>

    @foreach ($notifications as $notification)
    <tr>
        <td>{{ $notification->type }}</td>
        <td><a href="{!! $notification->url !!}">{{ $notification->title }}</a></td>
        <td>{{ $notification->user->name }}</td>
        <td>
            <time pubdate datetime="{{ $notification->created_at->format('c') }}" title="{{ $notification->getLocalTime() }}">
                {!! $notification->created_at->diffForHumans() !!}
            </time>
        </td>
    </tr>
    @endforeach

    </tbody>

</table>

{!! $notifications->links() !!}
@stop

@section('sidebar')

@stop
