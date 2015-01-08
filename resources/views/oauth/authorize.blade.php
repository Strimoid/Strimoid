@extends('global.master')

@section('content')
<div class="row">
    <div class="col-lg-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1 style="margin-top: 0">{{{ $client->name }}}</h1>

                Aplikacja chce uzyskać dostęp do następujących uprawnień:
            </div>
            <div class="panel-body">
                <ul>
                    @foreach ($scopes as $scope)
                        <li>{!! Lang::get('scopes.'. $scope) !!}</li>
                    @endforeach
                </ul>
            </div>
            <div class="panel-footer">
                {!! Form::open(array('action' => array('OAuthController@authorize',
                'response_type' => Input::get('response_type'),
                'client_id' => Input::get('client_id'),
                'redirect_uri' => Input::get('redirect_uri'),
                'scope' => Input::get('scope'),
                'state' => Input::get('state')
                ))) !!}

                <input type="hidden" name="authorize" value="1" />
                <button type="submit" class="btn btn-primary pull-right">Autoryzuj</button>
                <div class="clearfix"></div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@stop

@section('sidebar')

@stop
