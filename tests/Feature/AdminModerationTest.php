<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Post;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserSuspendedMail;
use App\Mail\ContentDeletedAdminMail;

class AdminModerationTest extends TestCase
{
    use RefreshDatabase;
    public function test_regular_user_cannot_access_admin_panel()
    {
        $userRole = Role::create(['name' => 'User', 'description' => 'User Role']);
        $user = User::factory()->create(['role_id' => $userRole->id, 'email_verified_at' => now()]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
    }
}
