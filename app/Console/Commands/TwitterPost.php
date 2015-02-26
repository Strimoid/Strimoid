<?php namespace Strimoid\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Console\Command;

class TwitterPost extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lara:twitterpost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Posts most popular content to Twitter.';

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
        $time = new MongoDate(time() - 86400);

        $content = Content::where('created_at', '>', $time)
            ->orderBy('uv', 'desc')
            ->firstOrFail();

        $client = new Client([
            'base_url' => 'https://api.twitter.com/1.1/',
            'defaults' => ['auth' => 'oauth'],
        ]);

        $oauth = new Oauth1([
            'consumer_key'    => Config::get('social.twitter.consumer_key'),
            'consumer_secret' => Config::get('social.twitter.consumer_secret'),
            'token'           => Config::get('social.twitter.token'),
            'token_secret'    => Config::get('social.twitter.token_secret'),
        ]);

        $client->getEmitter()->attach($oauth);

        $params = [
            'status' => Str::limit($content->title, 100).' https://strm.pl/'.$content->_id,
        ];

        $request = $client->post('statuses/update.json', [
            'body' => $params,
        ]);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
