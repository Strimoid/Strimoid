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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $time = Carbon::now()->subDay();
        $content = Content::where('created_at', '>', $time)
            ->orderBy('uv', 'desc')
            ->firstOrFail();

        $params = [
            'access_token' => Config::get('social.facebook.page_token'),
            'name'         => $content->title,
            'link'         => route('content_comments', $content->getKey()),
            'description'  => $content->description,
        ];

        $params['picture'] = $content->thumbnail
            ? 'https:'.$content->getThumbnailPath(500, 250)
            : '';

        Guzzle::post('https://graph.facebook.com/strimoid/feed', [
            'body' => $params,
        ]);
    }
}
