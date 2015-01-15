@extends('global.master')

@section('content_class') col-xs-12 @stop

@section('sidebar_class') hide @stop

@section('content')
<table class="table pull-left">
    <thead>
    <tr>
        <th></th>
        <th><span class="glyphicon glyphicon-envelope"></span> Treść</th>
        <th><span class="glyphicon glyphicon-user"></span> Autor</th>
        <th><span class="glyphicon glyphicon-time"></span> Data</th>
    </tr>
    </thead>
    <tbody>

    @foreach ($notifications as $notification)
    <tr>
        <td>{!! $notification->getTypeDescription() !!}</td>
        <td><a href="{!! $notification->getURL() !!}">{!! $notification->title !!}</a></td>
        <td>{!! $notification->source_user_id !!}</td>
        <td><time pubdate datetime="{!! $notification->created_at->format('c') !!}" title="{!! $notification->getLocalTime() !!}">{!! $notification->created_at->diffForHumans() !!}</time></td>
    </tr>
    @endforeach

    </tbody>

</table>

{!! with(new BootstrapPresenter($notifications))->render() !!}
@stop

@section('sidebar')

@stop
