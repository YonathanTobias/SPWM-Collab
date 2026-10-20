@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Konfigurasi Sistem & Tampilan Publik')

@section('content')
<div class="max-w-4xl space-y-6">

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold space-y-1">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base"></i>
                <span>Terdapat kesalahan pengisian data:</span>
            </div>
            <ul class="list-disc pl-5 font-normal text-xs text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Card 1: Sakelar Filter Katalog Publik -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-stikes-100 text-stikes-700 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-filter"></i>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Filter Dokumen Kedaluwarsa di Katalog Publik</h3>
                <p class="text-xs text-slate-500">Atur apakah dokumen yang sudah kedaluwarsa ditampilkan atau disembunyikan dari pengunjung publik.</p>
            </div>
        </div>

        <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold text-slate-800 block mb-0.5">Status Filter Publik Saat Ini:</span>
                <p class="text-xs text-slate-600">
                    @if($hideExpiredSetting)
                        <strong class="text-emerald-700">Hanya Dokumen Belum Kedaluwarsa</strong> (Dokumen expired disembunyikan otomatis).
                    @else
                        <strong class="text-slate-800">Tampilkan Semua Dokumen</strong> (Termasuk dokumen expired).
                    @endif
                </p>
            </div>

            <form action="{{ route('admin.settings.toggleHideExpired') }}" method="POST">
                @csrf
                <button type="submit" class="px-5 py-2.5 rounded-lg text-xs font-bold transition-all shadow-sm flex items-center gap-2 cursor-pointer {{ $hideExpiredSetting ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'bg-slate-800 text-slate-200 hover:bg-slate-900' }}">
                    <i class="fa-solid {{ $hideExpiredSetting ? 'fa-toggle-on text-lg' : 'fa-toggle-off text-lg text-slate-400' }}"></i>
                    <span>{{ $hideExpiredSetting ? 'Ubah ke Tampilkan Semua' : 'Ubah ke Sembunyikan Expired' }}</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Card 2: Pengaturan Lengkap Konten Footer Halaman Publik -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-stikes-100 text-stikes-700 flex items-center justify-center text-lg flex-shrink-0">
                <i class="fa-solid fa-pen-nib"></i>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Pengelolaan Konten Footer Halaman Publik</h3>
                <p class="text-xs text-slate-500">Sesuaikan deskripsi profil, detail kontak, tautan peta Google Maps, serta teks hak cipta di bagian bawah.</p>
            </div>
        </div>

        <form action="{{ route('admin.settings.updateFooter') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Deskripsi Singkat Kampus / Profil -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Singkat / Profil Footer</label>
                <textarea name="footer_description" 
                          rows="3" 
                          required 
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">{{ old('footer_description', $footerDescription) }}</textarea>
                <p class="text-[11px] text-slate-500 mt-1">Teks narasi profil yang tampil di bawah judul instansi (kolom 1 footer).</p>
            </div>

            <!-- Kontak Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Kontak Resmi</label>
                    <input type="email" 
                           name="footer_email" 
                           value="{{ old('footer_email', $footerEmail) }}" 
                           required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Telepon / WhatsApp</label>
                    <input type="text" 
                           name="footer_phone" 
                           value="{{ old('footer_phone', $footerPhone) }}" 
                           required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                </div>
            </div>

            <!-- Alamat Fisik -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Lengkap Kampus</label>
                <input type="text" 
                       name="footer_address" 
                       value="{{ old('footer_address', $footerAddress) }}" 
                       required 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
            </div>

            <!-- Google Maps URL -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">URL Sematan Google Maps (Embed Iframe src)</label>
                <input type="text" 
                       name="footer_maps_url" 
                       value="{{ old('footer_maps_url', $footerMapsUrl) }}" 
                       placeholder="https://maps.google.com/maps?q=..." 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                <p class="text-[11px] text-slate-500 mt-1">Tautan embed Google Maps untuk widget peta lokasi kampus.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Teks Hak Cipta (Copyright)</label>
                    <input type="text" 
                           name="footer_copyright" 
                           value="{{ old('footer_copyright', $footerCopyright) }}" 
                           required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                    <p class="text-[11px] text-slate-500 mt-1">Tampil di pojok kiri bawah footer.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Teks Tagline & Versi Sistem</label>
                    <input type="text" 
                           name="footer_tagline" 
                           value="{{ old('footer_tagline', $footerTagline) }}" 
                           required 
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium focus:ring-2 focus:ring-stikes-500 focus:bg-white transition-all">
                    <p class="text-[11px] text-slate-500 mt-1">Tampil di pojok kanan bawah footer.</p>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-stikes-600 hover:bg-stikes-700 text-white font-bold text-xs rounded-lg shadow-md shadow-stikes-600/30 transition-all inline-flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Seluruh Pengaturan Footer</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
