<?php

namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Strimoid\Models\Content;
use Strimoid\Models\Group;

class UpdateThresholds extends Command
{
    protected $name = 'lara:updatethresholds';
    protected $description = 'Updates thresholds.';
    public function __construct(private \Illuminate\Database\DatabaseManager $databaseManager)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->databaseManager->connection()->disableQueryLog();

        foreach (Group::all() as $group) {
            $builder = Content::where('group_id', $group->getKey())
                //->where('created_at', '>', new MongoDate(time() - 86400 * 14))
                ->orderBy('uv', 'desc')
                ->take(50);

            $count = $builder->count();
            //$averageUv = $builder->avg('uv');

            if ($count < 10) {
                $threshold = 2;
            } else {
                $threshold = $this->median($builder->pluck('uv'));
                $threshold = round($threshold);
                $threshold = max(2, $threshold);
            }

            $group->popular_threshold = $threshold;
            $group->save();
        }
    }

    public function median($array): float
    {
        // perhaps all non numeric values should filtered out of $array here?
        $iCount = is_countable($array) ? count($array) : 0;

        // if we're down here it must mean $array
        // has at least 1 item in the array.
        $middle_index = floor($iCount / 2);
        sort($array, SORT_NUMERIC);

        $median = $array[$middle_index]; // assume an odd # of items

        // Handle the even case by averaging the middle 2 items
        if ($iCount % 2 === 0) {
            $median = ($median + $array[$middle_index - 1]) / 2;
        }

        return $median;
    }
}
