<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;


    public function update(User $user, User $signed_in_user)
    {
        return $signed_in_user->id == $user->id;
    }
}
