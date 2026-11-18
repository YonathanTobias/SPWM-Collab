@extends('layouts.app')

@section('title', 'Edit Halaman: ' . $page->title)
@section('page-title', 'Perbarui Halaman Informasi Resmi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Halaman</span>
        </a>

        @if($page->is_published)
            <a href="{{ route('public.pages.show', $page->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 hover:text-emerald-700">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>Lihat Tampilan Publik</span>
            </a>
        @endif
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
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <div class="pb-6 border-b border-slate-100 mb-6">
            <h3 class="text-xl font-extrabold text-slate-900">Perbarui Halaman Informasi</h3>
            <p class="text-xs text-slate-400 mt-1">Ubah isi dokumen informasi, tata letak narasi, atau status publikasi.</p>
        </div>

        <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Halaman <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $page->title) }}" required 
                       class="w-full px-4 py-3 text-sm font-semibold bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none transition-all">
            </div>

            <!-- Slug -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Slug URL Halaman</label>
                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-stikes-600 focus-within:bg-white">
                    <span class="px-4 text-xs text-slate-400 font-mono select-none bg-slate-100/80 border-r border-slate-200 py-3">{{ url('/page') }}/</span>
                    <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" 
                           class="w-full px-3 py-3 text-xs font-mono bg-transparent border-none focus:outline-none">
                </div>
            </div>

            <!-- Content -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Konten / Isi Halaman <span class="text-rose-500">*</span></label>
                <textarea name="content" rows="18" required 
                          class="w-full p-4 text-xs font-normal text-slate-800 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none transition-all leading-relaxed">{{ old('content', $page->content) }}</textarea>
            </div>

            <!-- Publish Switch -->
            <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-800">Status Publikasi</p>
                    <p class="text-[11px] text-slate-400">Aktifkan agar halaman dapat dibuka secara publik.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $page->is_published ? '1' : '0') == '1' ? 'checked' : '' }} class="sr-only peer">
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
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
