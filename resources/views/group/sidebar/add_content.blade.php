<?php

if (isset($group) && $group instanceof Strimoid\Models\Group)
    $url = action('ContentController@showAddForm', ['group' => $group->urlname]);
else
    $url = action('ContentController@showAddForm');

?>

<div class="well add_content_widget">
    <div class="btn-group btn-block">
        <a href="{!! $url !!}" class="btn btn-secondary half-width" rel="nofollow">
            <i class="fa fa-link"></i>
            Dodaj link
        </a>
        <a href="{!! $url !!}#content" class="btn btn-secondary half-width" rel="nofollow">
            <i class="fa fa-file-text-o"></i>
            Dodaj treść
        </a>
    </div>
</div>
