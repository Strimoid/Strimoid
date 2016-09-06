<?php namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Strimoid\Contracts\Repositories\UserRepository;
use Symfony\Component\Console\Input\InputArgument;

class ChangePassword extends Command
{
    /** @var string */
    protected $name = 'lara:chpasswd';

    /** @var string */
    protected $description = 'Change user password.';

    /**
     * @var UserRepository
     */
    protected $users;

    public function __construct(UserRepository $users)
    {
        parent::__construct();
        $this->users = $users;
    }

    public function fire()
    {
        $user = $this->users->requireByName($this->argument('username'));
        $user->password = $this->argument('password');
        $user->save();

        $this->info('Password changed');
    }

    protected function getArguments() : array
    {
        return [
            ['username', InputArgument::REQUIRED, 'User name.'],
            ['password', InputArgument::REQUIRED, 'New password.'],
        ];
    }
}
