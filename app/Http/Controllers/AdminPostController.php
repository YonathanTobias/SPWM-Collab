<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminPostController extends Controller
{
    /**
     * Display a listing of news articles.
     */
    public function index(Request $request)
    {
        $query = Post::with('author');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->input('status') !== '') {
            $query->where('is_published', $request->input('status') === '1');
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $totalCount = Post::count();
        $publishedCount = Post::where('is_published', true)->count();
        $draftCount = Post::where('is_published', false)->count();

        return view('admin.posts.index', compact(
            'posts',
            'totalCount',
            'publishedCount',
            'draftCount'
        ));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ], [
            'title.required' => 'Judul berita wajib diisi.',
            'content.required' => 'Isi berita wajib diisi.',
            'thumbnail.image' => 'File thumbnail harus berupa gambar.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 5MB.',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('posts', 'public');
        }

        $slug = Post::generateUniqueSlug($validated['title']);
        $isPublished = $request->boolean('is_published', true);
        $publishedAt = $validated['published_at'] ? Carbon::parse($validated['published_at']) : ($isPublished ? Carbon::now() : null);

        Post::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'thumbnail' => $thumbnailPath,
            'author_id' => Auth::id(),
            'is_published' => $isPublished,
            'published_at' => $publishedAt,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Berita / artikel baru berhasil diterbitkan.');
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ], [
            'title.required' => 'Judul berita wajib diisi.',
            'content.required' => 'Isi berita wajib diisi.',
            'thumbnail.image' => 'File thumbnail harus berupa gambar.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 5MB.',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $post->thumbnail = $request->file('thumbnail')->store('posts', 'public');
        }

        if ($post->title !== $validated['title']) {
            $post->slug = Post::generateUniqueSlug($validated['title'], $post->id);
        }

        $post->title = $validated['title'];
        $post->excerpt = $validated['excerpt'];
        $post->content = $validated['content'];
        $post->is_published = $request->boolean('is_published');
        
        if ($validated['published_at']) {
            $post->published_at = Carbon::parse($validated['published_at']);
        } elseif ($post->is_published && is_null($post->published_at)) {
            $post->published_at = Carbon::now();
        }

        $post->save();

        return redirect()->route('admin.posts.index')->with('success', 'Berita / artikel berhasil diperbarui.');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil dihapus dari sistem.');
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish($id)
    {
        $post = Post::findOrFail($id);
        $post->is_published = !$post->is_published;
        if ($post->is_published && is_null($post->published_at)) {
            $post->published_at = Carbon::now();
        }
        $post->save();

        $statusText = $post->is_published ? 'dipublikasikan' : 'diarsipkan ke draft';
        return redirect()->route('admin.posts.index')->with('success', "Status berita berhasil {$statusText}.");
    }
}
