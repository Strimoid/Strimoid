<?php

namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Strimoid\Models\DailyAction;
use Strimoid\Models\User;

class UpdateUserPoints extends Command
{
    protected $name = 'lara:updateuserpoints';
    protected $description = 'Updates user points amount.';
    public function __construct(private \Illuminate\Database\DatabaseManager $databaseManager)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->databaseManager->connection()->disableQueryLog();

        $conn = $this->databaseManager->connection('stats');
        $conn->disableQueryLog();

        $rows = DailyAction::select($this->databaseManager->raw('user_id, Sum(points) as points'))
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
