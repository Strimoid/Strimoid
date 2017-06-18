@extends('global.master')

@section('title')
    {{ Str::ucfirst(trans('auth.registration')) }}
@stop

@section('content')
<div class="row">
    {!! Form::open([
        'action' => 'Auth\RegistrationController@processRegistration',
        'class' => 'form-horizontal'
    ]) !!}
        @include('global.form.input_icon', [
            'type' => 'text', 'name' => 'username', 'label' => trans('auth.username'), 'icon' => 'user'
        ])
        @include('global.form.input_icon', [
            'type' => 'password', 'name' => 'password', 'label' => trans('auth.password'), 'icon' => 'lock'
        ])
        @include('global.form.input_icon', [
            'type' => 'email', 'name' => 'email', 'label' => trans('auth.email'), 'icon' => 'envelope'
        ])

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-6">
                <button type="submit" class="btn btn-primary">{{ trans('auth.register') }}</button>
            </div>
        </div>
    {!! Form::close() !!}
</div>
@stop
