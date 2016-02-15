@extends('global.master')

<?php $q = Input::get('q'); $t = Input::get('t') ? Input::get('t'): 'c'; ?>

@section('content')
{!! Form::open(['method' => 'GET', 'style' => 'margin-bottom: 20px']) !!}
<div class="input-group">
    {!! Form::text('q', Input::get('q'), ['class' => 'form-control', 'placeholder' => 'podaj wyszukiwaną frazę...']) !!}
    <input type="hidden" name="t" value="{{{ Input::get('t') }}}">

    <div class="input-group-btn">
        <button type="submit" class="btn btn-secondary btn-primary">Szukaj</button>
    </div>
</div>
{!! Form::close() !!}

@if (isset($results))

@if ($t == 'c')
    @foreach ($results as $content)
        @include('content.widget', ['content' => $content])
    @endforeach
@elseif ($t == 'e')
    @foreach ($results as $entry)
        @include('entries.widget', ['entry' => $entry])
    @endforeach
@elseif ($t == 'g')
    <div class="group_list">
    @foreach ($results as $group)
        @include('group.widget', ['group' => $group])
    @endforeach
    </div>

    <?php $group = null; ?>
@endif

{!! with(new BootstrapPresenter($results->appends(['q' => $q, 't' => $t])))->render() !!}

@endif

@stop

@section('sidebar')
<div class="well">
    <div class="list-group">

        <a href="{!! route('search', ['q' => $q, 't' => 'c']) !!}" class="list-group-item @if ($t == 'c')active @endif">Treści</a>
        <a href="{!! route('search', ['q' => $q, 't' => 'e']) !!}" class="list-group-item @if ($t == 'e')active @endif">Wpisy</a>
        <a href="{!! route('search', ['q' => $q, 't' => 'g']) !!}" class="list-group-item @if ($t == 'g')active @endif">Grupy</a>
    </div>
</div>
@stop
