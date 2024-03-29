@extends('global.master')

@section('title')
{{{ $content->title }}}
@stop

@section('head')
    <link rel="canonical" href="{{ route('content_comments_slug', [$content, Str::slug($content->title)]) }}">

    @if ($content->thumbnail) <meta property="og:image" content="https:{!! $content->getThumbnailPath() !!}"> @endif
    @if ($content->description) <meta name="description" content="{{{ $content->description }}}"> @endif
@stop

@section('content')
    <div class="content media" data-id="{{ $content->hashId() }}">
        @include('content.components.vote')

        @if ($content->thumbnail)
            <a class="pull-left" href="{{ $content->getURL() }}" rel="nofollow" target="_blank">
                <img class="media-object img-thumbnail"
                     src="{!! $content->getThumbnailPath(100, 75) !!}"
                     srcset="{!! $content->getThumbnailPath(200, 150) !!} 2x">
            </a>
        @elseif ($content->thumbnail_loading)
            <a class="pull-left" href="{{ $content->url }}" rel="nofollow" target="_blank">
                <div class="media-object img-thumbnail refreshing">
                    <i class="fa fa-refresh"></i>
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
                    <i class="fa fa-comments"></i>
                    <a href="{{ route('content_comments_slug', [$content, Str::slug($content->title)]) }}"
                       class="content_comments">
                        {!! Lang::choice('pluralization.comments', (int) $content->comments_count) !!}</a>

                    <i class="fa fa-tag"></i>
                    <a href="{!! route('group_contents', $content->group) !!}" class="content_group"
                       data-hover="group_widget" data-group="{!! $content->group->urlname !!}">
                        {{ $content->group->urlname }}
                    </a>

                    <i class="fa fa-user"></i>
                    <a href="{!! route('user_profile', $content->user) !!}" class="content_user"
                       data-hover="user_widget" data-user="{!! $content->user->name !!}">
                        {{ $content->user->name }}
                    </a>

                    <i class="fa fa-globe"></i>
                        <span class="content_domain">
                            {!! $content->getDomain() !!}
                        </span>

                    <i class="fa fa-link"></i>
                        <span class="content_comments">
                            {!! (int) $content->related_count !!}
                        </span>

                    <i class="fa fa-clock-o"></i>
                    <time pubdate datetime="{!! $content->created_at->getTimestamp() !!}"
                          title="{!! $content->getLocalTime() !!}">
                        {{ $content->createdAgo() }}
                    </time>

                    @auth
                        @if ($content->isSaved())
                            <i class="fa fa-star action_link save_content" title="zapisz"></i>
                        @else
                            <i class="fa fa-star-o action_link save_content" title="zapisz"></i>
                        @endif
                    @endauth
                </small>
            </p>
        </div>
        @if ($content->getEmbed())
        <div class="content_preview">
            {!! $content->getEmbed(false) !!}
        </div>
        @endif
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

@if (isset($content->poll['ends_at']) && Carbon::now()->gte($content->poll['ends_at']))
    @include('content.poll.scores', ['content' => $content, 'poll' => $content->poll])
@else
    @include('content.poll.vote', ['content' => $content, 'poll' => $content->poll])
@endif

@endif

<div class="page-header clearfix">
    <h4 class="pull-left">Powiązane</h4>

    @auth
        <button type="button" class="btn btn-sm btn-secondary pull-right add_related_btn" data-toggle="button">+dodaj</button>
    @endauth
</div>

    {{ html()->form(action: action('RelatedController@addRelated', $content))->class(['form-horizontal', 'related_add_form', 'mt-5'])->style('display: none')->open() }}

    @include('global.form.input', ['type' => 'text', 'name' => 'title', 'label' => 'Tytuł linku'])
    @include('global.form.input', ['type' => 'text', 'name' => 'url', 'label' => 'Adres URL'])

    <div class="form-group">
        <label class="col-lg-3 control-label">Dodatkowe opcje</label>

        <div class="col-lg-6">
            <div class="checkbox">
                <label>
                    {{ html()->checkbox('thumbnail', true, 'on') }} Miniaturka
                </label>
            </div>
            <div class="checkbox">
                <label>
                    {{ html()->checkbox('nsfw', value: 'on') }} Treść +18
                </label>
            </div>
            <div class="checkbox">
                <label>
                    {{ html()->checkbox('eng', value: 'on') }} Treść w języku angielskim
                </label>
            </div>
        </div>
    </div>

    @include('global.form.submit', ['label' => 'Dodaj powiązany link'])

