<?php

namespace ComplainDesk;

use BeyondCode\Mailbox\InboundEmail;
use ComplainDesk\Category;
use ComplainDesk\Mailers\AppMailer;
use ComplainDesk\Ticket;
use ComplainDesk\User;
use Illuminate\Support\Facades\Mail;

class MailHandler
{

    public function __invoke(InboundEmail $email, AppMailer $mailer)
    {

        // DECLARE ALL TICKET FIELDS VARIABLES
        $title;
        $user_id;
        $ticket_id;
        $category_id;
        $priority = 'high';
        $message;
        $status = 'Open';
        $fileNameToStore;
        $location;
        $copy_email2;
        $ticket_owner;
        $sender;

        /*
        |--------------------------------------------------------------------------
        | VALIDATION 1
        |--------------------------------------------------------------------------
        |
        | Here is where the application validates sender's post request address header[$email->from()]
        | Validate sender's email address against user's email in the database collection
         */
        /** @return $mailer */
        // Retrieve all user's records from the database
        $users = User::all();
        // Loop over the the result set
        foreach ($users as $user) {
            // Compare each user's email with sender's email
            if ($email->from() === $user->email) { // If it matched
                // Set sender record
                $sender = $user;
                // Ensure to set the following fields for the ticket
                // ticket_owner, location, user_id, and ticket_id
                $ticket_owner = $email->from();
                $user_id = $user->id;
                // Set ticket location
                $location = $sender->location;
                // Format ticket_id string
                // Retrieve tickets from tickets table by sender's location
                $tickets = Ticket::all()->where('location', $location);
                $count = count($tickets);

                // Check if count is less than 10
                if ($count < 9) { // if true
                    // increment count variable by 1 and assign the value to new_id variable
                    $new_id = $count + 1;
                    // concat ticket number with three zeros
                    $ticket_num = "000" . $new_id;

                } else { // ticket numbering should begin with two zeros
                    $new_id = $count + 1;
                    // concat ticket number with 000
                    $ticket_num = "00" . $new_id;
                }
                // If sender's location is not Head Office, make ticket_id from first two letters of user's location and ticket_num
                if ($location != 'Head Office') {
                    $ticket_id = strtoupper(mb_substr($location, 0, 2) . $ticket_num);
                } else {
                    $ticket_id = 'HO' . strtoupper($ticket_num);
                }

                /*
                |--------------------------------------------------------------------------
                | VALIDATION 2
                |--------------------------------------------------------------------------
                |
                | Here is where the application validates sender's post request address header [$email->cc()] and also create ticket for the sender
                | Validate cc email address,
                | Sender must copy at least 2 cc addresses.
                | Sender must not copy multiple category email in cc address
                 */

                
 
                    // Decalare an array counter and set the value to empty
                    $counter = []; // This will be used to set category_id in the nested loop
                  
                    // Check if the sender copied email
                    if(count($email->cc()) > 0) {
                       /** LOOP */
                        foreach ($email->cc() as $cc) {
                            // Check if category email matches any cc email
                            if ('nav.clikview@mouka-support.com' === $cc->getEmail()) { // if true
                                // Then find category by name
                                $category = Category::all()->where('name', 'NAV/QLIKVIEW')->first();
                                // Set category_id field
                                $counter[] = $category->id;
                            } elseif ('it.networks@mouka-support.com' === $cc->getEmail()) {
                                // Then find category by name
                                $category = Category::all()->where('name', 'Computers, Networks & Others')->first();
                                // Set category_id field
                                $counter[] = $category->id;
                            } else { // Otherwise set copy_email2
                                $copy_email2 = $cc->getEmail();
                            }
                        }
                        /** END OF LOOP */

                    }
                    // Set ticket title
                    $title = $email->subject();
                    
                    // Check if counter array elements is greater than 1
                    if (count($counter) > 1 || count($email->cc()) < 1 || $title === null) { // If true
                        // That means sender copied multiple category emails or no cc was found
                        // Return an error message to the user by mail
                        // Call mailer and pass sender's email as arg
                        return $mailer->sendErrorInfo($email);
                    } else { // no multiple category email was found
                        // Set category_id
                        foreach ($counter as $counter_id) {
                            $category_id = $counter_id;
                        }

                        // Get all attachements
                        if (count($email->attachments()) > 0) { // if true, then there is file to store
                            foreach ($email->attachments() as $attachment) {
                                $fileNameToStore = $attachment->getFilename();
                                $attachment->saveContent(storage_path($fileNameToStore));
                            }
                        } else { // no file to store
                            $fileNameToStore = 'noimage.jpg';
                        }
                        // Create Ticket for the sender
                        $ticket = new Ticket([
                            'title' => $title,
                            'user_id' => $user_id,
                            'ticket_id' => $ticket_id,
                            'category_id' => $category_id,
                            'priority' => $priority,
                            'message' => $email->text(),
                            'status' => $status,
                            'picture' => $fileNameToStore,
                            'location' => $location,
                            'copy_email2' => $copy_email2,
                            'ticket_owner' => $ticket_owner,
                        ]);

                        // save record to database
                        $ticket->save();
                        //Retrieve all categories
                        $categories = new Category;
                        // Check sender's location to determine moderator
                        if ($sender->location === "Head Office" || "Lagos") { // if true
                            // Then send mail to the moderator at Head Office whose user_type is 2
                            //  Fetch moderator at head office
                            $moderator = User::all()->where('location', "Head Office")->where('user_type', 2)->first();
                            return [$mailer->SendToModerator($categories, $ticket, $moderator, $sender),
                                // Notify the sender by mail about the ticket
                                //  Call mailer and pass in $ticket as arg
                                $mailer->sendTicketInformation2($sender, $ticket)];
                        } else {
                            // fetch moderator at user's location whose location is not Lagos or Head Office
                            $moderator = User::all()->whereNotIn('location', ["Head Office", "Lagos"])->where('location', $sender->location)->where('user_type', 1)->first();
                            return [$mailer->SendToModerator($categories, $ticket, $moderator, $sender),
                                // Notify the sender by mail about the ticket
                                //  Call mailer and pass in $ticket as arg
                                $mailer->sendTicketInformation2($sender, $ticket)];
                        }
                    }
                

                /** END OF VALIDATION 2 */

            }
        }

        /** END OF VALIDATION 1 */

        // If no match found
        // Then return an  message to the sender by mail
        // Call the mailer and pass sender's email as arg
        return $mailer->userNotFound($email);

    }

// MAIL_DRIVER=smtp
    // MAIL_HOST=smtp.gmail.com
    // MAIL_PORT=587
    // MAIL_USERNAME=sundayemmanuelabraham@gmail.com
    // MAIL_PASSWORD=wkhfdxheweskbzwl
    // MAIL_ENCRYPTION=ssl

// MAIL_DRIVER=mailgun
    // MAIL_HOST=smtp.mailgun.org
    // MAIL_PORT=587
    // MAIL_USERNAME=postmaster@inbound.mouka-support.com
    // MAIL_PASSWORD=6a43e1d7a73102341666723f7e9f0dd4-9c988ee3-beae2112
    // MAIL_ENCRYPTION=tls
    // MAILGUN_DOMAIN=inbound.mouka-support.com
    // MAILGUN_SECRET=key-69f6d6aeb88c0b7948d53d4b5c621a42

// MAILBOX_DRIVER=mailgun
    // MAILBOX_MAILGUN_KEY=key-69f6d6aeb88c0b7948d53d4b5c621a42

// MAILBOX_DRIVER=postmark
    // MAILBOX_HTTP_USERNAME=laravel-mailbox
    // MAILBOX_HTTP_PASSWORD=14e594cf599dd7b71c92e82767b282b41eb3490b162f3c54de41fda9c99fba1f
}
