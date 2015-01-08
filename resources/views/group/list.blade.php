@extends('...global.master')

@section('content')
<div class="group_list">
@foreach ($groups as $group)
    @include('widget', array('group' => $group))
@endforeach
</div>

<?php $group = null; ?>

{!! $groups->appends(array('sort' => Input::get('sort')))->links() !!}
@stop

@section('sidebar')
<div class="well group_search_widget">
    {!! Form::open(array('action' => 'SearchController@search', 'method' => 'GET')) !!}
    <div class="input-group">
        {!! Form::text('q', '', array('class' => 'form-control', 'placeholder' => 'podaj wyszukiwaną frazę...')) !!}
        <input type="hidden" name="t" value="g">

        <div class="input-group-btn">
            <button type="submit" class="btn btn-primary">Szukaj</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>

<div class="well">
    <div class="row">
        <div class="btn-group col-lg-12">
            <a href="{!! action('GroupController@showList') !!}" class="col-lg-6 btn @if (Input::get('sort') == '') btn-primary @else btn-default @endif">Najpopularniejsze</a>
            <a href="{!! action('GroupController@showList', ['sort' => 'newest']) !!}" class="col-lg-6 btn @if (Input::get('sort') == 'newest') btn-primary @else btn-default @endif">Najnowsze</a>
        </div>
    </div>
</div>

<div class="well">
    <a href="{!! action('GroupController@showCreateForm') !!}">
        <button type="button" class="btn btn-default group_subscribe_btn"><span class="glyphicon glyphicon-plus"></span> Załóż nową grupę</button>
    </a>
</div>

@if (isset($recommendedGroups))
<div class="well popular_contents_widget">
    <h4>Sprawdź też</h4>

    @foreach ($recommendedGroups as $recommended)
    @if (!Auth::user()->isSubscriber($recommended))
    <div class="row" style="margin: 15px 0px;" data-name="{!! $recommended->urlname !!}">
        <div style="float: left; width: 50px;">
            <a href="{!! route('group_contents', array('group' => $recommended->urlname)) !!}" rel="nofollow" target="_blank">
                <img src="{!! $recommended->getAvatarPath() !!}" style="height: 40px; width: 40px; border-radius: 3px;">
            </a>
        </div>

        <div style="float: left;">
            <h6 class="media-heading"><a href="{!! route('group_contents', array('group' => $recommended->urlname)) !!}">{{{ Str::limit($recommended->name, 50) }}}</a></h6>

            <small>
                <span class="glyphicon glyphicon-tag" style="color: #D3D3D3;"></span> g/{!! $recommended->urlname !!}</a>
            </small>
        </div>

        <button type="button" class="btn btn-default @if(Auth::check() && Auth::user()->isSubscriber($recommended)) btn-success @endif pull-right group_subscribe_btn">Subskrybuj</button>
    </div>
    @endif
    @endforeach

</div>
@endif

@stop
