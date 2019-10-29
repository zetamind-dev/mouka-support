<?php

namespace ComplainDesk;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
        'category_id', 'category_name', 'author_id', 'author_location', 'author_name', 'title', 'body'
    ];
}
