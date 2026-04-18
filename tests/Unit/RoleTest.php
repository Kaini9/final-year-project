<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_post_jobs_capability()
    {
        $brandRole = Role::create(['name' => 'Brand', 'description' => 'Brand', 'can_post_jobs' => true]);
        $userRole = Role::create(['name' => 'User', 'description' => 'User', 'can_post_jobs' => false]);

        $this->assertTrue($brandRole->can_post_jobs);
        $this->assertFalse($userRole->can_post_jobs);
    }
}
