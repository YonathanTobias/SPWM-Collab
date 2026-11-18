@extends('layouts.public')

@section('title', $post->title . ' - STIKes Panti Waluya Malang')

@section('content')
<div class="bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-8 overflow-x-auto whitespace-nowrap">
            <a href="{{ route('public.index') }}" class="hover:text-stikes-600 font-medium">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
            <a href="{{ route('public.posts.index') }}" class="hover:text-stikes-600 font-medium">Berita & Kegiatan</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
            <span class="text-slate-800 font-bold truncate max-w-xs sm:max-w-md">{{ $post->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            <!-- Main Article Column (8 Cols) -->
            <article class="lg:col-span-8 bg-white p-6 sm:p-10 rounded-3xl border border-slate-200/90 shadow-sm space-y-6">

                <!-- Meta Header -->
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
                        <span class="px-3 py-1 font-bold rounded-full bg-stikes-50 text-stikes-700 border border-stikes-200 inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-tag text-[10px]"></i>
                            <span>Warta Kerja Sama</span>
                        </span>

                        <span class="inline-flex items-center gap-1.5">
                            <i class="fa-regular fa-calendar text-slate-400"></i>
                            <span>{{ $post->published_at ? $post->published_at->translatedFormat('d F Y, H:i') : '-' }} WIB</span>
                        </span>

                        <span class="inline-flex items-center gap-1.5">
                            <i class="fa-regular fa-user text-slate-400"></i>
                            <span>{{ $post->author ? $post->author->name : 'Humas STIKes' }}</span>
                        </span>

                        <span class="inline-flex items-center gap-1.5 ms-auto">
                            <i class="fa-regular fa-eye text-slate-400"></i>
                            <span>{{ number_format($post->views_count) }}x dibaca</span>
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        {{ $post->title }}
                    </h1>
                </div>

                <!-- Featured Image Banner -->
                <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-xs">
                    <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full max-h-[460px] object-cover">
                </div>

                <!-- Article Body Content -->
                <div class="prose max-w-none text-slate-700 text-sm leading-relaxed space-y-4 pt-4 border-t border-slate-100 whitespace-pre-line font-normal">
{!! nl2br(e($post->content)) !!}
                </div>

                <!-- Share & Back Actions -->
                <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('public.posts.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-stikes-600 transition-colors">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali ke Berita Lainnya</span>
                    </a>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-slate-500">Bagikan:</span>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' - ' . url()->current()) }}" target="_blank" 
                           class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center text-xs hover:bg-emerald-600 hover:text-white transition-colors" title="Bagikan ke WhatsApp">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                        </a>
                        <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" 
                           class="w-8 h-8 rounded-full bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center text-xs hover:bg-sky-600 hover:text-white transition-colors" title="Bagikan ke Telegram">
                            <i class="fa-brands fa-telegram text-sm"></i>
                        </a>
                    </div>
                </div>

            </article>

            <!-- Sidebar Column (4 Cols) -->
            <aside class="lg:col-span-4 space-y-6">

                <!-- Related / Recent News Card -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="fa-solid fa-newspaper text-stikes-600"></i>
                            <span>Berita Lainnya</span>
                        </h3>
                        <a href="{{ route('public.posts.index') }}" class="text-xs font-bold text-stikes-600 hover:underline">Semua</a>
                    </div>

                    <div class="space-y-4">
                        @forelse($relatedPosts as $rel)
                            <a href="{{ route('public.posts.show', $rel->slug) }}" class="flex items-start gap-3 group">
                                <img src="{{ $rel->thumbnail_url }}" alt="{{ $rel->title }}" class="w-20 h-16 object-cover rounded-xl border border-slate-200 flex-shrink-0 group-hover:opacity-90 transition-opacity">
                                <div class="overflow-hidden">
                                    <h4 class="text-xs font-bold text-slate-900 group-hover:text-stikes-600 transition-colors line-clamp-2 leading-snug">
                                        {{ $rel->title }}
                                    </h4>
                                    <span class="text-[10px] text-slate-400 mt-1 block">
                                        {{ $rel->published_at ? $rel->published_at->translatedFormat('d M Y') : '-' }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <p class="text-xs text-slate-400">Belum ada berita lainnya.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Campus Portal Info Box -->
                <div class="bg-gradient-to-br from-stikes-900 to-slate-900 p-6 rounded-3xl text-white space-y-3 shadow-md">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-stikes-400 text-lg">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                    <h4 class="text-sm font-bold">Kemitraan STIKes Panti Waluya</h4>
                    <p class="text-xs text-slate-300 leading-relaxed font-normal">
                        Tertarik menjalin kerja sama Tridharma Perguruan Tinggi dengan STIKes Panti Waluya Malang? Silakan ajukan melalui kontak resmi kami.
                    </p>
                    <a href="mailto:kerjasama@stikespantiwaluya.ac.id" class="inline-flex items-center gap-2 text-xs font-bold text-stikes-400 hover:text-white transition-colors pt-1">
                        <span>Hubungi Bagian Kerja Sama</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

            </aside>

        </div>

    </div>
</div>
@endsection
