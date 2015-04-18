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
    @stopś
@endif

@section('content')
<div class="clearfix"></div>
@if (Auth::check())
{!! Form::open(['class' => 'form entry_add_form enter_send entry_add']) !!}
<div class="panel-default entry">
    <div class="entry_avatar">
        <img src="{!! Auth::user()->getAvatarPath() !!}">
    </div>

    <div class="entry_text">
        <div class="form-group @if ($errors->has('text')) has-error @endif col-lg-12">
            {!! Form::textarea('text', null, ['class' => 'form-control', 'placeholder' => 'Treść wpisu...', 'rows' => 2]) !!}

            @if($errors->has('text'))
            <p class="help-block">{!! $errors->first('text') !!}</p>
            @endif
        </div>

        <div class="form-group col-lg-12 pull-right @if ($errors->has('groupname')) has-error @endif">
            <div class="input-group input-group-appended">
                {!! Form::text('groupname', $suggestedGroup, array('class' => 'form-control group_typeahead', 'placeholder' => 'podaj nazwę grupy...')) !!}

                <div class="input-group-btn">
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </div>
            </div>

            @if($errors->has('groupname'))
            <p class="help-block">{!! $errors->first('groupname') !!}</p>
            @endif
        </div>
    </div>
</div>
<div class="clearfix"></div>
{!! Form::close() !!}
@endif

<div class="entries">
    @foreach ($entries as $entry)
        @include('entries.widget', ['entry' => $entry])

        @if ($entry->replies_count > 2 && !starts_with(Route::current()->getName(), 'single_entry'))
            <div class="entry entry_reply entry_expand_replies" data-id="{!! $entry->hashId() !!}">
                Pokaż pozostałe wpisy ({!! Lang::choice('pluralization.replies', ($entry->replies_count-2)) !!})
            </div>
        @endif

        <?php
        $replies = (starts_with(Route::current()->getName(), 'single_entry'))
            ? $entry->replies : $entry->replies->slice(-2, 2);
        ?>

        @foreach ($replies as $reply)
            @include('entries.widget', ['entry' => $reply, 'isReply' => true])
        @endforeach
    @endforeach
</div>

@if (is_object($entries))
    {!! with(new BootstrapPresenter($entries))->render() !!}
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
