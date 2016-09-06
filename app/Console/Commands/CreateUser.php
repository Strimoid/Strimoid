<?php namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class CreateUser extends Command
{
    /** @var string */
    protected $name = 'lara:createuser';

    /** @var string */
    protected $description = 'Creates user.';

    public function fire()
    {
        if (!$this->argument('username')) {
            echo 'no username given';
        }

        $email = $this->argument('username').'@strimoid.dev';

        $user = new User();
        $user->name = $this->argument('username');
        $user->password = $this->argument('username');
        $user->email = $email;
        $user->is_activated = true;
        $user->last_ip = '127.0.0.1';
        $user->save();

        $this->info('User created');
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
