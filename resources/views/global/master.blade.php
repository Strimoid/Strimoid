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
                <img src="/static/img/logo.png" alt="Strimoid">
            </a>
        </div>

        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                @include('global.parts.tabs')
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a><p class="toggle_night_mode"><span class="glyphicon glyphicon-adjust"></span></p></a></li>
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
            @include('flash::message')
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

<script src="/assets/js/laroute.js"></script>
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
    <script type="text/javascript">
        var _paq = _paq || [];
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="//piwik.strm.pl/";
            _paq.push(['setTrackerUrl', u+'piwik.php']);
            _paq.push(['setSiteId', 1]);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
        })();
    </script>
    <noscript><p><img src="//piwik.strm.pl/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>

    <script src="//cdn.ravenjs.com/1.1.22/jquery,native/raven.min.js"></script>
    <script>
        Raven.config('https://90c44c5ad4ff4a13abbb0721278678f9:1096b02c7bd64defbe32f64930c64288@sentry.strm.pl/3', {
            whitelistUrls: [/strm\.pl/]
        }).install()
    </script>
@endif

<script>
    $(document).pjax('body > .container a', 'body > .container')
    $(document).on('pjax:end', function() {
        riot.mount('*')
    })

    riot.mount('*')
</script>

</body>
</html>
