<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DeleteAvatar extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lara:delavatar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes user avatar.';

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
        if (!$this->argument('username'))
            print 'no username given';

        $user = User::findOrFail($this->argument('username'));
        $user->deleteAvatar();
        $user->save();
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