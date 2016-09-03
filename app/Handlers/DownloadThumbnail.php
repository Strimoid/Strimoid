<?php namespace Strimoid\Handlers;

use Strimoid\Models\Content;
use Vinkla\Pusher\Facades\Pusher;

class DownloadThumbnail
{
    public function fire($job, $data)
    {
        $content = Content::findOrFail($data['id']);
        $content->autoThumbnail();

        $job->delete();

        Pusher::trigger('content-'.$content->getKey(), 'loaded-thumbnail', [
            'url' => $content->getThumbnailPath(),
        ]);
    }
}
