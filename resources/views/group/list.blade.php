@extends('global.master')

@section('content')
<div class="group_list">
    @each('group.widget', $groups, 'group')
</div>

<?php $group = null; ?>

{!! $groups->links() !!}
@stop

@section('sidebar')
<div class="well group_search_widget">
    {{ html()->form('GET', action('SearchController@search'))->open() }}
    <div class="input-group">
        {{ html()->text('q')->class('form-control')->placeholder('podaj wyszukiwaną frazę...') }}
        <input type="hidden" name="t" value="g">

        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>
    {{ html()->form()->close() }}
</div>

<div class="well">
    <div class="row">
        <div class="btn-group col-lg-12">
            <a href="{!! action('GroupController@showList') !!}"
               class="col-lg-6 btn {{ Input::get('sort') == '' ? 'btn-secondary' : 'btn-light' }}">
                @ucFirstLang('groups.popular')
            </a>
            <a href="{!! action('GroupController@showList', ['sort' => 'newest']) !!}"
               class="col-lg-6 btn {{ Input::get('sort') === 'newest' ? 'btn-secondary' : 'btn-light' }}">
                @ucFirstLang('groups.new')
            </a>
        </div>
    </div>
</div>

<div class="well">
    <a href="{!! action('GroupController@showCreateForm') !!}">
        <button type="button" class="btn btn-primary w-100 group_subscribe_btn">
            <i class="fa fa-plus mr-1"></i>
            @ucFirstLang('groups.create')
        </button>
    </a>
</div>

@if (isset($recommendedGroups))
<div class="well popular_contents_widget">
    <h4>{{ __('common.check also') }}</h4>

    @foreach ($recommendedGroups as $recommended)
    @if (!Auth::user()->isSubscriber($recommended))
    <div class="row" style="margin: 15px 0;" data-name="{!! $recommended->urlname !!}">
        <div style="float: left; width: 50px;">
            <a href="{!! route('group_contents', ['group' => $recommended->urlname]) !!}" rel="nofollow" target="_blank">
                <img src="{!! $recommended->getAvatarPath() !!}" style="height: 40px; width: 40px; border-radius: 3px;">
            </a>
        </div>

        <div style="float: left;">
            <h6 class="media-heading">
                <a href="{!! route('group_contents', ['group' => $recommended->urlname]) !!}">
                    {{{ Str::limit($recommended->name, 50) }}}
                </a>
            </h6>

            <small>
                <i class="fa fa-tag" style="color: #D3D3D3;"></i>
                g/{!! $recommended->urlname !!}</a>
            </small>
        </div>

        <button type="button" class="btn btn-secondary @if(Auth::check() && Auth::user()->isSubscriber($recommended)) btn-success @endif pull-right group_subscribe_btn">Subskrybuj</button>
    </div>
    @endif
    @endforeach

</div>
@endif

@stop
