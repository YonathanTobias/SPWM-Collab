@extends('layouts.app')

@section('title', 'Kelola Halaman Statis')
@section('page-title', 'Pengelolaan Halaman Konten & Panduan Resmi')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-xs font-semibold shadow-sm animate-fade-in">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Header Description Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-bold border border-blue-200 mb-2">
                <i class="fa-solid fa-file-lines"></i>
                <span>Halaman Kustom Kampus</span>
            </div>
            <h3 class="text-xl font-extrabold text-slate-900">Kelola Halaman Informasi & Panduan</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-xl leading-relaxed">
                Buat halaman statis seperti <strong>Panduan Prosedur Kerja Sama</strong>, <strong>Profil Lembaga</strong>, atau <strong>Standar Layanan</strong>. Tautan halaman ini dapat langsung Anda hubungkan ke menu navbar.
            </p>
        </div>

        <a href="{{ route('admin.pages.create') }}" class="px-5 py-3 bg-stikes-600 hover:bg-stikes-700 text-white font-bold text-xs rounded-2xl shadow-lg shadow-stikes-600/30 transition-all inline-flex items-center gap-2 flex-shrink-0 hover:-translate-y-0.5">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Halaman Baru</span>
        </a>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Halaman</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $totalPages }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Halaman Aktif</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">{{ $publishedCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-lg">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Draft / Nonaktif</p>
                <p class="text-2xl font-black text-amber-600 mt-1">{{ $draftCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center text-lg">
                <i class="fa-solid fa-file-circle-xmark"></i>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden" x-data="{
        copiedSlug: '',
        copyLink(url, slug) {
            navigator.clipboard.writeText(url);
            this.copiedSlug = slug;
            setTimeout(() => { this.copiedSlug = ''; }, 2000);
        }
    }">
        
        <div class="p-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Daftar Halaman Resmi</span>
            <span class="text-xs text-slate-500 font-semibold">{{ $pages->total() }} Halaman</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-600 font-extrabold text-[11px] uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3.5 px-6">Judul Halaman</th>
                        <th class="py-3.5 px-6">Tautan Halaman (Slug)</th>
                        <th class="py-3.5 px-6">Terakhir Diperbarui</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($pages as $page)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Title -->
                            <td class="py-4 px-6 font-bold text-slate-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-file-lines"></i>
                                    </div>
                                    <a href="{{ route('public.pages.show', $page->slug) }}" target="_blank" class="hover:text-stikes-600 transition-colors">
                                        {{ $page->title }}
                                    </a>
                                </div>
                            </td>

                            <!-- URL with Copy Button -->
                            <td class="py-4 px-6 font-mono text-[11px] text-slate-600">
                                <div class="inline-flex items-center gap-2 px-2.5 py-1 bg-slate-100 border border-slate-200 rounded-lg">
                                    <span class="select-all">/page/{{ $page->slug }}</span>
                                    <button type="button" @click="copyLink('{{ url('/page/' . $page->slug) }}', '{{ $page->slug }}')" 
                                            class="text-slate-400 hover:text-stikes-600 transition-colors" title="Salin Tautan Lengkap">
                                        <i class="fa-solid text-[10px]" :class="copiedSlug === '{{ $page->slug }}' ? 'fa-check text-emerald-600' : 'fa-copy'"></i>
                                    </button>
                                </div>
                                <span x-show="copiedSlug === '{{ $page->slug }}'" class="text-[9px] text-emerald-600 font-bold block mt-0.5" x-cloak>
                                    Tautan berhasil disalin!
                                </span>
                            </td>

                            <!-- Updated At -->
                            <td class="py-4 px-6 text-slate-500 text-[11px]">
                                {{ $page->updated_at ? $page->updated_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>

                            <!-- Status Toggle -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <form action="{{ route('admin.pages.togglePublish', $page->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="cursor-pointer" title="Klik untuk mengubah status">
                                        @if($page->is_published)
                                            <span class="px-2.5 py-1 font-extrabold text-[10px] rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1.5 hover:bg-emerald-200 transition-colors">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span>Aktif</span>
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 font-extrabold text-[10px] rounded-lg bg-slate-200 text-slate-600 border border-slate-300 inline-flex items-center gap-1.5 hover:bg-slate-300 transition-colors">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                <span>Nonaktif</span>
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('public.pages.show', $page->slug) }}" target="_blank" class="p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Buka Halaman">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>

                                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Halaman">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>

                                    <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus halaman \'{{ addslashes($page->title) }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Halaman">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-file-circle-question text-3xl mb-2 text-slate-300 block"></i>
                                <p class="text-xs font-semibold">Belum ada halaman statis yang dibuat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pages->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $pages->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
