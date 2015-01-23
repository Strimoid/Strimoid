<?php namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BlockUser extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lara:blockuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Blocks user.';

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
        $user = User::findOrFail($this->argument('username'));
        $user->blocked_at = new MongoDate();
        $user->save();

        $this->info('User blocked');
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