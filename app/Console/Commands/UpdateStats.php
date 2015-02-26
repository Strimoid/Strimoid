<?php namespace Strimoid\Console\Commands;

use Carbon;
use DB;
use Illuminate\Console\Command;

class UpdateStats extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lara:updatestats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates stats.';

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

        $conn = DB::connection('stats');
        $conn->disableQueryLog();
        $conn->statement('pragma synchronous = off;');

        $firstDay = Carbon::create(2013, 1, 1);

        $x = 1;

        $weekAgo = Carbon::today()->subWeek();
        $actions = UserAction::where('created_at', '>', $weekAgo)->get();

        // Delete old data first
        $fromDay = $weekAgo->diffInDays($firstDay);
        $conn->table('daily_actions')->where('day', '>=', $fromDay)->delete();

        foreach ($actions as $action) {
            $object = $action->getObject();

            if (!$object) {
                continue;
            }

            $day = $object->created_at->diffInDays($firstDay);
            $points = $this->calculatePoints($action, $object);

            $fieldName = $this->getFieldName($action);

            $query = $conn->table('daily_actions')
                ->where('day', $day)
                ->where('user_id', $action->user_id)
                ->where('group_id', $object->group_id);

            // First try to increase existing record
            $result = $query->increment($fieldName);

            if ($result) {
                $query->increment('uv', $object->uv);
                $query->increment('uv', $object->dv);

                if ($points > 0) {
                    $query->increment('points', $points);
                }
                if ($points < 0) {
                    $query->decrement('points', $points);
                }
            } else {
                $data = [
                    'day'      => $day,
                    'user_id'  => $action->user_id,
                    'group_id' => $object->group_id,
                    'uv'       => $object->uv,
                    'dv'       => $object->dv,
                    'contents' => 0,
                    'comments' => 0,
                    'entries'  => 0,
                    'points'   => $points,
                ];

                $data[$fieldName] = 1;

                $conn->table('daily_actions')->insert($data);
            }

            // Show progress
            if (! ($x++ % 100)) {
                $this->info($x.' actions processed');
            }
        }

        $this->info('All actions processed');
    }

    protected function getFieldName($action)
    {
        switch ($action->type) {
            case UserAction::TYPE_CONTENT: return 'contents';

            case UserAction::TYPE_COMMENT:
            case UserAction::TYPE_COMMENT_REPLY: return 'comments';

            case UserAction::TYPE_ENTRY:
            case UserAction::TYPE_ENTRY_REPLY: return 'entries';
        }
    }

    protected function calculatePoints($action, $object)
    {
        $actionPointsBase = 1;
        $actionPointsModifier = 1;
        $actionPoints = $actionPointsBase * $actionPointsModifier;

        $actionScoreBase = $object->uv - $object->dv;
        $actionScoreModifier = 0.5;
        $actionScore = round($actionScoreBase * $actionScoreModifier);

        $points = $actionPoints + $actionScore;

        return $points;
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
