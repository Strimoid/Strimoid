{!! Form::open(['action' => 'UserController@changePassword', 'class' => 'form-horizontal', 'style' => 'margin-top: 20px']) !!}

@include('global.form.input', ['type' => 'password', 'name' => 'old_password', 'label' => 'Aktualne hasło'])
@include('global.form.input', ['type' => 'password', 'name' => 'password', 'label' => 'Nowe hasło'])
@include('global.form.input', ['type' => 'password', 'name' => 'password_confirmation', 'label' => 'Nowe hasło - powtórzenie'])

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
        <button type="submit" class="btn btn-primary">Zmień hasło</button>
    </div>
</div>

{!! Form::close() !!}
