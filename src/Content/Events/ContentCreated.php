<?php

namespace Strimoid\Content\Events;

use Strimoid\Models\Content;

class ContentCreated
{
    public function __construct(private readonly Content $content)
    {
    }
}
