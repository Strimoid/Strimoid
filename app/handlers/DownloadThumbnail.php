<?php

class DownloadThumbnail {

    public function fire($job, $data)
    {
        $content = Content::findOrFail($data['id']);
        $content->autoThumbnail();

        WS::send(json_encode([
            'topic' => 'content.'. $content->_id .'.thumbnail',
            'url' => $content->getThumbnailPath(100, 75)
        ]));

        $content->unset('thumbnail_loading');

        $job->delete();
    }

}
