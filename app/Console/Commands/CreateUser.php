<?php namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateUser extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lara:createuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates user.';

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
        if (!$this->argument('username'))
            print 'no username given';

        $email = $this->argument('username') .'@strimoid.dev';

        $user = new User();
        $user->_id = $this->argument('username');
        $user->name = $this->argument('username');
        $user->shadow_name = Str::lower($this->argument('username'));
        $user->password = $this->argument('username');
        $user->email = $email;
        $user->is_activated = true;
        $user->last_ip = '127.0.0.1';
        $user->save();

        $this->info('User created');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
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
        return array();
    }

}