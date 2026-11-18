@extends('layouts.app')

@section('title', 'Kelola Berita & Artikel Kampus')
@section('page-title', 'Pengelolaan Berita, Pengumuman & Artikel Kerja Sama')

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
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-200 mb-2">
                <i class="fa-solid fa-newspaper"></i>
                <span>Modul CMS Berita Kampus</span>
            </div>
            <h3 class="text-xl font-extrabold text-slate-900">Kelola Berita & Kegiatan Kerja Sama</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-xl leading-relaxed">
                Publikasikan dokumentasi kegiatan, penandatanganan MoU/MoA, seminar, kunjungan institusi, dan informasi penting lainnya agar dapat dibaca oleh publik dan mitra.
            </p>
        </div>

        <a href="{{ route('admin.posts.create') }}" class="px-5 py-3 bg-stikes-600 hover:bg-stikes-700 text-white font-bold text-xs rounded-2xl shadow-lg shadow-stikes-600/30 transition-all inline-flex items-center gap-2 flex-shrink-0 hover:-translate-y-0.5">
            <i class="fa-solid fa-pen-nib"></i>
            <span>Tulis Berita Baru</span>
        </a>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Artikel</p>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $totalCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status Tayang (Publik)</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">{{ $publishedCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-lg">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Draft / Arsip</p>
                <p class="text-2xl font-black text-amber-600 mt-1">{{ $draftCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center text-lg">
                <i class="fa-solid fa-box-archive"></i>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Filter Header -->
        <div class="p-5 border-b border-slate-200 bg-slate-50 flex flex-col md:flex-row gap-4 items-center justify-between">
            <form action="{{ route('admin.posts.index') }}" method="GET" class="w-full md:w-auto flex flex-wrap items-center gap-3">
                <div class="relative flex-1 sm:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul berita..." 
                           class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-stikes-600 focus:outline-none">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </div>

                <select name="status" onchange="this.form.submit()" class="py-2 px-3 text-xs bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-stikes-600 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Publikasi (Tayang)</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Draft</option>
                </select>

                @if(request('search') || request('status') !== null)
                    <a href="{{ route('admin.posts.index') }}" class="text-xs text-rose-600 hover:text-rose-800 font-semibold inline-flex items-center gap-1">
                        <i class="fa-solid fa-xmark"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </form>

            <span class="text-xs text-slate-500 font-semibold self-end md:self-center">{{ $posts->total() }} Berita Ditemukan</span>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-600 font-extrabold text-[11px] uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3.5 px-6">Sampul & Judul Berita</th>
                        <th class="py-3.5 px-6">Penulis</th>
                        <th class="py-3.5 px-6">Tanggal Rilis</th>
                        <th class="py-3.5 px-4 text-center">Dilihat</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($posts as $post)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Thumbnail & Title -->
                            <td class="py-4 px-6 font-bold text-slate-900 max-w-md">
                                <div class="flex items-center gap-3.5">
                                    <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-14 h-11 object-cover rounded-xl border border-slate-200 flex-shrink-0 shadow-xs">
                                    <div class="overflow-hidden">
                                        <a href="{{ route('public.posts.show', $post->slug) }}" target="_blank" class="hover:text-stikes-600 transition-colors line-clamp-1 text-xs">
                                            {{ $post->title }}
                                        </a>
                                        <p class="text-[10px] text-slate-400 font-normal line-clamp-1 mt-0.5">{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 80) }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Author -->
                            <td class="py-4 px-6 text-slate-600 text-[11px]">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-user-circle text-slate-400 text-sm"></i>
                                    <span>{{ $post->author ? $post->author->name : 'Admin Kampus' }}</span>
                                </div>
                            </td>

                            <!-- Date -->
                            <td class="py-4 px-6 text-slate-500 text-[11px]">
                                {{ $post->published_at ? $post->published_at->translatedFormat('d M Y') : 'Belum diatur' }}
                            </td>

                            <!-- Views -->
                            <td class="py-4 px-4 text-center font-mono text-[11px] text-slate-500">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-semibold">
                                    <i class="fa-solid fa-eye text-[10px] me-1 text-slate-400"></i>{{ number_format($post->views_count) }}
                                </span>
                            </td>

                            <!-- Status Toggle -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <form action="{{ route('admin.posts.togglePublish', $post->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="cursor-pointer" title="Klik untuk mengubah status">
                                        @if($post->is_published)
                                            <span class="px-2.5 py-1 font-extrabold text-[10px] rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1.5 hover:bg-emerald-200 transition-colors">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span>Tayang</span>
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 font-extrabold text-[10px] rounded-lg bg-amber-100 text-amber-800 border border-amber-300 inline-flex items-center gap-1.5 hover:bg-amber-200 transition-colors">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                <span>Draft</span>
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('public.posts.show', $post->slug) }}" target="_blank" class="p-2 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Lihat di Publik">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                    </a>

                                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="p-2 text-slate-500 hover:text-stikes-600 hover:bg-stikes-50 rounded-lg transition-colors" title="Edit Berita">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>

                                    <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Berita">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-newspaper text-3xl mb-2 text-slate-300 block"></i>
                                <p class="text-xs font-semibold">Belum ada berita atau artikel yang diterbitkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $posts->links() }}
            </div>
        @endif

    </div>

</div>
@endsection
