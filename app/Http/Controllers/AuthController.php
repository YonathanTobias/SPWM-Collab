<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'pimpinan') {
                return redirect()->route('dashboard.index');
            }
            return redirect()->route('admin.cooperations.index');
        }

        return view('auth.login');
    }

    /**
     * Authenticate user.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'pimpinan') {
                return redirect()->intended(route('dashboard.index'))->with('success', 'Selamat datang kembali, Pimpinan STIKes Panti Waluya Malang.');
            }

            return redirect()->intended(route('admin.cooperations.index'))->with('success', 'Selamat datang di Panel Pengelola Dokumen Kerjasama.');
        }

        return back()->withErrors([
            'email' => 'Kombinasi email dan kata sandi yang dimasukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('public.index')->with('info', 'Anda telah berhasil keluar dari sistem.');
    }
}
