<?php

namespace Strimoid\Http\Controllers;

use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\ServerFactory;

class ImageController extends BaseController
{
    private $server;

    public function __construct(ServerFactory $serverFactory)
    {
        $this->server = $serverFactory->create([
            'source' => storage_path('uploads'),
            'cache'  => '/tmp/strimoid/glide/',
            'driver' => config('image.driver'),
        ]);

        $this->server->setResponseFactory(
            new SymfonyResponseFactory()
        );
    }

    public function resizeImage($width = 200, $height = 200, $folder = '', $filename = '')
    {
        $sourcePath = $folder.DIRECTORY_SEPARATOR.$filename.'.png';

        return $this->server->getImageResponse($sourcePath, [
            'w' => $width,
            'h' => $height
        ]);
    }
}
