@extends('global.master')

@section('content')
<div>
    <ul class="nav nav-tabs">
        <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">Profil</a></li>
        <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Ustawienia</a></li>
        <li class="nav-item"><a class="nav-link" href="#style" data-toggle="tab">Styl CSS</a></li>
        <li class="nav-item"><a class="nav-link" href="#moderators" data-toggle="tab">Moderatorzy</a></li>
        <li class="nav-item"><a class="nav-link" href="#blocked" data-toggle="tab">Zablokowani użytkownicy</a></li>
    </ul>

    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="profile">
            {!! Form::open(['action' => ['GroupController@saveProfile', $group], 'class' => 'form-horizontal', 'style' => 'margin-top: 20px', 'files' => true]) !!}

            <div class="form-group">
                <label class="col-lg-3 control-label">Adres grupy</label>
                <div class="col-lg-6">
                    <p class="form-control-static">{!! $group->urlname !!}</p>
                </div>
            </div>

            @include('global.form.input_value', ['type' => 'text', 'name' => 'name', 'label' => 'Nazwa grupy', 'value' => $group->name])

            <div class="form-group @if ($errors->has('avatar')) has-error @endif">
                <label class="col-lg-3 control-label">Avatar</label>
                <div class="col-lg-6">
                    {!! Form::file('avatar') !!}

                    @if($errors->has('avatar'))
                    <p class="help-block">{!! $errors->first('avatar') !!}</p>
                    @else
                    <p class="help-block">Najlepiej 100x100, maksymalny rozmiar: 100KB.</p>
                    @endif
                </div>
            </div>

            @include('global.form.input_value', ['type' => 'textarea', 'name' => 'description', 'label' => 'Opis grupy', 'value' => $group->description])
            @include('global.form.input_value', ['type' => 'textarea', 'name' => 'sidebar', 'label' => 'Opis w sidebarze', 'value' => $group->sidebar_source])
            @include('global.form.input_tags', ['type' => 'text', 'name' => 'tags', 'label' => 'Tagi', 'tags' => $group->tags])

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>

        <div class="tab-pane fade" id="settings">
            {{--
            {!! Form::open(['action' => ['GroupController@saveSettings', $group->urlname], 'class' => 'form-horizontal', 'style' => 'margin-top: 20px']) !!}

            <div class="form-group">
                <label class="col-lg-3 control-label">Etykiety</label>

                <div class="col-lg-6">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('enable_labels', 'on', @$group->settings['enable_labels'])  !!} Włącz etykiety
                        </label>
                    </div>
                </div>
            </div>

            @include('global.form.input_tags', ['type' => 'text', 'name' => 'labels', 'label' => 'Lista etykiet', 'noun' => 'Etykiety', 'tags' => $group->labels])

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                </div>
            </div>

            {!! Form::close() !!}
            --}}
        </div>

        @include('group.settings.style')
        @include('group.settings.moderators')
        @include('group.settings.blocked')
    </div>
</div>
@stop

@section('sidebar')
<div class="well">
    <h4>Czym są grupy?</h4>
    <p>Grupy są całkowicie oddzielnymi zbiorami treści, każda grupa posiada też swoich moderatorów.</p>
</div>
@stop
