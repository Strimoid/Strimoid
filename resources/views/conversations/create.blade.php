@extends('global.master')

@section('content')
{!! Form::open(['action' => 'ConversationController@createConversation', 'class' => 'form-horizontal']) !!}

@include('global.form.input_value', ['type' => 'text', 'class' => 'user_typeahead', 'name' => 'username', 'label' => 'Nazwa użytkownika', 'value' => $username])
@include('global.form.input', ['type' => 'textarea', 'name' => 'text', 'label' => 'Wiadomość'])

<div class="form-group">
    <div class="col-lg-6 offset-lg-3">
        <button type="submit" class="btn btn-primary">Wyślij</button>
    </div>
</div>
{!! Form::close() !!}
@stop

@section('sidebar')
    @include('conversations.list')
@stop

