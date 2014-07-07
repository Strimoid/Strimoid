<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DeleteUser extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lara:deleteuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes user.';

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

        $user = User::findOrFail($this->argument('username'));

        $user->removed_at = new MongoDate();
        $user->type = 'deleted';
        $user->unset(['age', 'description', 'email', 'last_login', 'last_ip',
            'location', 'password', 'settings', 'sex', 'shadow_email']);
        $user->deleteAvatar();

        $user->save();

        $this->info('User deleted');

        /*

        foreach (Content::where('user_id', $this->argument('username'))->get() as $obj)
            $obj->delete();

        foreach (Entry::where('user_id', $this->argument('username'))->get() as $obj)
            $obj->delete();

        foreach (EntryReply::where('user_id', $this->argument('username'))->get() as $obj)
            $obj->delete();

        foreach (Comment::where('user_id', $this->argument('username'))->get() as $obj)
            $obj->delete();

        foreach (CommentReply::where('user_id', $this->argument('username'))->get() as $obj)
            $obj->delete();

        foreach (ContentRelated::where('user_id', $this->argument('username'))->get() as $obj)
            $obj->delete();

        */


    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('username', InputArgument::REQUIRED, 'User name.'),
        );
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