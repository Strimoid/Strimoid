@extends('global.master')

@section('content')
    @foreach ($comments as $comment)
        @include('comments.widget', ['comment' => $comment, 'contentLink' => true])
    @endforeach

    {!! $comments->onEachSide(2)->links() !!}
@stop

@section('sidebar')
    @include('group.sidebar.add_content')

    @include('content.sidebar.sort')

    @include('group.sidebar.search')

    @if (isset($group) && $group instanceof Strimoid\Models\Group)
        @include('group.sidebar.description', ['group' => $group])
        @include('group.sidebar.stats', ['group' => $group])
    @endif

    @if (isset($folder))
        @include('folders.sidebar.group_list', ['folder' => $folder])
    @endif

    @include('group.sidebar.moderator')

    @include('group.sidebar.popular_contents')
    @include('group.sidebar.popular_comments')
@stop
