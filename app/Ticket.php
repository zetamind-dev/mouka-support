<?php

namespace ComplainDesk;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    //
    protected $fillable = [
        'user_id', 'category_id', 'department_id','ticket_id', 'title', 'priority', 'message', 'status','picture','location','copy_email2', 'ticket_owner'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
