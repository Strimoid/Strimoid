<?php namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Strimoid\Models\User;
use Symfony\Component\Console\Input\InputArgument;

class BlockUser extends Command
{
    /** @var string */
    protected $name = 'lara:blockuser';

    /** @var string */
    protected $description = 'Blocks user.';

    public function fire()
    {
        $user = User::findOrFail($this->argument('username'));
        $user->blocked_at = \Carbon::now();
        $user->save();

        $this->info('User blocked');
    }

    protected function getArguments() : array
    {
        return [
            ['username', InputArgument::REQUIRED, 'User name.'],
        ];
    }

    protected function getOptions() : array
    {
        return [];
    }
}
