@extends('global.master')

@section('content')
    @if ($type == 'contents')
        @foreach ($contents as $content)
            @include('content.widget', ['content' => $content])
        @endforeach

        {!! with(new BootstrapPresenter($contents))->render() !!}
    @elseif ($type == 'comments')
        @foreach ($comments as $comment)
            @include('user.widgets.comment', ['comment' => $comment])
        @endforeach

        {!! with(new BootstrapPresenter($comments))->render() !!}
    @elseif ($type == 'entries')
        @foreach ($entries as $entry)
            @include('user.widgets.entry', ['entry' => $entry])
        @endforeach

        {!! with(new BootstrapPresenter($entries))->render() !!}
    @elseif ($type == 'moderated')
    <?php $x = $moderated->firstItem() ?>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nazwa grupy</th>
                    <th>Dodany</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($moderated as $group)
            <tr>
                <td>{!! $x++ !!}</td>
                <td><a href="{!! route('group_contents', $group) !!}">{{ $group->urlname }}</a></td>
                <td><time pubdate datetime="{!! $group->pivot->created_at->format('c') !!}" title="{!! $group->getLocalTime() !!}">{!! $group->pivot->created_at->diffForHumans() !!}</time></td>
            </tr>
            @endforeach
            </tbody>
        </table>

        {!! with(new BootstrapPresenter($moderated))->render() !!}
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

    @if ($action->element instanceof Strimoid\Models\Content)
        @include('content.widget', ['content' => $action->element])
    @endif

    @if ($action->element instanceof Strimoid\Models\Comment)
        @include('user.widgets.comment', ['comment' => $action->element])
    @endif

    @if ($action->element instanceof Strimoid\Models\CommentReply)
        @if (!isset($oldReply->comment) || $oldReply->comment->getKey() != $action->element->parent->getKey())
            @include('user.widgets.comment', ['content' => $action->element->parent])
        @endif

        @include('user.widgets.comment_reply', ['reply' => $action->element])
    @endif

    @if ($action->element instanceof Strimoid\Models\Entry)
        @include('user.widgets.entry', ['entry' => $action->element])
    @endif

    @if ($action->element instanceof Strimoid\Models\EntryReply)
        @if (!isset($oldReply->entry) || $oldReply->entry->getKey() != $action->element->parent->getKey())
            @include('user.widgets.entry', ['entry' => $action->element->parent])
        @endif

        @include('user.widgets.entry_reply', ['reply' => $action->element])
    @endif

    @endforeach

    {!! with(new BootstrapPresenter($actions))->render() !!}

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

@if (Auth::check() && Auth::id() != $user->getKey())
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
            <a href="{!! route('user_profile.type_filter', array($user->name, 'contents')) !!}">{!! Lang::choice('pluralization.contents', intval($user->contents->count())) !!}</a>
        </li>
        <li @if ($type == 'comments') class="active" @endif>
            <a href="{!! route('user_profile.type_filter', array($user->name, 'comments')) !!}">{!! Lang::choice('pluralization.comments', intval($user->comments->count())) !!}</a>
        </li>
        <li @if ($type == 'comment_replies') class="active" @endif>
        <a href="{!! route('user_profile.type_filter', array($user->name, 'comment_replies')) !!}">{!! Lang::choice('pluralization.comments', intval($user->comments->count())) !!} na komentarze</a>
        </li>
        <li @if ($type == 'entries') class="active" @endif>
            <a href="{!! route('user_profile.type_filter', array($user->name, 'entries')) !!}">{!! Lang::choice('pluralization.entries', intval($user->entries->count())) !!}</a>
        </li>
        <li @if ($type == 'entry_replies') class="active" @endif>
        <a href="{!! route('user_profile.type_filter', array($user->name, 'entry_replies')) !!}">{!! Lang::choice('pluralization.replies', intval($user->entries->count())) !!} na wpisy</a>
        </li>
        <li @if ($type == 'moderated') class="active" @endif>
            <a href="{!! route('user_profile.type_filter', array($user->name, 'moderated')) !!}">{!! Lang::choice('pluralization.moderatedgroups', intval(Strimoid\Models\GroupModerator::where('user_id', $user->getKey())->count())) !!}</a>
        </li>
    </ul>
</div>

@stop
