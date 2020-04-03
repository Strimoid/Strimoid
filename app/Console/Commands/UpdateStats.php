<?php

namespace Strimoid\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Strimoid\Models\Content;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\UserAction;

class UpdateStats extends Command
{
    protected $name = 'lara:updatestats';

    protected $description = 'Updates stats.';

    public function handle(): void
    {
        DB::connection()->disableQueryLog();

        $conn = DB::connection();
        $conn->disableQueryLog();

        $firstDay = Carbon::create(2013, 1, 1);

        $x = 1;

        $weekAgo = Carbon::today()->subWeek();
        $actions = UserAction::where('created_at', '>', $weekAgo)->get();

        // Delete old data first
        $fromDay = $weekAgo->diffInDays($firstDay);
        $conn->table('daily_actions')->where('day', '>=', $fromDay)->delete();

        foreach ($actions as $action) {
            $object = $action->element;

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
                    'day' => $day,
                    'user_id' => $action->user_id,
                    'group_id' => $object->group_id,
                    'uv' => $object->uv,
                    'dv' => $object->dv,
                    'contents' => 0,
                    'comments' => 0,
                    'entries' => 0,
                    'points' => $points,
                ];

                $data[$fieldName] = 1;

                $conn->table('daily_actions')->insert($data);
            }

            // Show progress
            if (!$x++ % 100) {
                $this->info($x . ' actions processed');
            }
        }

        $this->info('All actions processed');
    }

    protected function getFieldName($action)
    {
        $className = get_class($action->element);

        switch ($className) {
            case Content::class:
                return 'contents';

            case Comment::class:
            case CommentReply::class:
                return 'comments';

            case Entry::class:
            case EntryReply::class:
                return 'entries';
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

        return $actionPoints + $actionScore;
    }
}
