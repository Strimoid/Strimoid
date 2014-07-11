<?php

$isReply = isset($isReply) ? true : false;

?>

<div class="panel-default entry @if ($isReply) entry_reply @endif" data-id="{{ $entry->_id }}" @if ($isReply) data-parent-id="{{ $entry->entry->_id }}" @endif>
    <a name="{{ $entry->_id }}"></a>

    <div class="entry_avatar">
        <img src="{{ $entry->user->getAvatarPath() }}" alt="{{ $entry->user->name }}">
        <div class="sex_marker {{ $entry->user->getSexClass() }}"></div>
    </div>

    <div class="panel-heading entry_header">
        <a href="{{ route('user_profile', $entry->user->name) }}" class="entry_author">{{ $entry->user->getColoredName() }}</a>

        <span class="pull-right">
            @if (!$isReply)
                <span class="glyphicon glyphicon-tag"></span> <a href="{{ route('group_entries', $entry->group_id) }}" class="entry_group">g/{{{ $entry->group_id }}}</a>
            @endif

            <span class="glyphicon glyphicon-time"></span> <a href="{{ $entry->getURL() }}"><time pubdate datetime="{{ $entry->created_at->format('c') }}" title="{{ $entry->getLocalTime() }}">{{ $entry->created_at->diffForHumans() }}</time></a>

            <span class="voting" data-id="{{ $entry->_id }}" data-state="{{ $entry->getVoteState() }}" @if (!$isReply) data-type="entry" @else data-type="entry_reply" @endif>
                <button type="button" class="btn btn-default btn-xs vote-btn-up @if ($entry->getVoteState() == 'uv') btn-success @endif">
                    <span class="glyphicon glyphicon-arrow-up vote-up"></span> <span class="count">{{ $entry->uv }}</span>
                </button>

                <button type="button" class="btn btn-default btn-xs vote-btn-down @if ($entry->getVoteState() == 'dv') btn-danger @endif">
                    <span class="glyphicon glyphicon-arrow-down vote-down"></span> <span class="count">{{ $entry->dv }}</span>
                </button>
            </span>
        </span>
    </div>

    <div class="entry_text md @if ($entry->isHidden()) blocked @endif"">
        {{ $entry->text }}
    </div>

    @if ($entry->isHidden())
    <div class="entry_text">
        <a class="show_blocked_link action_link">[Odpowiedź została ukryta, kliknij aby ją wyświetlić]</a>
    </div>
    @endif

    <div class="entry_actions pull-right">
        @if (Auth::check())
            @if(!$isReply && in_array($entry->_id, (array) Auth::user()->data->_saved_entries))
                <span class="glyphicon glyphicon-star action_link save_entry" title="zapisz"></span>
            @elseif (!$isReply)
                <span class="glyphicon glyphicon-star-empty action_link save_entry" title="zapisz"></span>
            @endif

            <a class="entry_reply_link action_link">odpowiedz</a>

            @if ($entry->canRemove(Auth::user()))
                <a class="entry_remove_link action_link">usuń</a>
            @endif
            @if ($entry->canEdit(Auth::user()))
                <a class="entry_edit_link action_link">edytuj</a>
            @endif
        @endif

        <a href="{{ $entry->getURL() }}">#</a>
    </div>
</div>
