@extends('global.master')

@section('title')
{{{ $content->title }}}
@stop

@section('head')
    @if ($content->thumbnail) <meta property="og:image" content="https:{!! $content->getThumbnailPath() !!}"> @endif
    @if ($content->description) <meta name="description" content="{{{ $content->description }}}"> @endif
@stop

@section('content')
<div class="content" data-id="{!! $content->_id !!}">
    <div class="media">
        <div class="voting" data-id="{!! $content->_id !!}" data-state="{!! $content->getVoteState() !!}" data-type="content">
            <button type="button" class="btn btn-default btn-sm pull-left vote-btn-up @if ($content->getVoteState() == 'uv') btn-success @endif">
                <span class="glyphicon glyphicon-arrow-up vote-up"></span> <span class="count">{!! $content->uv !!}</span>
            </button>

            <button type="button" class="btn btn-default btn-sm pull-left vote-btn-down @if ($content->getVoteState() == 'dv') btn-danger @endif">
                <span class="glyphicon glyphicon-arrow-down vote-down"></span> <span class="count">{!! $content->dv !!}</span>
            </button>
        </div>

        @if ($content->thumbnail)
        <a class="pull-left" href="{{{ $content->url }}}" rel="nofollow" target="_blank">
            <img class="media-object img-thumbnail" src="{!! $content->getThumbnailPath() !!}">
        </a>
        @elseif ($content->thumbnail_loading)
        <a class="pull-left" href="{{{ $content->url }}}" rel="nofollow" target="_blank">
            <div class="media-object img-thumbnail refreshing">
                <span class="glyphicon glyphicon-refresh"></span>
            </div>
        </a>
        @endif

        <div class="media-body content_desc">
            <h1 class="media-heading content_head">
                <a href="{{{ $content->url }}}" rel="nofollow" target="_blank">{{{ $content->title }}}</a>
            </h1>
            {{{ $content->description }}}
            <p class="summary">
                <small>
                    <span class="glyphicon glyphicon-comment"></span> <a href="{!! route('content_comments_slug', array($content->_id, Str::slug($content->title))) !!}" class="content_comments" rel="nofollow">{!! Lang::choice('pluralization.comments', $content->comments_count) !!}</a>
                    <span class="glyphicon glyphicon-tag"></span> <a href="{!! route('group_contents', $content->group_id) !!}" class="content_group">g/{{{ $content->group->urlname }}}</a>
                    <span class="glyphicon glyphicon-user"></span> <a href="{!! route('user_profile', $content->user_id) !!}" class="content_user">u/{{{ $content->user->name }}}</a>
                    <span class="glyphicon glyphicon-globe"></span> <span class="content_domain">{!! $content->getDomain() !!}</span>
                    <span class="glyphicon glyphicon-link"></span> <span class="content_comments">{!! intval($content->related_count) !!}</span>
                    <span class="glyphicon glyphicon-time"></span> <time pubdate datetime="{!! $content->created_at->format('c') !!}" title="{!! $content->getLocalTime() !!}">{!! $content->created_at->diffForHumans() !!}</time>
                    @if (Auth::check())
                        @if ($content->isSaved())
                            <span class="glyphicon glyphicon-star action_link save_content" title="zapisz"></span>
                        @else
                            <span class="glyphicon glyphicon-star-empty action_link save_content" title="zapisz"></span>
                        @endif
                    @endif
                </small>
            </p>
        </div>
        @if ($content->getEmbed())
        <div class="content_preview">
            {!! $content->getEmbed() !!}
        </div>
        @endif
    </div>
</div>

@if ($content->text)
<div class="page-header">
    <h4>Treść</h4>
</div>

<div class="well md">
    {!! $content->text !!}
</div>
@endif

@if ($content->poll)
<div class="page-header">
    <h4>Ankieta</h4>
</div>

@if (isset($content->poll['ends_at']) && Carbon::now()->gte(md_to_carbon($content->poll['ends_at'])))
    @include('content.poll.scores', ['content' => $content, 'poll' => $content->poll])
@else
    @include('content.poll.vote', ['content' => $content, 'poll' => $content->poll])
@endif

@endif

<div class="page-header clearfix">
    <h4 class="pull-left">Powiązane</h4>

    @if (Auth::check())
        <button type="button" class="btn btn-sm btn-default pull-right add_related_btn" data-toggle="button">+dodaj</button>
    @endif
</div>

{!! Form::open(['action' => array('RelatedController@addRelated', $content->_id), 'class' => 'form-horizontal related_add_form', 'style' => 'display: none; margin-top: 20px;']) !!}

@include('global.form.input', ['type' => 'text', 'name' => 'title', 'label' => 'Tytuł linku'])
@include('global.form.input', ['type' => 'text', 'name' => 'url', 'label' => 'Adres URL'])

