@extends('global.master')

<?php

$suggestedGroup = (isset($group) && $group instanceof Strimoid\Models\Group) ? $group->urlname : '';

?>

@section('content_class') col-md-8 entries_col @stop

@if (Route::currentRouteName() == 'single_entry' || Route::currentRouteName() == 'single_entry_reply')
    @section('title')
        {{ Str::limit(strip_tags($entries[0]->text), 60) }}
    @stop
@elseif (isset($group) && $group instanceof Strimoid\Models\Group)
    @section('title')
        {{ $group->name }}
    @stop
@endif

@section('content')
    <div class="clearfix"></div>
    @if (Auth::check())
        @include('entries.form')
    @endif

    <div class="entries">
        @foreach ($entries as $entry)
            @include('entries.widget', ['entry' => $entry, 'isReply' => false])

            @if ($entry->replies_count > 2 && !Str::startsWith(Route::current()->getName(), 'single_entry'))
                <div class="entry entry_reply entry_expand_replies" data-id="{!! $entry->hashId() !!}">
                    @lang('entries.show remaining entries') ({!! Lang::choice('pluralization.replies', ($entry->replies_count-2)) !!})
                </div>
            @endif

            <?php
            $replies = (Str::startsWith(Route::current()->getName(), 'single_entry'))
                ? $entry->replies : $entry->replies->slice(-2, 2);
            ?>

            @foreach ($replies as $reply)
                @include('entries.widget', ['entry' => $reply, 'isReply' => true])
            @endforeach
        @endforeach
    </div>

    @if (is_object($entries))
        {!! $entries->links() !!}
    @endif
@stop

@section('sidebar')
    @include('group.sidebar.add_content')

    @if (isset($group) && $group instanceof Strimoid\Models\Group)
        @include('group.sidebar.description', compact($group))
        @include('group.sidebar.stats', compact($group))
    @endif

    @include('group.sidebar.popular_entries')
@stop
