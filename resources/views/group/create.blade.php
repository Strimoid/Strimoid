@extends('global.master')

@section('content')
<div class="row">
    {!! Form::open(['action' => 'GroupController@createGroup', 'class' => 'form-horizontal']) !!}

    <div class="form-group row @if ($errors->has('urlname')) has-error @endif">
        <label for="urlname" class="col-lg-3 control-label">Adres grupy</label>

        <div class="col-lg-6">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">g/</span>
                </div>

                {!! Form::text('urlname', null, ['class' => 'form-control', 'placeholder' => 'Adres grupy']) !!}
            </div>

            @if($errors->has('urlname'))
                <p class="help-block">{!! $errors->first('urlname') !!}</p>
            @endif
        </div>
    </div>

    @include('global.form.input', ['type' => 'text', 'name' => 'groupname', 'label' => 'Nazwa grupy'])
    @include('global.form.input', ['type' => 'textarea', 'name' => 'description', 'label' => 'Opis grupy'])

    {{--<div class="form-group">
        <label class="control-label col-lg-3">Rodzaj grupy</label>

        <div class="col-lg-6">
            <div class="radio">
                <label>
                    {!! Form::radio('type', 'public', true) !!}
                    <strong>Publiczna</strong> - każdy może przeglądać i dodawać treści
                </label>
            </div>

            <div class="radio">
                <label>
                    {!! Form::radio('type', 'moderated') !!}
                    <strong>Moderowana</strong> - każdy może przeglądać treści, jednak muszą być one zaakceptowane przed dodaniem
                </label>
            </div>

            <div class="radio">
                <label>
                    {!! Form::radio('type', 'private') !!}
                    <strong>Prywatna</strong> - tylko osoby będące w grupie będą mogły przeglądać i dodawać treści
                </label>
            </div>
        </div>
    </div>--}}

    @include('global.form.submit', ['label' => trans('groups.create')])

    {!! Form::close() !!}
</div>
@stop

@section('sidebar')
<div class="well">
    <h4>Czym są grupy?</h4>
    <p>Grupy są całkowicie oddzielnymi zbiorami treści, każda grupa posiada też swoich moderatorów.</p>
</div>
@stop
