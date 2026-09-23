<?php

namespace App\Http\Controllers;

use App\Models\NavMenu;
use Illuminate\Http\Request;

class NavMenuController extends Controller
{
    /**
     * Display menu management panel.
     */
    public function index()
    {
        $menus = NavMenu::with(['parent', 'children'])
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Get parent candidates (top-level menus only)
        $parentMenus = NavMenu::whereNull('parent_id')->orderBy('order', 'asc')->get();

        return view('admin.nav_menus.index', compact('menus', 'parentMenus'));
    }

    /**
     * Store new menu item (Parent or Sub-Menu).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:nav_menus,id',
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'icon' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'is_external' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $validated['parent_id'] = $request->input('parent_id') ?: null;
        $validated['is_external'] = $request->has('is_external');
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? (NavMenu::max('order') + 1);

        NavMenu::create($validated);

        return redirect()->route('admin.nav_menus.index')
            ->with('success', 'Menu navbar baru berhasil ditambahkan.');
    }

    /**
     * Update existing menu item.
     */
    public function update(Request $request, $id)
    {
        $menu = NavMenu::findOrFail($id);

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:nav_menus,id',
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:500',
            'icon' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
            'is_external' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Prevent setting parent_id to itself
        if ($request->input('parent_id') == $id) {
            $validated['parent_id'] = null;
        } else {
            $validated['parent_id'] = $request->input('parent_id') ?: null;
        }

        $validated['is_external'] = $request->has('is_external');
        $validated['is_active'] = $request->has('is_active');

        $menu->update($validated);

        return redirect()->route('admin.nav_menus.index')
            ->with('success', "Menu '{$menu->title}' berhasil diperbarui.");
    }

    /**
     * Delete menu item.
     */
    public function destroy($id)
    {
        $menu = NavMenu::findOrFail($id);
        $menu->delete();

        return redirect()->route('admin.nav_menus.index')
            ->with('success', 'Menu navbar telah dihapus.');
    }

    /**
     * Quick toggle active status.
     */
    public function toggleStatus($id)
    {
        $menu = NavMenu::findOrFail($id);
        $menu->update(['is_active' => !$menu->is_active]);

        $statusText = $menu->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.nav_menus.index')
            ->with('success', "Status menu '{$menu->title}' telah {$statusText}.");
    }
}
