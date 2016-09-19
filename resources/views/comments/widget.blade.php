<?php

$isReply = isset($isReply) ? true : false;

?>

<div class="panel-default comment @if ($isReply) comment_reply @endif" data-id="{{ $comment->hashId() }}"
    @if ($isReply) data-parent-id="{{ $comment->parent->getKey() }}" @endif>
    <a name="{!! $comment->hashId() !!}"></a>

    <div class="comment_avatar">
        <img src="{!! $comment->user->getAvatarPath() !!}" alt="{{ $comment->user->name }}"
             class="{!! $comment->user->getSexClass() !!}">
        <div class="sex_marker {!! $comment->user->getSexClass() !!}"></div>
    </div>

    <div class="panel-heading comment_header">
        <a href="{!! route('user_profile', $comment->user) !!}" class="comment_author"
           data-hover="user_widget" data-user="{!! $comment->user->name !!}">
            {!! $comment->user->getColoredName() !!}
        </a>

        <span class="pull-right">
            <i class="fa fa-clock-o"></i>

            <a href="{!! $comment->getURL() !!}">
                <time pubdate datetime="{{ $comment->created_at->format('c') }}" title="{{ $comment->getLocalTime() }}">
                    {{ $comment->createdAgo() }}
                </time>
            </a>

            @if (isset($contentLink) && $comment->content)
                <i class="fa fa-share"></i>

                <a href="{!! route('content_comments', $comment->content) !!}">
                    {{ Str::limit($comment->content->title, 40) }}
                </a>
            @endif

            <span class="voting" data-id="{!! $comment->hashId() !!}" state="{!! $comment->getVoteState() !!}" @if (!$isReply) data-type="comment" @else data-type="comment_reply" @endif>
                <button type="button" class="btn btn-secondary btn-xs vote-btn-up @if ($comment->getVoteState() == 'uv') btn-success @endif">
                    <i class="fa fa-arrow-up vote-up"></i> <span class="count">{!! $comment->uv !!}</span>
                </button>

                <button type="button" class="btn btn-secondary btn-xs vote-btn-down @if ($comment->getVoteState() == 'dv') btn-danger @endif">
                    <i class="fa fa-arrow-down vote-down"></i> <span class="count">{!! $comment->dv !!}</span>
                </button>
            </span>
        </span>
    </div>

    <div class="comment_text md @if ($comment->isHidden()) blocked @endif">
        {!! $comment->text !!}
    </div>

    @if ($comment->isHidden())
    <div class="comment_text">
        <a class="show_blocked_link action_link">
            [Komentarz został ukryty, kliknij aby go wyświetlić]
        </a>
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

        <a href="{!! $comment->getURL() !!}">#</a>
    </div>
</div>
