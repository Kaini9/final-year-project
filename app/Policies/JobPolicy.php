<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobPolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin() || ($user->role && $user->role->can_post_jobs);
    }
    public function update(User $user, Job $job): bool
    {
        return $user->isAdmin() || $user->id === $job->user_id;
    }
    public function delete(User $user, Job $job): bool
    {
        return $user->isAdmin() || $user->id === $job->user_id;
    }
}
