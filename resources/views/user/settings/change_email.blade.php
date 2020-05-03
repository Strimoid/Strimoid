{!! Form::open(['action' => 'UserController@changeEmail', 'class' => 'form-horizontal', 'style' => 'margin-top: 20px']) !!}

@include('global.form.input', ['type' => 'text', 'name' => 'email', 'label' => 'Nowe adres email'])
@include('global.form.input', ['type' => 'text', 'name' => 'email_confirmation', 'label' => 'Nowy adres email - powtórzenie'])

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
        <button type="submit" class="btn btn-primary">Zmień adres email</button>
    </div>
</div>

{!! Form::close() !!}
