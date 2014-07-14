<?php

$isReply = isset($isReply) ? true : false;

?>

<div class="panel-default comment @if ($isReply) comment_reply @endif" data-id="{{ $comment->_id }}" @if ($isReply) data-parent-id="{{ $comment->comment->_id }}" @endif>
    <a name="{{ $comment->_id }}"></a>

    <div class="comment_avatar">
        <img src="{{ $comment->user->getAvatarPath() }}" alt="{{ $comment->user->name }}" class="{{ $comment->user->getSexClass() }}">
        <div class="sex_marker {{ $comment->user->getSexClass() }}"></div>
    </div>

    <div class="panel-heading comment_header">
        <a href="{{ route('user_profile', $comment->user->name) }}" class="comment_author" data-hover="user_widget" data-user="{{ $comment->user_id }}">{{ $comment->user->getColoredName() }}</a>

        <span class="pull-right">
            <span class="glyphicon glyphicon-time"></span> <a href="{{ $comment->getURL() }}"><time pubdate datetime="{{ $comment->created_at->format('c') }}" title="{{ $comment->getLocalTime() }}">{{ $comment->created_at->diffForHumans() }}</time></a>

            @if (isset($contentLink) && $comment->content)
                <span class="glyphicon glyphicon-share-alt"></span> <a href="{{ route('content_comments', $comment->content->_id) }}">{{{ Str::limit($comment->content->title, 40) }}}</a>
            @endif

            <span class="voting" data-id="{{ $comment->_id }}" data-state="{{ $comment->getVoteState() }}" @if (!$isReply) data-type="comment" @else data-type="comment_reply" @endif>
                <button type="button" class="btn btn-default btn-xs vote-btn-up @if ($comment->getVoteState() == 'uv') btn-success @endif">
                    <span class="glyphicon glyphicon-arrow-up vote-up"></span> <span class="count">{{ $comment->uv }}</span>
                </button>

                <button type="button" class="btn btn-default btn-xs vote-btn-down @if ($comment->getVoteState() == 'dv') btn-danger @endif">
                    <span class="glyphicon glyphicon-arrow-down vote-down"></span> <span class="count">{{ $comment->dv }}</span>
                </button>
            </span>
        </span>
    </div>

    <div class="comment_text md @if ($comment->isHidden()) blocked @endif">
        {{ $comment->text }}
    </div>

    @if ($comment->isHidden())
    <div class="comment_text">
        <a class="show_blocked_link action_link">[Komentarz został ukryty, kliknij aby go wyświetlić]</a>
    </div>
    @endif

    <div class="comment_actions pull-right">
        <a class="comment_reply_link action_link">odpowiedz</a>

        @if (Auth::check())
            @if ($comment->canRemove(Auth::user()))
                <a class="comment_remove_link action_link">usuń</a>
            @endif
            @if ($comment->canEdit(Auth::user()))
                <a class="comment_edit_link action_link">edytuj</a>
            @endif
        @endif

        <a href="{{ $comment->getURL() }}">#</a>
    </div>
</div>
