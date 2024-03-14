{{ html()->form(action: action('UserController@changePassword'))->class(['form-horizontal', 'mt-5'])->open() }}

@include('global.form.input', ['type' => 'password', 'name' => 'old_password', 'label' => 'Aktualne hasło'])
@include('global.form.input', ['type' => 'password', 'name' => 'password', 'label' => 'Nowe hasło'])
@include('global.form.input', ['type' => 'password', 'name' => 'password_confirmation', 'label' => 'Nowe hasło - powtórzenie'])
@include('global.form.submit', ['label' => 'Zapisz hasło'])

{{ html()->form()->close() }}
