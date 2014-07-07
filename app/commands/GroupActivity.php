<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GroupActivity extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lara:groupactivity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update group activity meter.';

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
        $x = 1;

        foreach (Group::all() as $group) {
            $contents = Content::where('group_id', $group->_id)->count();
            $entries = Entry::where('group_id', $group->_id)->count();
            $total = $contents + $entries;

            // Default activity is medium = 2
            $group->activity = 2;

            // Low activity, when nothing was added last week
            if ($total == 0)
                $group->activity = 1;

            if ($total > 15)
                $group->activity = 3;

            if ($total > 50)
                $group->activity = 4;

            $group->save();

            if (!($x % 100))
                $this->info($x .' groups processed');

            $x++;
        }

        $this->info('All groups processed');

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
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