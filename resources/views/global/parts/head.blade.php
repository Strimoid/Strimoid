<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta property="og:title" content="@yield('title', 'Strimoid')">
<meta name="description" content="@yield('description', 'Strimoid')">

<link rel="shortcut icon" href="/favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png">
<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="/rss">
<link rel="manifest" href="/manifest.json">

<title>@yield('title', e($pageTitle))</title>

<link href="{{ mix('client.css', 'assets') }}" rel="stylesheet">

@if (isset($group)  && $group instanceof Strimoid\Models\Group
        && $group->style  && !setting('disable_groupstyles'))
    <link href="/uploads/styles/{!! $group->style !!}" rel="stylesheet" data-id="group_style">
@elseif (isset($group) && Storage::disk('styles')->exists(Str::lower($group->urlname) .'.css') && !setting('disable_groupstyles'))
    <link href="/uploads/styles/{!! Str::lower($group->urlname) !!}.css" rel="stylesheet" data-id="group_style">
@elseif (auth()->check() && setting('css_style'))
    <link href="{{{ setting('css_style') }}}" rel="stylesheet">
@endif

@yield('head')
