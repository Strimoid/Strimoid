<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title media" data-name="{!! $group->urlname !!}">
            <img class="media-object pull-left" src="{!! $group->getAvatarPath() !!}">

            <a href="{!! route('group_contents', ['group' => $group->urlname]) !!}">{{{ $group->name }}}</a>
            <button type="button" class="btn btn-default @if(Auth::check() && Auth::user()->isSubscriber($group)) btn-success @endif pull-right group_subscribe_btn">Subskrybuj</button>

            <span class="urlname" style="font-size: 70%; display: block">g/{!! $group->urlname !!}</span>

            <span class="info" style="font-size: 70%; display: block">Utworzona {!! $group->created_at->diffForHumans() !!} przez <a href="{!! route('user_profile', $group->creator->name) !!}">{!! $group->creator->name !!}</a></span>
        </h3>
    </div>

    <div class="panel-body">
        {{{ $group->description }}}
    </div>

    <div class="panel-footer">
        @if ($group->tags)
        @foreach ($group->tags as $tag)
        <a href="{!! route('wizard_tag', $tag) !!}" class="label label-default">{{{ $tag }}}</a>
        @endforeach
        @endif
    </div>
</div>
