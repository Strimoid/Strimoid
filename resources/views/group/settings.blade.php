@extends('global.master')

@section('content')
<div class="bs-example bs-example-tabs">
    <ul id="myTab" class="nav nav-tabs">
        <li class="active"><a href="#profile" data-toggle="tab">Profil</a></li>
        <li><a href="#settings" data-toggle="tab">Ustawienia</a></li>
        <li><a href="#style" data-toggle="tab">Styl CSS</a></li>
        <li><a href="#moderators" data-toggle="tab">Moderatorzy</a></li>
        <li><a href="#blocked" data-toggle="tab">Zablokowani użytkownicy</a></li>
    </ul>

    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="profile">
            {!! Form::open(array('action' => ['GroupController@saveProfile', $group->_id], 'class' => 'form-horizontal', 'style' => 'margin-top: 20px', 'files' => true)) !!}

            <div class="form-group">
                <label class="col-lg-3 control-label">Adres grupy</label>
                <div class="col-lg-6">
                    <p class="form-control-static">{!! $group->urlname !!}</p>
                </div>
            </div>

            @include('global.form.input_value', array('type' => 'text', 'name' => 'name', 'label' => 'Nazwa grupy', 'value' => $group->name))

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

            @include('global.form.input_value', array('type' => 'textarea', 'name' => 'description', 'label' => 'Opis grupy', 'value' => $group->description))
            @include('global.form.input_value', array('type' => 'textarea', 'name' => 'sidebar', 'label' => 'Opis w sidebarze', 'value' => $group->sidebar_source))
            @include('global.form.input_tags', array('type' => 'text', 'name' => 'tags', 'label' => 'Tagi', 'tags' => $group->tags))

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>

        <div class="tab-pane fade" id="settings">
            {{--
            {!! Form::open(['action' => ['GroupController@saveSettings', $group->_id], 'class' => 'form-horizontal', 'style' => 'margin-top: 20px']) !!}

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

            @include('global.form.input_tags', array('type' => 'text', 'name' => 'labels', 'label' => 'Lista etykiet', 'noun' => 'Etykiety', 'tags' => $group->labels))

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                </div>
            </div>

            {!! Form::close() !!}
            --}}
        </div>

        <div class="tab-pane fade" id="style">
            {!! Form::open(array('action' => array('GroupController@saveStyle', $group->urlname), 'class' => 'form-horizontal', 'style' => 'margin-top: 20px')) !!}

            @include('global.form.input_value', array('type' => 'textarea', 'class' => 'css_editor', 'name' => 'css', 'label' => 'Styl CSS', 'rows' => '20', 'value' => $css))

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>

        <div class="tab-pane fade" id="moderators">
            @if (Auth::check() && Auth::user()->isAdmin($group))
            {!! Form::open(array('action' => 'GroupController@addModerator', 'class' => 'form-horizontal', 'style' => 'margin-top: 20px')) !!}

            <input type="hidden" name="groupname" value="{!! $group->urlname !!}">

            @include('global.form.input', array('type' => 'text', 'name' => 'username', 'class' => 'user_typeahead', 'label' => 'Nazwa użytkownika'))

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('admin') !!} <span class="has_tooltip" data-toggle="tooltip" title="Pozwala edytować ustawienia i listę moderatorów">Admin</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <button type="submit" class="btn btn-primary pull-right">Dodaj moderatora</button>
                </div>
            </div>

            {!! Form::close() !!}
            @endif

            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nazwa użytkownika</th>
                    <th>Dodano</th>
                    <th>Akcja</th>
                </tr>
                </thead>
                <tbody>

                <?php $x = 0; ?>

                @foreach ($moderators as $moderator)
                <?php $x++; ?>
                <tr>
                    <td>{!! $x !!}</td>
                    <td><a href="{!! route('user_profile', $moderator->user->name) !!}">{!! $moderator->user->name !!}</a></td>
                    <td>{!! $moderator->created_at->diffForHumans() !!}</td>
                    <td><button type="button" class="btn btn-xs btn-default">Usuń</button></td>
                </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="blocked">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nazwa użytkownika</th>
                    <th>Zablokowano</th>
                    <th>Akcja</th>
                </tr>
                </thead>
                <tbody>

                <?php $x = 0; ?>


                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('sidebar')
<div class="well">
    <h4>Czym są grupy?</h4>
    <p>Grupy są całkowicie oddzielnymi zbiorami treści, każda grupa posiada też swoich moderatorów.</p>
</div>
@stop
