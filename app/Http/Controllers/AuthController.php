<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Tampilan Halaman Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 2. Proses Login
    // 2. Proses Login
        public function login(Request $request)
        {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                // Pengecekan Role Admin
                if (Auth::user()->role === 'admin') {
                    // Hapus memori intended URL agar admin tidak nyasar ke route mahasiswa
                    session()->forget('url.intended'); 
                    
                    return redirect()->route('admin.dashboard');
                }

                // Pengecekan Role Mahasiswa
                return redirect()->route('mahasiswa.dashboard');
            }

            return back()->withErrors([
                'email' => 'Email atau password yang dimasukkan salah.',
            ])->onlyInput('email');
        }

    // 3. Tampilan Halaman Registrasi Mahasiswa
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // 4. Proses Registrasi Mahasiswa (Kembali ke Halaman Login)
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa', // Otomatis Mahasiswa
        ]);

        // ALUR DIPERBAIKI: Redirect ke Login dengan pesan sukses (TIDAK OTOMATIS LOGIN)
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login menggunakan akun Anda.');
    }

    // 5. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}