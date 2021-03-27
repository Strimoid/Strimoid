<?php

namespace Strimoid\Handlers;

use Strimoid\Models\Content;

class DownloadThumbnail
{
    public function fire($job, $data): void
    {
        $content = Content::findOrFail($data['id']);
        $content->autoThumbnail();

        $job->delete();

        /*
        Pusher::trigger('content-' . $content->getKey(), 'loaded-thumbnail', [
            'url' => $content->getThumbnailPath(),
        ]);
        */
    }
}
