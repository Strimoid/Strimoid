<div class="card">
    <div class="card-header">
        <h3 class="card-title" data-name="{!! $group->urlname !!}">
            <img class="media-object pull-left" src="{!! $group->getAvatarPath() !!}">

            <a href="{!! route('group_contents', $group->urlname) !!}">
                {{{ $group->name }}}
            </a>
            <button type="button" class="btn btn-secondary @if(Auth::check() && Auth::user()->isSubscriber($group)) btn-success @endif pull-right group_subscribe_btn">
                Subskrybuj
            </button>

            <small class="urlname text-muted" style="display: block">
                g/{!! $group->urlname !!}
            </small>
        </h3>
    </div>

    <div class="card-block">
        {{ $group->description }}
    </div>

    <div class="card-footer">
        @if ($group->tags)
            @foreach ($group->tags as $tag)
                <a href="{!! route('wizard_tag', $tag) !!}" class="label label-default">{{{ $tag }}}</a>
            @endforeach
        @endif

            <small class="text-muted">
                Utworzona {!! $group->created_at->diffForHumans() !!} przez
                <a href="{!! route('user_profile', $group->creator) !!}">{!! $group->creator->name !!}</a>
            </small>
    </div>
</div>
