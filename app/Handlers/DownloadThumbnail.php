<?php namespace Strimoid\Handlers;

use WS;
use Strimoid\Models\Content;

class DownloadThumbnail {

    public function fire($job, $data)
    {
        $content = Content::findOrFail($data['id']);
        $content->autoThumbnail();

        WS::send(json_encode([
            'topic' => 'content.'. $content->getKey() .'.thumbnail',
            'url' => $content->getThumbnailPath(100, 75)
        ]));

        $content->unset('thumbnail_loading');

        $job->delete();
    }

}
