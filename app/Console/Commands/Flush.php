<?php

namespace ComplainDesk\Console\Commands;

use Carbon\Carbon;
use ComplainDesk\Ticket;
use ComplainDesk\TicketDuration;
use Illuminate\Console\Command;

class Flush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flush:tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
         * ----TODO's----
         *   1. Retrieve all tickets from the ticktes table
         *   2. Loop over the result set
         *   3. Delete tickets if it has elapsed 3 days
         */

        // Retrieve all tickets from the tickets table
        $tickets = Ticket::all();
        // Get the current date time
        $current = Carbon::now();

        // Loop over the tickets resut set
        foreach ($tickets as $ticket) {
            // Check if the tickets has elaspsed 3 days
            if ($current->diffInDays($ticket->created_at) > 9) { // if true
                // then delete the ticket from the database
                $ticket->delete();
            }
        }

        /**
         * ----TODO's----
         *   1. Retrieve all tickets_duration from the tickets_duration table
         *   2. Loop over the result set
         *   3. Delete tickets_duration if the duration is greater than 100
         */

        // Retrieve all tickets_duration from ticket_duration table
        $tickets_duration = TicketDuration::all();

        //Loop over the result set
        foreach ($tickets_duration as $ticket_duration) {
            // Check if the tickets has elaspsed 3 days
            if ($ticket_duration > 100) { // if true
                // then delete the ticket_duration from the database
                $ticket_duration->delete();
            }
        }

    }
}
