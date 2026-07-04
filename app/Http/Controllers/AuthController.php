<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 3a. Field kosong
        $request->validate([
            'username_admin' => 'required',
            'password_admin' => 'required',
        ], [
            'username_admin.required' => 'Username harus diisi.',
            'password_admin.required' => 'Password harus diisi.',
        ]);

        // 3b. Cek username ditemukan atau tidak
        $admin = Admin::where('username_admin', $request->username_admin)->first();

        if (!$admin) {
            return back()->withErrors([
                'username_admin' => 'Username tidak ditemukan.',
                'password_admin' => 'Password salah.',
            ])->withInput();
        }

        // 3c. Cek password salah
        if (!Hash::check($request->password_admin, $admin->password_admin)) {
            return back()->withErrors([
                'username_admin' => 'Username tidak ditemukan.',
                'password_admin' => 'Password salah.',
            ])->withInput();
        }

        // Login berhasil
        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}