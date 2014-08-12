<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ChangePassword extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'lara:chpass';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Change password.';

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
        $user = User::where('name', $this->argument('username'))->firstOrFail();
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
		return array(
			array('username', InputArgument::REQUIRED, 'User name.'),
            array('password', InputArgument::REQUIRED, 'New password.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		);
	}

}