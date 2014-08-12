<div class="panel-default entry" data-id="{{ $entry->_id }}">
    <a name="{{ $entry->_id }}"></a>

    <div class="entry_avatar">
        <img src="{{ $entry->user->getAvatarPath() }}" alt="{{ $entry->user->name }}" class="{{ $entry->user->getSexClass() }}">
    </div>

    <div class="panel-heading entry_header">
        <a href="{{ route('user_profile', $entry->user->name) }}" class="entry_author">{{ $entry->user->getColoredName() }}</a>

        <span class="pull-right">
            <span class="glyphicon glyphicon-tag"></span> <a href="{{ route('group_contents', $entry->group->urlname) }}">g/{{{ $entry->group->urlname }}}</a>
            <span class="glyphicon glyphicon-time"></span> <a href="{{ $entry->getURL() }}" rel="nofollow"><time pubdate datetime="{{ $entry->created_at->format('Y-m-d H:i:s') }}" title="{{ $entry->getLocalTime() }}">{{ $entry->created_at->diffForHumans() }}</time></a>

            <span class="voting" data-id="{{ $entry->_id }}" data-state="{{ $entry->getVoteState() }}" data-type="entry">
                <button type="button" class="btn btn-default btn-xs vote-btn-up @if ($entry->getVoteState() == 'uv') btn-success @endif">
                    <span class="glyphicon glyphicon-arrow-up vote-up"></span> <span class="count">{{ $entry->uv }}</span>
                </button>

                <button type="button" class="btn btn-default btn-xs vote-btn-down @if ($entry->getVoteState() == 'dv') btn-danger @endif">
                    <span class="glyphicon glyphicon-arrow-down vote-down"></span> <span class="count">{{ $entry->dv }}</span>
                </button>
            </span>
        </span>
    </div>

    <div class="entry_text md">
        {{ $entry->text }}
    </div>
</div>
