<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Admin\AuthController;
use Closure;
use Illuminate\Http\Request;
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
                return response()->json(['message' => 'Unauthorized.'], 403);
            }

            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
