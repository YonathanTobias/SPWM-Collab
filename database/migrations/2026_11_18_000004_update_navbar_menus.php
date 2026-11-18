<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\NavMenu;
use App\Models\Page;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ensure 'sop-dan-pedoman' static page exists
        Page::updateOrCreate(
            ['slug' => 'sop-dan-pedoman'],
            [
                'title' => 'SOP dan Pedoman Kerja Sama Institusi',
                'content' => "STANDAR OPERASIONAL PROSEDUR (SOP) & PEDOMAN KERJA SAMA\nSTIKes Panti Waluya Malang\n\n1. TUJUAN\nPedoman ini menjadi acuan baku bagi seluruh unit kerja di lingkungan STIKes Panti Waluya Malang dan mitra eksternal dalam menginisiasi, menyusun, menandatangani, dan mengevaluasi dokumen kerja sama (MoU, MoA, dan Implementation Arrangement).\n\n2. LANDASAN HUKUM & STANDAR MUTU\n- Peraturan Menteri Pendidikan, Kebudayaan, Riset, dan Teknologi tentang Standar Nasional Pendidikan Tinggi.\n- Panduan Penyusunan Kerja Sama Perguruan Tinggi Direktorat Jenderal Pendidikan Tinggi Kemendikbudristek.\n- Instrumen Akreditasi Institusi & Program Studi Lembaga Akreditasi Mandiri Pendidikan Tinggi Kesehatan (LAM-PTKes).\n\n3. PROSEDUR OPERASIONAL BAKU\na. Tahap Inisiasi:\nUnit kerja pengusul atau calon mitra mengajukan permohonan kerja sama beserta Kerangka Acuan Kerja (TOR) atau permohonan resmi.\n\nb. Tahap Telaah Hukum & Substansi:\nBagian Kerja Sama menelaah kesesuaian klausul hukum, hak dan kewajiban para pihak, serta keselarasan dengan kalender akademik.\n\nc. Tahap Penandatanganan:\nNaskah kesepakatan difinalkan dan ditandatangani oleh Ketua STIKes Panti Waluya Malang dan Pimpinan Mitra secara fisik maupun digital tersertifikasi.\n\nd. Tahap Pelaksanaan & Pelaporan:\nUnit pelaksana menyusun Implementation Arrangement (IA) dan mengunggah dokumen bukti pelaksanaan kerja sama ke sistem SPWM-Collab guna pelaporan akreditasi.\n\nKontak Bagian Kerja Sama:\nEmail: kerjasama@stikespantiwaluya.ac.id\nTelepon: (0341) 369003\nAlamat: Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang",
                'is_published' => true,
            ]
        );

        // 2. Reset and seed exact navbar menus:
        // Beranda, Layanan, Mitra Kerjasama, SOP dan Pedoman, Kontak, Berita
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('nav_menus')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Beranda
        NavMenu::create([
            'parent_id' => null,
            'title' => 'Beranda',
            'url' => '/',
            'icon' => 'fa-solid fa-house',
            'is_external' => false,
            'order' => 1,
            'is_active' => true,
        ]);

        // 2. Layanan (Dropdown with Sub-menus)
        $layanan = NavMenu::create([
            'parent_id' => null,
            'title' => 'Layanan',
            'url' => '#',
            'icon' => 'fa-solid fa-layer-group',
            'is_external' => false,
            'order' => 2,
            'is_active' => true,
        ]);

        NavMenu::create([
            'parent_id' => $layanan->id,
            'title' => 'Sistem Rekapitulasi Borang',
            'url' => '/dashboard',
            'icon' => 'fa-solid fa-chart-pie',
            'is_external' => false,
            'order' => 1,
            'is_active' => true,
        ]);

        NavMenu::create([
            'parent_id' => $layanan->id,
            'title' => 'Website Utama STIKes',
            'url' => 'https://stikespantiwaluya.ac.id',
            'icon' => 'fa-solid fa-globe',
            'is_external' => true,
            'order' => 2,
            'is_active' => true,
        ]);

        NavMenu::create([
            'parent_id' => $layanan->id,
            'title' => 'Portal Resmi LAM-PTKes',
            'url' => 'https://lamptkes.org',
            'icon' => 'fa-solid fa-arrow-up-right-from-square',
            'is_external' => true,
            'order' => 3,
            'is_active' => true,
        ]);

        // 3. Mitra Kerjasama
        NavMenu::create([
            'parent_id' => null,
            'title' => 'Mitra Kerjasama',
            'url' => '/#katalog',
            'icon' => 'fa-solid fa-handshake',
            'is_external' => false,
            'order' => 3,
            'is_active' => true,
        ]);

        // 4. SOP dan Pedoman
        NavMenu::create([
            'parent_id' => null,
            'title' => 'SOP dan Pedoman',
            'url' => '/page/sop-dan-pedoman',
            'icon' => 'fa-solid fa-book-bookmark',
            'is_external' => false,
            'order' => 4,
            'is_active' => true,
        ]);

        // 5. Kontak
        NavMenu::create([
            'parent_id' => null,
            'title' => 'Kontak',
            'url' => '/#kontak',
            'icon' => 'fa-solid fa-envelope-open-text',
            'is_external' => false,
            'order' => 5,
            'is_active' => true,
        ]);

        // 6. Berita
        NavMenu::create([
            'parent_id' => null,
            'title' => 'Berita',
            'url' => '/berita',
            'icon' => 'fa-solid fa-newspaper',
            'is_external' => false,
            'order' => 6,
            'is_active' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep intact
    }
};
