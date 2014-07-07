<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">

    <meta property="og:title" content="@yield('title', 'Strimoid')">
    <meta name="description" content="@yield('description', 'Strimoid')">

    @if (Request::getHttpHost() == 'strimsy.pl')
        <meta name="robots" content="noindex">
    @endif

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="alternate" type="application/rss+xml" title="RSS Feed" href="/rss">

    <title>@yield('title', e($pageTitle))</title>

    <link href="/static/css/style.css?26" rel="stylesheet">

    @if (Input::get('night') || isset($_COOKIE['night_mode']))
        <link href="/static/css/night.css?1" rel="stylesheet" data-id="night_mode">
    @endif

    @if (isset($group) && $group->style  && !@Auth::user()->settings['disable_groupstyles'])
        <link href="/uploads/styles/{{ $group->style }}" rel="stylesheet" data-id="group_style">
    @elseif (isset($group) && file_exists(Config::get('app.uploads_path').'/styles/'. Str::lower($group->urlname) .'.css') && !@Auth::user()->settings['disable_groupstyles'])
        <link href="/uploads/styles/{{ Str::lower($group->urlname) }}.css" rel="stylesheet" data-id="group_style">
    @elseif (Auth::check() && @Auth::user()->settings['css_style'])
        <link href="{{{ Auth::user()->settings['css_style'] }}}" rel="stylesheet">
    @endif

    {{-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries --}}
    <!--[if lt IE 9]>
    <script src="/static/js/html5shiv.js"></script>
    <script src="/static/js/respond.min.js"></script>
    <![endif]-->

    @yield('head')
</head>

<body class="@if (Input::get('night') || isset($_COOKIE['night_mode']))night @endif">

<?php

if (isset($group))
    $groupURLName = $group->urlname;
elseif (isset($group_name) && $group_name == 'all' && (Auth::guest() || !@Auth::user()->settings['homepage_subscribed']))
    $groupURLName = null;
elseif (isset($group_name))
    $groupURLName = $group_name;

$currentRoute = (Route::current()) ? Route::current()->getName() : '';
$navbarClass = (Auth::check() && @Auth::user()->settings['pin_navbar']) ? 'fixed-top' : 'static-top';

?>

<div class="groupbar groupbar-{{ $navbarClass }}">
    <ul>
        <li><a href="/g/all" rel="nofollow">Wszystkie</a></li>

        @if (Auth::check())
        <?php $subscriptions = Auth::user()->subscribedGroups(); natcasesort($subscriptions); ?>

        <li class="dropdown subscribed_dropdown">
            <a href="/g/subscribed" class="dropdown-toggle" data-hover="dropdown">Subskrybowane</a><b class="caret"></b>

            <ul class="dropdown-menu">
                @foreach ($subscriptions as $subscription)
                <li><a href="{{ route('group_contents', array('group' => $subscription)) }}">{{ $subscription }}</a></li>
                @endforeach

                @if (!$subscriptions)
                <li><a href="{{ action('GroupController@showList') }}">Lista grup</a></li>
                @endif
            </ul>
        </li>

        <?php $moderatedGroups = Auth::user()->moderatedGroups(); natcasesort($moderatedGroups); ?>

        <li class="dropdown moderated_dropdown">
            <a href="/g/moderated" class="dropdown-toggle" data-hover="dropdown">Moderowane</a><b class="caret"></b>

            <ul class="dropdown-menu">
                @foreach ($moderatedGroups as $moderatedGroup)
                <li><a href="{{ route('group_contents', array('group' => $moderatedGroup)) }}">{{ $moderatedGroup }}</a></li>
                @endforeach

                @if (!$moderatedGroups)
                <li><a href="{{ action('GroupController@showCreateForm') }}">Zakładanie grupy</a></li>
                @endif
            </ul>
        </li>

        <?php $observedUsers = (array) Auth::user()->_observed_users; natcasesort($observedUsers); ?>

        <li class="dropdown observed_dropdown">
            <a href="/g/observed" class="dropdown-toggle" data-hover="dropdown">Obserwowani</a><b class="caret"></b>

            <ul class="dropdown-menu">
                @foreach ($observedUsers as $observedUser)
                <li><a href="{{ route('user_profile', $observedUser) }}">{{ $observedUser }}</a></li>
                @endforeach
            </ul>
        </li>

        @foreach (Auth::user()->folders as $cfolder)
        <?php $folderGroups = $cfolder->groups; natcasesort($folderGroups); ?>

        <li class="dropdown folder_dropdown">
            <a href="{{ route('user_folder_contents', [$cfolder->user->_id, $cfolder->_id]) }}" class="dropdown-toggle" data-hover="dropdown">{{{ $cfolder->name }}}</a><b class="caret"></b>

            <ul class="dropdown-menu">
                @foreach ($folderGroups as $folderGroup)
                <li><a href="{{ route('group_contents', array('group' => $folderGroup)) }}">{{ $folderGroup }}</a></li>
                @endforeach
            </ul>
        </li>
        @endforeach

        <li><a href="/g/saved">Zapisane</a></li>

        @endif

        @foreach ($popularGroups as $pgroup)
        <li><a href="/g/{{ $pgroup['urlname'] }}">{{ $pgroup['name'] }}</a></li>
        @endforeach

        <li class="group_list_link"><a href="/groups/list"><span class="glyphicon glyphicon-th-list"></span> Lista grup</a></li>
    </ul>
