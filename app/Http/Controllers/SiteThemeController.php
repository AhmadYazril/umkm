<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSiteThemeRequest;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class SiteThemeController extends Controller
{
    /**
     * Public READ-ONLY endpoint returning the active global site theme.
     */
    public function show(): JsonResponse
    {
        return response()
            ->json([
                'theme' => Setting::getSiteTheme(true),
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    /**
     * Admin-only endpoint to update the active global site theme ('light' or 'dark').
     */
    public function update(UpdateSiteThemeRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $theme = Setting::setSiteTheme($validated['theme']);
        $label = $theme === Setting::THEME_DARK ? 'Gelap (Dark)' : 'Terang (Light)';
        $message = "Tema publik berhasil diperbarui ke mode {$label}. Perubahan langsung berlaku untuk semua pengunjung.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'theme' => $theme,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