{{ html()->form()->close() }}

@if (!count($content->related))
    Brak powiązanych.
@endif

@foreach ($content->related as $related)
<div class="media related_link">
    <div class="voting" data-id="{!! $related->hashId() !!}" state="{!! $related->getVoteState() !!}" data-type="related">
        <button type="button" class="btn btn-secondary btn-xs pull-left vote-btn-up @if ($related->getVoteState() == 'uv') btn-success @endif">
            <i class="fa fa-arrow-up vote-up"></i> <span class="count">{!! $related->uv !!}</span>
        </button>

        <button type="button" class="btn btn-secondary btn-xs pull-left vote-btn-down @if ($related->getVoteState() == 'dv') btn-danger @endif">
            <i class="fa fa-arrow-down vote-down"></i> <span class="count">{!! $related->dv !!}</span>
        </button>
    </div>

    @if ($related->thumbnail && !$related->nsfw)
    <a class="pull-left">
        <img class="media-object img-thumbnail" alt="{{{ $related->title }}}"
             src="{!! $related->getThumbnailPath(100, 75) !!}"
             srcset="{!! $related->getThumbnailPath(200, 150) !!} 2x">
    </a>
    @endif

    <div class="media-body">
        <h4 class="media-heading">
            <a href="{{{ $related->url }}}">{{{ $related->title }}}</a>

            @if ($related->eng) <span class="eng">[ENG]</span> @endif
            @if ($related->nsfw) <span class="nsfw">[+18]</span> @endif

            @can('remove', $related)
                <a class="related_remove_link" data-id="{!! $related->hashId() !!}">
                    <i class="fa fa-trash"></i>
                </a>
            @endcan
        </h4>
        <span class="info">
            Dodane <time pubdate datetime="{!! $related->created_at->format('c') !!}" title="{!! $related->getLocalTime() !!}">{!! $related->created_at->diffForHumans() !!}</time>
            przez <a href="{!! route('user_profile', $related->user) !!}">u/{{{ $related->user->name }}}</a>
        </span>
    </div>
</div>
@endforeach

<div class="comments m-b-1">

    <div class="page-header clearfix">
        <h4 class="pull-left">@lang('common.comments')</h4>

        <div class="btn-group pull-right">
            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fa fa-sort"></i> @lang('common.sort')
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

@auth
<div class="page-header">
    <h4>@lang('common.add comment')</h4>
</div>

<div class="panel-default comment comment_add">
    <div class="comment_avatar">
        <img src="{!! user()->getAvatarPath() !!}">
    </div>

    <div class="comment_text">
        {{ html()->form()->class(['comment_add', 'enter_send'])->open() }}
        <input name="id" type="hidden" value="{!! $content->hashId() !!}">

        <div class="form-group @if ($errors->has('text')) has-error @endif col-lg-12">
            {{ html()->textarea('text')->class(['form-control', 'enter_send'])->placeholder('Treść komentarza...')->rows(3) }}

             @if ($errors->has('text'))
                <p class="help-block">{!! $errors->first('text') !!}</p>
             @endif
        </div>

        <div class="form-group col-lg-12">
                <button type="submit" class="btn btn-primary pull-right">
                    @lang('common.add')
                </button>
        </div>
        {{ html()->form()->close() }}
    </div>
</div>
@endauth

@stop

@section('sidebar')
    @include('group.sidebar.add_content')

    @include('content.sidebar.author_panel', ['content' => $content])

    @if (isset($group))
        @include('group.sidebar.description', ['group' => $group])
        @include('group.sidebar.stats', ['group' => $group])
    @endif

    @include('group.sidebar.popular_contents')
    @include('group.sidebar.popular_comments')
@stop

@section('scripts')
<script type="text/javascript">
    window.content_id = '{!! $content->hashId() !!}';
</script>
@stop
