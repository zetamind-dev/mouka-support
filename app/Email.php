<?php

namespace ComplainDesk;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
        protected $fillable = [
        'subject', 'text', 'from', 'to', 'cc', 'fromName'
    ];
}
