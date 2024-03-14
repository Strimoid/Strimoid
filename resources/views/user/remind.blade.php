@extends('global.master')

@section('content')
    {{ html()->form(action: action('UserController@remindPassword'))->class(['form-horizontal'])->open() }}

    @include('global.form.input', ['type' => 'email', 'name' => 'email', 'label' => 'Adres email'])
    @include('global.form.submit', ['label' => 'Dalej'])

    {{ html()->form()->close() }}
@stop

@section('sidebar')
<div class="well">
    <h4>Dlaczego warto się zarejestrować?</h4>
    <p>Dołączenie do społeczności {{ config('app.name') }} pozwoli Ci na pełny udział w życiu serwisu
        oraz możliwość dostosowania go do własnych upodobań.</p>
    <p>Zapraszamy!</p>
</div>
@stop
