<?php

namespace App\Policies;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Reply $reply)
    {
        return $user->id == $reply->user_id;
    }

    public function unfavorite(User $user, Reply $reply)
    {
        return !!$reply->favorites()->where('user_id', $user->id)->count();
    }

    public function create(User $user)
    {
        Logger('we are in policy');
        return $user->fresh()->lastReply ? !$user->lastReply->wasJustPublished() : true;
    }
}
