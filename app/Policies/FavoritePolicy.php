<?php

namespace App\Policies;

use App\Models\Favorite;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FavoritePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Favorite $reply)
    {
        return $user->id == $reply->user_id;
    }

}
