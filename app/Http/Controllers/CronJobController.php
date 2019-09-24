<?php

namespace ComplainDesk\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use ComplainDesk\Mailers\AppMailer;

class CronJobController extends Controller
{
    //Execute and send mail to designated escalation level
    public function index(AppMailer $mailer) {
        $mailer->SendToEscalationLevel();
        echo "It worked!";
    }
}
