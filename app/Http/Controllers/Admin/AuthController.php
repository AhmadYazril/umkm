<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\KasirController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::check() && Auth::user()->role === 'kasir') {
            return redirect()->route('kasir.index');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, false)) {
            $user = Auth::user();
            if ($user->role === 'kasir') {
                $request->session()->regenerate();
                KasirController::queueAuthCookie($user);

                return redirect()->route('kasir.index');
            }
            if ($user->role !== 'admin') {
                Auth::logout();

                return back()->withErrors(['email' => 'Akun Anda tidak memiliki akses admin.']);
            }
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
