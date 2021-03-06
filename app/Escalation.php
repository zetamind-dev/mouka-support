<?php

namespace ComplainDesk;

use Illuminate\Database\Eloquent\Model;

class Escalation extends Model
{
    protected $fillable = [
        'name', 'email', 'level', 'location', 'format', 'department_id'
    ];
}
