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
                'name' => 'Super Admin Kerjasama',
                'password' => Hash::make('password'),
                'role' => 'admin',
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
        $samplePdfContent = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj 2 0 obj<</Type/Pages/Count 1/Kids[3 0 R]>>endobj 3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<</Font<*/F1 4 0 R>>>>/Contents 5 0 R>>endobj 4 0 obj<</Type/Font/Subtype/Type1/BaseFont/Helvetica>>endobj 5 0 obj<</Length 84>>stream\nBT /F1 18 Tf 50 700 Td (Dokumen Resmi Kerjasama STIKes Panti Waluya Malang) Tj ET\nendstream\nendobj\nxref\n0 6\n0000000000 65535 f\n0000000009 00000 n\n0000000056 00000 n\n0000000111 00000 n\n0000000238 00000 n\n0000000306 00000 n\ntrailer<</Size 6/Root 1 0 R>>\nstartxref\n440\n%%EOF";
        
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
                'level' => 'Lokal',
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
                'level' => 'Internasional',
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
                'title' => 'Kerjasama Program Pengabdian Masyarakat dan Edukasi Stunting Desa Binaan',
                'partner_name' => 'Dinas Kesehatan Kota Malang',
                'document_number' => 'STIKES-PW/IA/2024/015',
                'document_type' => 'IA',
                'level' => 'Lokal',
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
                'level' => 'Nasional',
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
                'level' => 'Nasional',
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
                'level' => 'Nasional',
                'scope' => 'Kerjasama riset pengembangan obat herbal lokal Malang untuk manajemen luka diabetes dan uji laboratorium.',
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
                'level' => 'Internasional',
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
                'level' => 'Lokal',
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

        // Seed Parent and Sub Menus
        $profilMenu = \App\Models\NavMenu::create([
            'parent_id' => null,
            'title' => 'Profil & Layanan',
            'url' => '#',
            'icon' => 'fa-solid fa-building-columns',
            'is_external' => false,
            'order' => 1,
            'is_active' => true,
        ]);

        \App\Models\NavMenu::create([
            'parent_id' => $profilMenu->id,
            'title' => 'Website Utama STIKes',
            'url' => 'https://stikespantiwaluya.ac.id',
            'icon' => 'fa-solid fa-globe',
            'is_external' => true,
            'order' => 1,
            'is_active' => true,
        ]);

        \App\Models\NavMenu::create([
            'parent_id' => $profilMenu->id,
            'title' => 'Panduan Prosedur Kerjasama',
            'url' => '#',
            'icon' => 'fa-solid fa-file-invoice',
            'is_external' => false,
            'order' => 2,
            'is_active' => true,
        ]);

        $akreditasiMenu = \App\Models\NavMenu::create([
            'parent_id' => null,
            'title' => 'Layanan Akreditasi',
            'url' => '#',
            'icon' => 'fa-solid fa-certificate',
            'is_external' => false,
            'order' => 2,
            'is_active' => true,
        ]);

        \App\Models\NavMenu::create([
            'parent_id' => $akreditasiMenu->id,
            'title' => 'Sistem Rekapitulasi Borang',
            'url' => '/dashboard',
            'icon' => 'fa-solid fa-chart-pie',
            'is_external' => false,
            'order' => 1,
            'is_active' => true,
        ]);

        \App\Models\NavMenu::create([
            'parent_id' => $akreditasiMenu->id,
            'title' => 'Portal Resmi LAM-PTKes',
            'url' => 'https://lamptkes.org',
            'icon' => 'fa-solid fa-arrow-up-right-from-square',
            'is_external' => true,
            'order' => 2,
            'is_active' => true,
        ]);
    }
}
