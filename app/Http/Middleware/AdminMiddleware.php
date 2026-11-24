<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Admin\AuthController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! AuthController::checkOrRestoreAdminAuth($request)) {
            if ($request->expectsJson()) {
                if (! Auth::check()) {
                    return response()->json(['message' => 'Unauthenticated.'], 401);
                }

                return response()->json(['message' => 'Forbidden. Akses khusus admin.'], 403);
            }

            if (Auth::check()) {
                abort(403, 'Akses khusus admin.');
            }

            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
