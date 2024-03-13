<?php

namespace Strimoid\Http\Controllers;

use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\Server;
use League\Glide\ServerFactory;

class ImageController extends BaseController
{
    private readonly Server $server;

    public function __construct(ServerFactory $serverFactory, private readonly \Illuminate\Contracts\Config\Repository $configRepository, private readonly \Illuminate\Contracts\Routing\ResponseFactory $responseFactory)
    {
        $this->server = $serverFactory->create([
            'source' => storage_path('uploads'),
            'cache' => '/tmp/strimoid/glide/',
            'driver' => $this->configRepository->get('image.driver'),
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
            return $this->responseFactory->make('invalid image size', 400);
        }

        try {
            return $this->server->getImageResponse($sourcePath, [
                'w' => (int) $width,
                'h' => (int) $height,
            ]);
        } catch (FileNotFoundException) {
            return $this->responseFactory->make(null, 404);
        }
    }
}
