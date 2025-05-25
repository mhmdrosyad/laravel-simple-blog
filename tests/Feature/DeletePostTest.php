<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeletePostTest extends TestCase
{
    use RefreshDatabase;
    #[Test]
    public function user_can_delete_own_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->delete("/posts/{$post->id}");

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    #[Test]
    public function user_cannot_delete_others_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->delete("/posts/{$post->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
        ]);
    }
}
