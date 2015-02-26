<?php namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Strimoid\Contracts\Repositories\UserRepository;
use Symfony\Component\Console\Input\InputArgument;

class ChangePassword extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lara:chpasswd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change user password.';

    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * Create a new command instance.
     *
     * @param UserRepository $users
     */
    public function __construct(UserRepository $users)
    {
        parent::__construct();
        $this->users = $users;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $user = $this->users->requireByName($this->argument('username'));
        $user->password = $this->argument('password');
        $user->save();

        $this->info('Password changed');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['username', InputArgument::REQUIRED, 'User name.'],
            ['password', InputArgument::REQUIRED, 'New password.'],
        ];
    }
}
