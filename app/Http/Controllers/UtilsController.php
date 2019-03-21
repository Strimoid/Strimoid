<?php

namespace Strimoid\Http\Controllers;

use OEmbed;
use Strimoid\Models\Content;

class UtilsController extends BaseController
{
    public function getURLTitle()
    {
        $url = request('url');
        $data = OEmbed::getData($url);

        $title = data_get($data, 'meta.title', '');
        $title = str_limit($title, 128);

        $description = data_get($data, 'meta.description', '');
        $description = str_limit($description, 255);

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
