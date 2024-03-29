@extends('global.master')

@section('content')
<style type="text/css">
    a.tag { margin-top: 3px; margin-bottom: 3px; }
</style>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Popularne tagi</h3>
    </div>
    <div class="panel-body">
        @foreach ($popular_tags as $tag)
            <a href="{!! route('wizard_tag', $tag) !!}" class="btn btn-info tag">{!! $tag !!}</a>
        @endforeach
    </div>
    <div class="panel-footer">Wybranie tagu z powyższej listy ułatwi Ci znalezienie interesujących Cię grup.</div>
</div>

<div class="group_list">
    @foreach ($groups as $group)
        @include('group.widget', ['group' => $group])
    @endforeach
</div>

<?php $group = null;?>

{!! $groups->appends(['sort' => Input::get('sort')])!!}
@stop

@section('sidebar')
<div class="well group_search_widget">
    {{ html()->form('GET', action('SearchController@search'))->open() }}
    <div class="input-group">
        {{ html()->text('q')->class('form-control')->placeholder('podaj wyszukiwaną frazę...') }}
        <input type="hidden" name="t" value="g">

        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">Szukaj</button>
        </div>
    </div>
    {{ html()->form()->close() }}
</div>

<div class="well">
    <a href="{!! action('GroupController@showCreateForm') !!}">
        <button type="button" class="btn btn-secondary group_subscribe_btn">
            <span class="glyphicon glyphicon-plus"></span> Załóż nową grupę
        </button>
    </a>
</div>
@stop
