<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    /**
     * Display a listing of testimonials/reviews for admin curation.
     * Admin CANNOT add or edit review texts.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->input('q', ''));
        $filter = trim((string) $request->input('filter', 'all'));
        $rating = $request->input('rating');
        $status = trim((string) $request->input('status', 'all'));

        $query = Testimonial::query();

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%")
                    ->orWhere('handle', 'like', "%{$q}%")
                    ->orWhere('order_tag', 'like', "%{$q}%");
            });
        }

        if ($filter === 'pinned') {
            $query->where('is_pinned', true);
        } elseif ($filter === 'unpinned') {
            $query->where('is_pinned', false);
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($rating && in_array((int) $rating, [1, 2, 3, 4, 5], true)) {
            $query->where('rating', (int) $rating);
        }

        $testimonials = $query->orderByDesc('is_pinned')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $totalCount = Testimonial::count();
        $pinnedCount = Testimonial::where('is_pinned', true)->count();
        $unpinnedCount = Testimonial::where('is_pinned', false)->count();
        $avgRating = round((float) (Testimonial::avg('rating') ?: 5.0), 1);

        return view('admin.testimonials.index', compact(
            'testimonials',
            'totalCount',
            'pinnedCount',
            'unpinnedCount',
            'avgRating',
            'q',
            'filter',
            'rating',
            'status'
        ));
    }

    /**
     * Toggle pinned status of a review.
     * Only pinned reviews are displayed on the public company profile.
     */
    public function togglePin(Testimonial $testimonial, Request $request): JsonResponse|RedirectResponse
    {
        $testimonial->is_pinned = ! $testimonial->is_pinned;

        // If pinned, ensure it is also active
        if ($testimonial->is_pinned) {
            $testimonial->is_active = true;
        }

        $testimonial->save();

        $message = $testimonial->is_pinned
            ? "Ulasan dari {$testimonial->name} berhasil disematkan (di-pin) ke website!"
            : "Sematkan (pin) ulasan dari {$testimonial->name} berhasil dilepas.";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_pinned' => (bool) $testimonial->is_pinned,
                'is_active' => (bool) $testimonial->is_active,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Toggle active/inactive status (hide inappropriate reviews without permanent deletion).
     */
    public function toggleActive(Testimonial $testimonial, Request $request): JsonResponse|RedirectResponse
    {
        $testimonial->is_active = ! $testimonial->is_active;

        // If deactivated, automatically unpin
        if (! $testimonial->is_active) {
            $testimonial->is_pinned = false;
        }

        $testimonial->save();

        $message = $testimonial->is_active
            ? "Ulasan dari {$testimonial->name} diaktifkan kembali."
            : "Ulasan dari {$testimonial->name} disembunyikan dari publik.";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => (bool) $testimonial->is_active,
                'is_pinned' => (bool) $testimonial->is_pinned,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Delete an inappropriate or spam review permanently.
     */
    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $name = $testimonial->name ?: 'Pelanggan';
        $testimonial->deleteReviewImageFile();
        $testimonial->delete();

        return back()->with('success', "Ulasan dari {$name} berhasil dihapus permanen.");
    }
}
