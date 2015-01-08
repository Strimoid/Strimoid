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
    {!! Form::open(array('action' => 'UserController@removeAccount', 'class' => 'form-horizontal')) !!}

    @include('global.form.input_icon', array('type' => 'password', 'name' => 'password', 'label' => 'Hasło', 'icon' => 'lock'))
    @include('global.form.input_icon', array('type' => 'password', 'name' => 'password_confirmation', 'label' => 'Hasło - powtórzenie', 'icon' => 'lock'))

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-6">
            <button type="submit" class="btn btn-primary">Usuń konto</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@stop

@section('sidebar')
@stop
