<?php

if (isset($group))
    $url = action('ContentController@showAddForm', ['group' => $group->urlname]);
else
    $url = action('ContentController@showAddForm');

?>

<div class="well add_content_widget">
    <div class="btn-group btn-block">
        <a href="{!! $url !!}" class="btn btn-default half-width" rel="nofollow"><span class="glyphicon glyphicon-link"></span> Dodaj link</a>
        <a href="{!! $url !!}#content" class="btn btn-default half-width" rel="nofollow"><span class="glyphicon glyphicon-file"></span> Dodaj treść</a>
    </div>
</div>