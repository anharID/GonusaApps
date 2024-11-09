<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        // Cek jika user sudah login
        if (Auth::check()) {
            return redirect()->intended('dashboard');
        }

        // Jika belum login, tampilkan halaman login
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'user_code' => 'required|string',
            'user_password' => 'required|string',
        ]);

        // Manual authentication
        $user = User::where('user_code', $request->user_code)->first();

        // Cek apakah user ditemukan
        if (!$user) {
            return back()->withErrors([
                'user_code' => 'User tidak ditemukan.',
            ])->withInput($request->only('user_code'));
        }

        // Cek status user aktif/nonaktif
        if (!$user->data_status) {
            return back()->withErrors([
                'user_code' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.',
            ])->withInput($request->only('user_code'));
        }

        // Cek password
        if (!Hash::check($request->user_password, $user->user_password)) {
            return back()->withErrors([
                'user_code' => 'Password yang Anda masukkan salah.',
            ])->withInput($request->only('user_code'));
        }

        // Jika semua validasi berhasil, login user
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}