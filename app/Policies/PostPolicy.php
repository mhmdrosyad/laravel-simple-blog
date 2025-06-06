<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    public function view(?User $user, Post $post)
    {
        if ($post->status === 'draft') {
            return false;
        }

        if (Carbon::parse($post->published_at)?->isAfter(today())) {
            return false;
        }

        return true;
    }
}
