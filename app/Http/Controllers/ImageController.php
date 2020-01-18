<?php

namespace Strimoid\Http\Controllers;

use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\Server;
use League\Glide\ServerFactory;

class ImageController extends BaseController
{
    private Server $server;

    public function __construct(ServerFactory $serverFactory)
    {
        $this->server = $serverFactory->create([
            'source' => storage_path('uploads'),
            'cache' => '/tmp/strimoid/glide/',
            'driver' => config('image.driver'),
        ]);

        $this->server->setResponseFactory(
            new SymfonyResponseFactory()
        );
    }

    public function showImage($folder, $filename, $format)
    {
        return $this->resizeImage(200, 200, $folder, $filename, $format);
    }

    public function resizeImage($width, $height, $folder, $filename, $format)
    {
        $sourcePath = $folder . DIRECTORY_SEPARATOR . $filename . '.' . $format;

        if ($width > 1_000 || $height > 1_000) {
            return response('invalid image size', 400);
        }

        try {
            return $this->server->getImageResponse($sourcePath, [
                'w' => (int) $width,
                'h' => (int) $height,
            ]);
        } catch (FileNotFoundException $exception) {
            return response(null, 404);
        }
    }
}