</div>

<div class="navbar navbar-inverse navbar-{{ $navbarClass }}">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="/">
            @if (Request::getHttpHost() == 'strimsy.pl')
                <img src="/static/img/strimsy.png" alt="Strimoid">
            @else
                <img src="/static/img/logo.png" alt="Strimoid">
            @endif
            </a>
        </div>

        <?php

        if (!isset($groupURLName))
        {
            $popularTabURL = route('global_contents');
            $newTabURL = route('global_contents_new');
            $entriesTabURL = route('global_entries');
        }
        elseif (isset($folder))
        {
            $popularTabURL = route('user_folder_contents', [$folder->user->_id, $folder->_id]);
            $newTabURL = route('user_folder_contents_new', [$folder->user->_id, $folder->_id]);
            $entriesTabURL = route('user_folder_entries', [$folder->user->_id, $folder->_id]);
        }
        else
        {
            $popularTabURL = route('group_contents', ['group' => $groupURLName]);
            $newTabURL = route('group_contents_new', ['group' => $groupURLName]);
            $entriesTabURL = route('group_entries', ['group' => $groupURLName]);
        }

        ?>

        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li @if (ends_with($currentRoute, 'contents')) class="active" @endif>
                    <a href="{{ $popularTabURL }}">{{{ $currentGroup->name or 'Strimoid' }}}</a>
                </li>
                <li @if (ends_with($currentRoute, 'contents_new')) class="active" @endif>
                    <a href="{{ $newTabURL }}">nowe</a>
                </li>
                <li @if (ends_with($currentRoute, 'entries')) class="active" @endif>
                    <a href="{{ $entriesTabURL }}">wpisy</a>
                </li>
            </ul>

            @if (Auth::check())
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown notifications_dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-globe notifications_icon @if ($newNotificationsCount > 0) notifications_icon_new @endif"></span> <b class="caret"></b>
                        <span class="badge @if (!$newNotificationsCount) hide @endif">{{ $newNotificationsCount }}</span>
                    </a>

                    <div class="dropdown-menu notifications" data-new-notifications="{{ intval($newNotificationsCount) }}">
                        <div class="notifications_scroll">
                            <div class="notifications_list">
                                @foreach ($notifications as $notification)
                                <a href="{{ $notification->getURL() }}" class="@if (!$notification->read) new @endif" data-id="{{ Base58::encode($notification->_id) }}">
                                    @if ($notification->sourceUser)
                                        <img src="{{ $notification->sourceUser->getAvatarPath() }}" class="pull-left">
                                    @endif

                                    <div class="media-body">
                                        {{ $notification->title }}

                                        <br>
                                        <small class="pull-left">
                                            {{ $notification->getTypeDescription() }}
                                        </small>
                                        <small class="pull-right">
                                            <time pubdate title="{{ $notification->getLocalTime() }}">{{ $notification->created_at->diffForHumans() }}</time>
                                        </small>
                                    </div>

                                    <div class="clearfix"></div>
                                </a>
                                @endforeach

                                @if (!count($notifications))
                                    <a>Nie posiadasz żadnych powiadomień.</a>
                                @endif
                            </div>
                        </div>

                        <div class="notifications_footer">
                            <a href="/notifications">Wszystkie</a>
                            <a class="mark_as_read_link action_link pull-right">Oznacz jako przeczytane</a>
                        </div>
                    </div>
                </li>

                <li class="dropdown user_dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ Auth::user()->getAvatarPath(50, 50) }}">
                        {{ Auth::user()->name }} <b class="caret"></b>
                    </a>

                    <ul class="dropdown-menu user_menu">
                        <li><a href="{{ route('user_profile', Auth::user()->name) }}"><span class="glyphicon glyphicon-user"></span> twój profil</a></li>
                        <li><a href="/conversations"><span class="glyphicon glyphicon-envelope"></span> konwersacje</a></li>
                        <li><a href="{{ action('UserController@showSettings') }}"><span class="glyphicon glyphicon-wrench"></span> ustawienia</a></li>

                        <li class="divider"></li>

                        <li>
                            <a class="action_link" onclick="$('.logout_form').submit()">
                                <span class="glyphicon glyphicon-log-out"></span> wyloguj
                            </a>
                            {{ Form::open(array('action' => 'UserController@logout', 'class' => 'logout_form')) }}
                            {{ Form::close() }}
                        </li>
                    </ul>
                </li>
            </ul>
            @else
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">logowanie <b class="caret"></b></a>
                    <ul class="dropdown-menu login_menu">
                        {{ Form::open(array('action' => 'UserController@login', 'class' => 'navbar-form')) }}
                            <input type="text" name="username" placeholder="Login" class="form-control" style="margin-bottom: 10px">
                            <input type="password" name="password" placeholder="Hasło" class="form-control" style="margin-bottom: 10px">
                            <div class="checkbox" style="padding-top: 5px">
                                <label>
                                    <input name="remember" type="checkbox" value="true"> Zapamiętaj
                                </label>
                            </div>

                            <button type="submit" class="btn btn-success pull-right">Zaloguj</button>
                        {{ Form::close() }}
                    </ul>
                </li>
                <li><a href="{{ action('UserController@showRegisterForm') }}">rejestracja</a></li>
            </ul>
            @endif

        </div>
    </div>
