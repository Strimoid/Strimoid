 <div class="media content" data-id="{!! $content->hashId() !!}">
        <vote-buttons class="voting"
                      uv="{!! $content->uv !!}"
                      dv="{!! $content->dv !!}"
                      data-id="{!! $content->hashId() !!}"
                      data-state="{!! $content->getVoteState()  !!}"
                      data-type="content"></vote-buttons>

        @if ($content->thumbnail && !$content->nsfw)
        <a class="pull-left" href="{{ $content->getURL() }}" rel="nofollow" target="_blank">
            <img class="media-object img-thumbnail"
                 src="{!! $content->getThumbnailPath(100, 75) !!}"
                 srcset="{!! $content->getThumbnailPath(200, 150) !!} 2x">
        </a>
        @endif

        <div class="media-body content_desc">
            <h2 class="media-heading content_head">
                <a href="{{ $content->getURL() }}" rel="nofollow" target="_blank">{{ $content->title }}</a>
                @if ($content->eng) <span class="eng">[ENG]</span> @endif
                @if ($content->nsfw) <span class="nsfw">[+18]</span> @endif
            </h2>

            <p class="description">{{ $content->description }}</p>

            <p class="summary">
                <small>
                    @if ($content->getEmbed())
                        <a class="content_preview_link">
                            <i class="fa fa-play"></i>
                            podgląd
                        </a>
                    @endif

                        <i class="fa fa-comments"></i>
                        <a href="{{ route('content_comments_slug', [$content, Str::slug($content->title)]) }}"
                           class="content_comments">
                            {!! Lang::choice('pluralization.comments', intval($content->comments_count)) !!}</a>

                        <i class="fa fa-tag"></i>
                        <a href="{!! route('group_contents', $content->group) !!}" class="content_group"
                           data-hover="group_widget" data-group="{!! $content->group->urlname !!}">
                            {{ $content->group->urlname }}
                        </a>

                        <i class="fa fa-user"></i>
                        <a href="{!! route('user_profile', $content->user) !!}" class="content_user"
                           data-hover="user_widget" data-user="{!! $content->user->name !!}">
                            {{ $content->user->name }}
                        </a>

                        <i class="fa fa-globe"></i>
                        <span class="content_domain">
                            {!! $content->getDomain() !!}
                        </span>

                        <i class="fa fa-link"></i>
                        <span class="content_comments">
                            {!! intval($content->related_count) !!}
                        </span>

                        <i class="fa fa-clock-o"></i>
                        <time pubdate datetime="{!! $content->created_at->getTimestamp() !!}"
                              title="{!! $content->getLocalTime() !!}">
                            {{ $content->createdAgo() }}
                        </time>

                    @if (Auth::check())
                        <span class="glyphicon action_link save_content
                            @if ($content->isSaved()) glyphicon-star
                            @else glyphicon-star-empty @endif"
                            title="zapisz"></span>
                    @endif
                </small>
            </p>
        </div>

        @if (Auth::check() && Auth::user()->isModerator($content->group_id))
        <div class="content_actions pull-right">
            <a class="content_remove_link action_link"><span class="glyphicon glyphicon-trash"></span> usuń</a>
        </div>
        @endif
</div>

<div class="clearfix"></div>
