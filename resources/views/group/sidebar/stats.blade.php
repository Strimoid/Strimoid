<div class="well group_stats_widget">
    <h4>Statystyki</h4>
    <div class="row">
       <div class="col-lg-6">
            <p>{!! Lang::choice('pluralization.contents', intval($group->contents->count())) !!}</p>
            <p>{!! Lang::choice('pluralization.comments', intval($group->contents->sum('comments'))) !!}</p>
            <p>{!! Lang::choice('pluralization.entries', intval($group->entries->count())) !!}</p>
        </div>
        <div class="col-lg-6">
            <p>{!! Lang::choice('pluralization.subscribers', $group->subscribers) !!}</p>
            <p><a href="{!! route('group_banned', $group->urlname) !!}" rel="nofollow">{!! Lang::choice('pluralization.banned', intval(Strimoid\Models\GroupBanned::where('group_id', $group->getKey())->count())) !!}</a></p>
            <p><a href="{!! route('group_moderators', $group->urlname) !!}" rel="nofollow">{!! Lang::choice('pluralization.moderators', intval(Strimoid\Models\GroupModerator::where('group_id', $group->getKey())->count())) !!}</a></p>
        </div>
    </div>

    <br>
    <a href="{!! route('group_ranking', $group->urlname) !!}">Ranking użytkowników</a>
    <br>
    <br>
    <span style="font-size: 13px">Utworzona {!! $group->created_at->diffForHumans() !!} przez <a href="{!! route('user_profile', $group->creator_id) !!}">u/{!! $group->creator_id !!}</a></span>
</div>
