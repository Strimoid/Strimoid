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
        /*
            CAATlXpExF3gBAE7UPHKsQrakZBTI5P1YeBZCIlD2ZCHi3mOJkGk7GRKNP3sZCswWBanhbXegYIc4tg4SoOEvmU0wjWk9JZAilJZAuPo4QDoeGXf2OGw9e8vEmOxWTq2jGuT1p569w2cLZCo2gFAYS02tZC6VZB3ufNkOhNCeuZCae5ZCI1jLQWcZAtG2
         */

        $content = Content::where('created_at', '>', new MongoDate(time() - 86400))->orderBy('uv', 'desc')->firstOrFail();

        $client = new Client();

        $params = array(
            'access_token' => 'CAATlXpExF3gBAE7UPHKsQrakZBTI5P1YeBZCIlD2ZCHi3mOJkGk7GRKNP3sZCswWBanhbXegYIc4tg4SoOEvmU0wjWk9JZAilJZAuPo4QDoeGXf2OGw9e8vEmOxWTq2jGuT1p569w2cLZCo2gFAYS02tZC6VZB3ufNkOhNCeuZCae5ZCI1jLQWcZAtG2',
            //'message' => $content->title,
            'name' => $content->title,
            'link' => route('content_comments', $content->_id),
            'description' => $content->description
        );

        $params['picture'] = $content->thumbnail ? $content->getThumbnailPath(500, 250) : '';

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