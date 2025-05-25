<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

class UpdatePostTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authenticated_user_can_update_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create([
            'title' => 'Old Title',
            'content' => 'Old content',
            'status' => 'published',
        ]);

        $this->actingAs($user);

        $response = $this->put(route('posts.update', $post), [
            'title' => 'New Title',
            'content' => 'New content',
            'is_draft' => false,
            'published_at' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'New Title',
            'content' => 'New content',
            'status' => 'published',
        ]);
    }

    #[Test]
    public function user_cannot_update_post_of_others()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->for($otherUser)->create();

        $this->actingAs($user);

        $response = $this->put(route('posts.update', $post), [
            'title' => 'Should not update',
            'content' => 'No content',
        ]);

        $response->assertStatus(403);
    }


    #[Test]
    public function guest_cannot_update_post()
    {
        $post = Post::factory()->create();

        $response = $this->put(route('posts.update', $post), [
            'title' => 'No guest',
            'content' => 'No guest content',
        ]);

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function title_is_required_on_update()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $this->actingAs($user);

        $response = $this->from(route('posts.edit', $post))->put(route('posts.update', $post), [
            'title' => '',
            'content' => 'Content',
        ]);

        $response->assertRedirect(route('posts.edit', $post));
        $response->assertSessionHasErrors('title');
    }

    #[Test]
    public function content_is_required_on_update()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $this->actingAs($user);

        $response = $this->from(route('posts.edit', $post))->put(route('posts.update', $post), [
            'title' => 'Valid title',
            'content' => '',
        ]);

        $response->assertRedirect(route('posts.edit', $post));
        $response->assertSessionHasErrors('content');
    }

    #[Test]
    public function published_at_must_be_valid_date()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $this->actingAs($user);

        $response = $this->from(route('posts.edit', $post))->put(route('posts.update', $post), [
            'title' => 'Valid title',
            'content' => 'Valid content',
            'published_at' => 'invalid-date',
        ]);

        $response->assertRedirect(route('posts.edit', $post));
        $response->assertSessionHasErrors('published_at');
    }
}
