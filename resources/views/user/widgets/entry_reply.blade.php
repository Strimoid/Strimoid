<div class="panel-default entry entry_reply" data-id="{!! $reply->hashId() !!}">
    <a name="{!! $reply->hashId() !!}"></a>

    <div class="entry_avatar">
        <img src="{!! $reply->user->getAvatarPath() !!}" alt="{!! $reply->user->name !!}" class="{!! $reply->user->getSexClass() !!}">
    </div>

    <div class="panel-heading entry_header">
        <a href="{!! route('user_profile', $reply->user->name) !!}" class="entry_author">{!! $reply->user->getColoredName() !!}</a>

        <span class="pull-right">
            <i class="fa fa-clock-o"></i>
            <a href="{!! $reply->getURL() !!}">
                @include('global.el.time', ['date' => $reply->created_at])
            </a>

            <span class="voting" data-id="{!! $reply->hashId() !!}" data-state="{!! $reply->getVoteState() !!}" data-type="entry_reply">
                <button type="button" class="btn btn-secondary btn-xs vote-btn-up @if ($reply->getVoteState() == 'uv') btn-success @endif">
                    <i class="fa fa-arrow-up vote-up"></i> <span class="count">{!! $reply->uv !!}</span>
                </button>

                <button type="button" class="btn btn-secondary btn-xs vote-btn-down @if ($reply->getVoteState() == 'dv') btn-danger @endif">
                    <i class="fa fa-arrow-down vote-down"></i> <span class="count">{!! $reply->dv !!}</span>
                </button>
            </span>
        </span>
    </div>

    <div class="entry_text md">
        {!! $reply->text !!}
    </div>
</div>
