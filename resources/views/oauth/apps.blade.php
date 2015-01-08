@extends('...global.master')

@section('content')
<h1 class="pull-left" style="margin-top: 0">Twoje aplikacje</h1>

<a href="{!! action('OAuthController@addAppForm') !!}" class="btn btn-primary pull-right">+ Stwórz nową aplikację</a>

<div class="clearfix"></div>

@if (!$apps)
    <p>Nie dodałeś jeszcze żadnej aplikacji.</p>
@endif

@foreach ($apps as $app)
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{{ $app->name }}}</h3>
    </div>
    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Client ID</dt>
            <dd>{!! $app->client_id !!}</dd>
            <dt>Client Secret</dt>
            <dd>{!! $app->client_secret !!}</dd>
            <dt>Redirect URL</dt>
            <dd>{{{ $app->redirect_uri }}}</dd>
        </dl>
    </div>
</div>
@endforeach

@stop

@section('sidebar')

<div class="well">
    <h4>Dokumentacja</h4>

    <p><a href="http://developers.strimoid.pl">developers.strimoid.pl</a></p>
</div>

@stop
