{!! Form::open([
    'action' => 'UserController@saveProfile',
    'class' => 'form-horizontal',
    'style' => 'margin-top: 20px',
    'files' => true
]) !!}

<div class="form-group row">
    <label class="col-lg-3 control-label">{{ s(trans('auth.username'))->upperCaseFirst() }}</label>
    <div class="col-lg-6">
        <p class="form-control-static">{{ $user->name }}</p>
    </div>
</div>

@include('global.form.input_select', [
    'name' => 'sex',
    'label' => strans('common.sex')->upperCaseFirst(),
    'value' => $user->sex,
    'options' => ['unknown' => '', 'male' => 'Mężczyzna', 'female' => 'Kobieta']
])

<div class="form-group row @if ($errors->has('avatar')) has-error @endif">
    <label class="col-lg-3 control-label">Avatar</label>
    <div class="col-lg-6">
        {!! Form::file('avatar') !!}

        @if ($errors->has('avatar'))
            <p class="help-block">{!! $errors->first('avatar') !!}</p>
        @else
            <p class="help-block">Najlepiej 100x100, maksymalny rozmiar: 250KB.</p>
        @endif
    </div>
</div>

@include('global.form.input_value', ['type' => 'text', 'name' => 'age', 'label' => 'Rok urodzenia', 'value' => $user->age ?: ''])
@include('global.form.input_value', ['type' => 'text', 'name' => 'location', 'label' => 'Miejscowość', 'value' => $user->location])
@include('global.form.input_value', ['type' => 'textarea', 'name' => 'description', 'label' => 'O sobie', 'value' => $user->description])
@include('global.form.submit')

{!! Form::close() !!}
