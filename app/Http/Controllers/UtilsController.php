<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Strimoid\Facades\OEmbed;
use Strimoid\Models\Content;

class UtilsController extends BaseController
{
    public function getURLTitle(Request $request): array
    {
        $url = $request->get('url');
        $data = OEmbed::getData($url);

        $title = data_get($data, 'meta.title', '');
        $title = Str::limit($title, 128);

        $description = data_get($data, 'meta.description', '');
        $description = Str::limit($description, 255);

        // Find duplicates
        $duplicates = Content::where('url', $url)
            ->get(['title', 'group_id'])
            ->toArray();

        return [
            'status' => 'ok',
            'title' => $title,
            'description' => $description,
            'duplicates' => $duplicates,
        ];
    }
}
