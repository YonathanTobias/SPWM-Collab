@extends('layouts.app')

@section('title', 'Tambah Halaman Baru')
@section('page-title', 'Buat Halaman Informasi / Panduan Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Halaman</span>
        </a>
    </div>

    <!-- Error Alert -->
    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs font-semibold shadow-sm space-y-1">
            <div class="flex items-center gap-2 font-bold text-rose-900">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Terdapat kesalahan pada input:</span>
            </div>
            <ul class="list-disc list-inside ps-2 font-normal text-rose-700">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm" x-data="{
        title: '{{ old('title') }}',
        slug: '{{ old('slug') }}',
        generateSlug() {
            if (!this.slug) {
                this.slug = this.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
            }
        }
    }">
        <div class="pb-6 border-b border-slate-100 mb-6">
            <h3 class="text-xl font-extrabold text-slate-900">Formulir Pembuatan Halaman</h3>
            <p class="text-xs text-slate-400 mt-1">Buat dokumen informasi resmi seperti alur pengajuan, profil, atau panduan kerja sama.</p>
        </div>

        <form action="{{ route('admin.pages.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Halaman <span class="text-rose-500">*</span></label>
                <input type="text" name="title" x-model="title" @input="generateSlug" required placeholder="Contoh: Panduan Prosedur Pengajuan Kerja Sama" 
                       class="w-full px-4 py-3 text-sm font-semibold bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none transition-all">
            </div>

            <!-- Slug -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Slug URL Halaman</label>
                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-stikes-600 focus-within:bg-white">
                    <span class="px-4 text-xs text-slate-400 font-mono select-none bg-slate-100/80 border-r border-slate-200 py-3">{{ url('/page') }}/</span>
                    <input type="text" name="slug" x-model="slug" placeholder="panduan-prosedur" 
                           class="w-full px-3 py-3 text-xs font-mono bg-transparent border-none focus:outline-none">
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Bisa dikosongkan agar dibuat otomatis dari judul halaman.</p>
            </div>

            <!-- Content -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Konten / Isi Halaman <span class="text-rose-500">*</span></label>
                <textarea name="content" rows="16" required placeholder="Tuliskan isi panduan, alur prosedur, atau informasi resmi di sini. Anda dapat menggunakan teks paragraf, heading, atau daftar nomor..." 
                          class="w-full p-4 text-xs font-normal text-slate-800 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none transition-all leading-relaxed">{{ old('content') }}</textarea>
            </div>

            <!-- Publish Switch -->
            <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-800">Status Publikasi</p>
                    <p class="text-[11px] text-slate-400">Aktifkan agar halaman ini dapat diakses publik melalui link-nya.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-stikes-600"></div>
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.pages.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-stikes-600 hover:bg-stikes-700 text-white font-bold text-xs rounded-xl shadow-md shadow-stikes-600/30 transition-all hover:-translate-y-0.5">
                    <i class="fa-solid fa-floppy-disk me-1.5"></i>
                    <span>Simpan Halaman</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
