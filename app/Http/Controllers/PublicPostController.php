<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PublicPostController extends Controller
{
    /**
     * Display public listing of news & articles.
     */
    public function index(Request $request)
    {
        $query = Post::where('is_published', true)->with('author');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->orderBy('published_at', 'desc')->paginate(6)->withQueryString();
        $recentPosts = Post::where('is_published', true)->orderBy('published_at', 'desc')->take(4)->get();

        return view('public.posts.index', compact('posts', 'recentPosts'));
    }

    /**
     * Display single news article detail.
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->with('author')
            ->firstOrFail();

        // Increment views count
        $post->increment('views_count');

        // Recent related news
        $relatedPosts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('public.posts.show', compact('post', 'relatedPosts'));
    }
}
