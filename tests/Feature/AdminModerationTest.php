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

    public function test_admin_can_suspend_user()
    {
        Mail::fake();

        $adminRole = Role::create(['name' => 'Admin', 'description' => 'Admin Role']);
        $userRole = Role::create(['name' => 'User', 'description' => 'User Role']);

        $admin = User::factory()->create(['role_id' => $adminRole->id, 'email_verified_at' => now()]);
        $targetUser = User::factory()->create(['role_id' => $userRole->id, 'email_verified_at' => now()]);

        $response = $this->actingAs($admin)->post("/admin/users/{$targetUser->id}/suspend", [
            'hours' => 24,
            'reason' => 'Violation of terms'
        ]);

        $response->assertRedirect();
        
        $targetUser->refresh();
        $this->assertNotNull($targetUser->suspended_until);
        $this->assertEquals('Violation of terms', $targetUser->suspension_reason);

        Mail::assertQueued(UserSuspendedMail::class, function ($mail) use ($targetUser) {
            return $mail->hasTo($targetUser->email);
        });
    }

    public function test_admin_can_delete_post()
    {
        Mail::fake();

        $adminRole = Role::create(['name' => 'Admin', 'description' => 'Admin Role']);
        $userRole = Role::create(['name' => 'User', 'description' => 'User Role']);

        $admin = User::factory()->create(['role_id' => $adminRole->id, 'email_verified_at' => now()]);
        $targetUser = User::factory()->create(['role_id' => $userRole->id, 'email_verified_at' => now()]);

        $post = Post::create([
            'user_id' => $targetUser->id,
            'caption' => 'Bad post',
            'images' => ['test.jpg']
        ]);

        $response = $this->actingAs($admin)->delete("/admin/posts/{$post->id}", [
            'reason' => 'Inappropriate content'
        ]);

        $response->assertRedirect();
        $this->assertSoftDeleted($post);

        Mail::assertQueued(ContentDeletedAdminMail::class);
    }
}
