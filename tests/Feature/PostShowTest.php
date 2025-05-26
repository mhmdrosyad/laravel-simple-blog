<?php

namespace Tests\Feature;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PostShowTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_view_published_post_with_published_at_past_or_today()
    {
        $post = Post::factory()->create([
            'status' => 'published',
            'published_at' => Carbon::today()->subDay(),
        ]);

        $response = $this->get(route('posts.show', $post));

        $response->assertStatus(200);
    }

    #[Test]
    public function cannot_view_post_with_status_draft()
    {
        $post = Post::factory()->create([
            'status' => 'draft',
            'published_at' => Carbon::today()->subDay(),
        ]);

        $response = $this->get(route('posts.show', $post));

        $response->assertStatus(403);
    }

    #[Test]
    public function cannot_view_post_with_published_at_in_future()
    {
        $post = Post::factory()->create([
            'status' => 'published',
            'published_at' => Carbon::tomorrow(),
        ]);

        $response = $this->get(route('posts.show', $post));

        $response->assertStatus(403);
    }
}
