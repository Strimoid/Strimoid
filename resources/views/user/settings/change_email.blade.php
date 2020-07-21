{!! Form::open(['action' => 'UserController@changeEmail', 'class' => 'form-horizontal', 'style' => 'margin-top: 20px']) !!}

@include('global.form.input', ['type' => 'text', 'name' => 'email', 'label' => 'Nowe adres email'])
@include('global.form.input', ['type' => 'text', 'name' => 'email_confirmation', 'label' => 'Nowy adres email - powtórzenie'])
@include('global.form.submit', ['label' => 'Zmień adres email'])

{!! Form::close() !!}
