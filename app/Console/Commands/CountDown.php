<?php

namespace ComplainDesk\Console\Commands;

use Carbon\Carbon;
use ComplainDesk\TicketDuration;
use Illuminate\Console\Command;

class CountDown extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countdown:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compute and update ticket duration';

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
     * @return mixed
     */
    public function handle()
    {
        /**
         * Compute and Update ticket duartion
         */

        // Official working hour 8am - 5pm (8:00 - 17:00)
        // Retrieve all tickets from the ticket_durations table
        $tickets = new TicketDuration;

        foreach ($tickets as $ticket) {
            // Check if the ticket was created during the official hour
            if ($ticket->created_at->format('H') > 7 || $ticket->created_at->format('H') < 18) { // If true
                // Then check if the tickets has elaspsed a full day(new day)
                // Get current time stamps
                $current = Carbon::now();
                if ($current->diffInDays($ticket->created_at) > 0) { // if true
                    // then a new day has come!
                    /**
                     * ----TODO's----
                     *   1. Increment ticket duration field value by 1
                     *   2. Update the duartion
                     */

                    $ticket->duration = $ticket->duartion + 1;

                    // Update
                    $ticket->save();

                } else { // A new day has not come yet
                    
                    /**
                     * ----TODO's----
                     *   1. Increment ticket duration field value by 1
                     *   2. Update the duartion
                     */

                    $ticket->duration = $current->diffInHours($ticket->created_at);

                    // Update
                    $ticket->save();

                }
            }
        }
    }
}
