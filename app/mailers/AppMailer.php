<?php
namespace ComplainDesk\Mailers;

use ComplainDesk\Ticket;
use ComplainDesk\User;
use ComplainDesk\Category;
use Illuminate\Contracts\Mail\Mailer;
use Carbon\Carbon;

class AppMailer
{
    protected $mailer;
    protected $fromAddress = 'workflow@mouka.com';
    protected $fromName = 'Mouka Help Desk';
    protected $to;
    protected $cc ;
    protected $subject;
    protected $view;
    protected $data = [];


    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendTicketInformation($user, Ticket $ticket)
    {
        $this->to = $user->email;
        //dd($user->email);
        $this->subject = "[Ticket ID: $ticket->ticket_id] $ticket->title";
        $this->view = 'emails.ticket_info';
        $this->data = compact('user', 'ticket');
        //dd($this->cc);
        return $this->deliver();

    }

    public  function SendToCategory(Category $categories, Ticket $ticket, $user){  
        $category = $categories::find($ticket->category_id);
        $this->category_email = $category->email;
        $this->to = $this->category_email;
        $this->subject = "[Ticket ID: $ticket->ticket_id] $ticket->title";
        $this->view = 'emails.ticket_info3';
        $this->data = compact('ticket', 'user', 'category');



       return $this->mailer->send($this->view, $this->data, function ($message) {
            $message->from($this->fromAddress, $this->fromName)
                ->to($this->to)->subject($this->subject);


        });
    }

    public  function SendToEscalationLevel() {  

    // get the current time  - 2015-12-19 10:10:54
        $current = Carbon::now();

        // Declare hours as days
        $two_days = 48;
        $four_days = 96;
        $six_days = 144;

        // Retrieve tickets that has not been dropped by ticket owner and tickets that has not been resolved by ticket Moderators
        // from the Database 
        $tickets = Ticket::where('drop_ticket', 0)->where('status', 'Open')->get();

        // Retrieve Categories
        $categories = Category::all();

        // Ticket logs
        $tickets_log = [];
         
        // Iterate over each ticket and check the difference in hours 
        foreach($tickets as $ticket){
        // Check if ticket duration is up to 48 hours
        $duration = $ticket->created_at->diffInHours($current);
          if($duration === $two_days){// If true then
             // Set escalation level mail
             $escalation_mail = 'a.emmanuel2@yahoo.com';
             $tickets_log[] = $ticket;
          }
          elseif ($duration > $two_days || $duration < $six_days){
             // Set escalation level mail
             $escalation_mail = 'info@zetamindgroup.com';
             $tickets_log[] = $ticket;
          }elseif ($duration === $six_days  || $duration > $six_days) {
             // Set escalation level mail
             $escalation_mail = 'hello@mouka-support.com';
             $tickets_log[] = $ticket;
          }
        }


        $this->to  = $escalation_mail;
        $this->subject = "Escalated Tickets";
        $this->view = 'emails.ticket_info4';
        $this->data = compact('duration', 'tickets_log', 'categories');



       return $this->mailer->send($this->view, $this->data, function ($message) {
            $message->from($this->fromAddress, $this->fromName)
                ->to($this->to)->subject($this->subject);


        });
    }

    public function sendTicketComments($ticketOwner, $user, Ticket $ticket, $comment)
    {
       // $this->to = $ticketOwner->email;
        $this->to = $ticket->category->email;
        $this->subject = "RE: $ticket->title (Ticket ID: $ticket->ticket_id)";
        $this->view = 'emails.ticket_comments';
        $this->data = compact('ticketOwner', 'user', 'ticket', 'comment');

        return $this->deliver();
    }


    //sendTicketCommentsAdmin


    public function sendTicketCommentsAdmin($ticketOwner, $user, Ticket $ticket, $comment)
    {
       // dd($ticket->category);
        $this->to = $ticket->category->email;
        $this->subject = "RE: $ticket->title (Ticket ID: $ticket->ticket_id)";
        $this->view = 'emails.ticket_comments';
        $this->data = compact('ticketOwner', 'user', 'ticket', 'comment');

        return $this->deliver();
    }

    public function sendTicketStatusNotification($ticketOwner, Ticket $ticket)
    {
        $this->to = $ticketOwner->email;
        $this->cc = $ticket->copy_email2;
        $this->subject = "RE: $ticket->title (Ticket ID: $ticket->ticket_id)";
        $this->view = 'emails.ticket_status';
        $this->data = compact('ticketOwner', 'ticket');

        return $this->deliver();
    }

    public function multipledeliver()
    {
        $this->mailer->send($this->view, $this->data, function ($message) {
            $message->from($this->fromAddress, $this->fromName)
                    ->to($this->to)->subject($this->subject)
                    ->bcc([$this->cc]);

        });
    }

    public function singledeliver()
    {
        $this->mailer->send($this->view, $this->data, function ($message) {
            $message->from($this->fromAddress, $this->fromName)
                ->to($this->to)->subject($this->subject);


        });
    }

    public function deliver()
    {
        //dd($this);
       if ($this->cc == null) {
           $this->singledeliver();
       } else {
           $this->multipledeliver();
       }
    }
}
