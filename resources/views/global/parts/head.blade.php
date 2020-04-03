<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta property="og:title" content="@yield('title', 'Strimoid')">
<meta name="description" content="@yield('description', 'Strimoid')">

<link rel="shortcut icon" href="/favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
<link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="/rss">
<link rel="manifest" href="/manifest.json">

<title>@yield('title', e($pageTitle))</title>

<link href="{{ elixir('client.css', 'assets') }}" rel="stylesheet">

@if (isset($group)  && $group instanceof Strimoid\Models\Group
        && $group->style  && !@Auth::user()->settings['disable_groupstyles'])
    <link href="/uploads/styles/{!! $group->style !!}" rel="stylesheet" data-id="group_style">
@elseif (isset($group) && Storage::disk('styles')->exists(Str::lower($group->urlname) .'.css') && !@Auth::user()->settings['disable_groupstyles'])
    <link href="/uploads/styles/{!! Str::lower($group->urlname) !!}.css" rel="stylesheet" data-id="group_style">
@elseif (Auth::check() && @Auth::user()->settings['css_style'])
    <link href="{{{ Auth::user()->settings['css_style'] }}}" rel="stylesheet">
@endif

@yield('head')
