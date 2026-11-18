<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Serve post cover image safely without external adblocker interference.
     */
    public function thumbnail($id)
    {
        $post = Post::findOrFail($id);

        if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
            $filePath = Storage::disk('public')->path($post->thumbnail);
            $mimeType = @mime_content_type($filePath) ?: 'image/jpeg';
            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        return $this->placeholder();
    }

    /**
     * Return a clean, local SVG placeholder (no external CDN, 100% adblock-safe).
     */
    public function placeholder()
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="450" viewBox="0 0 800 450" fill="none">
            <rect width="800" height="450" fill="#0f172a"/>
            <rect width="800" height="450" fill="url(#grad)" opacity="0.85"/>
            <defs>
                <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#047857"/>
                    <stop offset="50%" stop-color="#0f172a"/>
                    <stop offset="100%" stop-color="#065f46"/>
                </linearGradient>
            </defs>
            <circle cx="400" cy="190" r="48" fill="#10b981" fill-opacity="0.2"/>
            <path d="M384 190h32M400 174v32" stroke="#34d399" stroke-width="4" stroke-linecap="round"/>
            <text x="400" y="270" font-family="Segoe UI, Roboto, Helvetica, Arial, sans-serif" font-size="20" font-weight="bold" fill="#f8fafc" text-anchor="middle">Warta &amp; Berita Kampus</text>
            <text x="400" y="300" font-family="Segoe UI, Roboto, Helvetica, Arial, sans-serif" font-size="13" fill="#94a3b8" text-anchor="middle">STIKes Panti Waluya Malang</text>
        </svg>';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }
}
