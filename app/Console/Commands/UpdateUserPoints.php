<?php

namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Strimoid\Models\DailyAction;
use Strimoid\Models\User;

class UpdateUserPoints extends Command
{
    /** @var string */
    protected $name = 'lara:updateuserpoints';

    /** @var string */
    protected $description = 'Updates user points amount.';

    public function handle(): void
    {
        \DB::connection()->disableQueryLog();

        $conn = \DB::connection('stats');
        $conn->disableQueryLog();

        $rows = DailyAction::select(DB::raw('user_id, Sum(points) as points'))
            ->with(['user' => function ($q): void {
                $q->select(['name', 'avatar']);
            }])
            ->groupBy('user_id')
            ->orderBy('points', 'desc')
            ->get();

        foreach ($rows as $row) {
            $user = User::find($row['user_id']);

            $user->total_points = $row['points'];
            $user->save();
        }

        $this->info('All users processed');
    }
}
