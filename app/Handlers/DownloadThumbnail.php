<?php namespace Strimoid\Handlers;

use Strimoid\Models\Content;

class DownloadThumbnail
{
    public function fire($job, $data)
    {
        $content = Content::findOrFail($data['id']);
        $content->autoThumbnail();

        $content->unset('thumbnail_loading');

        $job->delete();
    }
}
