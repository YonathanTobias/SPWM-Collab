<?php

namespace Database\Seeders;

use App\Models\Cooperation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Users
        User::updateOrCreate(
            ['email' => 'admin@stikespantiwaluya.ac.id'],
            [
                'name' => 'Super Admin Kerja Sama',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pimpinan@stikespantiwaluya.ac.id'],
            [
                'name' => 'Pimpinan STIKes Panti Waluya',
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
            ]
        );

        // Ensure storage directory for documents exists
        Storage::disk('public')->makeDirectory('documents');

        // Dummy PDF file creation for testing previews & downloads
        $samplePdfContent = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj 2 0 obj<</Type/Pages/Count 1/Kids[3 0 R]>>endobj 3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<</Font<*/F1 4 0 R>>>>/Contents 5 0 R>>endobj 4 0 obj<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>endobj 5 0 obj<</Length 84>>stream\nBT /F1 18 Tf 50 700 Td (Dokumen Resmi Kerja Sama STIKes Panti Waluya Malang) Tj ET\nendstream\nendobj\nxref\n0 6\n0000000000 65535 f\n0000000009 00000 n\n0000000056 00000 n\n0000000111 00000 n\n0000000238 00000 n\n0000000306 00000 n\ntrailer<</Size 6/Root 1 0 R>>\nstartxref\n440\n%%EOF";
        
        Storage::disk('public')->put('documents/sample_mou_rsud.pdf', $samplePdfContent);
        Storage::disk('public')->put('documents/sample_moa_university.pdf', $samplePdfContent);
        Storage::disk('public')->put('documents/sample_ia_dinkes.pdf', $samplePdfContent);

        // Seed Cooperations
        $today = Carbon::today();

        $cooperations = [
            [
                'title' => 'Penyelenggaraan Praktek Klinik Mahasiswa Keperawatan & Kebidanan',
                'partner_name' => 'RSUD dr. Saiful Anwar Malang',
                'document_number' => 'STIKES-PW/MOU/2024/001',
                'document_type' => 'MoU',
                'level' => 'Dalam Negeri',
                'scope' => 'Penyediaan sarana praktek klinik lapangan, pembimbingan preseptor, dan riset kesehatan kolaboratif mahasiswa Keperawatan & Kebidanan.',
                'start_date' => $today->copy()->subYears(2)->format('Y-m-d'),
                'end_date' => $today->copy()->addYears(3)->format('Y-m-d'), // Aktif
                'status' => 'Aktif',
                'is_public' => true,
                'file_path' => 'documents/sample_mou_rsud.pdf',
                'contact_person' => 'dr. Budi Santoso, Sp.A (Kabid Diklit)',
                'contact_email' => 'diklit@rsudsaifulanwar.go.id',
            ],
            [
                'title' => 'Program Pertukaran Mahasiswa dan Joint Research bidang Keperawatan Kesehatan Masyarakat',
                'partner_name' => 'Mahidol University, Thailand',
                'document_number' => 'STIKES-PW/MOA/INT/2023/008',
                'document_type' => 'MoA',
                'level' => 'Luar Negeri',
                'scope' => 'Pertukaran dosen tamu, publikasi jurnal internasional bersama, dan student exchange program semester pendek.',
                'start_date' => $today->copy()->subYear(1)->format('Y-m-d'),
                'end_date' => $today->copy()->addDays(45)->format('Y-m-d'), // Akan Berakhir (<90 hari)
                'status' => 'Akan Berakhir',
                'is_public' => true,
                'file_path' => 'documents/sample_moa_university.pdf',
                'contact_person' => 'Prof. Somchai Prasert (Director of Int Relations)',
                'contact_email' => 'ir@mahidol.ac.th',
            ],
            [
                'title' => 'Kerja Sama Program Pengabdian Masyarakat dan Edukasi Stunting Desa Binaan',
                'partner_name' => 'Dinas Kesehatan Kota Malang',
                'document_number' => 'STIKES-PW/IA/2024/015',
                'document_type' => 'IA',
                'level' => 'Dalam Negeri',
                'scope' => 'Implementation Arrangement untuk intervensi gizi balita, edukasi kesehatan reproduksi remaja, dan pendampingan posyandu.',
                'start_date' => $today->copy()->subMonths(6)->format('Y-m-d'),
                'end_date' => $today->copy()->addMonths(6)->format('Y-m-d'), // Aktif
                'status' => 'Aktif',
                'is_public' => true,
                'file_path' => 'documents/sample_ia_dinkes.pdf',
                'contact_person' => 'Siti Rahmawati, S.KM (Kasi Kesmas)',
                'contact_email' => 'dinkes@malangkota.go.id',
            ],
            [
                'title' => 'MoU Pengembangan Kurikulum Berbasis DUDI dan Penyerapan Lulusan',
                'partner_name' => 'Silowam Hospitals Group Surabaya',
                'document_number' => 'STIKES-PW/MOU/2021/042',
                'document_type' => 'MoU',
                'level' => 'Dalam Negeri',
                'scope' => 'Rekrutmen kampus langsung (on-campus recruitment), pelatihan keterampilan khusus perawatan intensif, dan magang industri.',
                'start_date' => $today->copy()->subYears(5)->format('Y-m-d'),
                'end_date' => $today->copy()->subMonths(2)->format('Y-m-d'), // Kedaluwarsa
                'status' => 'Kedaluwarsa',
                'is_public' => true,
                'file_path' => 'documents/sample_mou_rsud.pdf',
                'contact_person' => 'Hendro Wijaya, S.Psi (HRD Manager)',
                'contact_email' => 'recruitment@siloamhospitals.com',
            ],
            [
                'title' => 'Pengembangan Teknologi Informasi SIMRS & Digitalisasi Rekam Medis',
                'partner_name' => 'PT Medika Digital Nusantara',
                'document_number' => 'STIKES-PW/IA/2025/003',
                'document_type' => 'IA',
                'level' => 'Dalam Negeri',
                'scope' => 'Pengembangan laboratorium laboratorium rekammedis berbasis SIMRS bagi mahasiswa Rekam Medis & Informasi Kesehatan.',
                'start_date' => $today->copy()->subMonths(3)->format('Y-m-d'),
                'end_date' => $today->copy()->addDays(20)->format('Y-m-d'), // Dalam Proses Perpanjangan
                'status' => 'Dalam Proses Perpanjangan',
                'is_public' => false,
                'file_path' => 'documents/sample_ia_dinkes.pdf',
                'contact_person' => 'Irfan Maulana (Product Lead)',
                'contact_email' => 'contact@medikadigital.co.id',
            ],
            [
                'title' => 'MoU Konsorsium Riset Biomedis & Farmasi Herbal Nusantara',
                'partner_name' => 'Universitas Airlangga Surabaya',
                'document_number' => 'STIKES-PW/MOU/2023/101',
                'document_type' => 'MoU',
                'level' => 'Dalam Negeri',
                'scope' => 'Kerja sama riset pengembangan obat herbal lokal Malang untuk manajemen luka diabetes dan uji laboratorium.',
                'start_date' => $today->copy()->subYears(1)->format('Y-m-d'),
                'end_date' => $today->copy()->addYears(2)->format('Y-m-d'), // Aktif
                'status' => 'Aktif',
                'is_public' => true,
                'file_path' => 'documents/sample_mou_rsud.pdf',
                'contact_person' => 'Dr. Apt. Retno Wati (Kepala Lab Biomedis)',
                'contact_email' => 'lab.biomedis@unair.ac.id',
            ],
            [
                'title' => 'Academic Exchange & Dual Degree Nursing Program',
                'partner_name' => 'Taipei Medical University, Taiwan',
                'document_number' => 'STIKES-PW/MOA/INT/2024/002',
                'document_type' => 'MoA',
                'level' => 'Luar Negeri',
                'scope' => 'Program transfer kredit kuliah, pelatihan bahasa Inggris medis, dan fasilitasi beasiswa Magister Keperawatan.',
                'start_date' => $today->copy()->subMonths(8)->format('Y-m-d'),
                'end_date' => $today->copy()->addDays(75)->format('Y-m-d'), // Akan Berakhir (<90 hari)
                'status' => 'Akan Berakhir',
                'is_public' => true,
                'file_path' => 'documents/sample_moa_university.pdf',
                'contact_person' => 'Dr. Chen Wei-Ming (College of Nursing)',
                'contact_email' => 'nursing@tmu.edu.tw',
            ],
            [
                'title' => 'Praktek Lapangan Pengelolaan Sanitasi dan K3 Rumah Sakit',
                'partner_name' => 'RS Panti Waluya Sawahan Malang (RKZ)',
                'document_number' => 'STIKES-PW/IA/2024/088',
                'document_type' => 'IA',
                'level' => 'Dalam Negeri',
                'scope' => 'Penempatan mahasiswa di unit K3 dan Manajemen Risiko Rumah Sakit Panti Waluya Sawahan.',
                'start_date' => $today->copy()->subMonths(10)->format('Y-m-d'),
                'end_date' => $today->copy()->addYears(1)->format('Y-m-d'), // Aktif
                'status' => 'Aktif',
                'is_public' => true,
                'file_path' => 'documents/sample_ia_dinkes.pdf',
                'contact_person' => 'Sr. Maria Clara, ALMA (Direktur RS)',
                'contact_email' => 'sekretariat@pantiwaluya.or.id',
            ]
        ];

        foreach ($cooperations as $data) {
            Cooperation::create($data);
        }

        // Seed Requested Menus: Beranda, Layanan, Mitra Kerjasama, SOP dan Pedoman, Kontak, Berita
        \App\Models\NavMenu::create([
            'parent_id' => null,
            'title' => 'Beranda',
            'url' => '/',
            'icon' => 'fa-solid fa-house',
            'is_external' => false,
            'order' => 1,
            'is_active' => true,
        ]);

        $layananMenu = \App\Models\NavMenu::create([
            'parent_id' => null,
            'title' => 'Layanan',
            'url' => '#',
            'icon' => 'fa-solid fa-layer-group',
            'is_external' => false,
            'order' => 2,
            'is_active' => true,
        ]);

        \App\Models\NavMenu::create([
            'parent_id' => $layananMenu->id,
            'title' => 'Sistem Rekapitulasi Borang',
            'url' => '/dashboard',
            'icon' => 'fa-solid fa-chart-pie',
            'is_external' => false,
            'order' => 1,
            'is_active' => true,
        ]);

        \App\Models\NavMenu::create([
            'parent_id' => $layananMenu->id,
            'title' => 'Website Utama STIKes',
            'url' => 'https://stikespantiwaluya.ac.id',
            'icon' => 'fa-solid fa-globe',
            'is_external' => true,
            'order' => 2,
            'is_active' => true,
        ]);

        \App\Models\NavMenu::create([
            'parent_id' => $layananMenu->id,
            'title' => 'Portal Resmi LAM-PTKes',
            'url' => 'https://lamptkes.org',
            'icon' => 'fa-solid fa-arrow-up-right-from-square',
            'is_external' => true,
            'order' => 3,
            'is_active' => true,
        ]);

        \App\Models\NavMenu::create([
            'parent_id' => null,
            'title' => 'Mitra Kerjasama',
            'url' => '/#katalog',
            'icon' => 'fa-solid fa-handshake',
            'is_external' => false,
            'order' => 3,
            'is_active' => true,
        ]);

        \App\Models\NavMenu::create([
            'parent_id' => null,
            'title' => 'SOP dan Pedoman',
            'url' => '/page/sop-dan-pedoman',
            'icon' => 'fa-solid fa-book-bookmark',
            'is_external' => false,
            'order' => 4,
            'is_active' => true,
        ]);

        \App\Models\NavMenu::create([
            'parent_id' => null,
            'title' => 'Kontak',
            'url' => '/#kontak',
            'icon' => 'fa-solid fa-envelope-open-text',
            'is_external' => false,
            'order' => 5,
            'is_active' => true,
        ]);

        \App\Models\NavMenu::create([
            'parent_id' => null,
            'title' => 'Berita',
            'url' => '/berita',
            'icon' => 'fa-solid fa-newspaper',
            'is_external' => false,
            'order' => 6,
            'is_active' => true,
        ]);

        // Seed Sample CMS Static Pages
        \App\Models\Page::updateOrCreate(
            ['slug' => 'sop-dan-pedoman'],
            [
                'title' => 'SOP dan Pedoman Kerja Sama Institusi',
                'content' => "STANDAR OPERASIONAL PROSEDUR (SOP) & PEDOMAN KERJA SAMA\nSTIKes Panti Waluya Malang\n\n1. TUJUAN\nPedoman ini menjadi acuan baku bagi seluruh unit kerja di lingkungan STIKes Panti Waluya Malang dan mitra eksternal dalam menginisiasi, menyusun, menandatangani, dan mengevaluasi dokumen kerja sama (MoU, MoA, dan Implementation Arrangement).\n\n2. LANDASAN HUKUM & STANDAR MUTU\n- Peraturan Menteri Pendidikan, Kebudayaan, Riset, dan Teknologi tentang Standar Nasional Pendidikan Tinggi.\n- Panduan Penyusunan Kerja Sama Perguruan Tinggi Direktorat Jenderal Pendidikan Tinggi Kemendikbudristek.\n- Instrumen Akreditasi Institusi & Program Studi Lembaga Akreditasi Mandiri Pendidikan Tinggi Kesehatan (LAM-PTKes).\n\n3. PROSEDUR OPERASIONAL BAKU\na. Tahap Inisiasi:\nUnit kerja pengusul atau calon mitra mengajukan permohonan kerja sama beserta Kerangka Acuan Kerja (TOR) atau surat resmi.\nb. Tahap Telaah Hukum & Substansi:\nBagian Kerja Sama menelaah kesesuaian klausul hukum, hak dan kewajiban para pihak, serta keselarasan dengan kalender akademik.\nc. Tahap Penandatanganan:\nNaskah kesepakatan difinalkan dan ditandatangani oleh Ketua STIKes Panti Waluya Malang dan Pimpinan Mitra secara fisik maupun digital tersertifikasi.\nd. Tahap Pelaksanaan & Pelaporan:\nUnit pelaksana menyusun Implementation Arrangement (IA) dan mengunggah dokumen bukti pelaksanaan kerja sama ke sistem SPWM-Collab guna pelaporan akreditasi.\n\nKontak Bagian Kerja Sama:\nEmail: kerjasama@stikespantiwaluya.ac.id\nTelepon: (0341) 369003\nAlamat: Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang",
                'is_published' => true,
            ]
        );

        \App\Models\Page::updateOrCreate(
            ['slug' => 'panduan-prosedur'],
            [
                'title' => 'Panduan Prosedur Pengajuan Kerja Sama Institusi',
                'content' => "PANDUAN PROSEDUR KERJA SAMA (MoU, MoA, dan IA)\nSTIKes Panti Waluya Malang\n\n1. PENDAHULUAN\nDalam rangka meningkatkan mutu penyelenggaraan Tri Dharma Perguruan Tinggi (Pendidikan, Penelitian, dan Pengabdian kepada Masyarakat), STIKes Panti Waluya Malang senantiasa membuka peluang kolaborasi seluas-luasnya dengan Rumah Sakit, Puskesmas, Dinas Kesehatan, Industri Farmasi, serta Perguruan Tinggi baik di dalam maupun luar negeri.\n\n2. TAHAPAN PENGAJUAN KERJA SAMA\na. Pengajuan Permohonan / Inisiasi\nCalon mitra dapat mengirimkan surat permohonan resmi dan draft usulan kerja sama melalui email: kerjasama@stikespantiwaluya.ac.id atau bersurat langsung ke alamat kampus.\n\nb. Telaah & Pembahasan Dokumen\nBagian Kerja Sama dan Program Studi terkait akan melakukan penelaahan ruang lingkup kerja sama serta mencocokkan kalender akademik.\n\nc. Finalisasi Draft Naskah (MoU / MoA / IA)\nNaskah kesepakatan difinalkan dan diberi nomor dokumen resmi institusi.\n\nd. Penandatanganan (Signing Ceremony)\nPenandatanganan dokumen kerja sama oleh Pimpinan STIKes Panti Waluya Malang dan Direktur/Pimpinan Institusi Mitra.\n\ne. Pengarsipan & Pelaporan Borang\nDokumen resmi diarsipkan secara digital di sistem SPWM-Collab untuk pemenuhan data akreditasi LAM-PTKes.\n\nKontak Bagian Kerja Sama:\nEmail: kerjasama@stikespantiwaluya.ac.id\nTelepon: (0341) 369003\nAlamat: Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang",
                'is_published' => true,
            ]
        );

        // Seed Sample CMS News Articles
        $adminUser = \App\Models\User::first();

        \App\Models\Post::updateOrCreate(
            ['slug' => 'penandatanganan-mou-praktek-klinik-rsud-saiful-anwar'],
            [
                'title' => 'Penandatanganan Perjanjian Kerja Sama Praktek Klinik dengan RSUD dr. Saiful Anwar Malang',
                'excerpt' => 'STIKes Panti Waluya Malang resmi memperpanjang kemitraan strategis dengan RSUD dr. Saiful Anwar Malang guna penyediaan wahana praktek klinik keperawatan.',
                'content' => "MALANG – STIKes Panti Waluya Malang resmi menandatangani pembaruan naskah kesepakatan bersama (MoU dan MoA) dengan RSUD dr. Saiful Anwar Malang pada hari ini.\n\nKerja sama ini berfokus pada penyediaan sarana praktek klinik lapangan, pembimbingan preseptor klinis bersertifikasi, serta kolaborasi riset kesehatan antara dosen dan tenaga medis rumah sakit.\n\nMelalui kerja sama ini, mahasiswa program studi Keperawatan dan Farmasi mendapatkan akses pembelajaran langsung di rumah sakit rujukan utama Jawa Timur dengan standar pelayanan terbaik.",
                'thumbnail' => null,
                'author_id' => $adminUser ? $adminUser->id : null,
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(2),
                'views_count' => 142,
            ]
        );

        \App\Models\Post::updateOrCreate(
            ['slug' => 'kolaborasi-internasional-program-pertukaran-mahasiswa-thailand'],
            [
                'title' => 'Kolaborasi Internasional: Pembahasan Program Pertukaran Mahasiswa dan Dosen Tamu',
                'excerpt' => 'Penjajakan kerja sama internasional bidang keperawatan kesehatan masyarakat dan publikasi riset terindeks internasional bersama mitra universitas luar negeri.',
                'content' => "MALANG – Dalam rangka penguatan internasionalisasi institusi dan pemenuhan kriteria akreditasi unggul, STIKes Panti Waluya Malang menggelar pertemuan daring penjajakan kerja sama internasional.\n\nFokus kegiatan meliputi implementasi joint research bidang kesehatan masyarakat, visiting professor, serta student exchange program semester pendek bagi mahasiswa sarjana keperawatan.\n\nPimpinan STIKes Panti Waluya menyampaikan bahwa langkah ini merupakan bagian dari visi jangka panjang mencetak tenaga kesehatan yang kompeten dan berdaya saing global.",
                'thumbnail' => null,
                'author_id' => $adminUser ? $adminUser->id : null,
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(5),
                'views_count' => 98,
            ]
        );
    }
}
