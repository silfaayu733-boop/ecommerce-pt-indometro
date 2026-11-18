<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi admin.']);
            }

            return $this->redirectByRole($user);
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:128',
            'email' => 'required|string|email|max:128|unique:users',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'image' => 'default.jpg',
            'password' => Hash::make($request->password),
            'role_id' => 4, // 4: Customer
            'is_active' => 1,
        ]);

        Auth::login($user);
        return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang di PT. Indo Metro Surya Andola.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda telah berhasil keluar.');
    }

    private function redirectByRole($user)
    {
        return match ($user->role_id) {
            1 => redirect()->intended('/admin/dashboard'),
            2 => redirect()->intended('/kasir/dashboard'),
            3 => redirect()->intended('/gudang/dashboard'),
            default => redirect()->intended('/'),
        };
    }
}

