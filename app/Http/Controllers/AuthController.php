<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            if (Auth::check()) {
                return redirect()->route('admin'); // Arahkan ke dashboard jika sudah login
            }
    
            // Jika belum login, tampilkan halaman login
            $title = "Login";
            return view('admin.auth.login', compact('title'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function auth(Request $request)
    {
        try {
            // Validasi Form
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|'
            ],
            [
                'email.required' => 'email harus di isi',
                'email.email' => 'inputan harus berupa email',
                'password' => 'password harus di isi'
            ]);

            // Coba untuk login
            if (Auth::attempt($credentials, $request->filled('remember'))) {
                // Jika berhasil login, regenerate session
                $request->session()->regenerate();

                // Arahkan ke halaman dashboard
                return redirect()->intended('dashboard');
            }

            // Jika gagal login, kembali dengan pesan error
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ])->withInput($request->except('password')); // Kembalikan input kecuali password

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } 
    }
}
