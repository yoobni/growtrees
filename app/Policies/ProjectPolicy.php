<?php

namespace App\Policies;

use App\User;
use App\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
	
    public function access(User $user, Project $project)
    {
	if(strpos($project->members, $user->id . 'n') !== false) {
	    return true;
	} 
        else {
	    return false;
	}
    }
}
