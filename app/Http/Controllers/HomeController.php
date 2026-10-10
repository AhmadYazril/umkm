<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Facility;
use App\Models\GalleryItem;
use App\Models\Menu;
use App\Models\OperatingHour;
use App\Models\Setting;
use App\Models\Testimonial;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $featuredMenus = Menu::available()
            ->where(function ($query) {
                $query->where('is_featured', true)
                    ->orWhere('is_best_seller', true);
            })
            ->with('category')
            ->take(8)
            ->get();

        if ($featuredMenus->isEmpty()) {
            $featuredMenus = Menu::available()->with('category')->take(8)->get();
        }
        $facilities = Facility::where('is_active', true)->orderBy('sort_order')->get();
        $hours = OperatingHour::orderBy('day_of_week')->get();
        $testimonials = Testimonial::where('is_active', true)->take(6)->get();
        $gallery = GalleryItem::orderBy('sort_order')->take(6)->get();
        $events = Event::where('is_active', true)->orderBy('date')->take(3)->get();
        $settings = Setting::pluck('value', 'key');

        // Status buka/tutup sekarang
        $now = Carbon::now('Asia/Jakarta');
        $todayDow = $now->dayOfWeek; // 0=Minggu
        $todayHour = $hours->firstWhere('day_of_week', $todayDow);
        $isOpen = false;
        if ($todayHour && ! $todayHour->is_closed) {
            $openTime = Carbon::parse($todayHour->open_time, 'Asia/Jakarta');
            $closeTime = Carbon::parse($todayHour->close_time, 'Asia/Jakarta');
            $isOpen = $now->between($openTime, $closeTime);
        }

        return view('public.home', compact(
            'featuredMenus', 'facilities', 'hours', 'testimonials',
            'gallery', 'events', 'settings', 'isOpen', 'todayHour', 'now'
        ));
    }
}
