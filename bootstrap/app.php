<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ZendoTrackingController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\KasirMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'kasir' => KasirMiddleware::class,
        ]);
        $middleware->encryptCookies(except: [
            ZendoTrackingController::DEVICE_COOKIE,
            KasirController::KASIR_COOKIE,
            AdminAuthController::ADMIN_COOKIE,
        ]);
        $middleware->validateCsrfTokens(except: [
            'reservasi',
            'reservasi/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            $postMax = ini_get('post_max_size') ?: '8M';
            $message = "Total ukuran berkas yang dikirim melebihi batas server PHP (post_max_size: {$postMax}). Perkecil ukuran file atau hubungi pengelola server untuk menaikkan nilai post_max_size di php.ini.";

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 413);
            }

            return redirect()->back()
                ->withInput($request->except('_token'))
                ->withErrors([
                    'media_file' => $message,
                    'image' => $message,
                    'avatar' => $message,
                    'cafe_logo' => $message,
                    'photo_files' => $message,
                    'file' => $message,
                ])
                ->with('error', $message);
        });

        $handleCsrfExpired = function (Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Sesi keamanan (CSRF) telah kedaluwarsa.'], 419);
            }

            $fallback = $request->is('admin*')
                ? route('admin.login')
                : ($request->is('kasir*') ? route('kasir.login') : route('home'));

            return redirect()->back(fallback: $fallback)
                ->withInput($request->except('password', 'password_confirmation', '_token'))
                ->with('session_expired', 'Sesi keamanan (CSRF) telah kedaluwarsa karena tidak ada aktivitas. Halaman telah diperbarui dengan sesi baru, silakan masukkan kembali kata sandi Anda.');
        };

        $exceptions->render(function (HttpException $e, Request $request) use ($handleCsrfExpired) {
            if ($e->getStatusCode() === 419) {
                return $handleCsrfExpired($request);
            }
        });

        $exceptions->render(function (TokenMismatchException $e, Request $request) use ($handleCsrfExpired) {
            return $handleCsrfExpired($request);
        });
    })->create();
