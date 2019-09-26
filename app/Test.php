<?php

namespace ComplainDesk;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
            protected $fillable = [
        'name', 'email', 'level', 'location', 'format', 'duration'
    ];
}
