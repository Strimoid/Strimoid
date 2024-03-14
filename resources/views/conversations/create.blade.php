@extends('global.master')

@section('content')
    {{ html()->form(action: action('ConversationController@createConversation'))->class(['form-horizontal'])->open() }}

    @include('global.form.input_value', ['type' => 'text', 'class' => 'user_typeahead', 'name' => 'username', 'label' => 'Nazwa użytkownika', 'value' => $username])
    @include('global.form.input', ['type' => 'textarea', 'name' => 'text', 'label' => 'Wiadomość'])

    <div class="form-group">
        <div class="col-lg-6 offset-lg-3">
            <button type="submit" class="btn btn-primary">Wyślij</button>
        </div>
    </div>
    {{ html()->form()->close() }}
@stop

@section('sidebar')
    @include('conversations.list')
@stop

