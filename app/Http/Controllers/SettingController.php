<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display system settings form.
     */
    public function index()
    {
        $hideExpiredSetting = SiteSetting::getByKey('hide_expired_public', '0') === '1';
        $footerCopyright = SiteSetting::getByKey('footer_copyright', '© ' . date('Y') . ' STIKes Panti Waluya Malang. Seluruh hak cipta dilindungi undang-undang.');
        $footerTagline = SiteSetting::getByKey('footer_tagline', 'SPWM-Collab v1.0 • Publik Directory');

        return view('admin.settings.index', compact(
            'hideExpiredSetting',
            'footerCopyright',
            'footerTagline'
        ));
    }

    /**
     * Toggle hide expired documents setting.
     */
    public function toggleHideExpired()
    {
        $current = SiteSetting::getByKey('hide_expired_public', '0');
        $newValue = $current === '1' ? '0' : '1';
        SiteSetting::setByKey('hide_expired_public', $newValue);

        $statusText = $newValue === '1' 
            ? 'DIAKTIFKAN (Dokumen kedaluwarsa disembunyikan dari publik).' 
            : 'DINONAKTIFKAN (Seluruh dokumen ditampilkan di publik).';

        return back()->with('success', "Filter Katalog Publik: {$statusText}");
    }

    /**
     * Update custom footer text settings.
     */
    public function updateFooter(Request $request)
    {
        $request->validate([
            'footer_copyright' => 'required|string|max:500',
            'footer_tagline' => 'required|string|max:255',
        ]);

        SiteSetting::setByKey('footer_copyright', $request->input('footer_copyright'));
        SiteSetting::setByKey('footer_tagline', $request->input('footer_tagline'));

        return back()->with('success', 'Teks footer halaman publik berhasil diperbarui.');
    }
}
