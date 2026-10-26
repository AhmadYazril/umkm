<?php

use App\Http\Controllers\ZendoTrackingController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

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
        ]);
        $middleware->encryptCookies(except: [
            ZendoTrackingController::DEVICE_COOKIE,
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

        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Sesi keamanan telah kedaluwarsa.'], 419);
            }

            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation', '_token'))
                ->with('session_expired', 'Sesi keamanan (CSRF) telah kedaluwarsa karena tidak ada aktivitas. Halaman telah diperbarui, silakan masukkan kembali kata sandi Anda.');
        });
    })->create();
