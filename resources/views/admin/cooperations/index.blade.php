@extends('layouts.app')

@section('title', 'Kelola Dokumen Kerjasama')
@section('page-title', 'Pengelolaan Dokumen Kerjasama (MoU / MoA / IA)')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Summary Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Dokumen</p>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $totalCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-folder"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status Aktif</p>
                <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $activeCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Akan Berakhir (h-90)</p>
                <h3 class="text-2xl font-extrabold text-amber-600 mt-1">{{ $expiringCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kedaluwarsa</p>
                <h3 class="text-2xl font-extrabold text-rose-600 mt-1">{{ $expiredCount }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

    </div>

    <!-- Global Setting Toggle Switch Banner for Public Catalog -->
    <div class="bg-slate-900 text-white p-5 rounded-2xl border border-slate-800 shadow-md flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-stikes-600 text-white flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-filter"></i>
            </div>
            <div>
                <h4 class="text-sm font-extrabold text-white">Filter Katalog Publik (Dokumen Kedaluwarsa)</h4>
                <p class="text-xs text-slate-300">
                    Aktifkan sakelar ini untuk membatasi katalog publik hanya menampilkan dokumen yang **masih berlaku / aktif**.
                </p>
            </div>
        </div>

        <form action="{{ route('admin.settings.toggleHideExpired') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2 cursor-pointer {{ $hideExpiredSetting ? 'bg-emerald-500 text-slate-950 hover:bg-emerald-400' : 'bg-slate-800 text-slate-300 border border-slate-700 hover:bg-slate-700 hover:text-white' }}" title="Klik untuk mengubah sakelar filter kedaluwarsa publik">
                <i class="fa-solid {{ $hideExpiredSetting ? 'fa-toggle-on text-lg' : 'fa-toggle-off text-lg text-slate-500' }}"></i>
                <span>Tampilan Publik: {{ $hideExpiredSetting ? 'HANYA BELUM KEDALUWARSA' : 'TAMPILKAN SEMUA' }}</span>
            </button>
        </form>
    </div>

    <!-- Edit Footer Text Setting Box -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3" x-data="{ openFooterEdit: false }">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-stikes-100 text-stikes-700 flex items-center justify-center text-base flex-shrink-0">
                    <i class="fa-solid fa-pen-nib"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Pengaturan Teks Footer Publik</h4>
                    <p class="text-[11px] text-slate-500">Ubah hak cipta dan tagline versi aplikasi yang tampil di bagian bawah katalog publik.</p>
                </div>
            </div>

            <button type="button" @click="openFooterEdit = !openFooterEdit" class="px-3.5 py-1.5 rounded-xl border border-slate-300 hover:bg-slate-50 text-xs font-bold text-slate-700 transition-all flex items-center gap-1.5 cursor-pointer">
                <i class="fa-solid" :class="openFooterEdit ? 'fa-chevron-up' : 'fa-pen'"></i>
                <span x-text="openFooterEdit ? 'Tutup Form' : 'Edit Teks Footer'"></span>
            </button>
        </div>

        <form x-show="openFooterEdit" x-cloak action="{{ route('admin.settings.updateFooter') }}" method="POST" class="pt-4 border-t border-slate-200 space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Teks Hak Cipta (Copyright)</label>
                    <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $footerCopyright) }}" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Teks Tagline & Versi</label>
                    <input type="text" name="footer_tagline" value="{{ old('footer_tagline', $footerTagline) }}" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-stikes-600 hover:bg-stikes-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Teks Footer</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Header Controls -->
        <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
            
            <form action="{{ route('admin.cooperations.index') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3">
                <div class="relative w-full sm:w-80">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari mitra / nomor dokumen..." 
                           class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                </div>

                <select name="type" onchange="this.form.submit()" class="w-full sm:w-auto bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
                    <option value="">Semua Jenis (MoU/MoA/IA)</option>
                    <option value="MoU" {{ request('type') == 'MoU' ? 'selected' : '' }}>MoU</option>
                    <option value="MoA" {{ request('type') == 'MoA' ? 'selected' : '' }}>MoA</option>
                    <option value="IA" {{ request('type') == 'IA' ? 'selected' : '' }}>IA</option>
                </select>

                <select name="status" onchange="this.form.submit()" class="w-full sm:w-auto bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700">
                    <option value="">Semua Status</option>
                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Akan Berakhir" {{ request('status') == 'Akan Berakhir' ? 'selected' : '' }}>Akan Berakhir</option>
                    <option value="Kedaluwarsa" {{ request('status') == 'Kedaluwarsa' ? 'selected' : '' }}>Kedaluwarsa</option>
                    <option value="Dalam Proses Perpanjangan" {{ request('status') == 'Dalam Proses Perpanjangan' ? 'selected' : '' }}>Dalam Perpanjangan</option>
                </select>
            </form>

            <div class="flex items-center gap-2 w-full md:w-auto flex-wrap">
                <a href="{{ route('public.exportExcel', request()->all()) }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all inline-flex items-center justify-center gap-1.5" title="Ekspor Rekapitulasi ke Excel (CSV)">
                    <i class="fa-solid fa-file-excel"></i>
                    <span>Ekspor Excel</span>
                </a>

                <a href="{{ route('public.exportPdf', request()->all()) }}" target="_blank" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-sm transition-all inline-flex items-center justify-center gap-1.5" title="Cetak Rekapitulasi PDF">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak PDF</span>
                </a>

                <a href="{{ route('admin.cooperations.create') }}" class="px-5 py-2.5 bg-stikes-600 hover:bg-stikes-700 text-white font-bold text-xs rounded-xl shadow-md shadow-stikes-600/30 transition-all inline-flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Dokumen</span>
                </a>
            </div>

        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 font-extrabold text-[11px] uppercase tracking-wider border-b border-slate-200">
                        <th class="py-4 px-6">Mitra & Nomor Surat</th>
                        <th class="py-4 px-4">Jenis & Tingkat</th>
                        <th class="py-4 px-4">Ruang Lingkup / Judul</th>
                        <th class="py-4 px-4">Masa Berlaku</th>
                        <th class="py-4 px-4">Status Auto-Update</th>
                        <th class="py-4 px-4">Akses Publik</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($cooperations as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Partner Name & Doc Number -->
                            <td class="py-4 px-6 font-semibold">
                                <div class="text-slate-900 font-bold text-sm">{{ $item->partner_name }}</div>
                                <div class="text-slate-500 font-mono text-[11px] mt-0.5">{{ $item->document_number }}</div>
                            </td>

                            <!-- Type & Level -->
                            <td class="py-4 px-4">
                                <div class="flex flex-col gap-1 items-start">
                                    <span class="px-2.5 py-0.5 font-bold rounded-lg bg-slate-900 text-white text-[10px]">
                                        {{ $item->document_type }}
                                    </span>
                                    <span class="px-2 py-0.5 font-extrabold text-[10px] rounded-lg border {{ $item->level_badge_class }}">
                                        {{ $item->level }}
                                    </span>
                                </div>
                            </td>

                            <!-- Title / Scope -->
                            <td class="py-4 px-4 max-w-xs">
                                <p class="text-slate-700 font-medium line-clamp-2">{{ $item->title }}</p>
                            </td>

                            <!-- Start & End Date -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="font-medium text-slate-800">{{ $item->start_date->format('d/m/Y') }}</div>
                                <div class="text-slate-500 text-[11px]">s/d {{ $item->end_date->format('d/m/Y') }}</div>
                            </td>

                            <!-- Visual Status Pill (Auto-Updated) -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="px-3 py-1 font-extrabold text-[11px] rounded-xl border inline-flex items-center gap-1.5 {{ $item->status_badge_class }}">
                                    @if($item->computed_status === 'Aktif')
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    @elseif($item->computed_status === 'Akan Berakhir')
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    @elseif($item->computed_status === 'Kedaluwarsa')
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                    @endif
                                    <span>{{ $item->computed_status }}</span>
                                </span>
                            </td>

                            <!-- Public Visibility Toggle Badge -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <form action="{{ route('admin.cooperations.togglePublic', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="cursor-pointer group" title="Klik untuk mengubah akses publik/internal">
                                        @if($item->is_public)
                                            <span class="px-2.5 py-1 font-bold text-[10px] rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 group-hover:bg-emerald-100 transition-colors flex items-center gap-1 w-max">
                                                <i class="fa-solid fa-eye"></i>
                                                <span>Publik (Terbuka)</span>
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 font-bold text-[10px] rounded-lg bg-amber-50 text-amber-700 border border-amber-200 group-hover:bg-amber-100 transition-colors flex items-center gap-1 w-max">
                                                <i class="fa-solid fa-lock"></i>
                                                <span>Internal (Terkunci)</span>
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($item->file_path)
                                        <a href="{{ route('public.download', $item->id) }}" target="_blank" title="Unduh PDF" class="p-2 rounded-lg text-slate-500 hover:text-stikes-700 hover:bg-slate-100 transition-colors">
                                            <i class="fa-solid fa-file-pdf text-base text-rose-600"></i>
                                        </a>
                                    @endif

                                    <a href="{{ route('admin.cooperations.edit', $item->id) }}" title="Edit Data" class="p-2 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-slate-100 transition-colors">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </a>

                                    <form action="{{ route('admin.cooperations.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Dokumen" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-slate-100 transition-colors">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-500">
                                <i class="fa-solid fa-folder-open text-3xl text-slate-300 mb-2 block"></i>
                                <span>Belum ada data dokumen kerja sama.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer / Pagination -->
        <div class="p-6 border-t border-slate-200">
            {{ $cooperations->links() }}
        </div>

    </div>

</div>
@endsection