</div>

<div class="container @if (@Auth::user()->settings['pin_navbar']) navbar-fixed-margin @endif">
    <div class="row">
        <div class="main_col @yield('content_class', 'col-md-8')">
            @if (Session::has('success_msg'))
                <div class="alert alert-dismissable alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ Session::get('success_msg') }}
                </div>
            @endif

            @if (Session::has('info_msg'))
            <div class="alert alert-dismissable alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ Session::get('info_msg') }}
            </div>
            @endif

            @if (Session::has('warning_msg'))
            <div class="alert alert-dismissable alert-warning">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ Session::get('warning_msg') }}
            </div>
            @endif

            @if (Session::has('danger_msg'))
            <div class="alert alert-dismissable alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ Session::get('danger_msg') }}
            </div>
            @endif

            @yield('content')
        </div>

        <div class="@yield('sidebar_class', 'col-md-4') sidebar">
            @yield('sidebar')
        </div>
    </div>
</div>

<footer>

    <hr>

    <div class="row">
        <div class="col-sm-2 col-sm-offset-2">
            <ul>
                <li><a href="/" rel="nofollow">Strona główna</a></li>
                <li><a href="{{ action('GroupController@showList') }}" rel="nofollow">Lista grup</a></li>
            </ul>
        </div>

        <div class="col-sm-2">
            <ul>
                <li><a href="/guide" rel="nofollow">Przewodnik</a></li>
                <li><a href="/ranking" rel="nofollow">Ranking</a></li>
            </ul>
        </div>

        <div class="col-sm-2">
            <ul>
                <li><a href="/rss" rel="nofollow">RSS</a></li>
                <li><a href="http://developers.strimoid.pl/" rel="nofollow">API</a></li>
            </ul>
        </div>

        <div class="col-sm-2">
            <ul>
                <li><a href="/" rel="nofollow">Regulamin</a></li>
                <li><a href="/contact" rel="nofollow">Kontakt</a></li>
            </ul>
        </div>
    </div>

    <hr>

    <p class="pull-left">Serwis wykorzystuje <a href="/cookies" rel="nofollow">pliki cookies.</a></p>
    <p class="pull-right toggle_night_mode">tryb nocny <span class="glyphicon glyphicon-adjust"></span></p>

</footer>

<script src="/static/js/app.js?105"></script>

@if (Auth::check())
<script>
    window.username = '{{ Auth::id()  }}';
    window.settings = {{ json_encode(Auth::User()->settings) }};
    window.observed_users = {{ json_encode((array) Auth::user()->_observed_users) }};
    window.blocked_users = {{ json_encode(Auth::user()->blockedUsers()) }};
    window.blocked_groups = {{ json_encode(Auth::user()->blockedGroups()) }};
    window.subscribed_groups = {{ json_encode(Auth::user()->subscribedGroups()) }};
    window.moderated_groups = {{ json_encode(Auth::user()->moderatedGroups()) }};

    @if (isset($groupURLName) && $groupURLName)
        window.group = '{{{ $groupURLName }}}';
    @endif
</script>
@endif

@yield('scripts')

<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-45119517-1', 'strimoid.pl');
    ga('send', 'pageview');
</script>

<script src="//cdn.ravenjs.com/1.1.15/jquery,native/raven.min.js"></script>
<script>
    Raven.config('https://5b9dbcd47b434b228585ac5433b0c730@app.getsentry.com/26746', {
        whitelistUrls: ['strimoid.pl/']
    }).install();
</script>

</body>
</html>
