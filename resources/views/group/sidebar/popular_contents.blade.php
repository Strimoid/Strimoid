<?php

use Strimoid\Models\Content;

$builder = Content::fromDaysAgo(3);

if (isset($group) && $group instanceof Strimoid\Models\Group)
{
    $builder->where('group_id', $group->getKey());
}

$popularContents = $builder->remember(60)->orderBy('uv', 'desc')->take(5)->get();

?>

<div class="well popular_contents_widget">
    <h5>@lang('common.popular contents')</h5>

    <ul class="media-list popular_contents_list">
        @foreach ($popularContents as $content)
        <li class="media">
            @if ($content->thumbnail && !$content->nsfw)
            <a class="pull-left" href="{!! route('content_comments_slug', [$content, Str::slug($content->title)]) !!}"
               rel="nofollow" target="_blank">
                <img src="{!! $content->getThumbnailPath(40, 40) !!}" style="height: 40px; width: 40px; border-radius: 3px;" alt="">
            </a>
            @endif
            <div class="media-body">
                <h6 class="media-heading">
                    <a href="{!! route('content_comments_slug', [$content, Str::slug($content->title)]) !!}">
                        {{ Str::limit($content->title, 50) }}
                    </a>
                </h6>
                <small>
                    <i class="fa fa-thumbs-up"></i> {{ $content->uv }}
                    <i class="fa fa-thumbs-down"></i> {{ $content->dv }}
                </small>
            </div>
        </li>
        @endforeach
    </ul>
</div>
