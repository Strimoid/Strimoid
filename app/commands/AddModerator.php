<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AddModerator extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lara:addmod';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds new moderator.';

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
        $group = Group::findOrFail($this->argument('group'));

        $moderator = new GroupModerator();
        $moderator->group()->associate($group);
        $moderator->user()->associate($user);

        if ($this->option('admin'))
            $moderator->type = 'admin';
        else
            $moderator->type = 'moderator';

        $moderator->save();

        Cache::forget($user->_id . '.moderated_groups');

        $this->info($user->_id .' is now moderator of '. $group->_id);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('group', InputArgument::REQUIRED, 'Group.'),
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
        return array(
            array('admin', null, InputOption::VALUE_NONE, 'Makes user admin instead of moderator.', null),
        );
    }

}