<div class="panel-default entry" data-id="{!! $entry->hashId() !!}">
    <a name="{!! $entry->hashId() !!}"></a>

    <div class="entry_avatar">
        <img src="{!! $entry->user->getAvatarPath() !!}" alt="{!! $entry->user->name !!}" class="{!! $entry->user->getSexClass() !!}">
    </div>

    <div class="panel-heading entry_header">
        <a href="{!! route('user_profile', $entry->user->name) !!}" class="entry_author">{!! $entry->user->getColoredName() !!}</a>

        <span class="pull-right">
            <i class="fa fa-tag"></i>
            <a href="{!! route('group_contents', $entry->group->urlname) !!}">g/{{{ $entry->group->urlname }}}</a>

            <i class="fa fa-clock-o"></i>
            <a href="{!! $entry->getURL() !!}" rel="nofollow">
                @include('global.el.time', ['date' => $entry->created_at])
            </a>

            <span class="voting" data-id="{!! $entry->hashId() !!}" state="{!! $entry->getVoteState() !!}" data-type="entry">
                <button type="button" class="btn btn-secondary btn-xs vote-btn-up @if ($entry->getVoteState() == 'uv') btn-success @endif">
                    <i class="fa fa-arrow-up vote-up"></i> <span class="count">{!! $entry->uv !!}</span>
                </button>

                <button type="button" class="btn btn-secondary btn-xs vote-btn-down @if ($entry->getVoteState() == 'dv') btn-danger @endif">
                    <i class="fa fa-arrow-down vote-down"></i> <span class="count">{!! $entry->dv !!}</span>
                </button>
            </span>
        </span>
    </div>

    <div class="entry_text md">
        {!! $entry->text !!}
    </div>
</div>
