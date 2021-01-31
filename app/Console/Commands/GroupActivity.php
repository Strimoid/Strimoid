<?php

namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Strimoid\Models\Content;
use Strimoid\Models\Entry;
use Strimoid\Models\Group;

class GroupActivity extends Command
{
    protected $name = 'lara:groupactivity';
    protected $description = 'Update group activity meter.';

    public function handle(): void
    {
        $x = 1;

        foreach (Group::all() as $group) {
            $contents = Content::where('group_id', $group->getKey())->count();
            $entries = Entry::where('group_id', $group->getKey())->count();
            $total = $contents + $entries;

            // Default activity is medium = 2
            $group->activity = 2;

            // Low activity, when nothing was added last week
            if ($total === 0) {
                $group->activity = 1;
            }

            if ($total > 15) {
                $group->activity = 3;
            }

            if ($total > 50) {
                $group->activity = 4;
            }

            $group->save();

            if (!$x % 100) {
                $this->info($x . ' groups processed');
            }

            $x++;
        }

        $this->info('All groups processed');
    }
}
