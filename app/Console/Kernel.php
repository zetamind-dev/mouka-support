<?php

namespace ComplainDesk\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Schema;
use ComplainDesk\Escalation;
use ComplainDesk\Mailers\AppMailer;
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
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (Schema::hasTable('escalations')) {
            // Get all escalations from the database
            $escalations = Escalation::all();
            // Go through each escalations to dynamically set them up.
                foreach ($escalations as $escalation) {
                    if ($escalation === "daily") {
                            // Use the scheduler to add the task at its desired frequency
                            $schedule->call(function() use ($escalation) {
                              $escalations = Commands\Escalations::class;
                              $escalations->handle($escalation);
                            })->command('escalate:tickets')->dailyAt('13:00');
                    }
                }
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
