@extends('layouts.app')

@section('title', 'Edit Dokumen Kerja Sama')
@section('page-title', 'Form Edit Dokumen Kerja Sama')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.cooperations.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Dokumen</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
        
        <div class="border-b border-slate-200 pb-5 mb-6 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Perbarui Data Dokumen</h3>
                <p class="text-xs text-slate-500 mt-1">Ubah informasi atau ganti berkas PDF dokumen kerja sama.</p>
            </div>
            <span class="px-3 py-1 font-extrabold text-xs rounded-xl border {{ $cooperation->status_badge_class }}">
                Status: {{ $cooperation->computed_status }}
            </span>
        </div>

        <form action="{{ route('admin.cooperations.update', $cooperation->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 1. General Info Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                
                <!-- Partner Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Instansi / Lembaga Mitra *</label>
                    <input type="text" 
                           name="partner_name" 
                           value="{{ old('partner_name', $cooperation->partner_name) }}" 
                           required 
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                </div>

                <!-- Document Number -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Surat / Dokumen *</label>
                    <input type="text" 
                           name="document_number" 
                           value="{{ old('document_number', $cooperation->document_number) }}" 
                           required 
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all font-mono">
                </div>

                <!-- Document Type -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jenis Dokumen *</label>
                    <select name="document_type" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                        <option value="MoU" {{ old('document_type', $cooperation->document_type) == 'MoU' ? 'selected' : '' }}>MoU (Memorandum of Understanding)</option>
                        <option value="MoA" {{ old('document_type', $cooperation->document_type) == 'MoA' ? 'selected' : '' }}>MoA (Memorandum of Agreement)</option>
                        <option value="IA" {{ old('document_type', $cooperation->document_type) == 'IA' ? 'selected' : '' }}>IA (Implementation Arrangement)</option>
                    </select>
                </div>

                <!-- Level -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tingkat Wilayah Kerjasama *</label>
                    <select name="level" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                        <option value="Lokal" {{ old('level', $cooperation->level) == 'Lokal' ? 'selected' : '' }}>Lokal</option>
                        <option value="Nasional" {{ old('level', $cooperation->level) == 'Nasional' ? 'selected' : '' }}>Nasional</option>
                        <option value="Internasional" {{ old('level', $cooperation->level) == 'Internasional' ? 'selected' : '' }}>Internasional</option>
                    </select>
                </div>

            </div>

            <!-- Scope / Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul & Ruang Lingkup Kerja Sama *</label>
                <textarea name="title" rows="3" required class="w-full bg-slate-50 border border-slate-300 rounded-xl p-4 text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">{{ old('title', $cooperation->title) }}</textarea>
            </div>

            <!-- Scope Detail -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Rincian Deskripsi / Catatan Tambahan *</label>
                <textarea name="scope" rows="3" required class="w-full bg-slate-50 border border-slate-300 rounded-xl p-4 text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">{{ old('scope', $cooperation->scope) }}</textarea>
            </div>

            <!-- Dates & Status Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Mulai Berlaku *</label>
                    <input type="date" 
                           name="start_date" 
                           value="{{ old('start_date', $cooperation->start_date->format('Y-m-d')) }}" 
                           required 
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs font-semibold focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Berakhir *</label>
                    <input type="date" 
                           name="end_date" 
                           value="{{ old('end_date', $cooperation->end_date->format('Y-m-d')) }}" 
                           required 
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs font-semibold focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Keaktifan *</label>
                    <select name="status" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs font-semibold text-slate-800 focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                        <option value="Aktif" {{ old('status', $cooperation->status) == 'Aktif' ? 'selected' : '' }}>Aktif (Hijau)</option>
                        <option value="Akan Berakhir" {{ old('status', $cooperation->status) == 'Akan Berakhir' ? 'selected' : '' }}>Akan Berakhir (Kuning)</option>
                        <option value="Kedaluwarsa" {{ old('status', $cooperation->status) == 'Kedaluwarsa' ? 'selected' : '' }}>Kedaluwarsa (Merah)</option>
                        <option value="Dalam Proses Perpanjangan" {{ old('status', $cooperation->status) == 'Dalam Proses Perpanjangan' ? 'selected' : '' }}>Dalam Proses Perpanjangan (Biru)</option>
                    </select>
                </div>

            </div>

            <!-- Contact Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-200">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Kontak PIC Mitra</label>
                    <input type="text" 
                           name="contact_person" 
                           value="{{ old('contact_person', $cooperation->contact_person) }}" 
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email PIC / Instansi Mitra</label>
                    <input type="email" 
                           name="contact_email" 
                           value="{{ old('contact_email', $cooperation->contact_email) }}" 
                           class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                </div>
            </div>

            <!-- PDF Upload & Google Docs Link Dual Options -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-5" x-data="{ docSource: '{{ old('document_link', $cooperation->document_link) ? 'link' : 'file' }}' }">
                
                <div>
                    <label class="block text-xs font-extrabold text-slate-900 uppercase tracking-wider mb-2">Pilihan Berkas / Tautan Dokumen *</label>
                    <div class="grid grid-cols-2 gap-3 max-w-md mb-4">
                        <button type="button" 
                                @click="docSource = 'file'" 
                                :class="docSource === 'file' ? 'bg-stikes-600 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-100'" 
                                class="py-2.5 px-3 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-all">
                            <i class="fa-solid fa-file-pdf"></i>
                            <span>Unggah PDF Langsung</span>
                        </button>

                        <button type="button" 
                                @click="docSource = 'link'" 
                                :class="docSource === 'link' ? 'bg-stikes-600 text-white shadow-md' : 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-100'" 
                                class="py-2.5 px-3 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-all">
                            <i class="fa-solid fa-link"></i>
                            <span>Tautan Google Docs / Drive</span>
                        </button>
                    </div>

                    <!-- Option A: File Upload -->
                    <div x-show="docSource === 'file'" x-cloak>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Unggah PDF Baru (Opsional)</label>
                        @if($cooperation->file_path)
                            <div class="mb-3 p-3 bg-white rounded-xl border border-slate-200 flex items-center justify-between">
                                <span class="text-xs font-semibold text-slate-700">
                                    <i class="fa-solid fa-file-pdf text-rose-600 me-1.5"></i>
                                    Berkas Terunggah: {{ basename($cooperation->file_path) }}
                                </span>
                                <a href="{{ route('public.download', $cooperation->id) }}" target="_blank" class="text-xs font-bold text-stikes-700 hover:underline">Pratinjau</a>
                            </div>
                        @endif

                        <input type="file" 
                               name="file" 
                               accept="application/pdf" 
                               class="w-full text-xs text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-stikes-600 file:text-white hover:file:bg-stikes-700 transition-all cursor-pointer">
                    </div>

                    <!-- Option B: Google Docs / Drive Link -->
                    <div x-show="docSource === 'link'" x-cloak>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tautan URL Google Docs / Google Drive / External</label>
                        <div class="relative">
                            <i class="fa-brands fa-google-drive absolute left-4 top-3 text-slate-400 text-sm"></i>
                            <input type="url" 
                                   name="document_link" 
                                   value="{{ old('document_link', $cooperation->document_link) }}" 
                                   placeholder="https://docs.google.com/document/d/... atau https://drive.google.com/..." 
                                   class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-mono font-medium focus:ring-2 focus:ring-stikes-500 focus:outline-none">
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">Tempelkan link tautan Google Docs / Drive yang sudah disetting izin aksesnya.</p>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-200/80 flex items-center gap-3">
                    <input type="checkbox" 
                           id="is_public" 
                           name="is_public" 
                           value="1" 
                           {{ old('is_public', $cooperation->is_public) ? 'checked' : '' }} 
                           class="w-4 h-4 rounded text-stikes-600 focus:ring-stikes-500 border-slate-300">
                    <label for="is_public" class="text-xs font-semibold text-slate-800 cursor-pointer">
                        Izinkan Publik Mengunduh Berkas / Mengakses Tautan Dokumen Ini
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex items-center justify-end gap-3">
                <a href="{{ route('admin.cooperations.index') }}" class="px-5 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-stikes-600 hover:bg-stikes-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-stikes-600/30 transition-all">
                    <i class="fa-solid fa-check me-1.5"></i>
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
