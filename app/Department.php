<?php

namespace ComplainDesk;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name'];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
