<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(): View
    {
        $posts = Auth::check()
            ? Post::ownedBy(Auth::id())->latest()->get()
            : collect();
        return view('home', compact('posts'));
    }
}
