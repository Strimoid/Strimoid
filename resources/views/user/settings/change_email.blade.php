{{ html()->form(action: action('UserController@changeEmail'))->class(['form-horizontal', 'mt-5'])->open() }}

@include('global.form.input', ['type' => 'text', 'name' => 'email', 'label' => 'Nowe adres email'])
@include('global.form.input', ['type' => 'text', 'name' => 'email_confirmation', 'label' => 'Nowy adres email - powtórzenie'])
@include('global.form.submit', ['label' => 'Zmień adres email'])

{{ html()->form()->close() }}
