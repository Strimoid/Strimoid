@extends('global.master')

@section('content')
    @if ($type == 'contents')
        @foreach ($contents as $content)
            @include('content.widget', array('content' => $content))
        @endforeach

        {!! $contents->links() !!}
    @elseif ($type == 'comments')
        @foreach ($comments as $comment)
            @include('user.widgets.comment', array('comment' => $comment))
        @endforeach

        {!! $comments->links() !!}
    @elseif ($type == 'entries')
        @foreach ($entries as $entry)
            @include('user.widgets.entry', array('entry' => $entry))
        @endforeach

        {!! $entries->links() !!}
    @elseif ($type == 'moderated')
    <?php $x = 1 + (($moderated->getCurrentPage()-1) * 15); ?>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nazwa grupy</th>
                    <th>Dodany</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($moderated as $moderator)
            <tr>
                <td>{!! $x++ !!}</td>
                <td><a href="{!! route('group_contents', $moderator->group_id) !!}">{!! $moderator->group_id !!}</a></td>
                <td><time pubdate datetime="{!! $moderator->created_at->format('c') !!}" title="{!! $moderator->getLocalTime() !!}">{!! $moderator->created_at->diffForHumans() !!}</time></td>
            </tr>
            @endforeach
            </tbody>
        </table>

        {!! $moderated->links() !!}
    @endif

    @if (isset($actions))

    <?php $collection = $actions->getCollection(); ?>

    @foreach ($collection as $index => $action)

    <?php
        // Get previous item, it's needed for "grouping" replies
        if ($collection->has($index - 1)) {
            $prev = $collection->get($index - 1);

            if ($prev->reply)
                $oldReply = $prev->reply;
            else
                $oldReply = null;
        }
    ?>

    @if ($action->type == UserAction::TYPE_CONTENT && $action->content)
        @include('content.widget', array('content' => $action->content))
    @endif

    @if ($action->type == UserAction::TYPE_COMMENT && $action->comment && $action->comment->content)
        @include('user.widgets.comment', array('comment' => $action->comment))
    @endif

    @if ($action->type == UserAction::TYPE_COMMENT_REPLY && $action->reply)
        <?php $comment = $action->reply->comment; ?>

        @if (!isset($oldReply->comment) || $oldReply->comment->_id != $comment->_id)
            @include('user.widgets.comment', array('content' => $comment))
        @endif

        @include('user.widgets.comment_reply', array('reply' => $action->reply))
    @endif

    @if ($action->type == UserAction::TYPE_ENTRY && $action->entry)
        @include('user.widgets.entry', array('entry' => $action->entry))
    @endif

    @if ($action->type == UserAction::TYPE_ENTRY_REPLY && $action->reply)
        @if (!isset($oldReply->entry) || $oldReply->entry->_id != $action->reply->entry->_id)
            @include('user.widgets.entry', array('entry' => $action->reply->entry))
        @endif

        @include('user.widgets.entry_reply', array('reply' => $action->reply))
    @endif

    @endforeach

    {!! $actions->links() !!}

    @endif
@stop

@section('sidebar')
<div class="well user_info_widget">
    <h4>{!! $user->getColoredName() !!}</h4>

    <div class="row">
        <div class="col-lg-4">
            <p><img src="{!! $user->getAvatarPath() !!}" alt="{!! $user->name !!}" style="width: 100%; height: 100%;"></p>
        </div>
        <div class="col-lg-8">
            <p><strong>Dołączył:</strong> {!! $user->created_at->diffForHumans() !!}</p>

            @if (Auth::check())
                @if ($user->age)
                    <p><strong>Wiek:</strong> {!! (date('Y') - $user->age) !!}</p>
                @endif
                @if ($user->location)
                    <p><strong>Miejscowość:</strong> {{{ $user->location }}}</p>
            @endif
            @endif

            @if ($user->description)
                <p class="desc">{{{ $user->description }}}</p>
            @endif
        </div>
    </div>
</div>

@if (Auth::check() && Auth::user()->_id != $user->_id)
<?php

$observed = Auth::user()->isObservingUser($user);
$blocked = Auth::user()->isBlockingUser($user);

?>
<div class="well">
    <div class="btn-group" data-name="{!! $user->name !!}">
        <a href="{!! route('conversation.new_user', array('user' => $user->name)) !!}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-envelope"></span></a>
        <button class="user_observe_btn btn btn-sm @if($observed) btn-success @else btn-default @endif"><span class="glyphicon glyphicon-eye-open"></span> Obserwuj</button>
        <button class="user_block_btn btn btn-sm @if($blocked) btn-danger @else btn-default @endif"><span class="glyphicon glyphicon-ban-circle"></span> Zablokuj</button>
    </div>
</div>
@endif

<div class="well user_navigation_widget">
    <h4>Nawigacja</h4>

    <ul class="nav nav-pills nav-stacked">
        <li @if ($type == 'all') class="active" @endif>
            <a href="{!! route('user_profile', $user->name) !!}">Wszystkie akcje</a>
        </li>
        <li @if ($type == 'contents') class="active" @endif>
            <a href="{!! route('user_profile.type_filter', array($user->name, 'contents')) !!}">{!! Lang::choice('pluralization.contents', intval(Content::where('user_id', $user->getKey())->count())) !!}</a>
        </li>
        <li @if ($type == 'comments') class="active" @endif>
            <a href="{!! route('user_profile.type_filter', array($user->name, 'comments')) !!}">{!! Lang::choice('pluralization.comments', intval(Comment::where('user_id', $user->getKey())->count())) !!}</a>
        </li>
        <li @if ($type == 'comment_replies') class="active" @endif>
        <a href="{!! route('user_profile.type_filter', array($user->name, 'comment_replies')) !!}">{!! Lang::choice('pluralization.replies', intval(UserAction::where('user_id', $user->getKey())->where('type', UserAction::TYPE_COMMENT_REPLY)->count())) !!} na komentarze</a>
        </li>
        <li @if ($type == 'entries') class="active" @endif>
            <a href="{!! route('user_profile.type_filter', array($user->name, 'entries')) !!}">{!! Lang::choice('pluralization.entries', intval(Entry::where('user_id', $user->getKey())->count())) !!}</a>
        </li>
        <li @if ($type == 'entry_replies') class="active" @endif>
        <a href="{!! route('user_profile.type_filter', array($user->name, 'entry_replies')) !!}">{!! Lang::choice('pluralization.replies', intval(UserAction::where('user_id', $user->getKey())->where('type', UserAction::TYPE_ENTRY_REPLY)->count())) !!} na wpisy</a>
        </li>
        <li @if ($type == 'moderated') class="active" @endif>
            <a href="{!! route('user_profile.type_filter', array($user->name, 'moderated')) !!}">{!! Lang::choice('pluralization.moderatedgroups', intval(GroupModerator::where('user_id', $user->getKey())->count())) !!}</a>
        </li>
    </ul>
</div>

@stop
