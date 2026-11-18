<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPageController extends Controller
{
    /**
     * Display a listing of custom static pages.
     */
    public function index(Request $request)
    {
        $query = Page::query();

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        $pages = $query->orderBy('title', 'asc')->paginate(10)->withQueryString();

        $totalPages = Page::count();
        $publishedCount = Page::where('is_published', true)->count();
        $draftCount = Page::where('is_published', false)->count();

        return view('admin.pages.index', compact(
            'pages',
            'totalPages',
            'publishedCount',
            'draftCount'
        ));
    }

    /**
     * Show the form for creating a new custom page.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created custom page in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'is_published' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul halaman wajib diisi.',
            'slug.unique' => 'Slug / URL ini sudah digunakan oleh halaman lain.',
            'content.required' => 'Konten halaman wajib diisi.',
        ]);

        $slug = !empty($validated['slug']) 
            ? Str::slug($validated['slug']) 
            : Page::generateUniqueSlug($validated['title']);

        Page::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'is_published' => $request->boolean('is_published', true),
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Halaman baru berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified custom page.
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified custom page in storage.
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'required|string',
            'is_published' => 'nullable|boolean',
        ], [
            'title.required' => 'Judul halaman wajib diisi.',
            'slug.unique' => 'Slug / URL ini sudah digunakan oleh halaman lain.',
            'content.required' => 'Konten halaman wajib diisi.',
        ]);

        $slug = !empty($validated['slug']) 
            ? Str::slug($validated['slug']) 
            : ($page->title !== $validated['title'] ? Page::generateUniqueSlug($validated['title'], $page->id) : $page->slug);

        $page->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Halaman berhasil diperbarui.');
    }

    /**
     * Remove the specified custom page from storage.
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Halaman berhasil dihapus.');
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish($id)
    {
        $page = Page::findOrFail($id);
        $page->update(['is_published' => !$page->is_published]);

        $statusText = $page->is_published ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.pages.index')->with('success', "Status tayang halaman berhasil {$statusText}.");
    }
}
