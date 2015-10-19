<?php

use Strimoid\Models\Comment;

$builder = Comment::with([
    'user' => function($q) { $q->select(['id', 'avatar', 'name']); },
    'content' => function($q) { $q->select(['id', 'title']); }
])->fromDaysAgo(3);

if (isset($group) && $group instanceof Strimoid\Models\Group)
{
    $builder->where('group_id', $group->getKey());
}

$popularComments = $builder->remember(60)->orderBy('uv', 'desc')->take(5)->get();

?>

<div class="well popular_contents_widget">
    <h5>@lang('common.popular comments')</h5>

    <ul class="media-list popular_contents_list">
        @foreach ($popularComments as $comment)
        <?php
        $text = preg_replace('/<a class="show_spoiler">(.*?)<\/a>/s', '', $comment->text);
        $text = preg_replace('/<span class="spoiler">(.*?)<\/span>/s', '', $text);
        $text = strip_tags($text);

        $url = route('content_comments', ['content' => $comment->content]) .'#'. $comment->hashId();

        ?>
        <li class="media">
            <a class="pull-left" href="{!! $url !!}" rel="nofollow">
                <img src="{!! $comment->user->getAvatarPath(40, 40) !!}" alt="{!! $comment->user->name !!}" style="height: 40px; width: 40px; border-radius: 3px;">
            </a>
            <div class="media-body">
                <h6 class="media-heading"><a href="{!! $url !!}">{!! Str::limit($text, 100) !!}</a></h6>
                <small>
                    <i class="fa fa-thumbs-up"></i> {!! $comment->uv !!}
                    <i class="fa fa-thumbs-down"></i> {!! $comment->dv !!}

                    @if ($comment->content)
                        <span class="glyphicon glyphicon-share-alt"></span> {!! Str::limit($comment->content->title, 20) !!}
                    @endif
                </small>
            </div>
        </li>
        @endforeach
    </ul>
</div>
