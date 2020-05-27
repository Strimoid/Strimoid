@extends('global.master')

@section('content')
@if (Session::has('error'))
<div class="alert alert-dismissable alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {!! trans(Session::get('reason')) !!}
</div>
@endif

{!! Form::open(['class' => 'form-horizontal']) !!}
{!! Form::hidden('token', $token) !!}

@include('global.form.input', ['type' => 'email', 'name' => 'email', 'label' => 'Adres email'])
@include('global.form.input', ['type' => 'password', 'name' => 'password', 'label' => 'Nowe hasło'])
@include('global.form.input', ['type' => 'password', 'name' => 'password_confirmation', 'label' => 'Potwierdzenie hasła'])

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
        <button type="submit" class="btn btn-secondary">Zapisz</button>
    </div>
</div>
{!! Form::close() !!}
@stop

@section('sidebar')
<div class="well">
    <h4>Dlaczego warto się zarejestrować?</h4>
    <p>Dołączenie do społeczności {{ config('app.name') }} pozwoli Ci na pełny udział w życiu serwisu
        oraz możliwość dostosowania go do własnych upodobań.</p>
    <p>Zapraszamy!</p>
</div>
@stop
