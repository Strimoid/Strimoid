<?php

namespace Strimoid\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Strimoid\Facades\Guzzle;
use Strimoid\Models\Content;

class FacebookPost extends Command
{
    protected $name = 'lara:fbpost';
    protected $description = 'Posts most popular content to FB fanpage.';
    public function __construct(private \Illuminate\Contracts\Config\Repository $configRepository, private \Illuminate\Routing\UrlGenerator $urlGenerator)
    {
        parent::__construct();
        parent::__construct();
    }

    public function handle(): void
    {
        $dayBefore = Carbon::now()->subDay();
        $content = Content::where('created_at', '>', $dayBefore)
            ->orderBy('uv', 'desc')
            ->firstOrFail();

        $params = [
            'access_token' => $this->configRepository->get('social.facebook.page_token'),
            'name' => $content->title,
            'link' => $this->urlGenerator->route('content_comments', $content->getKey()),
            'description' => $content->description,
        ];

        if ($content->thumbnail) {
            $params['picture'] = $content->getThumbnailPath(500, 250);
        }

        Guzzle::post('https://graph.facebook.com/strimoid/feed', [
            'body' => $params,
        ]);
    }
}
