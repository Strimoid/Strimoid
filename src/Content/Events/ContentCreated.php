<?php

namespace Strimoid\Content\Events;

use Strimoid\Models\Content;

class ContentCreated
{
    private Content $content;

    public function __construct(Content $content)
    {
        $this->content = $content;
    }
}
