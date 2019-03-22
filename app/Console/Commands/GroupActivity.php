<?php

namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;

class GroupActivity extends Command
{
    /** @var string */
    protected $name = 'lara:groupactivity';

    /** @var string */
    protected $description = 'Update group activity meter.';

    public function fire(): void
    {
        $x = 1;

        foreach (Group::all() as $group) {
            $contents = Content::where('group_id', $group->getKey())->count();
            $entries = Entry::where('group_id', $group->getKey())->count();
            $total = $contents + $entries;

            // Default activity is medium = 2
            $group->activity = 2;

            // Low activity, when nothing was added last week
            if ($total == 0) {
                $group->activity = 1;
            }

            if ($total > 15) {
                $group->activity = 3;
            }

            if ($total > 50) {
                $group->activity = 4;
            }

            $group->save();

            if (!($x % 100)) {
                $this->info($x . ' groups processed');
            }

            $x++;
        }

        $this->info('All groups processed');
    }
}
