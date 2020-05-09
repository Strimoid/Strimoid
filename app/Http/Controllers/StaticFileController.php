<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\Mime\MimeTypes;

class StaticFileController extends BaseController
{
    public function getStaticFile(Request $request)
    {
        $path = public_path($request->path());

        if (!is_readable($path)) {
            return abort(404);
        }

        $guesser = new MimeTypes();

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $mimeType = Arr::first($guesser->getMimeTypes($extension));

        return response()->file($path, ['Content-Type' => $mimeType]);
    }
}
