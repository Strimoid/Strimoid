<?php

namespace Strimoid\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Strimoid\Models\Content;

class TwitterPost extends Command
{
    /** @var string */
    protected $name = 'lara:twitterpost';

    /** @var string */
    protected $description = 'Posts most popular content to Twitter.';

    public function fire(): void
    {
        $dayBefore = Carbon::now()->subDay();
        $content = Content::where('created_at', '>', $dayBefore)
            ->orderBy('uv', 'desc')
            ->firstOrFail();

        $client = new Client([
            'base_url' => 'https://api.twitter.com/1.1/',
            'defaults' => ['auth' => 'oauth'],
        ]);

        $oauth = new Oauth1([
            'consumer_key' => config('social.twitter.consumer_key'),
            'consumer_secret' => config('social.twitter.consumer_secret'),
            'token' => config('social.twitter.token'),
            'token_secret' => config('social.twitter.token_secret'),
        ]);

        $client->getEmitter()->attach($oauth);

        $params = [
            'status' => Str::limit($content->title, 100) . ' https://strm.pl/c/' . $content->hashId(),
        ];

        $client->post('statuses/update.json', [
            'body' => $params,
        ]);
    }
}
