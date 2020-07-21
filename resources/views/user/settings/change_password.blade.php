{!! Form::open(['action' => 'UserController@changePassword', 'class' => 'form-horizontal', 'style' => 'margin-top: 20px']) !!}

@include('global.form.input', ['type' => 'password', 'name' => 'old_password', 'label' => 'Aktualne hasło'])
@include('global.form.input', ['type' => 'password', 'name' => 'password', 'label' => 'Nowe hasło'])
@include('global.form.input', ['type' => 'password', 'name' => 'password_confirmation', 'label' => 'Nowe hasło - powtórzenie'])
@include('global.form.submit', ['label' => 'Zapisz hasło'])

{!! Form::close() !!}
