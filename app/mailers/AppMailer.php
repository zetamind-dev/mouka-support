<?php
namespace ComplainDesk\Mailers;

use BeyondCode\Mailbox\InboundEmail;
use Carbon\Carbon;
use ComplainDesk\Category;
use ComplainDesk\Escalation;
use ComplainDesk\Ticket;
use ComplainDesk\User;
use Illuminate\Contracts\Mail\Mailer;

class AppMailer
{
    protected $mailer;
    protected $fromAddress = 'workflow@mouka.com';
    protected $fromName = 'Mouka Help Desk';
    protected $to;
    protected $cc;
    protected $subject;
    protected $view;
    protected $data = [];

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendTicketInformation(User $user, Ticket $ticket)
    {
        $this->to = $user->email;
        //dd($user->email);
        $this->subject = "[Ticket ID: $ticket->ticket_id] $ticket->title";
        $this->view = 'emails.ticket_info';
        $this->data = compact('user', 'ticket');
        //dd($this->cc);
        return $this->deliver();

    }

    public function sendTicketInformation2(User $user, Ticket $ticket)
    {
        $this->to = $user->email;
        //dd($user->email);
        $this->subject = "[Ticket ID: $ticket->ticket_id] $ticket->title";
        $this->view = 'emails.ticket_info6';
        $this->data = compact('user', 'ticket');
        //dd($this->cc);

        return $this->mailer->send($this->view, $this->data, function ($message) {
            $message->from($this->fromAddress, $this->fromName)
                ->to($this->to)->subject($this->subject);
        });

    }

    public function SendToModerator(Category $categories, Ticket $ticket, $moderator, User $user)
    {
        $category = $categories::find($ticket->category_id);
        $this->to = $moderator->email;
        // Check if copy_email2 is not null
        if ($ticket->copy_email2 != null) {
            $this->cc = [$category->email, $ticket->copy_email2];
        } else {
            $this->cc = $category->email;
        }

        $this->subject = "[Ticket ID: $ticket->ticket_id] $ticket->title";
        $this->view = 'emails.ticket_info3';
        $this->data = compact('ticket', 'user', 'category');

        return $this->mailer->send($this->view, $this->data, function ($message) {
            $message->from($this->fromAddress, $this->fromName)
                ->to($this->to)->cc($this->cc)->subject($this->subject);

        });
    }

    public function SendToEscalationLevel1()
    {

        // get the current time  - 2015-12-19 10:10:54
        $current = Carbon::now();
        // Retrieve tickets that has not been dropped by ticket owner and tickets that has not been resolved by ticket Moderators
        // from the Database
        $tickets = Ticket::where('drop_ticket', 0)->where('status', 'Open')->get();

        // Retrieve escalations level 1 from the Database
        $escalations = Escalation::all()->where('level', 1);
        // Retrieve Categories
        $categories = Category::all();

        // Ticket logs
        $tickets_log = [];

        // Duration
        $duration_level = "12 hours";

        // Iterate over the escalations result set
        foreach ($escalations as $escalation) {
            // Iterate over tickets result set
            foreach ($tickets as $ticket) {
                // Check the ticket duration
                $duration = $current->diffInHours($ticket->created_at);
                if ($duration > 11 || $duration < 24) {
                    // Check if ticket location matches escalation level location
                    if ($ticket->location === $escalation->location) {
                        $tickets_log[] = $ticket->title;
                        $tickets_log[] = $ticket->id;
                        $tickets_log[] = $ticket->ticket_owner;
                        $tickets_log[] = $ticket->cpoy_email2;
                        $tickets_log[] = $ticket->location;
                        $tickets_log[] = $ticket->category;
                        $tickets_log[] = $ticket->priority;
                        $tickets_log[] = $duration;
                        $escalation_mail = $escalation->email;
                    }

                }
            }
            $this->to = $escalation->email;
            $this->subject = "Escalated Tickets";
            $this->view = 'emails.ticket_info4';
            $this->data = compact('tickets_log', 'categories', 'duration_level');

            return $this->mailer->send($this->view, $this->data, function ($message) {
                $message->from($this->fromAddress, $this->fromName)
                    ->to($this->to)->subject($this->subject);

            });

        }
    }

