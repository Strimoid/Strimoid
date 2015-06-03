<!DOCTYPE html>
<html lang="pl">
<head>
    @include('global.parts.head')
</head>

<body class="@if (Input::get('night') || isset($_COOKIE['night_mode'])) night @endif">

<?php

$currentRoute = Route::currentRouteName() ?: '';
$navbarClass = (Auth::check() && @Auth::user()->settings['pin_navbar'])
    ? 'fixed-top' : 'static-top';

?>

@include('global.parts.groupbar')

<div class="navbar navbar-inverse navbar-{!! $navbarClass !!}">
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

        if (isset($group))
            $groupURLName = $group->urlname;
        elseif (isset($group_name) && $group_name == 'all' && (Auth::guest() || !@Auth::user()->settings['homepage_subscribed']))
            $groupURLName = null;

        $routeData = ['name' => 'global', 'params' => null];

        if (isset($groupURLName))
        {
            $routeData = ['name' => 'group', 'params' => [
                $groupURLName
            ]];
        }
        elseif (isset($folder))
        {
            $routeData = ['name' => 'user_folder', 'params' => [
                $folder->user->getKey(), $folder->getKey()
            ]];
        }

        ?>

        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li @if (ends_with($currentRoute, '_contents')) class="active" @endif>
                    <a href="{!! route($routeData['name'] .'_contents', $routeData['params']) !!}">{{ $currentGroup->name or 'Strimoid' }}</a>
                </li>
                <li @if (ends_with($currentRoute, '_contents_new')) class="active" @endif>
                    <a href="{!! route($routeData['name'] .'_contents_new', $routeData['params']) !!}">nowe</a>
                </li>
                <li @if (ends_with($currentRoute, '_comments')) class="active" @endif>
                    <a href="{!! route($routeData['name'] .'_comments', $routeData['params']) !!}">komentarze</a>
                </li>
                <li @if (ends_with($currentRoute, '_entries')) class="active" @endif>
                    <a href="{!! route($routeData['name'] .'_entries', $routeData['params']) !!}">wpisy</a>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
            @if (Auth::check())
                @include('global.parts.notifications')
                @include('global.parts.user_dropdown')
            @else
                @include('global.parts.login')
            @endif
            </ul>

        </div>
    </div>
</div>

<div class="container @if (@Auth::user()->settings['pin_navbar']) navbar-fixed-margin @endif">
    <div class="row">
        <div class="main_col @yield('content_class', 'col-md-8')">
            @include('global.parts.alerts')

            @yield('content')
        </div>

        <div class="@yield('sidebar_class', 'col-md-4') sidebar">
            @yield('sidebar')
        </div>
    </div>
</div>

<footer>
    @include('global.parts.footer')
</footer>

<script src="{{ elixir('assets/js/all.js') }}"></script>

@if (Auth::check())
<script>
    window.username = '{!! Auth::id()  !!}';
    window.settings = {!! json_encode(Auth::User()->settings) !!};
    window.observed_users = {!! json_encode((array) Auth::user()->followedUsers()->lists('name')) !!};
    window.blocked_users = {!! json_encode(Auth::user()->blockedUsers()->lists('name')) !!};
    window.blocked_groups = {!! json_encode(Auth::user()->blockedGroups()->lists('urlname')) !!};
    window.subscribed_groups = {!! json_encode(Auth::user()->subscribedGroups()->lists('urlname')) !!};
    window.moderated_groups = {!! json_encode(Auth::user()->moderatedGroups()->lists('urlname')) !!};

    @if (isset($groupURLName) && $groupURLName)
        window.group = '{{{ $groupURLName }}}';
    @endif
</script>
@endif

@yield('scripts')

@if (!config('app.debug'))
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-45119517-1', 'strimoid.pl');
    ga('send', 'pageview');
</script>

<script src="//cdn.ravenjs.com/1.1.16/native/raven.min.js"></script>
<script>
    Raven.config('https://5b9dbcd47b434b228585ac5433b0c730@app.getsentry.com/26746', {
        whitelistUrls: ['strm.pl/']
    }).install()
</script>
@endif

<script>
    $(document).pjax('a', 'body > .container')
    $(document).on('pjax:end', function() {
        riot.mount('*')
    })

    riot.mount('*')
</script>

</body>
</html>
