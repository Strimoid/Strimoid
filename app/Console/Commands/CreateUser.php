<?php

namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Strimoid\Models\User;
use Symfony\Component\Console\Input\InputArgument;

class CreateUser extends Command
{
    protected $name = 'lara:createuser';
    protected $description = 'Creates user.';

    public function handle(): void
    {
        if (!$this->argument('username')) {
            $this->error('no username given');
            return;
        }

        $email = $this->argument('username') . '@strimoid.dev';

        $user = new User();
        $user->name = $this->argument('username');
        $user->password = $this->argument('username');
        $user->email = $email;
        $user->is_activated = true;
        $user->last_ip = '127.0.0.1';
        $user->save();

        $this->info('User created');
    }

    protected function getArguments(): array
    {
        return [
            ['username', InputArgument::REQUIRED, 'User name.'],
        ];
    }
}
