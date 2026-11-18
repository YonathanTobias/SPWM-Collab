@extends('layouts.public')

@section('title', 'Berita & Kegiatan Kerja Sama - STIKes Panti Waluya Malang')

@section('content')
<div>
    <!-- Hero Banner with Search -->
    <section class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-stikes-900 text-white overflow-hidden py-14 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl text-center mx-auto mb-8">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-md bg-stikes-500/10 border border-stikes-500/30 text-stikes-300 text-xs font-bold uppercase tracking-wider mb-3">
                    <i class="fa-solid fa-newspaper"></i>
                    <span>Kabar & Berita Kampus</span>
                </span>
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white leading-tight mb-3">
                    Warta & Kegiatan Kerja Sama
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 font-normal leading-relaxed">
                    Dokumentasi resmi penandatanganan kesepakatan, implementasi kegiatan tri dharma perguruan tinggi, seminar ilmiah, serta kolaborasi strategis STIKes Panti Waluya Malang.
                </p>
            </div>

            <!-- Search Form -->
            <div class="max-w-2xl mx-auto">
                <form action="{{ route('public.posts.index') }}" method="GET" class="relative group">
                    <div class="relative flex items-center">
                        <i class="fa-solid fa-magnifying-glass absolute left-5 text-slate-400 text-lg group-focus-within:text-stikes-400 transition-colors"></i>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari berita, agenda, atau nama mitra..." 
                               class="w-full pl-14 pr-28 py-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-stikes-400 focus:bg-slate-900/80 transition-all text-xs sm:text-sm shadow-2xl">
                        <button type="submit" class="absolute right-2.5 px-5 py-2.5 bg-stikes-600 hover:bg-stikes-500 text-white font-bold text-xs rounded-xl transition-all shadow-md">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- News Grid Container -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        @if(request('search'))
            <div class="mb-8 flex items-center justify-between p-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
                <p class="text-xs text-slate-600">
                    Menampilkan hasil pencarian untuk kata kunci: <strong class="text-slate-900">"{{ request('search') }}"</strong>
                </p>
                <a href="{{ route('public.posts.index') }}" class="text-xs font-bold text-rose-600 hover:underline inline-flex items-center gap-1">
                    <i class="fa-solid fa-xmark"></i> Hapus Filter
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
                <article class="bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col overflow-hidden group hover:-translate-y-1">
                    <!-- Thumbnail with Zoom on Hover -->
                    <a href="{{ route('public.posts.show', $post->slug) }}" class="relative h-52 overflow-hidden bg-slate-100 block">
                        <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-lg bg-slate-900/80 backdrop-blur-xs text-white border border-white/20 shadow-sm flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-[10px]"></i>
                                <span>{{ $post->published_at ? $post->published_at->translatedFormat('d M Y') : '-' }}</span>
                            </span>
                        </div>
                    </a>

                    <!-- Body Content -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <h2 class="text-base font-extrabold text-slate-900 group-hover:text-stikes-600 transition-colors leading-snug line-clamp-2">
                                <a href="{{ route('public.posts.show', $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">
                                {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 120) }}
                            </p>
                        </div>

                        <!-- Footer Meta -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                            <div class="flex items-center gap-1.5">
                                <i class="fa-solid fa-user-pen text-slate-400"></i>
                                <span>{{ $post->author ? $post->author->name : 'Humas STIKes' }}</span>
                            </div>

                            <a href="{{ route('public.posts.show', $post->slug) }}" class="font-bold text-stikes-600 hover:text-stikes-700 inline-flex items-center gap-1 text-xs group-hover:translate-x-0.5 transition-transform">
                                <span>Baca</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200">
                    <i class="fa-solid fa-newspaper text-4xl text-slate-300 mb-3 block"></i>
                    <h3 class="text-base font-bold text-slate-700">Belum Ada Berita yang Tersedia</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Silakan periksa kembali nanti atau gunakan kata kunci pencarian yang lain.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        @endif

    </section>
</div>
@endsection
