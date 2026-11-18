@extends('layouts.app')

@section('title', 'Tulis Berita Baru')
@section('page-title', 'Tulis & Publikasikan Berita Kerja Sama Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar Berita</span>
        </a>
    </div>

    <!-- Error Alert -->
    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs font-semibold shadow-sm space-y-1">
            <div class="flex items-center gap-2 font-bold text-rose-900">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Terdapat kesalahan pada input formulir:</span>
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
            <h3 class="text-xl font-extrabold text-slate-900">Formulir Publikasi Berita</h3>
            <p class="text-xs text-slate-400 mt-1">Lengkapi informasi berita kegiatan kerja sama kampus di bawah ini.</p>
        </div>

        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Berita / Kegiatan <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: STIKes Panti Waluya Malang Jalin Kerja Sama Praktek Klinik dengan RS Saiful Anwar" 
                       class="w-full px-4 py-3 text-sm font-semibold bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none transition-all">
            </div>

            <!-- Excerpt -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ringkasan Berita (Excerpt)</label>
                <textarea name="excerpt" rows="2" placeholder="Tuliskan 1-2 kalimat ringkasan yang menarik untuk ditampilkan pada kartu berita..." 
                          class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none transition-all">{{ old('excerpt') }}</textarea>
                <p class="text-[10px] text-slate-400 mt-1">Jika dikosongkan, sistem akan otomatis mengambil kalimat pembuka dari isi berita.</p>
            </div>

            <!-- Thumbnail Upload -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Foto Sampul (Thumbnail)</label>
                <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                    <label class="px-5 py-3 rounded-2xl border-2 border-dashed border-slate-300 hover:border-stikes-500 bg-slate-50 hover:bg-stikes-50/40 text-xs font-bold text-slate-700 cursor-pointer transition-all flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up text-stikes-600 text-sm"></i>
                        <span>Pilih Gambar Sampul (JPG, PNG, WebP)</span>
                        <input type="file" name="thumbnail" accept="image/*" @change="fileChosen" class="hidden">
                    </label>
                    <span class="text-[11px] text-slate-400">Maksimal 5MB. Format horizontal (16:9) disarankan.</span>
                </div>

                <!-- Preview Area -->
                <div x-show="previewUrl" class="mt-4" x-cloak>
                    <p class="text-[11px] font-bold text-slate-500 mb-1">Pratinjau Foto:</p>
                    <img :src="previewUrl" alt="Thumbnail Preview" class="w-full max-w-sm h-48 object-cover rounded-2xl border border-slate-200 shadow-sm">
                </div>
            </div>

            <!-- Content -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Isi Lengkap Berita <span class="text-rose-500">*</span></label>
                <textarea name="content" rows="12" required placeholder="Tuliskan isi berita secara lengkap di sini. Anda dapat menggunakan format paragraf biasa, poin-poin, maupun link..." 
                          class="w-full p-4 text-xs font-normal text-slate-800 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-stikes-600 focus:bg-white focus:outline-none transition-all leading-relaxed">{{ old('content') }}</textarea>
            </div>

            <!-- Meta Options -->
            <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Publikasi</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}" 
                           class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-stikes-600 focus:outline-none">
                </div>

                <div class="flex items-center gap-3 sm:pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-stikes-600"></div>
                        <span class="ms-3 text-xs font-bold text-slate-700">Langsung Tayangkan ke Publik</span>
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.posts.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-stikes-600 hover:bg-stikes-700 text-white font-bold text-xs rounded-xl shadow-md shadow-stikes-600/30 transition-all hover:-translate-y-0.5">
                    <i class="fa-solid fa-paper-plane me-1.5"></i>
                    <span>Simpan & Publikasikan</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
