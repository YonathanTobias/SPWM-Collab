@extends('layouts.app')

@section('title', 'Notifikasi Masa Berlaku')
@section('page-title', 'Pemantauan & Notifikasi Masa Berlaku Dokumen')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- Header Description Card -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-stikes-950 p-8 rounded-3xl text-white shadow-xl relative overflow-hidden">
        <div class="max-w-2xl relative z-10">
            <span class="px-3 py-1 bg-amber-500/20 text-amber-300 border border-amber-500/40 rounded-full text-xs font-extrabold uppercase tracking-wider mb-3 inline-block">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> Automatic Expiration Monitoring
            </span>
            <h2 class="text-2xl font-extrabold tracking-tight text-white mb-2">
                Peringatan Dini Masa Berlaku Dokumen Kerjasama
            </h2>
            <p class="text-xs text-slate-300 font-normal leading-relaxed">
                Sistem secara otomatis memantau seluruh tanggal berakhir dokumen MoU, MoA, dan IA. Notifikasi pengingat otomatis dikirimkan ke email Pengelola Kerjasama 90, 60, dan 30 hari sebelum tanggal kedaluwarsa.
            </p>
        </div>
    </div>

    <!-- Section 1: Dokumen Akan Berakhir dalam 90 Hari (Yellow Warning) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Dokumen Membutuhkan Perhatian / Perpanjangan (h-90 Hari)</h3>
                    <p class="text-xs text-slate-500">Daftar dokumen kerjasama yang akan berakhir dalam kurun waktu 90 hari mendatang.</p>
                </div>
            </div>
            <span class="px-3 py-1 bg-amber-100 text-amber-900 font-extrabold text-xs rounded-xl border border-amber-300">
                {{ $expiringIn90->count() }} Dokumen
            </span>
        </div>

        @if($expiringIn90->count() > 0)
            <div class="space-y-3">
                @foreach($expiringIn90 as $item)
                    <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-amber-50 transition-colors">
                        
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 text-[10px] font-extrabold rounded-md bg-amber-200 text-amber-900">
                                    {{ $item->document_type }}
                                </span>
                                <span class="text-xs font-mono font-semibold text-slate-600">{{ $item->document_number }}</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-slate-200 text-slate-700">
                                    Sisa {{ $item->days_remaining }} Hari Lagi
                                </span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-900">{{ $item->partner_name }}</h4>
                            <p class="text-xs text-slate-600 mt-0.5 line-clamp-1">{{ $item->title }}</p>
                            <p class="text-[11px] text-slate-500 mt-1">
                                <i class="fa-regular fa-calendar me-1"></i> Berlaku hingga: <span class="font-bold text-amber-900">{{ $item->end_date->format('d F Y') }}</span>
                            </p>
                        </div>

                        <!-- Manual Reminder Email Action -->
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('admin.cooperations.edit', $item->id) }}" class="px-3.5 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-xl transition-all">
                                Update / Perpanjang
                            </a>

                            <form action="{{ route('admin.notifications.sendReminder', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-md transition-all flex items-center gap-1.5">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    <span>Kirim Email Pengingat</span>
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center text-slate-500 bg-slate-50 rounded-2xl border border-slate-100">
                <i class="fa-solid fa-circle-check text-emerald-500 text-2xl mb-2 block"></i>
                <span class="text-xs font-semibold">Tidak ada dokumen yang akan berakhir dalam 90 hari mendatang. Seluruh perjanjian berada dalam masa aktif aman.</span>
            </div>
        @endif

    </div>

    <!-- Section 2: Dokumen Kedaluwarsa (Red Warning) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-800 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Dokumen Telah Kedaluwarsa (Expired)</h3>
                    <p class="text-xs text-slate-500">Arsip kerjasama yang masa berlakunya telah habis dan belum diperpanjang.</p>
                </div>
            </div>
            <span class="px-3 py-1 bg-rose-100 text-rose-900 font-extrabold text-xs rounded-xl border border-rose-300">
                {{ $expired->count() }} Dokumen
            </span>
        </div>

        @if($expired->count() > 0)
            <div class="space-y-3">
                @foreach($expired as $item)
                    <div class="p-5 rounded-2xl bg-rose-50/50 border border-rose-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-rose-50 transition-colors">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 text-[10px] font-extrabold rounded-md bg-rose-200 text-rose-900">
                                    {{ $item->document_type }}
                                </span>
                                <span class="text-xs font-mono font-semibold text-slate-600">{{ $item->document_number }}</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-rose-100 text-rose-800 border border-rose-300">
                                    Kedaluwarsa Sejak {{ $item->end_date->format('d/m/Y') }}
                                </span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-900">{{ $item->partner_name }}</h4>
                            <p class="text-xs text-slate-600 mt-0.5 line-clamp-1">{{ $item->title }}</p>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('admin.cooperations.edit', $item->id) }}" class="px-4 py-2 text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition-all">
                                <i class="fa-solid fa-rotate me-1"></i> Perbarui Masa Berlaku
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center text-slate-500 bg-slate-50 rounded-2xl border border-slate-100">
                <span class="text-xs font-semibold">Tidak ada dokumen kedaluwarsa.</span>
            </div>
        @endif

    </div>

</div>
@endsection
