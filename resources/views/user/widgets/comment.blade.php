@if ($comment->content)
    @include('user.widgets.comment_parent', ['content' => $comment->content])
@endif

<div class="panel-default comment" data-id="{!! $comment->hashId() !!}">
    <a name="{!! $comment->hashId() !!}"></a>

    <div class="comment_avatar">
        <img src="{!! $comment->user->getAvatarPath() !!}" alt="{!! $comment->user->name !!}" class="{!! $comment->user->getSexClass() !!}">
    </div>

    <div class="panel-heading comment_header">
        <a href="{!! route('user_profile', $comment->user) !!}" class="comment_author">{!! $comment->user->getColoredName() !!}</a>

        <span class="pull-right">
            <span class="glyphicon glyphicon-time"></span> <a href="{!! $comment->getURL() !!}" rel="nofollow"><time pubdate datetime="{!! $comment->created_at->format('c') !!}" title="{!! $comment->getLocalTime() !!}">{!! $comment->created_at->diffForHumans() !!}</time></a>

            <span class="voting" data-id="{!! $comment->_id !!}" data-state="{!! $comment->getVoteState() !!}" data-type="comment">
                <button type="button" class="btn btn-secondary btn-xs vote-btn-up @if ($comment->getVoteState() == 'uv') btn-success @endif">
                    <span class="glyphicon glyphicon-arrow-up vote-up"></span> <span class="count">{!! $comment->uv !!}</span>
                </button>

                <button type="button" class="btn btn-secondary btn-xs vote-btn-down @if ($comment->getVoteState() == 'dv') btn-danger @endif">
                    <span class="glyphicon glyphicon-arrow-down vote-down"></span> <span class="count">{!! $comment->dv !!}</span>
                </button>
            </span>
        </span>
    </div>

    <div class="comment_text md">
        {!! $comment->text !!}
    </div>
</div>
