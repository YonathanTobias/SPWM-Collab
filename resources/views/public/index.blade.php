@extends('layouts.public')

@section('title', 'Katalog Publik Dokumen Kerjasama - STIKes Panti Waluya Malang')

@section('content')
<div x-data="publicCatalog()">
    
    <!-- Hero Banner with Search -->
    <section class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-stikes-900 text-white overflow-hidden py-14 sm:py-20">
        
        <!-- Decorative Ambient Light -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-stikes-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl text-center mx-auto mb-8">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-stikes-500/10 border border-stikes-500/30 text-stikes-300 text-xs font-bold uppercase tracking-wider mb-3">
                    <i class="fa-solid fa-building-columns"></i>
                    <span>Katalog Terbuka Kerjasama Publik</span>
                </span>
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white leading-tight mb-3">
                    Daftar Dokumen Kerja Sama STIKes Panti Waluya Malang
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 font-normal leading-relaxed">
                    Sistem Katalog Terpusat Dokumen MoU, MoA, dan IA. Seluruh masyarakat, akademisi, dan mitra dapat langsung melihat daftar kerja sama serta mengunduh berkas resmi PDF tanpa harus melalukan login.
                </p>
            </div>

            <!-- Search Form -->
            <div class="max-w-2xl mx-auto">
                <form action="{{ route('public.index') }}" method="GET" class="relative group">
                    <div class="relative flex items-center">
                        <i class="fa-solid fa-magnifying-glass absolute left-5 text-slate-400 text-lg group-focus-within:text-stikes-400 transition-colors"></i>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari nama mitra, nomor surat, atau bidang kerja sama..." 
                               class="w-full pl-14 pr-32 py-4 bg-white/10 backdrop-blur-md text-white placeholder-slate-400 border border-white/20 rounded-2xl focus:outline-none focus:ring-2 focus:ring-stikes-400 focus:bg-white/15 text-sm transition-all shadow-2xl">
                        <button type="submit" class="absolute right-2 px-5 py-2.5 bg-stikes-600 hover:bg-stikes-500 text-white font-bold text-xs sm:text-sm rounded-xl transition-all shadow-md hover:shadow-stikes-500/40">
                            Cari
                        </button>
                    </div>

                    @if(request('level')) <input type="hidden" name="level" value="{{ request('level') }}"> @endif
                    @if(request('type')) <input type="hidden" name="type" value="{{ request('type') }}"> @endif
                    @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                </form>
            </div>

            <!-- Counter Stats Badges (MoU, MoA, IA) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-3xl mx-auto mt-10 pt-8 border-t border-slate-700/60">
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-4 text-center">
                    <div class="text-2xl sm:text-3xl font-extrabold text-emerald-400">{{ $totalMou }}</div>
                    <div class="text-xs font-medium text-slate-400 mt-1">MoU Kesepahaman</div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-4 text-center">
                    <div class="text-2xl sm:text-3xl font-extrabold text-blue-400">{{ $totalMoa }}</div>
                    <div class="text-xs font-medium text-slate-400 mt-1">MoA Perjanjian</div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-4 text-center">
                    <div class="text-2xl sm:text-3xl font-extrabold text-amber-400">{{ $totalIa }}</div>
                    <div class="text-xs font-medium text-slate-400 mt-1">IA Pelaksanaan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Public Directory Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Filter Controls Bar -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200/80 mb-8">
            <form action="{{ route('public.index') }}" method="GET" class="space-y-4">
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-stikes-600"></i>
                        <span>Filter & Filterisasi Katalog Publik</span>
                    </h3>

                    <div class="flex items-center gap-2 flex-wrap">
                        @if(request()->hasAny(['search', 'level', 'type', 'status']))
                            <a href="{{ route('public.index') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 inline-flex items-center gap-1 me-2">
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>Reset Filter</span>
                            </a>
                        @endif

                        <!-- View Switcher (Mode Grid vs Mode Tabel) -->
                        <div class="bg-slate-100 p-1 rounded-xl flex items-center gap-1 border border-slate-200 me-2">
                            <button type="button" 
                                    @click="viewMode = 'grid'; localStorage.setItem('sim_view_mode', 'grid')" 
                                    :class="viewMode === 'grid' ? 'bg-white text-stikes-700 shadow-sm font-extrabold' : 'text-slate-600 hover:text-slate-900 font-medium'" 
                                    class="px-3 py-1.5 rounded-lg text-xs flex items-center gap-1.5 transition-all"
                                    title="Tampilan Mode Grid Kartu">
                                <i class="fa-solid fa-table-cells-large"></i>
                                <span>Mode Grid</span>
                            </button>
                            <button type="button" 
                                    @click="viewMode = 'table'; localStorage.setItem('sim_view_mode', 'table')" 
                                    :class="viewMode === 'table' ? 'bg-white text-stikes-700 shadow-sm font-extrabold' : 'text-slate-600 hover:text-slate-900 font-medium'" 
                                    class="px-3 py-1.5 rounded-lg text-xs flex items-center gap-1.5 transition-all"
                                    title="Tampilan Mode Tabel Rapat">
                                <i class="fa-solid fa-table-list"></i>
                                <span>Mode Tabel</span>
                            </button>
                        </div>

                        <!-- Export Buttons for Entire/Filtered List -->
                        <a href="{{ route('public.exportExcel', request()->all()) }}" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5" title="Ekspor Rekapitulasi ke Excel (CSV)">
                            <i class="fa-solid fa-file-excel"></i>
                            <span>Ekspor Excel</span>
                        </a>

                        <a href="{{ route('public.exportPdf', request()->all()) }}" target="_blank" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5" title="Cetak Rekapitulasi PDF">
                            <i class="fa-solid fa-print"></i>
                            <span>Cetak PDF</span>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- Level Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tingkat Wilayah</label>
                        <select name="level" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                            <option value="">Semua Tingkat (Lokal/Nasional/Internasional)</option>
                            <option value="Lokal" {{ request('level') == 'Lokal' ? 'selected' : '' }}>Lokal</option>
                            <option value="Nasional" {{ request('level') == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                            <option value="Internasional" {{ request('level') == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                        </select>
                    </div>

                    <!-- Type Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Dokumen</label>
                        <select name="type" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                            <option value="">Semua Jenis (MoU/MoA/IA)</option>
                            <option value="MoU" {{ request('type') == 'MoU' ? 'selected' : '' }}>MoU (Nota Kesepahaman)</option>
                            <option value="MoA" {{ request('type') == 'MoA' ? 'selected' : '' }}>MoA (Perjanjian Kerja Sama)</option>
                            <option value="IA" {{ request('type') == 'IA' ? 'selected' : '' }}>IA (Implementation Arrangement)</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Keaktifan</label>
                        <select name="status" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                            <option value="">Semua Status</option>
                            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Akan Berakhir" {{ request('status') == 'Akan Berakhir' ? 'selected' : '' }}>Akan Berakhir</option>
                            <option value="Kedaluwarsa" {{ request('status') == 'Kedaluwarsa' ? 'selected' : '' }}>Kedaluwarsa</option>
                            <option value="Dalam Proses Perpanjangan" {{ request('status') == 'Dalam Proses Perpanjangan' ? 'selected' : '' }}>Dalam Perpanjangan</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Urutan Data</label>
                        <select name="sort" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru Ditambahkan</option>
                            <option value="expiring_soon" {{ request('sort') == 'expiring_soon' ? 'selected' : '' }}>Masa Berlaku Segera Berakhir</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Masa Berlaku Terlama</option>
                        </select>
                    </div>

                </div>
            </form>
        </div>

        <!-- Cooperation Data Container -->
        @if($cooperations->count() > 0)
            
            <!-- OPTION 1: Grid Cards View -->
            <div x-show="viewMode === 'grid'" x-cloak class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($cooperations as $item)
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/90 hover:border-stikes-400 hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            <!-- Header Badges -->
                            <div class="flex items-center justify-between gap-2 mb-4">
                                <span class="px-3 py-1 text-[11px] font-extrabold rounded-xl border {{ $item->level_badge_class }}">
                                    {{ $item->level }}
                                </span>

                                <span class="px-3 py-1 text-[11px] font-extrabold rounded-xl border {{ $item->status_badge_class }}">
                                    {{ $item->computed_status }}
                                </span>
                            </div>

                            <!-- Document Type & Partner Name -->
                            <div class="mb-3">
                                <span class="text-xs font-bold text-stikes-700 uppercase tracking-wider block mb-1">
                                    {{ $item->document_type }} • {{ $item->document_number }}
                                </span>
                                <h4 class="text-base font-bold text-slate-900 group-hover:text-stikes-700 transition-colors line-clamp-2">
                                    {{ $item->partner_name }}
                                </h4>
                            </div>

                            <p class="text-xs text-slate-600 font-medium mb-4 line-clamp-3 leading-relaxed">
                                {{ $item->title }}
                            </p>
                        </div>

                        <!-- Card Actions & Direct PDF / Google Docs Download Button -->
                        <div class="pt-4 border-t border-slate-100 space-y-3">
                            <div class="text-[11px] font-semibold text-slate-500 flex items-center justify-between">
                                <span><i class="fa-regular fa-calendar-check text-stikes-600 me-1"></i> Masa Berlaku:</span>
                                <span class="font-bold text-slate-700">{{ $item->start_date->format('d/m/Y') }} - {{ $item->end_date->format('d/m/Y') }}</span>
                            </div>

                            <div class="flex items-center gap-2 pt-1">
                                @if($item->is_public && $item->download_url)
                                    <a href="{{ $item->download_url }}" target="_blank" class="flex-1 py-2 px-3 text-center text-xs font-bold text-white bg-stikes-600 hover:bg-stikes-700 rounded-xl transition-all shadow-sm flex items-center justify-center gap-1.5">
                                        @if($item->document_link)
                                            <i class="fa-brands fa-google-drive"></i>
                                            <span>Buka Google Docs</span>
                                        @else
                                            <i class="fa-solid fa-download"></i>
                                            <span>Unduh PDF</span>
                                        @endif
                                    </a>
                                @else
                                    <span class="flex-1 py-2 px-3 text-center text-[11px] font-semibold text-slate-500 bg-slate-100 rounded-xl flex items-center justify-center gap-1">
                                        <i class="fa-solid fa-lock text-slate-400"></i>
                                        <span>Akses Internal</span>
                                    </span>
                                @endif

                                <button @click="openModal({{ $item->id }})" class="py-2 px-3.5 text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all border border-slate-200">
                                    Detail
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- OPTION 2: Compact High-Density Table View -->
            <div x-show="viewMode === 'table'" x-cloak class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-slate-300 text-[11px] font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-4 text-center w-12">No</th>
                                <th class="py-3.5 px-4">Instansi Mitra</th>
                                <th class="py-3.5 px-4">Jenis & Nomor</th>
                                <th class="py-3.5 px-4">Ruang Lingkup / Perihal</th>
                                <th class="py-3.5 px-4">Masa Berlaku</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-4 text-center">Aksi / Berkas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-xs font-medium text-slate-700">
                            @foreach($cooperations as $index => $item)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                        {{ $cooperations->firstItem() + $index }}
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $item->partner_name }}</span>
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-lg border {{ $item->level_badge_class }}">
                                                {{ $item->level }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-800 whitespace-nowrap">
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-stikes-100 text-stikes-800 rounded-md uppercase me-1">
                                            {{ $item->document_type }}
                                        </span>
                                        <span class="text-[11px] text-slate-500 font-mono block sm:inline">{{ $item->document_number }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 max-w-xs">
                                        <p class="line-clamp-2 text-slate-600 font-normal leading-relaxed">
                                            {{ $item->title }}
                                        </p>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap text-[11px] font-semibold text-slate-700">
                                        {{ $item->start_date->format('d/m/Y') }} - {{ $item->end_date->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-xl border {{ $item->status_badge_class }}">
                                            {{ $item->computed_status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if($item->is_public && $item->download_url)
                                                <a href="{{ $item->download_url }}" target="_blank" 
                                                   class="px-2.5 py-1.5 bg-stikes-600 hover:bg-stikes-700 text-white rounded-lg font-bold text-[11px] shadow-sm transition-all inline-flex items-center gap-1"
                                                   title="{{ $item->document_link ? 'Buka Google Docs' : 'Unduh PDF' }}">
                                                    @if($item->document_link)
                                                        <i class="fa-brands fa-google-drive"></i>
                                                    @else
                                                        <i class="fa-solid fa-download"></i>
                                                    @endif
                                                </a>
                                            @else
                                                <span class="px-2 py-1 bg-slate-100 text-slate-400 rounded-lg text-[10px] font-bold" title="Akses Internal">
                                                    <i class="fa-solid fa-lock"></i>
                                                </span>
                                            @endif

                                            <button @click="openModal({{ $item->id }})" 
                                                    class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-bold text-[11px] border border-slate-200 transition-all">
                                                Detail
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $cooperations->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm max-w-lg mx-auto">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-slate-400 text-2xl mb-4">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Dokumen Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500 mb-6">Tidak ada dokumen yang sesuai dengan kata kunci atau kriteria filter yang Anda pilih.</p>
                <a href="{{ route('public.index') }}" class="px-5 py-2.5 bg-stikes-600 text-white font-bold text-xs rounded-xl hover:bg-stikes-700 transition-colors">
                    Lihat Semua Dokumen Kerja Sama
                </a>
            </div>
        @endif

    </section>

    <!-- Detail & PDF Preview Modal -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="modalOpen" x-transition.scale.95 
                 class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-3xl border border-slate-200">
                
                <div x-show="loading" class="p-12 text-center">
                    <i class="fa-solid fa-circle-notch fa-spin text-3xl text-stikes-600 mb-3"></i>
                    <p class="text-xs font-semibold text-slate-500">Memuat rincian dokumen...</p>
                </div>

                <template x-if="!loading && cooperation">
                    <div>
                        <!-- Modal Header -->
                        <div class="px-6 py-5 bg-slate-900 text-white flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold bg-stikes-600 text-white rounded-full uppercase" x-text="cooperation.document_type"></span>
                                    <span class="text-xs text-slate-300 font-mono" x-text="cooperation.document_number"></span>
                                </div>
                                <h3 class="text-lg font-bold text-white leading-tight" x-text="cooperation.partner_name"></h3>
                            </div>
                            <button @click="modalOpen = false" class="text-slate-400 hover:text-white p-2 rounded-xl hover:bg-slate-800 transition-colors">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                            
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-3 py-1 text-xs font-extrabold rounded-xl border" :class="cooperation.level_badge" x-text="cooperation.level"></span>
                                <span class="px-3 py-1 text-xs font-extrabold rounded-xl border" :class="cooperation.status_badge" x-text="cooperation.status"></span>
                                <template x-if="!cooperation.is_public">
                                    <span class="px-3 py-1 text-xs font-bold rounded-xl bg-amber-100 text-amber-800 border border-amber-300">
                                        <i class="fa-solid fa-lock me-1"></i> Akses Internal (Ringkasan Sahaja)
                                    </span>
                                </template>
                            </div>

                            <div>
                                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Ruang Lingkup / Judul Kerja Sama</h4>
                                <p class="text-sm text-slate-800 font-medium leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-200" x-text="cooperation.scope"></p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                    <span class="text-xs font-bold text-slate-500 block mb-1">Masa Berlaku Dokumen</span>
                                    <p class="text-xs font-semibold text-slate-900">
                                        <span x-text="cooperation.start_date"></span> s/d <span x-text="cooperation.end_date"></span>
                                    </p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                    <span class="text-xs font-bold text-slate-500 block mb-1">Kontak Instansi / PIC</span>
                                    <p class="text-xs font-semibold text-slate-900" x-text="cooperation.contact_person"></p>
                                    <p class="text-[11px] text-slate-500" x-text="cooperation.contact_email"></p>
                                </div>
                            </div>

                            <!-- Document File / Link Action -->
                            <div class="p-5 rounded-2xl border bg-emerald-50/80 border-emerald-200">
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-stikes-600 text-white flex items-center justify-center text-xl flex-shrink-0">
                                            <i :class="cooperation.has_external_link ? 'fa-brands fa-google-drive' : 'fa-solid fa-file-pdf'"></i>
                                        </div>
                                        <div>
                                            <h5 class="text-xs font-bold text-slate-900" x-text="cooperation.has_external_link ? 'Tautan Resmi Google Docs / Drive' : 'Berkas Dokumen Resmi PDF'"></h5>
                                            <p class="text-[11px] text-slate-600" x-show="cooperation.is_public">Dokumen ini telah disetujui untuk diakses secara bebas oleh publik.</p>
                                            <p class="text-[11px] text-amber-700" x-show="!cooperation.is_public">Dokumen ini bersifat internal. Hanya ringkasan yang ditampilkan untuk publik.</p>
                                        </div>
                                    </div>

                                    <template x-if="cooperation.is_public && cooperation.has_file">
                                        <a :href="cooperation.download_url" target="_blank" class="w-full sm:w-auto px-5 py-2.5 bg-stikes-600 hover:bg-stikes-700 text-white font-bold text-xs rounded-xl transition-all shadow-md text-center inline-flex items-center justify-center gap-2">
                                            <i :class="cooperation.has_external_link ? 'fa-brands fa-google-drive' : 'fa-solid fa-download'"></i>
                                            <span x-text="cooperation.has_external_link ? 'Buka Google Docs' : 'Unduh Dokumen PDF'"></span>
                                        </a>
                                    </template>
                                </div>
                            </div>

                        </div>

                        <div class="px-6 py-4 bg-slate-100 flex justify-end">
                            <button @click="modalOpen = false" class="px-5 py-2 bg-slate-300 hover:bg-slate-400 text-slate-800 font-bold text-xs rounded-xl transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>

</div>
@endsection
