<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;
    #[Test]
    public function authenticated_user_can_create_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/posts', [
            'title' => 'Example title',
            'content' => 'This is example body content.',
            'user_id' => $user->id
        ]);

        $response->assertRedirect('/posts');
        $this->assertDatabaseHas('posts', [
            'title' => 'Example title',
            'content' => 'This is example body content.',
            'user_id' => $user->id
        ]);
    }

    #[Test]
    public function guest_cannot_create_post()
    {
        $response = $this->post('/posts', [
            'title' => 'Guest Test',
            'content' => 'Must error.',
        ]);

        $response->assertRedirect('/login');
    }

    #[Test]
    public function title_is_required()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/posts', [
            'title' => '',
            'content' => 'Konten valid',
        ]);

        $response->assertSessionHasErrors('title');
    }

    #[Test]
    public function content_is_required()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/posts', [
            'title' => 'Judul valid',
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    #[Test]
    public function title_must_not_exceed_max_length()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $longTitle = str_repeat('a', 256);

        $response = $this->post('/posts', [
            'title' => $longTitle,
            'content' => 'Konten valid',
        ]);

        $response->assertSessionHasErrors('title');
    }

    #[Test]
    public function title_with_max_length_is_accepted()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $title = str_repeat('a', 60);

        $response = $this->post('/posts', [
            'title' => $title,
            'content' => 'Konten valid',
        ]);

        $response->assertSessionDoesntHaveErrors('title');
    }


    #[Test]
    public function is_draft_must_be_boolean()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/posts', [
            'title' => 'Judul valid',
            'content' => 'Konten valid',
            'is_draft' => 'not_boolean',
        ]);

        $response->assertSessionHasErrors('is_draft');
    }

    #[Test]
    public function published_at_can_be_null()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/posts', [
            'title' => 'Judul valid',
            'content' => 'Konten valid',
            'published_at' => null,
        ]);

        $response->assertSessionDoesntHaveErrors('published_at');
        $response->assertRedirect('/posts');
    }

    #[Test]
    public function post_status_is_draft_if_is_draft_true()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post('/posts', [
            'title' => 'Judul Draft',
            'content' => 'Konten Draft',
            'is_draft' => true,
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Judul Draft',
            'status' => 'draft',
        ]);
    }

    #[Test]
    public function post_status_is_published_if_is_draft_false_or_null()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post('/posts', [
            'title' => 'Judul Publish',
            'content' => 'Konten Publish',
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Judul Publish',
            'status' => 'published',
        ]);
    }

    #[Test]
    public function published_at_must_be_valid_date()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/posts', [
            'title' => 'Judul valid',
            'content' => 'Konten valid',
            'published_at' => 'not-a-date',
        ]);
        $response->assertSessionHasErrors('published_at');
    }

    #[Test]
    public function published_at_accepts_valid_date()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/posts', [
            'title' => 'Judul valid',
            'content' => 'Konten valid',
            'published_at' => '2025-05-25',
        ]);
        $response->assertSessionDoesntHaveErrors('published_at');
        $response->assertRedirect('/posts');
    }
}
