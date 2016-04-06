@extends('global.master')

@section('content')

{!! Form::open(['action' => 'OAuthController@addApp', 'class' => 'form-horizontal']) !!}

@include('global.form.input', ['type' => 'text', 'name' => 'name', 'label' => 'Nazwa aplikacji'])
@include('global.form.input', ['type' => 'textarea', 'name' => 'redirect_url', 'label' => 'Redirect URL'])

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
        <button type="submit" class="btn btn-primary">Dodaj aplikację</button>
    </div>
</div>

{!! Form::close() !!}

@stop
