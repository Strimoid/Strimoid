<!DOCTYPE html>
<html lang="pl">
<head>
    @include('global.parts.head')
</head>

<body class="@if (isset($_COOKIE['night_mode'])) night @endif">

<?php

$currentRoute = Route::currentRouteName() ?: '';
$navbarClass = (auth()->check() && @user()->settings['pin_navbar']) ? 'fixed-top' : 'static-top';

?>

@include('global.parts.groupbar')
@include('global.parts.navbar')

<div class="container @if (@user()->settings['pin_navbar']) navbar-fixed-margin @endif">
    <div class="row">
        <div class="main_col @yield('content_class', 'col-md-8')">
            @include('flash::message')
            @include('global.parts.alerts')

            @yield('content')
        </div>

        <aside class="@yield('sidebar_class', 'col-md-4') sidebar">
            @yield('sidebar')
        </aside>
    </div>
</div>

<footer>
    @include('global.parts.footer')
</footer>

@if (auth()->guest())
    @include('auth.login-modal')
@endif

<script src="{{ mix('client.js', 'assets') }}"></script>

@if (auth()->check())
    <script>
        window.username = '{!! user()->name  !!}';
        window.settings = {!! json_encode(user()->settings) !!};
        window.observed_users = {!! json_encode(user()->followedUsers()->pluck('name')) !!};
        window.blocked_users = {!! json_encode(user()->blockedUsers()->pluck('name')) !!};
        window.blocked_groups = {!! json_encode(user()->blockedGroups()->pluck('urlname')) !!};
        window.subscribed_groups = {!! json_encode(user()->subscribedGroups()->pluck('urlname')) !!};
        window.moderated_groups = {!! json_encode(user()->moderatedGroups()->pluck('urlname')) !!};
        window.bugsnag_key = '{!! config('bugsnag.public_api_key') !!}';

        @if (isset($groupURLName) && $groupURLName)
            window.group = '{{{ $groupURLName }}}';
        @endif
    </script>
@endif

@yield('scripts')

@if (!config('app.debug'))
    @if (config('services.piwik.host') && config('services.piwik.site_id'))
        <script type="text/javascript">
            var _paq = _paq || [];
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            (function() {
                var u="{{ config('services.piwik.host') }}";
                _paq.push(['setTrackerUrl', u+'piwik.php']);
                _paq.push(['setSiteId', {{ (int) config('services.piwik.site_id') }}]);
                var d=document,g=d.createElement('script'),s=d.getElementsByTagName('script')[0];
                g.type='text/javascript';g.async=true;g.defer=true;g.src=u+'piwik.js';s.parentNode.insertBefore(g,s)
            })();
        </script>
        <noscript><p><img src="{{ config('services.piwik.host') }}piwik.php?idsite=1" style="border:0"></p></noscript>
    @endif

    @if (config('services.raven.public_dsn'))
        <script src="//cdn.ravenjs.com/3.7.0/console/raven.min.js"></script>
        <script>
            Raven.config('{{ config('services.raven.public_dsn') }}').install()
        </script>
    @endif
@endif

<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "url": "https://strm.pl/",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://strm.pl/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
</script>

{{--
<script>
    new Pjax({
        elements: 'body > .container a[href]',
        selectors: ['body > .container a', 'body > .container']
    })
</script>
--}}

</body>
</html>
