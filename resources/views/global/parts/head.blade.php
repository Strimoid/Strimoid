<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta property="og:title" content="@yield('title', 'Strimoid')">
<meta name="description" content="@yield('description', 'Strimoid')">

@if (Request::getHttpHost() == 'strimsy.pl')
    <meta name="robots" content="noindex">
@endif

<link rel="shortcut icon" href="/favicon.ico">
<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="/rss">

<title>@yield('title', e($pageTitle))</title>

<link href="{!! $cssFilename !!}" rel="stylesheet">

@if (Input::get('night') || isset($_COOKIE['night_mode']))
    <link href="/static/css/night.css?1" rel="stylesheet" data-id="night_mode">
@endif

@if (isset($group)  && $group instanceof Strimoid\Models\Group
        && $group->style  && !@Auth::user()->settings['disable_groupstyles'])
    <link href="/uploads/styles/{!! $group->style !!}" rel="stylesheet" data-id="group_style">
@elseif (isset($group) && file_exists(Config::get('app.uploads_path').'/styles/'. Str::lower($group->urlname) .'.css') && !@Auth::user()->settings['disable_groupstyles'])
    <link href="/uploads/styles/{!! Str::lower($group->urlname) !!}.css" rel="stylesheet" data-id="group_style">
@elseif (Auth::check() && @Auth::user()->settings['css_style'])
    <link href="{{{ Auth::user()->settings['css_style'] }}}" rel="stylesheet">
@endif

<script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.0-beta.13/angular.min.js"></script>

<script src="{!! $componentsFilename !!}"></script>

@yield('head')
