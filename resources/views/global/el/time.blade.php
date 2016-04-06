<?php

$timezone = Setting::get('timezone');
$timezone = new DateTimeZone($timezone);

$local = $date->setTimeZone($timezone)->format('d/m/Y H:i:s');

?>

<time pubdate datetime="{!! $date->format('c') !!}" title="{!! $local !!}">
    {!! $date->diffForHumans() !!}
</time>
