<?php

namespace App\Http\Middleware;

use App\Http\Controllers\KasirController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KasirMiddleware
{
    /**
     * Handle an incoming request for the Kasir workspace.
     * Automatically restores authentication from the persistent Kasir cookie if present.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! KasirController::checkOrRestoreKasirAuth($request)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('kasir.login');
        }

        return $next($request);
    }
}
