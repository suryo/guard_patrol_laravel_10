<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $cred = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Panggil endpoint API /login (internal app)
        // Sesuaikan struktur respons sesuai AuthController Anda.
        $apiUrl = url('/api/login');

        $response = Http::post($apiUrl, $cred);

        if (!$response->successful()) {
            return back()->withInput()->with('error', 'Login gagal. Periksa email/password.');
        }

        $data = $response->json();

        // CONTOH struktur umum respons:
        // $data = ['token' => 'JWT...', 'user' => ['name' => '...', 'email' => '...']]
        // Jika berbeda (mis. access_token), sesuaikan key-nya.
        $token = $data['token'] ?? $data['access_token'] ?? null;
        $user  = $data['user']  ?? null;

        if (!$token) {
            return back()->withInput()->with('error', 'Token tidak ditemukan pada respons API.');
        }

        // Simpan ke session
        session([
            'jwt_token' => $token,
            'user'      => $user,
        ]);

        return redirect()->route('dashboard')->with('ok', 'Login berhasil.');
    }

    public function logout(Request $request)
    {
        // Hapus data session
        $request->session()->forget(['jwt_token', 'user']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('ok', 'Anda telah logout.');
    }
}
