<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\KasirController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public const ADMIN_COOKIE = 'nucomu_admin_auth_v1';

    public const ADMIN_COOKIE_MINUTES = 60 * 24 * 30; // 30 days

    public static function generateAdminCookieToken(User $user): string
    {
        return hash_hmac('sha256', 'nucomu_admin|'.$user->id.'|'.$user->email, (string) config('app.key'));
    }

    public static function queueAdminAuthCookie(User $user): string
    {
        $cookieValue = $user->id.'|'.self::generateAdminCookieToken($user);

        Cookie::queue(
            Cookie::make(
                self::ADMIN_COOKIE,
                $cookieValue,
                self::ADMIN_COOKIE_MINUTES,
                '/',
                null,
                false,
                true,
                false,
                'lax'
            )
        );

        return $cookieValue;
    }

    public static function checkOrRestoreAdminAuth(Request $request): bool
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return true;
        }

        $cookieValue = (string) ($request->cookie(self::ADMIN_COOKIE) ?? '');
        if ($cookieValue !== '' && str_contains($cookieValue, '|')) {
            [$userId, $token] = explode('|', $cookieValue, 2);
            if (is_numeric($userId) && $token !== '') {
                try {
                    $user = User::find((int) $userId);
                    if ($user && $user->role === 'admin' && hash_equals(self::generateAdminCookieToken($user), $token)) {
                        Auth::login($user, false);

                        return true;
                    }
                } catch (\Throwable $e) {
                    return false;
                }
            }
        }

        return false;
    }

    public function showLogin(Request $request)
    {
        if (self::checkOrRestoreAdminAuth($request)) {
            return redirect()->route('admin.dashboard');
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
                $request->session()->put('kasir_authenticated', true);
                KasirController::queueAuthCookie($user);

                return redirect()->route('kasir.index');
            }
            if ($user->role !== 'admin') {
                Auth::logout();

                return back()->withErrors(['email' => 'Akun Anda tidak memiliki akses admin.']);
            }
            $request->session()->regenerate();
            self::queueAdminAuthCookie($user);

            $intended = $request->session()->pull('url.intended', route('admin.dashboard'));
            if (! str_contains($intended, '/admin') || str_contains($intended, '/admin/login')) {
                $intended = route('admin.dashboard');
            }

            return redirect()->to($intended);
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Cookie::queue(Cookie::forget(self::ADMIN_COOKIE));

        return redirect()->route('admin.login');
    }
}
