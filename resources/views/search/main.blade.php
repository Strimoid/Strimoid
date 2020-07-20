@extends('global.master')

<?php $q = Input::get('q'); $t = Input::get('t') ? Input::get('t'): 'c'; ?>

@section('content')
{!! Form::open(['method' => 'GET', 'style' => 'margin-bottom: 20px']) !!}
<div class="input-group">
    {!! Form::text('q', Input::get('q'), ['class' => 'form-control', 'placeholder' => 'podaj wyszukiwaną frazę...']) !!}
    <input type="hidden" name="t" value="{{{ Input::get('t') }}}">

    <div class="input-group-append">
        <button type="submit" class="btn btn-secondary btn-primary">Szukaj</button>
    </div>
</div>
{!! Form::close() !!}

@if (isset($results))

@if ($t == 'c')
    @each('content.widget', $results, 'content')
@elseif ($t == 'e')
    @foreach ($results as $reply)
        @include('entries.widget', ['entry' => $reply, 'isReply' => false])
    @endforeach
@elseif ($t == 'g')
    <div class="group_list">
        @each('group.widget', $results, 'group')
    </div>

    <?php $group = null; ?>
@endif

{!! $results->appends(['q' => $q, 't' => $t])->links() !!}

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
