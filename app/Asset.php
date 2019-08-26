<?php

namespace ComplainDesk;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'name', 'user_id', 'email', 'employeeno', 'laptop_name', 'laptop_model', 'laptop_serial_no','laptop_duration','remark','computer_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }
}
