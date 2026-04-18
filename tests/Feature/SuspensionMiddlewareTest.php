<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class SuspensionMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_suspended_user_is_redirected_to_login()
    {
        $role = Role::create(['name' => 'User', 'description' => 'User Role']);
        $suspendedUser = User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
            'suspended_until' => now()->addDays(5),
            'suspension_reason' => 'Spamming content.',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($suspendedUser)->get('/dashboard');

        $response->assertRedirect('/login');
        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_active_user_can_access_dashboard()
    {
        $role = Role::create(['name' => 'User', 'description' => 'User Role']);
        $user = User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
            'suspended_until' => null,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }
}
