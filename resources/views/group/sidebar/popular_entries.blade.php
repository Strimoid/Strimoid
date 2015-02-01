<?php

use Strimoid\Models\Entry;

$builder = Entry::with([
    'user' => function($q) { $q->select(['avatar', 'name']); }
])->fromDaysAgo(3);

if (isset($group) && $group instanceof Strimoid\Models\Group)
{
    $builder->where('group_id', $group->getKey());
}

$popularEntries = $builder->orderBy('uv', 'desc')->take(5)->get();

?>

<div class="well popular_contents_widget">
    <h4>Ostatnio popularne</h4>

    <ul class="media-list popular_contents_list">
        @foreach ($popularEntries as $entry)
        <?php
        $text = preg_replace('/<a class="show_spoiler">(.*?)<\/a>/s', '', $entry->text);
        $text = preg_replace('/<span class="spoiler">(.*?)<\/span>/s', '', $text);
        $text = strip_tags($text);
        ?>
        <li class="media">
            <a class="pull-left" href="{!! route('single_entry', $entry->_id) !!}">
                <img src="{!! $entry->user->getAvatarPath(40, 40) !!}" alt="{!! $entry->user->name !!}" style="height: 40px; width: 40px; border-radius: 3px;">
            </a>
            <div class="media-body">
                <h6 class="media-heading"><a href="{!! route('single_entry', $entry->_id) !!}">{!! Str::limit($text, 100) !!}</a></h6>
                <small>
                    <span class="glyphicon glyphicon-thumbs-up"></span> {!! $entry->uv !!}</a>
                    <span class="glyphicon glyphicon-thumbs-down"></span> {!! $entry->dv !!}</a>
                </small>
            </div>
        </li>
        @endforeach
    </ul>
</div>