<div class="form-group">
    <label class="col-lg-3 control-label">Dodatkowe opcje</label>

    <div class="col-lg-6">
        <div class="checkbox">
            <label>
                {!! Form::checkbox('thumbnail', 'on', Input::old('thumbnail', true)) !!} Miniaturka
            </label>
        </div>
        <div class="checkbox">
            <label>
                {!! Form::checkbox('nsfw', 'on', Input::old('nsfw')) !!} Treść +18
            </label>
        </div>
        <div class="checkbox">
            <label>
                {!! Form::checkbox('eng', 'on', Input::old('eng')) !!} Treść w języku angielskim
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
        <button type="submit" class="btn btn-primary pull-right">Dodaj powiązany link</button>
    </div>
</div>
{!! Form::close() !!}

@if (!count($content->related))
Brak powiązanych.
@endif

@foreach ($content->related as $related)
<div class="media related_link">
    <div class="voting" data-id="{!! $related->_id !!}" data-state="{!! $related->getVoteState() !!}" data-type="related">
        <button type="button" class="btn btn-default btn-xs pull-left vote-btn-up @if ($related->getVoteState() == 'uv') btn-success @endif">
            <span class="glyphicon glyphicon-arrow-up vote-up"></span> <span class="count">{!! $related->uv !!}</span>
        </button>

        <button type="button" class="btn btn-default btn-xs pull-left vote-btn-down @if ($related->getVoteState() == 'dv') btn-danger @endif">
            <span class="glyphicon glyphicon-arrow-down vote-down"></span> <span class="count">{!! $related->dv !!}</span>
        </button>
    </div>

    @if ($related->thumbnail && !$related->nsfw)
    <a class="pull-left">
        <img class="media-object" src="{!! $related->getThumbnailPath() !!}" alt="{{{ $related->title }}}">
    </a>
    @endif

    <div class="media-body">
        <h4 class="media-heading">
            <a href="{{{ $related->url }}}">{{{ $related->title }}}</a>

            @if ($related->eng) <span class="eng">[ENG]</span> @endif
            @if ($related->nsfw) <span class="nsfw">[+18]</span> @endif

            @if (Auth::check() && Auth::user()->getKey() == $related->user->getKey())
            <a class="related_remove_link" data-id="{!! $related->_id !!}"><span class="glyphicon glyphicon-trash"></span></a>
            @endif
        </h4>
        <span class="info">
            Dodane <time pubdate datetime="{!! $related->created_at->format('c') !!}" title="{!! $related->getLocalTime() !!}">{!! $related->created_at->diffForHumans() !!}</time>
            przez <a href="{!! route('user_profile', $related->user_id) !!}">u/{{{ $related->user_id }}}</a>
        </span>
    </div>
</div>
@endforeach

<div class="comments">

    <div class="page-header clearfix">
        <h4 class="pull-left">Komentarze</h4>

        <div class="btn-group pull-right">
            <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                <span class="glyphicon glyphicon-sort-by-attributes-alt"></span> Sortowanie
                <span class="caret"></span>
            </button>

            <ul class="dropdown-menu content_sort">
                <li>
                    <a class="action_link @if (!Input::has('sort')) selected @endif" data-sort="">Domyślne</a>
                </li>
                <li>
                    <a class="action_link @if (Input::get('sort') == 'uv') selected @endif" data-sort="uv">Liczba UV</a>
                </li>
                <li>
                    <a class="action_link @if (Input::get('sort') == 'replies') selected @endif" data-sort="replies">Liczba odpowiedzi</a>
                </li>
            </ul>
        </div>
    </div>

    @if (!count($content->comments))
    <p class="no_comments">Nie dodano jeszcze komentarzy do tej treści.</p>
    @endif

@foreach ($content->comments as $comment)
    @include('comments.widget', compact('comment'))

    @if ($comment->replies)
        @foreach ($comment->replies as $reply)
            @include('comments.widget', ['comment' => $reply, 'isReply' => true])
        @endforeach
    @endif
@endforeach

</div>

@if (Auth::check())
<div class="page-header">
    <h4>Dodaj komentarz</h4>
</div>

<div class="panel-default comment comment_add">
    <div class="comment_avatar">
        <img src="{!! Auth::user()->getAvatarPath() !!}">
    </div>

    <div class="comment_text">
        {!! Form::open(['class' => 'comment_add enter_send']) !!}
        <input name="id" type="hidden" value="{!! $content->_id !!}">

        <div class="form-group @if ($errors->has('text')) has-error @endif col-lg-12">
            {!! Form::textarea('text', Input::old('text'), array('class' => 'form-control enter_send', 'placeholder' => 'Treść komentarza...', 'rows' => 3)) !!}

             @if ($errors->has('text'))
                <p class="help-block">{!! $errors->first('text') !!}</p>
             @endif
        </div>

        <div class="form-group col-lg-12">
                <button type="submit" class="btn btn-primary pull-right">Dodaj</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endif

@stop

@section('sidebar')
    @include('group.sidebar.add_content')

    @include('content.sidebar.author_panel', array('content' => $content))

    @if (isset($group))
        @include('group.sidebar.description', array('group' => $group))
        @include('group.sidebar.stats', array('group' => $group))
    @endif

    @include('group.sidebar.popular_contents')
    @include('group.sidebar.popular_comments')
@stop

@section('scripts')
<script type="text/javascript">
    window.content_id = '{!! $content->_id !!}';
</script>
@stop