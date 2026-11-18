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
        // Fetch hierarchical tree (Level 1 -> Level 2 -> Level 3)
        $rootMenus = NavMenu::with(['children.children', 'parent.parent'])
            ->whereNull('parent_id')
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $orderedMenus = collect();
        foreach ($rootMenus as $root) {
            $orderedMenus->push($root);
            foreach ($root->children as $child) {
                $orderedMenus->push($child);
                foreach ($child->children as $grandchild) {
                    $orderedMenus->push($grandchild);
                }
            }
        }

        // Catch any orphan menus (e.g. if parent was corrupted or detached)
        $orphanMenus = NavMenu::with(['parent.parent'])
            ->whereNotNull('parent_id')
            ->whereNotIn('id', $orderedMenus->pluck('id'))
            ->get();

        $menus = $orderedMenus->concat($orphanMenus);

        // Parent options for creating/editing:
        // 1. Level 1 (Menu Utama) -> for creating Sub-Menu (Level 2)
        $level1Menus = NavMenu::whereNull('parent_id')
            ->orderBy('order', 'asc')
            ->get();

        // 2. Level 2 (Sub-Menu) -> for creating Sub-Sub-Menu (Level 3)
        $level2Menus = NavMenu::whereNotNull('parent_id')
            ->whereHas('parent', function ($q) {
                $q->whereNull('parent_id');
            })
            ->with('parent')
            ->orderBy('order', 'asc')
            ->get();

        // Statistics
        $totalCount = NavMenu::count();
        $level1Count = NavMenu::whereNull('parent_id')->count();
        $level2Count = $level2Menus->count();
        $level3Count = $totalCount - $level1Count - $level2Count;

        return view('admin.nav_menus.index', compact(
            'menus',
            'level1Menus',
            'level2Menus',
            'totalCount',
            'level1Count',
            'level2Count',
            'level3Count'
        ));
    }

    /**
     * Store new menu item (Menu Utama, Sub-Menu, or Sub-Sub-Menu).
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
        ], [
            'title.required' => 'Nama menu wajib diisi.',
            'url.required' => 'Tautan URL menu wajib diisi.',
            'parent_id.exists' => 'Induk menu yang dipilih tidak valid.',
        ]);

        $parentId = $request->input('parent_id') ?: null;

        // Depth check: max 3 levels (Menu Utama -> Sub-Menu -> Sub-Sub-Menu)
        if ($parentId) {
            $parent = NavMenu::with('parent.parent')->find($parentId);
            if ($parent && $parent->parent && !is_null($parent->parent->parent_id)) {
                return back()->with('error', 'Batas maksimal kedalaman navigasi adalah 3 tingkat (Menu Utama › Sub-Menu › Sub-Sub-Menu).');
            }
        }

        $validated['parent_id'] = $parentId;
        $validated['is_external'] = $request->has('is_external');
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? (NavMenu::max('order') + 1);

        NavMenu::create($validated);

        return redirect()->route('admin.nav_menus.index')
            ->with('success', 'Menu navigasi baru berhasil ditambahkan.');
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
        ], [
            'title.required' => 'Nama menu wajib diisi.',
            'url.required' => 'Tautan URL menu wajib diisi.',
            'parent_id.exists' => 'Induk menu yang dipilih tidak valid.',
        ]);

        $parentId = $request->input('parent_id') ?: null;

        // Prevent setting parent to itself
        if ($parentId == $id) {
            return back()->with('error', 'Menu tidak dapat menjadi induk bagi dirinya sendiri.');
        }

        // Prevent setting parent to its own descendants
        if ($parentId) {
            $descendantIds = $this->getDescendantIds($menu);
            if (in_array($parentId, $descendantIds)) {
                return back()->with('error', 'Tidak dapat memilih sub-menu turunan sebagai induk menu.');
            }

            // Depth check: max 3 levels
            $targetParent = NavMenu::with('parent.parent')->find($parentId);
            if ($targetParent && $targetParent->parent && !is_null($targetParent->parent->parent_id)) {
                return back()->with('error', 'Batas maksimal kedalaman navigasi adalah 3 tingkat (Menu Utama › Sub-Menu › Sub-Sub-Menu).');
            }
        }

        $validated['parent_id'] = $parentId;
        $validated['is_external'] = $request->has('is_external');
        $validated['is_active'] = $request->has('is_active');

        $menu->update($validated);

        return redirect()->route('admin.nav_menus.index')
            ->with('success', "Menu '{$menu->title}' berhasil diperbarui.");
    }

    /**
     * Delete menu item (cascading deletes children).
     */
    public function destroy($id)
    {
        $menu = NavMenu::findOrFail($id);
        $title = $menu->title;
        $menu->delete();

        return redirect()->route('admin.nav_menus.index')
            ->with('success', "Menu '{$title}' dan seluruh sub-menu di bawahnya telah dihapus.");
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

    /**
     * Helper to collect all descendant IDs of a menu.
     */
    private function getDescendantIds(NavMenu $menu): array
    {
        $ids = [];
        $children = NavMenu::where('parent_id', $menu->id)->get();
        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }
        return $ids;
    }
}
