@extends('...global.master')

@section('content')
    @foreach ($comments as $comment)
        @include('widget', ['comment' => $comment, 'contentLink' => true])
    @endforeach

    {!! with(new BootstrapPresenter($comments))->render() !!}
@stop

@section('sidebar')
    @include('...group.sidebar.add_content')

    @include('...content.sidebar.sort')

    @include('...group.sidebar.search')

    @if (isset($group))
        @include('...group.sidebar.description', array('group' => $group))
        @include('...group.sidebar.stats', array('group' => $group))
    @endif

    @if (isset($folder))
        @include('...folders.sidebar.group_list', array('folder' => $folder))
    @endif

    @include('...group.sidebar.moderator')

    @include('...group.sidebar.popular_contents')
    @include('...group.sidebar.popular_comments')
@stop
