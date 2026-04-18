<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Post;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_api_returns_paginated_json_with_correct_keys()
    {
        $role = Role::create(['name' => 'User', 'description' => 'User Role']);
        $user = User::factory()->create(['role_id' => $role->id, 'is_active' => true, 'email_verified_at' => now()]);

        // Create 6 posts
        for ($i = 0; $i < 6; $i++) {
            Post::create([
                'user_id' => $user->id,
                'caption' => "Test post $i",
                'images' => ["test$i.jpg"]
            ]);
        }

        $response = $this->actingAs($user)->getJson('/posts/api/load?page=1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'user_id', 'caption', 'images', 'created_at', 'updated_at',
                    'user' => ['id', 'name', 'is_verified', 'profile', 'role'],
                    'likes', 'comments'
                ]
            ],
            'has_more',
            'current_page',
            'last_page'
        ]);

        $this->assertEquals(4, count($response->json('data'))); // 4 posts horizontally
        $this->assertTrue($response->json('has_more'));

        $page2 = $this->actingAs($user)->getJson('/posts/api/load?page=2');
        $this->assertEquals(2, count($page2->json('data')));
        $this->assertFalse($page2->json('has_more'));
    }
}
