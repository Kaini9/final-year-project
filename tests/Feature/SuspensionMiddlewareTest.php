<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class SuspensionMiddlewareTest extends TestCase
{
    use RefreshDatabase;

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
