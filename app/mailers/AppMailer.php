<?php
namespace ComplainDesk\Mailers;

use ComplainDesk\Ticket;
use ComplainDesk\User;
use Illuminate\Contracts\Mail\Mailer;

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
        $this->cc = $ticket->copy_email;
        $this->subject = "[Ticket ID: $ticket->ticket_id] $ticket->title";
        $this->view = 'emails.ticket_info';
        $this->data = compact('user', 'ticket');
    //dd($this->cc);
        return $this->deliver();

    }

    public  function SendToCategory($categoryemail, Ticket $ticket){
        $this->to = $categoryemail;
        $this->subject = "[Ticket ID: $ticket->ticket_id] $ticket->title";
        $this->view = 'emails.ticket_info3';
        $this->data = compact('ticket');



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
