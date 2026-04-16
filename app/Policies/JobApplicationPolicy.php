<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;

class JobApplicationPolicy
{
    public function create(User $user): bool
    {
        // Any authenticated user can create a job application
        // Role matching is validated in the view and controller
        return true;
    }

    public function update(User $user, JobApplication $jobApplication): bool
    {
        // Only the job poster (Designer) or Admin can accept/reject the application
        return $user->isAdmin() || $user->id === $jobApplication->job->user_id;
    }

    public function delete(User $user, JobApplication $jobApplication): bool
    {
        return $user->isAdmin() || $user->id === $jobApplication->user_id;
    }
}
