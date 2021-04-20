<?php

namespace App\Policies;

use App\Models\Gist;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GistPolicy
{
    use HandlesAuthorization;

     //Determine whether the user can update the model.

     public function update(User $user, Gist $gist)
    {
        return $user->id == $gist->user_id;
    }

     //Determine whether the user can delete the model.
    public function delete(User $user, Gist $gist)
    {
        return $user->id == $gist->user_id;
    }
}
