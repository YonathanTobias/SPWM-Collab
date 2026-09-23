<?php

namespace App\Http\Controllers;

use App\Models\Cooperation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCooperationController extends Controller
{
    /**
     * Display a listing of cooperations for Admin.
     */
    public function index(Request $request)
    {
        $query = Cooperation::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('partner_name', 'like', "%{$search}%")
                  ->orWhere('document_number', 'like', "%{$search}%");
            });
        }

        if ($level = $request->input('level')) {
            $query->where('level', $level);
        }

        if ($type = $request->input('type')) {
            $query->where('document_type', $type);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $cooperations = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Statistics for summary badges
        $today = Carbon::today();
        $totalCount = Cooperation::count();
        $activeCount = Cooperation::where('status', 'Aktif')->count();
        $expiringCount = Cooperation::where(function ($q) use ($today) {
            $q->where('status', 'Akan Berakhir')
              ->orWhereBetween('end_date', [$today, $today->copy()->addDays(90)]);
        })->count();
        $expiredCount = Cooperation::where('status', 'Kedaluwarsa')->orWhere('end_date', '<', $today)->count();

        $hideExpiredSetting = \App\Models\SiteSetting::getByKey('hide_expired_public', '0') === '1';

        return view('admin.cooperations.index', compact(
            'cooperations',
            'totalCount',
            'activeCount',
            'expiringCount',
            'expiredCount',
            'hideExpiredSetting'
        ));
    }

    /**
     * Show form for creating new cooperation.
     */
    public function create()
    {
        return view('admin.cooperations.create');
    }

    /**
     * Store new cooperation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:1000',
            'partner_name' => 'required|string|max:255',
            'document_number' => 'required|string|max:255|unique:cooperations,document_number',
            'document_type' => 'required|in:MoU,MoA,IA',
            'level' => 'required|in:Lokal,Nasional,Internasional',
            'scope' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Aktif,Akan Berakhir,Kedaluwarsa,Dalam Proses Perpanjangan',
            'is_public' => 'boolean',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'document_link' => 'nullable|url|max:1000',
            'file' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ]);

        $validated['is_public'] = $request->has('is_public');

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('documents', 'public');
            $validated['file_path'] = $path;
        }

        Cooperation::create($validated);

        return redirect()->route('admin.cooperations.index')
            ->with('success', 'Dokumen kerja sama baru berhasil ditambahkan.');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $cooperation = Cooperation::findOrFail($id);
        return view('admin.cooperations.edit', compact('cooperation'));
    }

    /**
     * Update cooperation.
     */
    public function update(Request $request, $id)
    {
        $cooperation = Cooperation::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:1000',
            'partner_name' => 'required|string|max:255',
            'document_number' => 'required|string|max:255|unique:cooperations,document_number,' . $id,
            'document_type' => 'required|in:MoU,MoA,IA',
            'level' => 'required|in:Lokal,Nasional,Internasional',
            'scope' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Aktif,Akan Berakhir,Kedaluwarsa,Dalam Proses Perpanjangan',
            'is_public' => 'boolean',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'document_link' => 'nullable|url|max:1000',
            'file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $validated['is_public'] = $request->has('is_public');

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($cooperation->file_path && Storage::disk('public')->exists($cooperation->file_path)) {
                Storage::disk('public')->delete($cooperation->file_path);
            }
            $path = $request->file('file')->store('documents', 'public');
            $validated['file_path'] = $path;
        }

        $cooperation->update($validated);

        return redirect()->route('admin.cooperations.index')
            ->with('success', 'Data dokumen kerja sama berhasil diperbarui.');
    }

    /**
     * Delete cooperation.
     */
    public function destroy($id)
    {
        $cooperation = Cooperation::findOrFail($id);

        if ($cooperation->file_path && Storage::disk('public')->exists($cooperation->file_path)) {
            Storage::disk('public')->delete($cooperation->file_path);
        }

        $cooperation->delete();

        return redirect()->route('admin.cooperations.index')
            ->with('success', 'Dokumen kerja sama telah dihapus.');
    }

    /**
     * Expiration Monitoring & Notification Panel.
     */
    public function notifications()
    {
        $today = Carbon::today();

        // Expiring in 90 days
        $expiringIn90 = Cooperation::whereBetween('end_date', [$today, $today->copy()->addDays(90)])
            ->orWhere('status', 'Akan Berakhir')
            ->orderBy('end_date', 'asc')
            ->get();

        // Expired documents
        $expired = Cooperation::where('end_date', '<', $today)
            ->orWhere('status', 'Kedaluwarsa')
            ->orderBy('end_date', 'desc')
            ->get();

        return view('admin.notifications', compact('expiringIn90', 'expired'));
    }

    /**
     * Simulate sending email notification for document expiration.
     */
    public function sendReminder(Request $request, $id)
    {
        $cooperation = Cooperation::findOrFail($id);

        return back()->with('success', "Notifikasi pengingat sukses dikirimkan ke email pengelola ({$cooperation->contact_email} / pengelola@stikespantiwaluya.ac.id) untuk dokumen '{$cooperation->title}'.");
    }

    /**
     * Quick toggle hide expired documents setting for public catalog.
     */
    public function toggleHideExpired(Request $request)
    {
        $current = \App\Models\SiteSetting::getByKey('hide_expired_public', '0');
        $newValue = $current === '1' ? '0' : '1';
        \App\Models\SiteSetting::setByKey('hide_expired_public', $newValue);

        $statusText = $newValue === '1' 
            ? 'DIAKTIFKAN. Dokumen yang kedaluwarsa disembunyikan dari katalog publik.' 
            : 'DINONAKTIFKAN. Seluruh dokumen (termasuk kedaluwarsa) dapat dilihat di katalog publik.';

        return back()->with('success', "Pengaturan Katalog Publik: {$statusText}");
    }

    /**
     * Quick toggle public visibility (Publik vs Internal).
     */
    public function togglePublic($id)
    {
        $cooperation = Cooperation::findOrFail($id);
        $cooperation->update(['is_public' => !$cooperation->is_public]);

        $statusText = $cooperation->is_public ? 'diizinkan untuk publik (Bisa diunduh)' : 'diubah menjadi akses internal (Terbuka ringkasan saja)';

        return back()->with('success', "Akses dokumen '{$cooperation->partner_name}' berhasil {$statusText}.");
    }
}
