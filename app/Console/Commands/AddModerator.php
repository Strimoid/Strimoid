<?php namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class AddModerator extends Command
{
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
        $moderator->type = $this->option('admin') ? 'admin' : 'moderator';
        $moderator->save();

        $this->info($user->name.' is now moderator of '.$group->urlname);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['group', InputArgument::REQUIRED, 'Group.'],
            ['username', InputArgument::REQUIRED, 'User name.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['admin', null, InputOption::VALUE_NONE, 'Makes user admin instead of moderator.', null],
        ];
    }
}
