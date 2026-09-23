<?php

namespace App\Http\Controllers;

use App\Models\Cooperation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicCatalogController extends Controller
{
    /**
     * Display public directory of cooperation documents.
     */
    public function index(Request $request)
    {
        $query = Cooperation::query();

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('partner_name', 'like', "%{$search}%")
                  ->orWhere('document_number', 'like', "%{$search}%")
                  ->orWhere('scope', 'like', "%{$search}%");
            });
        }

        // Level filter (Lokal, Nasional, Internasional)
        if ($level = $request->input('level')) {
            $query->where('level', $level);
        }

        // Document type filter (MoU, MoA, IA)
        if ($type = $request->input('type')) {
            $query->where('document_type', $type);
        }

        // Status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        } else {
            // Check global Admin Setting to hide expired documents from public catalog
            if (\App\Models\SiteSetting::getByKey('hide_expired_public', '0') === '1') {
                $query->where('status', '!=', 'Kedaluwarsa')
                      ->where('end_date', '>=', Carbon::today());
            }
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'oldest') {
            $query->orderBy('start_date', 'asc');
        } elseif ($sort === 'expiring_soon') {
            $query->orderBy('end_date', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $cooperations = $query->paginate(9)->withQueryString();

        // Quick Stats Counter
        $totalCooperations = Cooperation::count();
        $totalMou = Cooperation::where('document_type', 'MoU')->count();
        $totalMoa = Cooperation::where('document_type', 'MoA')->count();
        $totalIa = Cooperation::where('document_type', 'IA')->count();
        $totalInternational = Cooperation::where('level', 'Internasional')->count();

        return view('public.index', compact(
            'cooperations',
            'totalCooperations',
            'totalMou',
            'totalMoa',
            'totalIa',
            'totalInternational'
        ));
    }

    /**
     * Get JSON detail for modal preview.
     */
    public function show($id)
    {
        $cooperation = Cooperation::findOrFail($id);
        
        return response()->json([
            'id' => $cooperation->id,
            'title' => $cooperation->title,
            'partner_name' => $cooperation->partner_name,
            'document_number' => $cooperation->document_number,
            'document_type' => $cooperation->document_type,
            'level' => $cooperation->level,
            'scope' => $cooperation->scope,
            'start_date' => $cooperation->start_date->format('d M Y'),
            'end_date' => $cooperation->end_date->format('d M Y'),
            'status' => $cooperation->computed_status,
            'status_badge' => $cooperation->status_badge_class,
            'level_badge' => $cooperation->level_badge_class,
            'is_public' => $cooperation->is_public,
            'has_file' => !empty($cooperation->file_path) || !empty($cooperation->document_link),
            'has_external_link' => !empty($cooperation->document_link),
            'document_link' => $cooperation->document_link,
            'contact_person' => $cooperation->contact_person ?? '-',
            'contact_email' => $cooperation->contact_email ?? '-',
            'download_url' => $cooperation->download_url,
        ]);
    }

    /**
     * Download / Preview document PDF or Redirect to Google Docs link.
     */
    public function download($id)
    {
        $cooperation = Cooperation::findOrFail($id);

        if (!$cooperation->is_public && !auth()->check()) {
            abort(403, 'Dokumen ini dikategorikan internal dan hanya dapat diakses oleh Pengelola STIKes Panti Waluya.');
        }

        // If Google Docs / external link is set, redirect directly
        if (!empty($cooperation->document_link)) {
            return redirect()->away($cooperation->document_link);
        }

        if (!$cooperation->file_path || !Storage::disk('public')->exists($cooperation->file_path)) {
            abort(404, 'Berkas PDF dokumen belum diunggah atau tidak ditemukan.');
        }

        $filePath = Storage::disk('public')->path($cooperation->file_path);
        
        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($cooperation->file_path) . '"',
        ]);
    }

    /**
     * Export all or filtered cooperation list to CSV/Excel for public or general reporting.
     */
    public function exportExcel(Request $request)
    {
        $query = Cooperation::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('partner_name', 'like', "%{$search}%")
                  ->orWhere('document_number', 'like', "%{$search}%");
            });
        }
        if ($level = $request->input('level')) { $query->where('level', $level); }
        if ($type = $request->input('type')) { $query->where('document_type', $type); }
        if ($status = $request->input('status')) { $query->where('status', $status); }

        $cooperations = $query->orderBy('created_at', 'desc')->get();

        $filename = "Daftar_Kerjasama_STIKes_Panti_Waluya_" . date('Ymd_His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($cooperations) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel

            fputcsv($file, [
                'No',
                'Nama Instansi Mitra',
                'Tingkat (Lokal/Nasional/Internasional)',
                'Jenis Dokumen',
                'Nomor Surat/Dokumen',
                'Judul / Ruang Lingkup Kerja Sama',
                'Tanggal Mulai',
                'Tanggal Berakhir',
                'Status Keaktifan',
                'PIC Kontak'
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
                    $item->contact_person ? "{$item->contact_person} ({$item->contact_email})" : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export printable PDF report of all or filtered cooperations.
     */
    public function exportPdf(Request $request)
    {
        $query = Cooperation::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('partner_name', 'like', "%{$search}%")
                  ->orWhere('document_number', 'like', "%{$search}%");
            });
        }
        if ($level = $request->input('level')) { $query->where('level', $level); }
        if ($type = $request->input('type')) { $query->where('document_type', $type); }
        if ($status = $request->input('status')) { $query->where('status', $status); }

        $cooperations = $query->orderBy('created_at', 'desc')->get();
        $dateGenerated = Carbon::now()->translatedFormat('d F Y H:i');

        return view('dashboard.print_pdf', compact('cooperations', 'dateGenerated'));
    }
}
