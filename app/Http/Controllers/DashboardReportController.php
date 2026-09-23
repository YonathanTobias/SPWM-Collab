<?php

namespace App\Http\Controllers;

use App\Models\Cooperation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DashboardReportController extends Controller
{
    /**
     * Display Leadership & Accreditation Dashboard.
     */
    public function index()
    {
        $totalCount = Cooperation::count();

        // Type Breakdown
        $mouCount = Cooperation::where('document_type', 'MoU')->count();
        $moaCount = Cooperation::where('document_type', 'MoA')->count();
        $iaCount = Cooperation::where('document_type', 'IA')->count();

        // Level Breakdown
        $lokalCount = Cooperation::where('level', 'Lokal')->count();
        $nasionalCount = Cooperation::where('level', 'Nasional')->count();
        $internasionalCount = Cooperation::where('level', 'Internasional')->count();

        // Status Breakdown
        $aktifCount = Cooperation::where('status', 'Aktif')->count();
        $akanBerakhirCount = Cooperation::where('status', 'Akan Berakhir')->count();
        $kedaluwarsaCount = Cooperation::where('status', 'Kedaluwarsa')->count();
        $perpanjanganCount = Cooperation::where('status', 'Dalam Proses Perpanjangan')->count();

        // Expiring Soon List (top 5)
        $today = Carbon::today();
        $expiringSoonList = Cooperation::whereBetween('end_date', [$today, $today->copy()->addDays(90)])
            ->orWhere('status', 'Akan Berakhir')
            ->orderBy('end_date', 'asc')
            ->take(5)
            ->get();

        // Latest Cooperations
        $recentCooperations = Cooperation::orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard.index', compact(
            'totalCount',
            'mouCount',
            'moaCount',
            'iaCount',
            'lokalCount',
            'nasionalCount',
            'internasionalCount',
            'aktifCount',
            'akanBerakhirCount',
            'kedaluwarsaCount',
            'perpanjanganCount',
            'expiringSoonList',
            'recentCooperations'
        ));
    }

    /**
     * Export Cooperation Data to CSV (Excel format for Accreditation BAN-PT / LAM-PTKes).
     */
    public function exportExcel(Request $request)
    {
        $cooperations = Cooperation::orderBy('created_at', 'desc')->get();

        $filename = "Rekapitulasi_Kerjasama_STIKes_Panti_Waluya_" . date('Ymd_His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($cooperations) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fputs($file, "\xEF\xBB\xBF");

            // Header Row (Standard Borang Akreditasi Format)
            fputcsv($file, [
                'No',
                'Nama Lembaga Mitra',
                'Tingkat (Lokal/Nasional/Internasional)',
                'Jenis Dokumen (MoU/MoA/IA)',
                'Nomor Dokumen/Surat',
                'Judul / Ruang Lingkup Kerja Sama',
                'Tanggal Mulai',
                'Tanggal Berakhir',
                'Status Keaktifan',
                'Akses Publik',
                'Kontak PIC'
            ]);

            $no = 1;
            foreach ($cooperations as $item) {
                fputcsv($file, [
                    $no++,
                    $item->partner_name,
                    $item->level,
                    $item->document_type,
                    $item->document_number,
                    $item->title,
                    $item->start_date->format('Y-m-d'),
                    $item->end_date->format('Y-m-d'),
                    $item->computed_status,
                    $item->is_public ? 'Ya' : 'Tidak (Internal)',
                    $item->contact_person ? "{$item->contact_person} ({$item->contact_email})" : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Printable PDF Report View.
     */
    public function exportPdf()
    {
        $cooperations = Cooperation::orderBy('created_at', 'desc')->get();
        $dateGenerated = Carbon::now()->translatedFormat('d F Y H:i');

        return view('dashboard.print_pdf', compact('cooperations', 'dateGenerated'));
    }
}
