<?php

namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Strimoid\Models\Group;
use Strimoid\Models\User;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class AddModerator extends Command
{
    /** @var string */
    protected $name = 'lara:addmod';

    /** @var string */
    protected $description = 'Adds new moderator.';

    public function fire()
    {
        $user = User::findOrFail($this->argument('username'));
        $group = Group::findOrFail($this->argument('group'));

        $moderator = new GroupModerator();
        $moderator->group()->associate($group);
        $moderator->user()->associate($user);
        $moderator->type = $this->option('admin') ? 'admin' : 'moderator';
        $moderator->save();

        $this->info($user->name . ' is now moderator of ' . $group->urlname);
    }

    protected function getArguments(): array
    {
        return [
            ['group', InputArgument::REQUIRED, 'Group.'],
            ['username', InputArgument::REQUIRED, 'User name.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['admin', null, InputOption::VALUE_NONE, 'Makes user admin instead of moderator.', null],
        ];
    }
}
