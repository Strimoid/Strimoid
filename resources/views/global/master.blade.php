<!DOCTYPE html>
<html lang="pl">
<head>
    @include('global.parts.head')
</head>

<body class="{{ request()->cookie('night_mode') ? 'night' : '' }}">

<?php

$currentRoute = Route::currentRouteName() ?: '';
$navbarClass = setting('pin_navbar') ? 'fixed-top' : 'static-top';

?>

@include('global.parts.groupbar')
@include('global.parts.navbar')

<div class="container {{ setting('pin_navbar') ? 'navbar-fixed-margin' : '' }}">
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



@if (auth()->check())
    <script>
        window.username = {{ Js::from(user()->name) }};
        window.settings = {{ Js::from(user()->settings) }};
        window.observed_users = {{ Js::from(user()->followedUsers()->pluck('name')) }};
        window.blocked_users = {{ Js::from(user()->blockedUsers()->pluck('name')) }};
        window.blocked_groups = {{ Js::from(user()->blockedGroups()->pluck('urlname')) }};
        window.subscribed_groups = {{ Js::from(user()->subscribedGroups()->pluck('urlname')) }};
        window.moderated_groups = {{ Js::from(user()->moderatedGroups()->pluck('urlname')) }};
        window.bugsnag_key = '{{ config('bugsnag.public_api_key') }}';

        @if (isset($groupURLName) && $groupURLName)
            window.group = '{{{ $groupURLName }}}';
        @endif
    </script>
@endif

@yield('scripts')

@if (!config('app.debug'))
    @if (config('strimoid.html_snippet'))
        {!! config('strimoid.html_snippet') !!}
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
      "@@context": "https://schema.org",
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
