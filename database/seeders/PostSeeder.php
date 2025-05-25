<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Draft Post Example',
            'content' => 'Ini adalah contoh post dengan status draft.',
            'status' => 'draft',
            'published_at' => null,
        ]);

        Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Published Post Example',
            'content' => 'Ini adalah contoh post yang sudah dipublikasikan.',
            'status' => 'published',
            'published_at' => Carbon::now()->subDay(),
        ]);

        Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Scheduled Post Example',
            'content' => 'Ini adalah contoh post yang dijadwalkan untuk dipublikasikan nanti.',
            'status' => 'published',
            'published_at' => Carbon::now()->addDay(),
        ]);
    }
}
