<div class="panel-default comment comment_reply" data-id="{!! $reply->hashId() !!}" data-parent-id="{!! $reply->parent->hashId() !!}">
    <a name="{!! $reply->hashId() !!}"></a>

    <div class="comment_avatar">
        <img src="{!! $reply->user->getAvatarPath() !!}" alt="{!! $reply->user->name !!}" class="{!! $reply->user->getSexClass() !!}">
    </div>

    <div class="panel-heading comment_header">
        <a href="{!! route('user_profile', $reply->user->name) !!}" class="comment_author">{!! $reply->user->getColoredName() !!}</a>

        <span class="pull-right">
            <span class="glyphicon glyphicon-time"></span> <a href="{!! $reply->getURL() !!}" rel="nofollow"><time pubdate datetime="{!! $reply->created_at->format('c') !!}" title="{!! $reply->getLocalTime() !!}">{!! $reply->created_at->diffForHumans() !!}</time></a>

            <span class="voting" data-id="{!! $reply->hashId() !!}" data-state="{!! $reply->getVoteState() !!}" data-type="comment_reply">
                <button type="button" class="btn btn-default btn-xs vote-btn-up @if ($reply->getVoteState() == 'uv') btn-success @endif">
                    <span class="glyphicon glyphicon-arrow-up vote-up"></span> <span class="count">{!! $reply->uv !!}</span>
                </button>

                <button type="button" class="btn btn-default btn-xs vote-btn-down @if ($reply->getVoteState() == 'dv') btn-danger @endif">
                    <span class="glyphicon glyphicon-arrow-down vote-down"></span> <span class="count">{!! $reply->dv !!}</span>
                </button>
            </span>
        </span>
    </div>

    <div class="comment_text md">
        {!! $reply->text !!}
    </div>
</div>
