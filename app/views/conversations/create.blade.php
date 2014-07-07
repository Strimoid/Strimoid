@extends('global.master')

@section('content')
{{ Form::open(array('action' => 'ConversationController@createConversation', 'class' => 'form-horizontal')) }}

@include('global.form.input_value', array('type' => 'text', 'class' => 'user_typeahead', 'name' => 'username', 'label' => 'Nazwa użytkownika', 'value' => $username))
@include('global.form.input', array('type' => 'textarea', 'name' => 'text', 'label' => 'Wiadomość'))

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
        <button type="submit" class="btn btn-primary">Wyślij</button>
    </div>
</div>
{{ Form::close() }}
@stop

@section('sidebar')
    @include('conversations.list')
@stop

