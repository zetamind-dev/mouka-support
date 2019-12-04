<?php

namespace ComplainDesk\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use ComplainDesk\Ticket;

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
       // Retrieve all tickets from the tickets table
       $tickets =  Ticket::all();
       // Get the current date time 
       $current = Carbon::now();

       // Loop over the tickets resut set 
       foreach ($tickets as $ticket) {
           // Check if the tickets has elaspsed 3 days
           if($current->diffInDays($ticket->created_at) > 3){// if true 
             // then delete th ticket from the database
             $ticket->delete();
           }
       }
    }
}
