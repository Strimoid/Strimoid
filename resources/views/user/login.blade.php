@extends('...global.master')

@section('content')
<div class="row">
    {!! Form::open(array('action' => 'UserController@login', 'class' => 'form-horizontal')) !!}

    @include('...global.form.input', array('type' => 'text', 'name' => 'username', 'label' => 'Nazwa użytkownika'))
    @include('...global.form.input', array('type' => 'password', 'name' => 'password', 'label' => 'Hasło'))

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-6">
            <div class="checkbox">
                <label>
                    {!! Form::checkbox('remember', 'true') !!} Zapamiętaj mnie
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-3">
            <button type="submit" class="btn btn-primary">Zaloguj</button>
        </div>
        <div class="col-lg-3">
            <a href="/remind">Nie pamiętasz hasła?</a>
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
