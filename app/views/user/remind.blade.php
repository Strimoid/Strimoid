@extends('global.master')

@section('content')
{{ Form::open(array('action' => 'UserController@remindPassword', 'class' => 'form-horizontal')) }}

@include('global.form.input', array('type' => 'email', 'name' => 'email', 'label' => 'Adres email'))

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
        <button type="submit" class="btn btn-primary">Dalej</button>
    </div>
</div>
{{ Form::close() }}
@stop

@section('sidebar')
<div class="well">
    <h4>Dlaczego warto się zarejestrować?</h4>
    <p>Dołączenie do społeczności {{ Config::get('app.site_name') }} pozwoli Ci na pełny udział w życiu serwisu
        oraz możliwość dostosowania go do własnych upodobań.</p>
    <p>Zapraszamy!</p>
</div>
@stop
