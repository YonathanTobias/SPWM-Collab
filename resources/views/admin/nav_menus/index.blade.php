@extends('layouts.app')

@section('title', 'Kelola Menu Navbar & Sub-Menu')
@section('page-title', 'Pengelolaan Menu & Sub-Menu Navigasi Utama')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto" x-data="{ createModal: false, editModal: false, editMenu: {} }">

    <!-- Header Description Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <h3 class="text-xl font-extrabold text-slate-900">Kelola Menu & Sub-Menu Navbar</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-xl">
                Fitur ini memungkinkan Super Admin untuk membuat menu utama maupun sub-menu dropdown yang tampil rapi pada header katalog publik.
            </p>
        </div>

        <button @click="createModal = true" class="px-5 py-3 bg-stikes-600 hover:bg-stikes-700 text-white font-bold text-xs rounded-2xl shadow-lg shadow-stikes-600/30 transition-all inline-flex items-center gap-2 flex-shrink-0">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Menu / Sub-Menu</span>
        </button>
    </div>

    <!-- Menu List Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Hirarki Menu Navigasi</span>
            <span class="text-xs text-slate-500 font-semibold">{{ $menus->count() }} Item Terdaftar</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-600 font-extrabold text-[11px] uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3.5 px-6">Urutan</th>
                        <th class="py-3.5 px-6">Tingkat & Nama Menu</th>
                        <th class="py-3.5 px-6">Tautan (URL)</th>
                        <th class="py-3.5 px-4">Tipe Tautan</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($menus as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $item->parent_id ? 'bg-slate-50/40' : '' }}">
                            <!-- Order -->
                            <td class="py-4 px-6 font-bold text-slate-500 font-mono">
                                #{{ $item->order }}
                            </td>

                            <!-- Title & Indentation for Sub-Menu -->
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <div class="flex items-center gap-2.5 {{ $item->parent_id ? 'ms-6' : '' }}">
                                    @if($item->parent_id)
                                        <span class="text-slate-400 font-normal">↳</span>
                                    @endif
                                    <div class="w-8 h-8 rounded-lg {{ $item->parent_id ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-stikes-50 text-stikes-700 border border-stikes-200' }} flex items-center justify-center text-xs">
                                        <i class="{{ $item->icon ?: 'fa-solid fa-link' }}"></i>
                                    </div>
                                    <div>
                                        <span>{{ $item->title }}</span>
                                        @if($item->parent)
                                            <span class="block text-[10px] text-slate-400 font-normal">Sub-menu dari: {{ $item->parent->title }}</span>
                                        @elseif($item->children->count() > 0)
                                            <span class="block text-[10px] text-stikes-600 font-bold">Induk Menu ({{ $item->children->count() }} Sub-menu)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- URL -->
                            <td class="py-4 px-6 text-slate-600 font-mono text-[11px] max-w-xs truncate">
                                {{ $item->url }}
                            </td>

                            <!-- Target External/Internal -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($item->is_external)
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-purple-50 text-purple-700 border border-purple-200 flex items-center gap-1 w-max">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        <span>Tab Baru</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-slate-100 text-slate-700 border border-slate-200 flex items-center gap-1 w-max">
                                        <i class="fa-solid fa-link"></i>
                                        <span>Internal Page</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Status Toggle -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <form action="{{ route('admin.nav_menus.toggle', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="cursor-pointer">
                                        @if($item->is_active)
                                            <span class="px-3 py-1 font-extrabold text-[11px] rounded-xl bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1.5 hover:bg-emerald-200 transition-colors">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span>Aktif</span>
                                            </span>
                                        @else
                                            <span class="px-3 py-1 font-extrabold text-[11px] rounded-xl bg-slate-200 text-slate-600 border border-slate-300 inline-flex items-center gap-1.5 hover:bg-slate-300 transition-colors">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                <span>Nonaktif</span>
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="editModal = true; editMenu = {{ json_encode($item) }}" class="p-2 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-slate-100 transition-colors" title="Edit Menu">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </button>

                                    <form action="{{ route('admin.nav_menus.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus menu navigasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-slate-100 transition-colors" title="Hapus Menu">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500">
                                Belum ada menu navbar. Klik "Tambah Menu / Sub-Menu" untuk menambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Menu Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div x-show="createModal" x-transition.opacity @click="createModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="createModal" x-transition.scale.95 class="relative bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <h3 class="text-lg font-extrabold text-slate-900">Tambah Menu / Sub-Menu Baru</h3>
                    <button @click="createModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
                </div>

                <form action="{{ route('admin.nav_menus.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Posisi Menu (Parent / Sub-Menu) *</label>
                        <select name="parent_id" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-stikes-500 focus:bg-white">
                            <option value="">[ Header Menu Utama ] (Top-Level Parent)</option>
                            @foreach($parentMenus as $parent)
                                <option value="{{ $parent->id }}">↳ Sub-Menu di bawah: {{ $parent->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Menu (Title) *</label>
                        <input type="text" name="title" required placeholder="Contoh: Website Utama / Panduan" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tautan URL *</label>
                        <input type="text" name="url" required placeholder="Contoh: https://stikespantiwaluya.ac.id atau #" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-medium font-mono focus:ring-2 focus:ring-stikes-500 focus:bg-white">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ikon FontAwesome</label>
                            <input type="text" name="icon" placeholder="fa-solid fa-globe" value="fa-solid fa-link" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-medium font-mono focus:ring-2 focus:ring-stikes-500 focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Urutan (Order)</label>
                            <input type="number" name="order" value="{{ $menus->count() + 1 }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white">
                        </div>
                    </div>

                    <div class="pt-2 space-y-2">
                        <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                            <input type="checkbox" name="is_external" value="1" checked class="rounded text-stikes-600">
                            <span>Buka di Tab Baru (External Link)</span>
                        </label>

                        <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded text-stikes-600">
                            <span>Tampilkan Menu (Status Aktif)</span>
                        </label>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3">
                        <button type="button" @click="createModal = false" class="px-4 py-2 bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-stikes-600 hover:bg-stikes-700 text-white font-bold text-xs rounded-xl shadow-md">Simpan Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Menu Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div x-show="editModal" x-transition.opacity @click="editModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="editModal" x-transition.scale.95 class="relative bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-200">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <h3 class="text-lg font-extrabold text-slate-900">Edit Menu / Sub-Menu</h3>
                    <button @click="editModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
                </div>

                <form :action="`/admin/nav-menus/${editMenu.id}`" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Posisi Menu (Parent / Sub-Menu) *</label>
                        <select name="parent_id" x-model="editMenu.parent_id" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-stikes-500 focus:bg-white">
                            <option value="">[ Header Menu Utama ] (Top-Level Parent)</option>
                            @foreach($parentMenus as $parent)
                                <option value="{{ $parent->id }}">↳ Sub-Menu di bawah: {{ $parent->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Menu (Title) *</label>
                        <input type="text" name="title" x-model="editMenu.title" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tautan URL *</label>
                        <input type="text" name="url" x-model="editMenu.url" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-medium font-mono focus:ring-2 focus:ring-stikes-500 focus:bg-white">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ikon FontAwesome</label>
                            <input type="text" name="icon" x-model="editMenu.icon" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-medium font-mono focus:ring-2 focus:ring-stikes-500 focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Urutan (Order)</label>
                            <input type="number" name="order" x-model="editMenu.order" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white">
                        </div>
                    </div>

                    <div class="pt-2 space-y-2">
                        <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                            <input type="checkbox" name="is_external" value="1" :checked="editMenu.is_external" class="rounded text-stikes-600">
                            <span>Buka di Tab Baru (External Link)</span>
                        </label>

                        <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" :checked="editMenu.is_active" class="rounded text-stikes-600">
                            <span>Tampilkan Menu (Status Aktif)</span>
                        </label>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3">
                        <button type="button" @click="editModal = false" class="px-4 py-2 bg-slate-200 text-slate-700 font-bold text-xs rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-stikes-600 hover:bg-stikes-700 text-white font-bold text-xs rounded-xl shadow-md">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
