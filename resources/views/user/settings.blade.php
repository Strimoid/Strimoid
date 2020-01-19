@extends('global.master')

@section('content')
<div>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#profile" data-toggle="tab">
                <span class="fa fa-user"></span>
                {{ strans('common.profile')->upperCaseFirst() }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#settings" data-toggle="tab">
                <i class="fa fa-wrench"></i>
                {{ strans('common.settings')->upperCaseFirst() }}
            </a>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-lock"></i>
                {{ strans('common.account')->upperCaseFirst() }}
                <span class="caret"></span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#password" data-toggle="tab">Zmiana hasła</a>
                <a class="dropdown-item" href="#email" data-toggle="tab">Zmiana adresu email</a>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                {{ strans('common.domains')->upperCaseFirst() }} <span class="caret"></span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#domains" data-toggle="tab">Zablokowane</a>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                {{ strans('common.groups')->upperCaseFirst() }} <span class="caret"></span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#subscribed" data-toggle="tab">
                    {{ strans('groups.subscribed')->upperCaseFirst() }}
                </a>
                <a class="dropdown-item" href="#moderated" data-toggle="tab">
                    {{ strans('groups.moderated')->upperCaseFirst() }}
                </a>
                <a class="dropdown-item" href="#blocked" data-toggle="tab">
                    {{ strans('groups.blocked')->upperCaseFirst() }}
                </a>
                <a class="dropdown-item" href="#bans" data-toggle="tab">
                    {{ strans('groups.banned')->upperCaseFirst() }}
                </a>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                {{ strans('common.users')->upperCaseFirst() }} <span class="caret"></span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="#blockedusers" data-toggle="tab">Zablokowani użytownicy</a>
            </div>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade in active" id="profile">
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
                'options' => ['' => '', 'male' => 'Mężczyzna', 'female' => 'Kobieta']
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
                            {!! Form::checkbox('enter_send', 'on', setting('enter_send')) !!} Wysyłaj treści/komentarze enterem
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('homepage_subscribed', 'on', setting('homepage_subscribed')) !!} Subskrybowane jako strona główna serwisu
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
                            {!! Form::checkbox('notifications_sound', 'on', setting('notifications_sound')) !!} Odtwarzaj dźwięk po otrzymaniu powiadomienia
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('notifications[auto_read]', 'on', setting('notifications.auto_read')) !!} Automatycznie oznaczaj powiadomienia jako przeczytane
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-3 control-label">Wygląd</label>

                <div class="col-lg-6">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('pin_navbar', 'on', setting('pin_navbar'))  !!} Przypnij górny pasek
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('disable_groupstyles', 'on', setting('disable_groupstyles'))  !!} Wyłącz style grup
                        </label>
                    </div>
                </div>
            </div>

            @include('global.form.input_select', ['name' => 'contents_per_page', 'label' => 'Ilość treści na stronę', 'value' => setting('contents_per_page'), 'options' => app('settings')->getOptions('contents_per_page')])
            @include('global.form.input_select', ['name' => 'entries_per_page', 'label' => 'Ilość wpisów na stronę', 'value' => setting('entries_per_page'), 'options' => app('settings')->getOptions('entries_per_page')])

            @include('global.form.input_select', ['name' => 'timezone', 'label' => 'Strefa czasowa', 'value' => setting('timezone'), 'options' => setting()->getOptions('timezone')])

            @include('global.form.input_value', ['type' => 'text', 'name' => 'css_style', 'label' => 'Własny styl CSS', 'value' => setting('css_style'), 'placeholder' => 'http://link.do/stylu.css'])

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
                    <td><a href="{!! route('group_contents', $subscribedGroup->urlname) !!}">{!! $subscribedGroup->name !!}</a></td>
                    <td>{!! $subscribedGroup->created_at->diffForHumans() !!}</td>
                    <td><button type="button" data-name="{!! $subscribedGroup->urlname !!}" class="btn btn-xs group_subscribe_btn btn-success">Subskrybuj</button></td>
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
                    <td><a href="{!! route('group_contents', $moderatedGroup->urlname) !!}">{!! $moderatedGroup->name !!}</a></td>
                    <td>{!! $moderatedGroup->created_at->diffForHumans() !!}</td>
                    <td data-name="{!! $moderatedGroup->urlname !!}"><button type="button" data-name="{!! $moderatedGroup->urlname !!}" class="btn btn-xs group_subscribe_btn btn-success">Subskrybuj</button></td>
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
                    <td><a href="{!! route('group_contents', $blockedGroup->urlname) !!}">{!! $blockedGroup->name !!}</a></td>
                    <td>{!! $blockedGroup->created_at->diffForHumans() !!}</td>
                    <td data-name="{!! $blockedGroup->urlname !!}"><button type="button" data-name="{!! $blockedGroup->urlname !!}" class="btn btn-xs group_block_btn btn-danger">Blokuj</button></td>
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
                    <td><a href="{!! route('group_contents', $ban->urlname) !!}">{!! $ban->name !!}</a></td>
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
                    <td><a href="{!! route('user_profile', $blockedUser->name) !!}">{!! $blockedUser->name !!}</a></td>
                    <td>{!! $blockedUser->created_at->diffForHumans() !!}</td>
                    <td data-name="{!! $blockedUser->name !!}">{{--<button type="button" data-name="{!! $blockedUser->name !!}" class="btn btn-xs group_block_btn btn-danger">Zablokuj</button>--}}</td>
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
              <button class="btn btn-secondary" ng-click="blockDomain(domain)">Zablokuj domenę</button>
            </form>
        </div>
    </div>
</div>
@stop

