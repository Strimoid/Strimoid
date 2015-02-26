<?php namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;

class UpdateUserPoints extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lara:updateuserpoints';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates user points amount.';

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

        $rows = DailyAction::select(DB::raw('user_id, Sum(points) as points'))
            ->with(['user' => function ($q) { $q->select(['name', 'avatar']); }])
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
