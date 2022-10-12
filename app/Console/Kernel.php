<?php

namespace App\Console;

use App\Console\Commands\Push;
use App\Console\Commands\PushAndroid;
use App\Console\Commands\PushIos;
use App\Console\Commands\RemoveLogs;
use App\Console\Commands\RemoveOldTime;
use App\Console\Commands\SyncCrm;
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
        SyncCrm::class,
        RemoveOldTime::class,
        Push::class,
        RemoveLogs::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(SyncCrm::class)->everyMinute();
        $schedule->command(RemoveOldTime::class)->everyMinute();
        $schedule->command(Push::class)->everyMinute();
        $schedule->command(RemoveLogs::class)->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
