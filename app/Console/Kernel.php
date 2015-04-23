<?php namespace Strimoid\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'Strimoid\Console\Commands\AddModerator',
        'Strimoid\Console\Commands\ChangePassword',
        'Strimoid\Console\Commands\FacebookPost',
        'Strimoid\Console\Commands\UpdateStats',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('lara:fbpost')->dailyAt('20:00');
    }
}
