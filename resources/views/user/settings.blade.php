@extends('global.master')

@section('content')
<div>
    <ul id="myTab" class="nav nav-tabs">
        <li class="active">
            <a href="#profile" data-toggle="tab"><span class="glyphicon glyphicon-user"></span> Profil</a>
        </li>
        <li>
            <a href="#settings" data-toggle="tab"><span class="glyphicon glyphicon-wrench"></span> Ustawienia</a>
        </li>

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="glyphicon glyphicon-lock"></span> Konto <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#password" data-toggle="tab">Zmiana hasła</a>
                </li>
                <li>
                    <a href="#email" data-toggle="tab">Zmiana adresu email</a>
                </li>
            </ul>
        </li>

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                Domeny <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#domains" data-toggle="tab">Zablokowane</a>
                </li>
            </ul>
        </li>

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                Grupy <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="#subscribed" data-toggle="tab">Subskrybowane</a></li>
                <li><a href="#moderated" data-toggle="tab">Moderowane</a></li>
                <li><a href="#blocked" data-toggle="tab">Zablokowane</a></li>
                <li><a href="#bans" data-toggle="tab">Twoje bany</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                Użytkownicy <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="#blockedusers" data-toggle="tab">Zablokowani użytownicy</a></li>
            </ul>
        </li>
    </ul>

    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="profile">
            {!! Form::open(['action' => 'UserController@saveProfile', 'class' => 'form-horizontal', 'style' => 'margin-top: 20px', 'files' => true]) !!}

            <div class="form-group">
                <label class="col-lg-3 control-label">Nazwa użytkownika</label>
                <div class="col-lg-6">
                    <p class="form-control-static">{!! $user->name !!}</p>
                </div>
            </div>

            @include('global.form.input_select', ['name' => 'sex', 'label' => 'Płeć', 'value' => $user->sex, 'options' => ['' => '', 'male' => 'Mężczyzna', 'female' => 'Kobieta']])

            <div class="form-group @if ($errors->has('avatar')) has-error @endif">
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

            @include('global.form.input_value', array('type' => 'text', 'name' => 'age', 'label' => 'Rok urodzenia', 'value' => $user->age ?: ''))
            @include('global.form.input_value', array('type' => 'text', 'name' => 'location', 'label' => 'Miejscowość', 'value' => $user->location))
            @include('global.form.input_value', array('type' => 'textarea', 'name' => 'description', 'label' => 'O sobie', 'value' => $user->description))

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>

        <div class="tab-pane fade" id="password">
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
        </div>

        <div class="tab-pane fade" id="email">
            {!! Form::open(['action' => 'UserController@changeEmail', 'class' => 'form-horizontal', 'style' => 'margin-top: 20px']) !!}

            @include('global.form.input', ['type' => 'text', 'name' => 'email', 'label' => 'Nowe adres email'])
            @include('global.form.input', ['type' => 'text', 'name' => 'email_confirmation', 'label' => 'Nowy adres email - powtórzenie'])

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <button type="submit" class="btn btn-primary">Zmień adres email</button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>

        <div class="tab-pane fade" id="settings">
            {!! Form::open(['action' => 'SettingsController@saveSettings', 'class' => 'form-horizontal', 'style' => 'margin-top: 20px']) !!}

            <div class="form-group">
                <label class="col-lg-3 control-label">Opcje</label>

                <div class="col-lg-6">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('enter_send', 'on', @$user->settings['enter_send']) !!} Wysyłaj treści/komentarze enterem
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('homepage_subscribed', 'on', @$user->settings['homepage_subscribed']) !!} Subskrybowane jako strona główna serwisu
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-3 control-label">Powiadomienia</label>

                <div class="col-lg-6">
                    <div class="checkbox">
                        <label>
                            <input name="browser_notifications" type="checkbox" value="on"> Wyświetlaj powiadomienia w przeglądarce
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('notifications_sound', 'on', @$user->settings['notifications_sound']) !!} Odtwarzaj dźwięk po otrzymaniu powiadomienia
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('notifications[auto_read]', 'on', Setting::get('notifications.auto_read', false)) !!} Automatycznie oznaczaj powiadomienia jako przeczytane
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-3 control-label">Wygląd</label>

                <div class="col-lg-6">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('pin_navbar', 'on', @$user->settings['pin_navbar'])  !!} Przypnij górny pasek
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('disable_groupstyles', 'on', @$user->settings['disable_groupstyles'])  !!} Wyłącz style grup
                        </label>
                    </div>
                </div>
            </div>

            @include('global.form.input_select', array('name' => 'contents_per_page', 'label' => 'Ilość treści na stronę', 'value' => Setting::get('contents_per_page', 25), 'options' => app('settings')->getOptions('contents_per_page')))
            @include('global.form.input_select', array('name' => 'entries_per_page', 'label' => 'Ilość wpisów na stronę', 'value' => Setting::get('entries_per_page', 25), 'options' => app('settings')->getOptions('entries_per_page')))

            @include('global.form.input_select', array('name' => 'timezone', 'label' => 'Strefa czasowa', 'value' => Setting::get('timezone', 'Europe/Warsaw'), 'options' => app('settings')->getOptions('timezone')))

            @include('global.form.input_value', array('type' => 'text', 'name' => 'css_style', 'label' => 'Własny styl CSS', 'value' => @$user->settings['css_style'], 'placeholder' => 'http://link.do/stylu.css'))

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>

        <div class="tab-pane fade" id="subscribed">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nazwa grupy</th>
                    <th>Zasubskrybowano</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>

                <?php $x = 0; ?>
                @foreach ($subscribedGroups as $subscribedGroup)
                <?php $x++; ?>
                <tr>
                    <td>{!! $x !!}</td>
                    <td><a href="{!! route('group_contents', $subscribedGroup->group->urlname) !!}">{!! $subscribedGroup->group->name !!}</a></td>
                    <td>{!! $subscribedGroup->created_at->diffForHumans() !!}</td>
                    <td><button type="button" data-name="{!! $subscribedGroup->group->urlname !!}" class="btn btn-xs group_subscribe_btn btn-success">Subskrybuj</button></td>
                </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="moderated">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nazwa grupy</th>
                    <th>Dodano</th>
                    <th>Subskrypcja</th>
                </tr>
                </thead>
                <tbody>

                <?php $x = 0; ?>
                @foreach ($moderatedGroups as $moderatedGroup)
                <?php $x++; ?>
                <tr>
                    <td>{!! $x !!}</td>
                    <td><a href="{!! route('group_contents', $moderatedGroup->group->urlname) !!}">{!! $moderatedGroup->group->name !!}</a></td>
                    <td>{!! $moderatedGroup->created_at->diffForHumans() !!}</td>
                    <td data-name="{!! $moderatedGroup->group->urlname !!}"><button type="button" data-name="{!! $moderatedGroup->group->urlname !!}" class="btn btn-xs group_subscribe_btn btn-success">Subskrybuj</button></td>
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
                    <th>Nazwa grupy</th>
                    <th>Zablokowano</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>

                <?php $x = 0; ?>
                @foreach ($blockedGroups as $blockedGroup)
                <?php $x++; ?>
                <tr>
                    <td>{!! $x !!}</td>
                    <td><a href="{!! route('group_contents', $blockedGroup->group->urlname) !!}">{!! $blockedGroup->group->name !!}</a></td>
                    <td>{!! $blockedGroup->created_at->diffForHumans() !!}</td>
                    <td data-name="{!! $blockedGroup->group->urlname !!}"><button type="button" data-name="{!! $blockedGroup->group->urlname !!}" class="btn btn-xs group_block_btn btn-danger">Blokuj</button></td>
                </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="bans">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nazwa grupy</th>
                    <th>Zbanowano</th>
                    <th>Powód</th>
                </tr>
                </thead>
                <tbody>

                <?php $x = 0; ?>
                @foreach ($bans as $ban)
                <?php $x++; ?>
                <tr>
                    <td>{!! $x !!}</td>
                    <td><a href="{!! route('group_contents', $ban->group->urlname) !!}">{!! $ban->group->name !!}</a></td>
                    <td>{!! $ban->created_at->diffForHumans() !!}</td>
                    <td>{{{ $ban->reason }}}</td>
                </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="blockedusers">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nazwa użytkownika</th>
                    <th>Zablokowano</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>

                <?php $x = 0; ?>
                @foreach ($blockedUsers as $blockedUser)
                <?php $x++; ?>
                <tr>
                    <td>{!! $x !!}</td>
                    <td><a href="{!! route('user_profile', $blockedUser->target_id) !!}">{!! $blockedUser->target_id !!}</a></td>
                    <td>{!! $blockedUser->created_at->diffForHumans() !!}</td>
                    <td data-name="{!! $blockedUser->target_id !!}"><button type="button" data-name="{!! $blockedUser->target_id !!}" class="btn btn-xs group_block_btn btn-danger">Zablokuj</button></td>
                </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="domains">
            <table class="table" ng-init='blockedDomains = {!! json_encode(Auth::user()->_blocked_domains); !!}'>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nazwa domeny</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="domain in blockedDomains">
                    <td>@{!! $index + 1 !!}</td>
                    <td>@{!! domain !!}</td>
                    <td><button class="btn btn-xs btn-danger" ng-click="unblockDomain(domain)">Usuń</button></td>
                </tr>
                </tbody>
            </table>

            <form role="form">
              <div class="form-group">
                <label for="domain">Domena</label>
                <input type="text" class="form-control" id="domain" placeholder="np. strims.pl" ng-model="domain">
              </div>
              <button class="btn btn-default" ng-click="blockDomain(domain)">Zablokuj domenę</button>
            </form>
        </div>
    </div>
</div>
@stop

