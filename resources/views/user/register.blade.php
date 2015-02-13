@extends('global.master')

@section('title')
Rejestracja
@stop

@section('content')
<div class="row">
    {!! Form::open([
        'action' => 'UserController@processRegistration',
        'class' => 'form-horizontal'
    ]) !!}
        @include('global.form.input_icon', ['type' => 'text', 'name' => 'username', 'label' => 'Nazwa użytkownika', 'icon' => 'user'])
        @include('global.form.input_icon', ['type' => 'password', 'name' => 'password', 'label' => 'Hasło', 'icon' => 'lock'])
        @include('global.form.input_icon', ['type' => 'email', 'name' => 'email', 'label' => 'Adres email', 'icon' => 'envelope'])

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-6">
                <button type="submit" class="btn btn-primary">Zarejestruj</button>
            </div>
        </div>
    {!! Form::close() !!}
</div>
@stop

@section('sidebar')
<div class="well">
    <h4>Dlaczego warto się zarejestrować?</h4>
    <p>Dołączenie do społeczności {!! Config::get('app.site_name') !!} pozwoli Ci na pełny udział w życiu serwisu
        oraz możliwość dostosowania go do własnych upodobań.</p>
    <p>Zapraszamy!</p>
</div>
@stop
