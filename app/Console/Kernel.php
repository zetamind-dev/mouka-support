<?php

namespace ComplainDesk\Console;

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
        Commands\EmailParserCommand::class,
        Commands\Escalations::class,
        Commands\CountDown::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('escalate:tickets')
            ->hourly()
            ->between('8:00', '17:00')
            ->runInBackground();

        // Run hourly from 8 AM to 5 PM on weekdays...
        $schedule->command('countdown:start')
            ->weekdays()
            ->hourly()
            ->between('8:00', '17:00')
            ->runInBackground();

        $schedule->command('flush:tickets')
        ->dailyAt('13:00')
        ->runInBackground();

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
