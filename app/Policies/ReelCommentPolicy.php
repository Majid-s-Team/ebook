<?php

namespace App\Policies;

use App\Models\ReelComment;
use App\Models\User;

class ReelCommentPolicy
{
    public function update(User $user, ReelComment $comment)
    {
        return $user->id === $comment->user_id;
    }

    public function delete(User $user, ReelComment $comment)
    {
        return $user->id === $comment->user_id;
    }
}
