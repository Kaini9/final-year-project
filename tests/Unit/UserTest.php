<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Verification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_admin_method()
    {
        $adminRole = Role::create(['name' => 'Admin', 'description' => 'Admin Role']);
        $userRole = Role::create(['name' => 'User', 'description' => 'User Role']);

        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $user = User::factory()->create(['role_id' => $userRole->id]);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($user->isAdmin());
    }

    public function test_is_suspended_method()
    {
        $role = Role::create(['name' => 'User', 'description' => 'User Role']);
        $activeUser = User::factory()->create(['role_id' => $role->id]);
        $suspendedUser = User::factory()->create(['role_id' => $role->id, 'suspended_until' => now()->addDays(5), 'suspension_reason' => 'Spam']);
        $pastSuspendedUser = User::factory()->create(['role_id' => $role->id, 'suspended_until' => now()->subDays(5)]);

        $this->assertFalse($activeUser->isSuspended());
        $this->assertTrue($suspendedUser->isSuspended());
        $this->assertFalse($pastSuspendedUser->isSuspended());
    }

    public function test_is_verified_attribute()
    {
        $adminRole = Role::create(['name' => 'Admin', 'description' => 'Admin Role']);
        $userRole = Role::create(['name' => 'User', 'description' => 'User Role']);

        $admin = User::factory()->create(['role_id' => $adminRole->id]);
        $user = User::factory()->create(['role_id' => $userRole->id]);

        $this->assertTrue($admin->is_verified);
        $this->assertFalse($user->is_verified);

        Verification::create([
            'user_id' => $user->id,
            'document_path' => 'doc.pdf',
            'status' => 'approved',
            'is_active' => true,
            'payment_status' => 'completed',
            'transaction_id' => 'abc'
        ]);

        $this->assertTrue($user->fresh()->is_verified);
    }
}
