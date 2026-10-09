<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Facility;
use App\Models\GalleryItem;
use App\Models\OperatingHour;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        $hours    = OperatingHour::orderBy('day_of_week')->get();
        $facilities = Facility::orderBy('sort_order')->get();
        return view('admin.settings.index', compact('settings', 'hours', 'facilities'));
    }

    public function update(Request $request)
    {
        $keys = [
            'cafe_name', 'cafe_tagline', 'cafe_subtitle', 'cafe_story', 'cafe_history',
            'cafe_concept', 'cafe_address', 'cafe_maps_url', 'cafe_whatsapp',
            'cafe_email', 'cafe_instagram', 'cafe_tiktok',
        ];
        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }
        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function updateHours(Request $request)
    {
        $days = $request->input('hours', []);
        foreach ($days as $dayOfWeek => $data) {
            OperatingHour::updateOrCreate(
                ['day_of_week' => $dayOfWeek],
                [
                    'open_time'  => $data['is_closed'] ?? false ? null : ($data['open_time'] ?? null),
                    'close_time' => $data['is_closed'] ?? false ? null : ($data['close_time'] ?? null),
                    'is_closed'  => isset($data['is_closed']),
                ]
            );
        }
        return back()->with('success', 'Jam operasional berhasil diperbarui.');
    }

    // ── Reservasi ──────────────────────────────────────
    public function reservations(Request $request)
    {
        $query = Reservation::query();
        if ($request->filled('status')) $query->where('status', $request->status);
        $reservations = $query->latest()->paginate(20);
        return view('admin.reservations.index', compact('reservations'));
    }

    public function updateReservation(Request $request, Reservation $reservation)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,cancelled']);
        $reservation->update(['status' => $request->status]);
        return back()->with('success', 'Status reservasi diperbarui.');
    }

    // ── Galeri ─────────────────────────────────────────
    public function gallery()
    {
        $items = GalleryItem::orderBy('sort_order')->get();
        return view('admin.gallery.index', compact('items'));
    }

    public function storeGallery(Request $request)
    {
        $request->validate(['image' => 'required|image|max:4096', 'caption' => 'nullable|string', 'category' => 'nullable|string']);
        $path = $request->file('image')->store('gallery', 'public');
        GalleryItem::create([
            'image'      => $path,
            'caption'    => $request->caption,
            'category'   => $request->category ?? 'ambience',
            'sort_order' => GalleryItem::max('sort_order') + 1,
        ]);
        return back()->with('success', 'Foto berhasil ditambahkan.');
    }

    public function destroyGallery(GalleryItem $galleryItem)
    {
        Storage::disk('public')->delete($galleryItem->image);
        $galleryItem->delete();
        return back()->with('success', 'Foto berhasil dihapus.');
    }

    // ── Testimoni ──────────────────────────────────────
    public function testimonials()
    {
        $testimonials = Testimonial::latest()->paginate(20);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function storeTestimonial(Request $request)
    {
        $request->validate(['name' => 'required', 'content' => 'required', 'rating' => 'required|integer|min:1|max:5']);
        Testimonial::create([
            'name'      => $request->name,
            'content'   => $request->content,
            'rating'    => $request->rating,
            'is_sample' => false,
            'is_active' => true,
        ]);
        return back()->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function destroyTestimonial(Testimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success', 'Testimoni berhasil dihapus.');
    }

    public function deleteSampleTestimonials()
    {
        $count = Testimonial::where('is_sample', true)->count();
        Testimonial::where('is_sample', true)->delete();
        return back()->with('success', "$count testimoni data contoh berhasil dihapus.");
    }

    // ── Events ─────────────────────────────────────────
    public function events()
    {
        $events = Event::latest()->paginate(20);
        return view('admin.events.index', compact('events'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate(['title' => 'required', 'description' => 'required', 'date' => 'nullable|date']);
        $data = $request->only(['title', 'description', 'date']);
        $data['is_active'] = true;
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }
        Event::create($data);
        return back()->with('success', 'Event berhasil ditambahkan.');
    }

    public function destroyEvent(Event $event)
    {
        if ($event->image) Storage::disk('public')->delete($event->image);
        $event->delete();
        return back()->with('success', 'Event berhasil dihapus.');
    }
}
