@extends('global.master')

@section('title')
    @ucFirstLang('auth.registration')
@stop

@section('content')
    <h1 class="mb-4">@ucFirstLang('auth.registration')</h1>

    <hr class="my-4">

    <div class="row">
        {{ html()->form(action: action('Auth\RegistrationController@processRegistration'))->class(['form-horizontal', 'w-100'])->open() }}
            @include('global.form.input_icon', [
                'type' => 'text', 'name' => 'username', 'label' => trans('auth.username'), 'icon' => 'user'
            ])
            @include('global.form.input_icon', [
                'type' => 'email', 'name' => 'email', 'label' => trans('auth.email'), 'icon' => 'envelope'
            ])
            @include('global.form.input_icon', [
                'type' => 'password', 'name' => 'password', 'label' => trans('auth.password'), 'icon' => 'lock'
            ])

            <div class="form-group row">
                <div class="col-lg-6 offset-lg-3">
                    <button type="submit" class="btn btn-primary">{{ trans('auth.register') }}</button>
                </div>
            </div>
        {{ html()->form()->close() }}
    </div>
@stop
