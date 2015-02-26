<?php namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;

class UpdateThresholds extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lara:updatethresholds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates thresholds.';

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
        DB::connection()->disableQueryLog();

        foreach (Group::all() as $group) {
            $builder = Content::where('group_id', $group->_id)
                //->where('created_at', '>', new MongoDate(time() - 86400 * 14))
                ->orderBy('uv', 'desc')
                ->take(50);

            $count = $builder->count();
            //$averageUv = $builder->avg('uv');

            if ($count < 10) {
                $threshold = 2;
            } else {
                $threshold = $this->median($builder->lists('uv'));
                $threshold = round($threshold);
                $threshold = max(2, $threshold);
            }

            $group->popular_threshold = $threshold;
            $group->save();
        }
    }

    public function median($array)
    {
        // perhaps all non numeric values should filtered out of $array here?
        $iCount = count($array);

        // if we're down here it must mean $array
        // has at least 1 item in the array.
        $middle_index = floor($iCount / 2);
        sort($array, SORT_NUMERIC);

        $median = $array[$middle_index]; // assume an odd # of items

        // Handle the even case by averaging the middle 2 items
        if ($iCount % 2 == 0) {
            $median = ($median + $array[$middle_index - 1]) / 2;
        }

        return $median;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
