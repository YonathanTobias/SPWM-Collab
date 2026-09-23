@extends('layouts.app')

@section('title', 'Visualisasi Data & Rekapitulasi Akreditasi')
@section('page-title', 'Visualisasi Data & Rekapitulasi Akreditasi')

@section('content')
<div class="space-y-8">

    <!-- Hero Accreditation Export Banner -->
    <div class="bg-gradient-to-br from-stikes-900 via-stikes-800 to-slate-900 p-6 sm:p-8 rounded-3xl text-white shadow-xl flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="max-w-2xl">
            <span class="px-3 py-1 bg-white/10 text-stikes-200 border border-white/20 rounded-full text-xs font-bold uppercase tracking-wider mb-3 inline-block">
                <i class="fa-solid fa-graduation-cap me-1"></i> Borang Akreditasi LAM-PTKes / BAN-PT
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mb-2">
                Visualisasi Data & Rekapitulasi Akreditasi
            </h2>
            <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal">
                Unduh rekapitulasi data kerjasama institusi dan prodi secara cepat sesuai format borang akreditasi nasional dan internasional STIKes Panti Waluya Malang.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 flex-shrink-0">
            <a href="{{ route('dashboard.exportExcel') }}" class="w-full sm:w-auto px-5 py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-xs rounded-2xl shadow-lg shadow-emerald-500/30 transition-all inline-flex items-center justify-center gap-2">
                <i class="fa-solid fa-file-excel text-base"></i>
                <span>Ekspor Excel / CSV Akreditasi</span>
            </a>

            <a href="{{ route('dashboard.exportPdf') }}" target="_blank" class="w-full sm:w-auto px-5 py-3 bg-white/10 hover:bg-white/20 text-white border border-white/30 font-bold text-xs rounded-2xl transition-all inline-flex items-center justify-center gap-2">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Laporan PDF</span>
            </a>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Dokumen</div>
            <div class="text-3xl font-extrabold text-slate-900 mt-2">{{ $totalCount }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Seluruh arsip kerjasama</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tingkat Internasional</div>
            <div class="text-3xl font-extrabold text-purple-600 mt-2">{{ $internasionalCount }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Mitra luar negeri</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tingkat Nasional</div>
            <div class="text-3xl font-extrabold text-blue-600 mt-2">{{ $nasionalCount }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Instansi dalam negeri</div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tingkat Lokal</div>
            <div class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $lokalCount }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Wilayah Malang & Jatim</div>
        </div>
    </div>

    <!-- Graphical Charts Grid (Chart.js) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Chart 1: Distribution by Document Type -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-stikes-600"></i>
                    <span>Proporsi Jenis Dokumen (MoU / MoA / IA)</span>
                </h3>
            </div>
            <div class="h-64 relative flex items-center justify-center">
                <canvas id="typeChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Distribution by Status -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-chart-bar text-stikes-600"></i>
                    <span>Distribusi Status Keaktifan Dokumen</span>
                </h3>
            </div>
            <div class="h-64 relative flex items-center justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Expiring Soon & Recent Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Table 1: Expiring Soon -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-amber-500"></i>
                    <span>Masa Berlaku Berakhir Lebih Awal (h-90)</span>
                </h3>
                <a href="{{ route('admin.notifications') }}" class="text-xs font-bold text-stikes-600 hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                @forelse($expiringSoonList as $item)
                    <div class="p-3.5 rounded-2xl bg-amber-50/60 border border-amber-200/60 flex items-center justify-between gap-3 text-xs">
                        <div>
                            <div class="font-bold text-slate-900">{{ $item->partner_name }}</div>
                            <div class="text-[11px] text-slate-500">{{ $item->document_type }} • {{ $item->document_number }}</div>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-0.5 font-bold text-[10px] bg-amber-200 text-amber-900 rounded-md">
                                s/d {{ $item->end_date->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-xs text-slate-500 bg-slate-50 rounded-2xl">
                        Tidak ada dokumen yang akan berakhir dalam 90 hari.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Table 2: Recent Cooperations -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-folder-plus text-stikes-600"></i>
                    <span>Dokumen Kerjasama Terbaru</span>
                </h3>
                <a href="{{ route('admin.cooperations.index') }}" class="text-xs font-bold text-stikes-600 hover:underline">Kelola Data</a>
            </div>

            <div class="space-y-3">
                @foreach($recentCooperations as $item)
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between gap-3 text-xs">
                        <div>
                            <div class="font-bold text-slate-900">{{ $item->partner_name }}</div>
                            <div class="text-[11px] text-slate-500 line-clamp-1">{{ $item->title }}</div>
                        </div>
                        <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-lg border whitespace-nowrap {{ $item->level_badge_class }}">
                            {{ $item->level }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // Chart 1: Document Type Doughnut Chart
        const ctxType = document.getElementById('typeChart').getContext('2d');
        new Chart(ctxType, {
            type: 'doughnut',
            data: {
                labels: ['MoU (Nota Kesepahaman)', 'MoA (Perjanjian Kerjasama)', 'IA (Implementation Arrangement)'],
                datasets: [{
                    data: [{{ $mouCount }}, {{ $moaCount }}, {{ $iaCount }}],
                    backgroundColor: ['#059669', '#2563eb', '#9333ea'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { family: 'Plus Jakarta Sans', size: 11, weight: 'bold' } }
                    }
                }
            }
        });

        // Chart 2: Status Bar Chart
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'bar',
            data: {
                labels: ['Aktif', 'Akan Berakhir', 'Kedaluwarsa', 'Dalam Perpanjangan'],
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: [{{ $aktifCount }}, {{ $akanBerakhirCount }}, {{ $kedaluwarsaCount }}, {{ $perpanjanganCount }}],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#0284c7'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });

    });
</script>
@endpush
