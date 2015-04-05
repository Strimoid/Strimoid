<div class="well group_stats_widget">
    <h4>Statystyki</h4>
    <div class="row">
       <div class="col-lg-6">
            <p>{!! Lang::choice('pluralization.contents', $group->contents->count()) !!}</p>
            <p>{!! Lang::choice('pluralization.comments', $group->comments->count()) !!}</p>
            <p>{!! Lang::choice('pluralization.entries', $group->entries->count()) !!}</p>
        </div>
        <div class="col-lg-6">
            <p>
                {!! Lang::choice('pluralization.subscribers', $group->subscribers_count) !!}
            </p>
            <p>
                <a href="{!! route('group_banned', $group) !!}" rel="nofollow">
                    {!! Lang::choice('pluralization.banned', $group->bannedUsers->count()) !!}
                </a>
            </p>
            <p>
                <a href="{!! route('group_moderators', $group) !!}" rel="nofollow">
                    {!! Lang::choice('pluralization.moderators', $group->moderators->count()) !!}</a>
            </p>
        </div>
    </div>

    <br>
    <a href="{!! route('group_ranking', $group->urlname) !!}">Ranking użytkowników</a>
    <br>
    <br>
    <span style="font-size: 13px">Utworzona {!! $group->createdAgo() !!} przez <a href="{!! route('user_profile', $group->creator) !!}">{!! $group->creator->name !!}</a></span>
</div>
