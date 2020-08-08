<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\Mime\MimeTypes;

class StaticFileController extends BaseController
{
    private const CACHEABLE_EXTENSIONS = ['css', 'eot', 'js', 'png', 'svg', 'ttf', 'woff', 'woff2'];

    public function getStaticFile(Request $request)
    {
        $path = public_path($request->path());

        if (!is_readable($path)) {
            return abort(404);
        }

        $guesser = new MimeTypes();

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $mimeType = Arr::first($guesser->getMimeTypes($extension));

        $response = response()->file($path, ['Content-Type' => $mimeType]);

        if (in_array($extension, self::CACHEABLE_EXTENSIONS, false)) {
            $response = $response
                ->setPublic()
                ->setMaxAge(60 * 60 * 24 * 365)
                ->setImmutable(true);
        }

        return $response;
    }
}
