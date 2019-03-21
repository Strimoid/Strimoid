<?php

namespace Strimoid\Http\Controllers\Content;

use Strimoid\Http\Controllers\BaseController;

class EmbedController extends BaseController
{
    public function getEmbedCode($content): array
    {
        $embedCode = $content->getEmbed();

        return ['status' => 'ok', 'code' => $embedCode];
    }
}
