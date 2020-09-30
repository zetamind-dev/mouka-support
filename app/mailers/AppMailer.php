<?php
namespace ComplainDesk\Mailers;

use BeyondCode\Mailbox\InboundEmail;
use Carbon\Carbon;
use ComplainDesk\Category;
use ComplainDesk\Escalation;
use ComplainDesk\Ticket;
use ComplainDesk\TicketDuration;
use ComplainDesk\User;
use Illuminate\Contracts\Mail\Mailer;

class AppMailer
{
    protected $mailer;
    protected $duration;
    protected $level;
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
        $this->helperFunction(1, "12 hours");
    }

    public function SendToEscalationLevel2()
    {
        $this->helperFunction(2, "24 hours");
    }

    public function SendToEscalationLevel3()
    {
        $this->helperFunction(3, "48 hours");
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

    public function userNotFound(InboundEmail $email)
    {
        $this->to = $email->from();
        $this->subject = "No Match Found!";
        $this->view = 'emails.ticket_info2';
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

    /**
     * @param level duration
     * @return mailer
     */
    public function helperFunction($level, $duration)
    {
        $this->level = $level;
        $this->duration = $duration;

        // get the current time  - 2015-12-19 10:10:54
        $current = Carbon::now();
        /**
         * Retrieve tickets and tickets_duration that has not been dropped by ticket owner and tickets that has not been resolved by ticket Moderators from the Database
         */
        $tickets = Ticket::all()->where('drop_ticket', 0)->where('status', 'Open');
        $tickets_duration = TicketDuration::all();

        // Retrieve escalations level 1 from the Database
        $escalations = Escalation::all()->where('level', $this->level);
        // Retrieve Categories
        $categories = Category::all();

        // Ticket logs
        $tickets_log['tickets'] = array();

        // Duration
        $duration_level = $this->duration;

        // Check if escalations exist
        if (count($escalations) > 0) { // if true then
            // iterate over the escalations result set
            foreach ($escalations as $escalation) {
                // Set counter
                $counter = 0;
                // Iterate over tickets result set
                foreach ($tickets as $ticket) {
                    // Get modeartor
                    foreach ($categories as $category) {
                        $moderator = User::all()
                            ->where('department_id', $ticket->department_id)
                            ->where('location', $escalation->location)
                            ->where('user_type', '>', 0)->first();
                    }
                    foreach ($tickets_duration as $ticket_duration) {

                        // Check if the ticket id matches
                        if ($ticket->id === $ticket_duration->ticket_id) {
                            if ($ticket_duration->duration === 12) {
                                // Check if ticket location matches escalation level location and department
                                if ($ticket->location === $escalation->location && $ticket->department_id === $escalation->department_id) {
                                    $ticket_item = array(
                                        'ticket_id' => $ticket->ticket_id,
                                        'title' => $ticket->title,
                                        'ticket_owner' => $ticket->ticket_owner,
                                        'copy_email2' => $ticket->copy_email2,
                                        'location' => $ticket->location,
                                        'category_id' => $ticket->category_id,
                                        'priority' => $ticket->priority,
                                        'duration' => $ticket_duration->duration,
                                    );

                                    // Push to tickets array
                                    array_push($tickets_log['tickets'], $ticket_item);
                                    // increment counter by 1
                                    $counter++;
                                }

                            }

                        }
                    }
                }
                // Check if counter is greater than 0
                if ($counter > 0) { // if true
                    // then escalate
                    $this->to = $escalation->email;
                    $this->subject = "Escalated Tickets";
                    $this->view = 'emails.ticket_info4';
                    $this->data = compact('tickets_log', 'categories', 'duration_level', 'current', 'moderator');

                    return $this->mailer->send($this->view, $this->data, function ($message) {
                        $message->from($this->fromAddress, $this->fromName)
                            ->to($this->to)->subject($this->subject);

                    });

                }
            }

        }

    }
}
