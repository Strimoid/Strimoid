@extends('global.master')

@section('content')
<div class="row">
    {{ html()->form(action: action('AuthController@login'))->class(['form-horizontal', 'w-100'])->open() }}

    @include('global.form.input_icon', [
        'type' => 'text', 'name' => 'username', 'label' => ucfirst(trans('auth.username')), 'icon' => 'user'
    ])
    @include('global.form.input_icon', [
        'type' => 'password', 'name' => 'password', 'label' => ucfirst(trans('auth.password')), 'icon' => 'lock'
    ])

    <div class="form-group">
        <div class="col-lg-6 offset-md-3">
            @include('global.form.input_checkbox', ['name' => 'remember', 'label' => trans('auth.remember')])
        </div>
    </div>

    <div class="form-group row">
        <div class="col-lg-3 offset-md-3">
            <button type="submit" class="btn btn-primary">{{ trans('auth.sign_in') }}</button>
        </div>
        <div class="col-lg-3">
            <a class="btn btn-info" href="/remind">{{ trans('auth.forgot_password') }}</a>
        </div>
    </div>
    {{ html()->form()->close() }}
</div>
@stop
