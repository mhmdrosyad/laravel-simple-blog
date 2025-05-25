<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_sees_login_and_register_links()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Please');
        $response->assertSee('login');
        $response->assertSee('register');
    }

    #[Test]
    public function authenticated_user_sees_own_posts()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'User\'s Post',
        ]);

        $this->actingAs($user);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Your Posts');
        $response->assertSee($post->title);
    }

    #[Test]
    public function authenticated_user_does_not_see_others_posts()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Post::factory()->create([
            'user_id' => $otherUser->id,
            'title' => 'Other User\'s Post',
        ]);

        $this->actingAs($user);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Other User\'s Post');
    }
}
