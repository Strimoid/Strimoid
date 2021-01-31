<?php

namespace Strimoid\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Strimoid\Models\User;
use Symfony\Component\Console\Input\InputArgument;

class BlockUser extends Command
{
    protected $name = 'lara:blockuser';
    protected $description = 'Blocks user.';

    public function handle(): void
    {
        $user = User::findOrFail($this->argument('username'));
        $user->blocked_at = Carbon::now();
        $user->save();

        $this->info('User blocked');
    }

    protected function getArguments(): array
    {
        return [
            ['username', InputArgument::REQUIRED, 'User name.'],
        ];
    }
}
