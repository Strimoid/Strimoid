<?php namespace Strimoid\Console\Commands;

use Carbon;
use Config;
use Guzzle;
use Illuminate\Console\Command;
use Strimoid\Models\Content;

class FacebookPost extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lara:fbpost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Posts most popular content to FB fanpage.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $dayBefore = Carbon::now()->subDay();
        $content = Content::where('created_at', '>', $dayBefore)
            ->orderBy('uv', 'desc')
            ->firstOrFail();

        $params = [
            'access_token' => Config::get('social.facebook.page_token'),
            'name'         => $content->title,
            'link'         => route('content_comments', $content->getKey()),
            'description'  => $content->description,
        ];

        if ($content->thumbnail) {
            $params['picture'] = 'https:'.$content->getThumbnailPath(500, 250);
        }

        Guzzle::post('https://graph.facebook.com/strimoid/feed', [
            'body' => $params,
        ]);
    }
}
