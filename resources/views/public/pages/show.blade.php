@extends('layouts.public')

@section('title', $page->title . ' - STIKes Panti Waluya Malang')

@section('content')
<div class="bg-slate-50 py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 overflow-x-auto whitespace-nowrap">
            <a href="{{ route('public.index') }}" class="hover:text-stikes-600 font-medium">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
            <span class="text-slate-800 font-bold truncate">{{ $page->title }}</span>
        </nav>

        <!-- Main Page Container Card -->
        <article class="bg-white p-8 sm:p-12 rounded-3xl border border-slate-200/90 shadow-sm space-y-8">
            
            <!-- Page Header -->
            <div class="border-b border-slate-100 pb-6 space-y-3">
                <span class="px-3 py-1 text-[11px] font-extrabold rounded-full bg-blue-50 text-blue-700 border border-blue-200 inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-file-lines text-[10px]"></i>
                    <span>Informasi Resmi Kampus</span>
                </span>
                
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    {{ $page->title }}
                </h1>

                <div class="flex items-center gap-2 text-[11px] text-slate-400">
                    <i class="fa-regular fa-clock"></i>
                    <span>Terakhir diperbarui: {{ $page->updated_at ? $page->updated_at->translatedFormat('d F Y, H:i') : '-' }} WIB</span>
                </div>
            </div>

            <!-- Page Body Content -->
            <div class="text-slate-700 text-sm leading-relaxed space-y-4 whitespace-pre-line font-normal">
{!! nl2br(e($page->content)) !!}
            </div>

            <!-- Page Footer Actions -->
            <div class="pt-8 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('public.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-stikes-600 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali ke Beranda</span>
                </a>

                <button type="button" onclick="window.print()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl inline-flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Halaman</span>
                </button>
            </div>

        </article>

    </div>
</div>
@endsection
