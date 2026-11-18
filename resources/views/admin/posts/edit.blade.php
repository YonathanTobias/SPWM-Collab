@extends('layouts.app')

@section('title', 'Edit Berita: ' . $post->title)
@section('page-title', 'Edit Berita / Artikel Kerja Sama')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Berita</span>
        </a>

        @if($post->is_published)
            <a href="{{ route('public.posts.show', $post->slug) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 hover:text-emerald-700">
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
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm" x-data="{
        previewUrl: null,
        fileChosen(event) {
            const file = event.target.files[0];
            if (file) {
                this.previewUrl = URL.createObjectURL(file);
            }
        }
    }">
        <div class="pb-6 border-b border-slate-100 mb-6">
            <h3 class="text-xl font-extrabold text-slate-900">Perbarui Artikel Berita</h3>
            <p class="text-xs text-slate-400 mt-1">Perbarui judul, teks narasi, atau ganti foto sampul berita.</p>
        </div>

        <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Berita / Kegiatan <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required 
                       class="w-full px-4 py-3 text-sm font-semibold bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none transition-all">
            </div>

            <!-- Excerpt -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ringkasan Berita (Excerpt)</label>
                <textarea name="excerpt" rows="2" 
                          class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none transition-all">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>

            <!-- Thumbnail Upload -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Foto Sampul (Thumbnail)</label>
                
                @if($post->thumbnail)
                    <div class="mb-3">
                        <p class="text-[11px] font-bold text-slate-500 mb-1">Foto Sampul Saat Ini:</p>
                        <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full max-w-sm h-48 object-cover rounded-2xl border border-slate-200 shadow-sm">
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                    <label class="px-5 py-3 rounded-2xl border-2 border-dashed border-slate-300 hover:border-stikes-500 bg-slate-50 hover:bg-stikes-50/40 text-xs font-bold text-slate-700 cursor-pointer transition-all flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up text-stikes-600 text-sm"></i>
                        <span>Pilih Foto Baru untuk Mengganti</span>
                        <input type="file" name="thumbnail" accept="image/*" @change="fileChosen" class="hidden">
                    </label>
                    <span class="text-[11px] text-slate-400">Kosongkan jika tidak ingin mengganti foto sampul.</span>
                </div>

                <!-- New Preview Area -->
                <div x-show="previewUrl" class="mt-4" x-cloak>
                    <p class="text-[11px] font-bold text-emerald-600 mb-1">Pratinjau Foto Pengganti Baru:</p>
                    <img :src="previewUrl" alt="Thumbnail Preview" class="w-full max-w-sm h-48 object-cover rounded-2xl border-2 border-emerald-400 shadow-sm">
                </div>
            </div>

            <!-- Content -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Isi Lengkap Berita <span class="text-rose-500">*</span></label>
                <textarea name="content" rows="14" required 
                          class="w-full p-4 text-xs font-normal text-slate-800 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none transition-all leading-relaxed">{{ old('content', $post->content) }}</textarea>
            </div>

            <!-- Meta Options -->
            <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Publikasi</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}" 
                           class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-stikes-600 focus:outline-none">
                </div>

                <div class="flex items-center gap-3 sm:pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $post->is_published ? '1' : '0') == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-stikes-600"></div>
                        <span class="ms-3 text-xs font-bold text-slate-700">Tayangkan ke Publik</span>
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.posts.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors">
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
