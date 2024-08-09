<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $page): bool
    {
        //
        return true;

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return true;

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $page): bool
    {
        //
        // log("userId: ", $user->id);
        // log("PostUserID: ", $post->user_id);
        if ($user->isAdmin){
            return true;
        }
        return $user->id === $page->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $page): bool
    {
        //
        // Log::info('Checking delete permission', [
        //     'user_id' => $user->id,
        //     'post_id' => $page->id,
        //     'isOwner' => $user->id === $page->user_id
        // ]);
        // dd([
        //     'user_id' => $user->id,
        //     'post_id' => $page->id,
        //     // 'isAdmin' => $user->isAdmin(),
        //     'isOwner' => $user->id === $page->user_id
        // ]);
        // die(); // 确保脚本停止运行以便查看输出
        if ($user->isAdmin){
            return true;
        }
        return $user->id === $page->user_id;
        // return true;

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        //
        return true;

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        //
        return true;

    }
}
