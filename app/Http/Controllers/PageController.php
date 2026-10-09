<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Facility;
use App\Models\GalleryItem;
use App\Models\OperatingHour;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $settings = Setting::pluck('value', 'key');
        return view('public.about', compact('settings'));
    }

    public function gallery()
    {
        $items = GalleryItem::orderBy('sort_order')->get();
        return view('public.gallery', compact('items'));
    }

    public function facilities()
    {
        $facilities = Facility::where('is_active', true)->orderBy('sort_order')->get();
        return view('public.facilities', compact('facilities'));
    }

    public function reservation()
    {
        $hours = OperatingHour::orderBy('day_of_week')->get();
        return view('public.reservation', compact('hours'));
    }

    public function storeReservation(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:100',
            'phone'  => 'required|string|max:20',
            'date'   => 'required|date|after:today',
            'time'   => 'required',
            'guests' => 'required|integer|min:1|max:50',
            'notes'  => 'nullable|string|max:500',
        ]);

        Reservation::create([
            'name'   => $request->name,
            'phone'  => $request->phone,
            'date'   => $request->date,
            'time'   => $request->time,
            'guests' => $request->guests,
            'notes'  => $request->notes,
            'status' => 'pending',
        ]);

        $whatsapp = Setting::get('cafe_whatsapp', '');
        $message  = urlencode("Halo Nucomu Cafe, saya {$request->name} ingin konfirmasi reservasi untuk {$request->guests} orang pada {$request->date} pukul {$request->time}. Terima kasih!");
        $waUrl    = $whatsapp ? "https://wa.me/{$whatsapp}?text={$message}" : null;

        return view('public.reservation-success', compact('waUrl'));
    }

    public function contact()
    {
        $settings = Setting::pluck('value', 'key');
        $hours    = OperatingHour::orderBy('day_of_week')->get();
        return view('public.contact', compact('settings', 'hours'));
    }

    public function events()
    {
        $events = Event::where('is_active', true)->orderBy('date')->get();
        return view('public.events', compact('events'));
    }
}
