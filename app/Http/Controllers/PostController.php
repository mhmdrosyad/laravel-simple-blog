<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $posts = Post::published()
            ->orderBy('published_at', 'desc')
            ->get();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try {
            Post::create($request->data());

            return to_route('home')->with('success', 'Post berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan post', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan post.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): View
    {
        $this->authorize('view', $post);
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        try {
            $post->update($request->data());

            return to_route('home')->with('success', 'Post berhasil diupdate.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan post', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan post.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('home')->with('success', 'Post deleted successfully.');
    }
}
