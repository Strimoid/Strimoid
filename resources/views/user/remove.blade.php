@extends('global.master')

@section('title')
Usuwanie konta
@stop

@section('content')
<div class="alert alert-warning">
Proces usuwania konta jest nieodwracalny!
</div>

<div class="page-header">
    <h1>Usuwanie konta</h1>
</div>

<div class="row">
    {!! Form::open(['action' => 'UserController@removeAccount', 'class' => 'form-horizontal']) !!}

    @include('global.form.input_icon', ['type' => 'password', 'name' => 'password', 'label' => 'Hasło', 'icon' => 'lock'])
    @include('global.form.input_icon', ['type' => 'password', 'name' => 'password_confirmation', 'label' => 'Hasło - powtórzenie', 'icon' => 'lock'])
    @include('global.form.submit', ['label' => 'Usuń konto'])

    {!! Form::close() !!}
</div>
@stop

@section('sidebar')
@stop
