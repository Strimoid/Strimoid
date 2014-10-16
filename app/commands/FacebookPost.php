<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Guzzle\Http\Client;

class FacebookPost extends Command {

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
        $content = Content::where('created_at', '>', new MongoDate(time() - 86400))->orderBy('uv', 'desc')->firstOrFail();

        $client = new Client();

        $params = [
            'access_token' => Config::get('app.fb_page_token'),
            //'message' => $content->title,
            'name' => $content->title,
            'link' => route('content_comments', $content->_id),
            'description' => $content->description
        ];

        $params['picture'] = $content->thumbnail ? 'https:' . $content->getThumbnailPath(500, 250) : '';

        $request = $client->post('https://graph.facebook.com/strimoid/feed', array(), $params);

        $response = $request->send();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }

}