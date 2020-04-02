<span class="voting" data-id="{!! $content->hashId() !!}" state="{!! $content->getVoteState() !!}" data-type="content">
    <button type="button"
            class="btn btn-default btn-sm vote-btn-up @if ($content->getVoteState() == 'uv') btn-success @endif">
        <i class="fa fa-arrow-up vote-up"></i>
        <span class="count">{!! $content->uv !!}</span>
    </button>

    <button type="button"
            class="btn btn-default btn-sm vote-btn-down @if ($content->getVoteState() == 'dv') btn-danger @endif">
        <i class="fa fa-arrow-down vote-down"></i>
        <span class="count">{!! $content->dv !!}</span>
    </button>
</span>
