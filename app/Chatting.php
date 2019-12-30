<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chatting extends Model
{
    //
    protected $fillable = [
		'project_id', 'user_id', 'message'
    ];
}
