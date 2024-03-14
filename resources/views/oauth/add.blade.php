@extends('global.master')

@section('content')
    {{ html()->form(action: action('OAuthController@addApp'))->class(['form-horizontal'])->open() }}

    @include('global.form.input', ['type' => 'text', 'name' => 'name', 'label' => 'Nazwa aplikacji'])
    @include('global.form.input', ['type' => 'textarea', 'name' => 'redirect_url', 'label' => 'Redirect URL'])

    <div class="form-group">
        <div class="col-lg-6 offset-lg-3">
            <button type="submit" class="btn btn-primary">Dodaj aplikację</button>
        </div>
    </div>

    {{ html()->form()->close() }}
@stop
