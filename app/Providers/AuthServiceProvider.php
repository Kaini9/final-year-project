<?php

namespace App\Providers;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Job::class => \App\Policies\JobPolicy::class,
        \App\Models\Post::class => \App\Policies\PostPolicy::class,
        \App\Models\JobApplication::class => \App\Policies\JobApplicationPolicy::class,
    ];
    public function boot()
    {
        $this->registerPolicies();
    }
}
