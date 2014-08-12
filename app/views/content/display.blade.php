@extends('global.master')

@section('head')
    @if (Request::path() == '/')
        <link rel="search" type="application/opensearchdescription+xml" href="http://strimoid.pl/static/xml/search/contents.xml" title="Strimoid - treści">
        <link rel="search" type="application/opensearchdescription+xml" href="http://strimoid.pl/static/xml/search/entries.xml" title="Strimoid - wpisy">
        <link rel="search" type="application/opensearchdescription+xml" href="http://strimoid.pl/static/xml/search/groups.xml" title="Strimoid - grupy">
    @endif
@stop

@section('content')
    @foreach ($contents as $content)
        @include('content.widget', ['content' => $content])
    @endforeach

    {{ $contents->links() }}
@stop

@section('sidebar')
    @include('group.sidebar.add_content')

    @include('content.sidebar.sort')

    @include('group.sidebar.search')

    @if (isset($group))
        @include('group.sidebar.description', array('group' => $group))
        @include('group.sidebar.stats', array('group' => $group))
    @endif

    @if (isset($folder))
        @include('folders.sidebar.group_list', array('folder' => $folder))
    @endif

    @include('group.sidebar.moderator')

    @include('group.sidebar.popular_contents')
    @include('group.sidebar.popular_comments')
@stop
