<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function index()
    {
        return view('auth.login');
    }

    // Proses pengecekan login
    public function login(Request $request)
    {
        // Validasi inputan user
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cek apakah email & password cocok dengan database
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Ambil data user yang sedang login
            $user = Auth::user();

            // Cek Role dan arahkan ke halaman yang sesuai
            if ($user->role == 'admin') {
                return redirect()->intended('/dashboard');
            } elseif ($user->role == 'kasir') {
                return redirect()->intended('/transaksi');
            }

            // Default jika role tidak dikenali
            return redirect('/');
        }

        // Jika gagal, balik ke halaman login dengan pesan error
        return back()->with('error', 'Email atau Password salah!');
    }

    // Proses keluar dari aplikasi
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