    public function SendToEscalationLevel2()
    {
        // get the current time  - 2015-12-19 10:10:54
        $current = Carbon::now();
        // Retrieve tickets that has not been dropped by ticket owner and tickets that has not been resolved by ticket Moderators
        // from the Database
        $tickets = Ticket::where('drop_ticket', 0)->where('status', 'Open')->get();

        // Retrieve escalations level 1 from the Database
        $escalations = Escalation::all()->where('level', 2);
        // Retrieve Categories
        $categories = Category::all();

        // Ticket logs
        $tickets_log = [];

        // Duration
        $duration_level = "24 hours";

        // Iterate over the escalations result set
        foreach ($escalations as $escalation) {
            // Iterate over tickets result set
            foreach ($tickets as $ticket) {
                // Check the ticket duration
                $duration = $current->diffInHours($ticket->created_at);
                if ($duration > 24 || $duration < 48) {
                    // Check if ticket location matches escalation level location
                    if ($ticket->location === $escalation->location) {
                        $tickets_log[] = $ticket->title;
                        $tickets_log[] = $ticket->id;
                        $tickets_log[] = $ticket->ticket_owner;
                        $tickets_log[] = $ticket->cpoy_email2;
                        $tickets_log[] = $ticket->location;
                        $tickets_log[] = $ticket->category;
                        $tickets_log[] = $ticket->priority;
                        $tickets_log[] = $duration;
                    }

                }
            }
            $this->to = $escalation->email;
            $this->subject = "Escalated Tickets";
            $this->view = 'emails.ticket_info4';
            $this->data = compact('tickets_log', 'categories', 'duration_level');

            return $this->mailer->send($this->view, $this->data, function ($message) {
                $message->from($this->fromAddress, $this->fromName)
                    ->to($this->to)->subject($this->subject);

            });

        }

    }

    public function SendToEscalationLevel3()
    {
        // get the current time  - 2015-12-19 10:10:54
        $current = Carbon::now();
        // Retrieve tickets that has not been dropped by ticket owner and tickets that has not been resolved by ticket Moderators
        // from the Database
        $tickets = Ticket::where('drop_ticket', 0)->where('status', 'Open')->get();

        // Retrieve escalations level 1 from the Database
        $escalations = Escalation::all()->where('level', 3);
        // Retrieve Categories
        $categories = Category::all();

        // Ticket logs
        $tickets_log = [];

        // Duration
        $duration_level = "48 hours";

        // Iterate over the escalations result set
        foreach ($escalations as $escalation) {
            // Iterate over tickets result set
            foreach ($tickets as $ticket) {
                // Check the ticket duration
                $duration = $current->diffInHours($ticket->created_at);
                if ($duration === 48 || $duration > 48) {
                    // Check if ticket location matches escalation level location
                    if ($ticket->location === $escalation->location) {
                        $tickets_log[] = $ticket->title;
                        $tickets_log[] = $ticket->id;
                        $tickets_log[] = $ticket->ticket_owner;
                        $tickets_log[] = $ticket->cpoy_email2;
                        $tickets_log[] = $ticket->location;
                        $tickets_log[] = $ticket->category;
                        $tickets_log[] = $ticket->priority;
                        $tickets_log[] = $duration;
                    }

                }
            }
            $this->to = $escalation->email;
            $this->subject = "Escalated Tickets";
            $this->view = 'emails.ticket_info4';
            $this->data = compact('tickets_log', 'categories', 'duration_level');

            return $this->mailer->send($this->view, $this->data, function ($message) {
                $message->from($this->fromAddress, $this->fromName)
                    ->to($this->to)->subject($this->subject);

            });

        }

    }

    public function sendErrorInfo(InboundEmail $email)
    {
        $this->to = $email->from();
        $this->subject = "Request Interrupted!";
        $this->view = 'emails.ticket_info5';
        $this->data = compact('email');

        return $this->mailer->send($this->view, $this->data, function ($message) {
            $message->from($this->fromAddress, $this->fromName)
                ->to($this->to)->subject($this->subject);

        });
    }

    public function sendTicketComments($ticketOwner, $user, Ticket $ticket, $comment)
    {
        //$this->to = $ticketOwner->email;
        $this->to = $ticket->category->email;
        //$this->to = $ticket->copy_email2;
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
        //$this->to = $ticket->copy_email2;
        $this->subject = "RE: $ticket->title (Ticket ID: $ticket->ticket_id)";
        $this->view = 'emails.ticket_comments';
        $this->data = compact('ticketOwner', 'user', 'ticket', 'comment');

        return $this->deliver();
    }

    public function sendTicketStatusNotification($ticketOwner, Ticket $ticket)
    {
        $this->to = $ticketOwner->email;
        //$this->cc = $ticket->copy_email2;
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
                ->cc($this->cc);

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
