<?php

namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Strimoid\Contracts\Repositories\UserRepository;
use Symfony\Component\Console\Input\InputArgument;

class ChangePassword extends Command
{
    protected $name = 'lara:chpasswd';
    protected $description = 'Change user password.';

    public function __construct(protected UserRepository $users)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $user = $this->users->requireByName($this->argument('username'));
        $user->password = $this->argument('password');
        $user->save();

        $this->info('Password changed');
    }

    protected function getArguments(): array
    {
        return [
            ['username', InputArgument::REQUIRED, 'User name.'],
            ['password', InputArgument::REQUIRED, 'New password.'],
        ];
    }
}
