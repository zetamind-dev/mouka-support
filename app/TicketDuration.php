<?php

namespace ComplainDesk;

use Illuminate\Database\Eloquent\Model;

class TicketDuration extends Model
{
    protected $fillable = [
       'ticket_id', 'duration'
    ];
}
