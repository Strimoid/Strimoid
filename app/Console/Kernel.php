<?php

namespace Strimoid\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Strimoid\Console\Commands\AddModerator;
use Strimoid\Console\Commands\ChangePassword;
use Strimoid\Console\Commands\FacebookPost;
use Strimoid\Console\Commands\SearchIndex;
use Strimoid\Console\Commands\TwitterPost;
use Strimoid\Console\Commands\UpdateStats;

class Kernel extends ConsoleKernel
{
    /** @var array */
    protected $commands = [
        AddModerator::class,
        ChangePassword::class,
        FacebookPost::class,
        TwitterPost::class,
        UpdateStats::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('lara:updatestats')->hourlyAt(15);
        $schedule->command('lara:fbpost')->dailyAt('20:00');
        $schedule->command('lara:twitterpost')->dailyAt('20:05');
    }
}
