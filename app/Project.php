<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
	    'name', 'description', 'author', 'members', 'requests', 'token', 'due_date'
    ];

    /**
    * Get the route key for the model.
    *
    * @return string
    */
    public function getRouteKeyName()
    {
        return 'token';
    }
}
