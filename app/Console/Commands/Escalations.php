<?php

namespace ComplainDesk\Console\Commands;

use Illuminate\Console\Command;
use ComplainDesk\Escalation;
use ComplainDesk\Mailers\AppMailer;

class Escalations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'escalate:tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send mails to escalation levels';

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
      $tickets = Ticket::where('drop_ticket', 0)->where('status', 'Open')->get();
    }
}
