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
        $footerDescription = SiteSetting::getByKey(
            'footer_description', 
            'Sistem Informasi Terpadu Pengelolaan Dokumen Kerjasama (MoU, MoA, dan IA) untuk keterbukaan informasi publik dan pemenuhan standar akreditasi institusi & program studi.'
        );
        $footerAddress = SiteSetting::getByKey(
            'footer_address', 
            'Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang, Jawa Timur 65117'
        );
        $footerEmail = SiteSetting::getByKey('footer_email', 'kerjasama@stikespantiwaluya.ac.id');
        $footerPhone = SiteSetting::getByKey('footer_phone', '(0341) 369003');
        $footerMapsUrl = SiteSetting::getByKey(
            'footer_maps_url', 
            'https://maps.google.com/maps?q=STIKes+Panti+Waluya+Malang,+Jl.+Yulius+Usman+No.62,+Kasin,+Kec.+Klojen,+Kota+Malang,+Jawa+Timur+65117&t=&z=15&ie=UTF8&iwloc=&output=embed'
        );
        $footerCopyright = SiteSetting::getByKey(
            'footer_copyright', 
            '© ' . date('Y') . ' STIKes Panti Waluya Malang. Seluruh hak cipta dilindungi undang-undang.'
        );
        $footerTagline = SiteSetting::getByKey('footer_tagline', 'SPWM-Collab v1.0 • Publik Directory');

        return view('admin.settings.index', compact(
            'hideExpiredSetting',
            'footerDescription',
            'footerAddress',
            'footerEmail',
            'footerPhone',
            'footerMapsUrl',
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
            'footer_description' => 'required|string|max:1000',
            'footer_address' => 'required|string|max:500',
            'footer_email' => 'required|email|max:255',
            'footer_phone' => 'required|string|max:100',
            'footer_maps_url' => 'nullable|string|max:2000',
            'footer_copyright' => 'required|string|max:500',
            'footer_tagline' => 'required|string|max:255',
        ]);

        SiteSetting::setByKey('footer_description', $request->input('footer_description'));
        SiteSetting::setByKey('footer_address', $request->input('footer_address'));
        SiteSetting::setByKey('footer_email', $request->input('footer_email'));
        SiteSetting::setByKey('footer_phone', $request->input('footer_phone'));
        if ($request->filled('footer_maps_url')) {
            SiteSetting::setByKey('footer_maps_url', $request->input('footer_maps_url'));
        }
        SiteSetting::setByKey('footer_copyright', $request->input('footer_copyright'));
        SiteSetting::setByKey('footer_tagline', $request->input('footer_tagline'));

        return back()->with('success', 'Seluruh konten dan informasi footer halaman publik berhasil diperbarui.');
    }
}
