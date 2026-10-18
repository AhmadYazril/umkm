<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Reservation;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NucomuController extends Controller
{
    /**
     * Display the main landing page with all 5 sections.
     */
    public function index()
    {
        $brand = config('nucomu.brand');
        $contact = config('nucomu.contact');
        $facilities = config('nucomu.facilities');
        $bestMenu = config('nucomu.best_menu');
        $allMenu = config('nucomu.all_menu');
        $gallery = config('nucomu.gallery');
        $services = config('nucomu.services');
        $integrations = config('nucomu.integrations');
        $menuCategories = config('nucomu.menu_categories');
        $recommendations = config('nucomu.recommendations');
        $reviews = config('nucomu.reviews');

        // Check if database settings override contact info
        try {
            $dbSettings = Setting::pluck('value', 'key');
            if (! empty($dbSettings['cafe_name'])) {
                $brand['name'] = $dbSettings['cafe_name'];
            }
            if (! empty($dbSettings['cafe_address'])) {
                $contact['address'] = $dbSettings['cafe_address'];
            }
            if (! empty($dbSettings['cafe_whatsapp'])) {
                $contact['whatsapp_number'] = $dbSettings['cafe_whatsapp'];
                $contact['whatsapp_display'] = $dbSettings['cafe_whatsapp'];
            }
        } catch (\Throwable $e) {
            // Fallback gracefully to config
        }

        return view('home.index', compact(
            'brand',
            'contact',
            'facilities',
            'bestMenu',
            'allMenu',
            'gallery',
            'services',
            'integrations',
            'menuCategories',
            'recommendations',
            'reviews'
        ));
    }

    /**
     * Display the dedicated full All Menu catalog page.
     */
    public function allMenu(Request $request)
    {
        $brand = config('nucomu.brand');
        $contact = config('nucomu.contact');

        $configMenu = collect(config('nucomu.all_menu', []));

        // Attempt to load from database and merge
        try {
            $dbMenus = Menu::available()->with('category')->get()->map(function ($m) {
                $catSlug = $m->category ? strtolower($m->category->slug ?? $m->category->name) : 'coffee';
                // Normalize category to match filter tabs: coffee, non-coffee, dessert, snacks
                if (str_contains($catSlug, 'non') || str_contains($catSlug, 'tea') || str_contains($catSlug, 'beverage')) {
                    $normCat = 'non-coffee';
                } elseif (str_contains($catSlug, 'dessert') || str_contains($catSlug, 'pastry') || str_contains($catSlug, 'cake')) {
                    $normCat = 'dessert';
                } elseif (str_contains($catSlug, 'snack') || str_contains($catSlug, 'meal') || str_contains($catSlug, 'food')) {
                    $normCat = 'snacks';
                } else {
                    $normCat = 'coffee';
                }

                $img = null;
                if ($m->image && file_exists(public_path('storage/'.$m->image))) {
                    $img = asset('storage/'.$m->image);
                } elseif ($m->image && (str_starts_with($m->image, 'http://') || str_starts_with($m->image, 'https://'))) {
                    $img = $m->image;
                }

                return [
                    'id' => $m->slug ?: 'menu-'.$m->id,
                    'name' => $m->name,
                    'category' => $normCat,
                    'category_label' => $m->category->name ?? ucfirst($normCat),
                    'price' => (int) $m->price,
                    'price_formatted' => 'Rp '.number_format($m->price, 0, ',', '.'),
                    'short_info' => $m->description ?? 'Racikan pilihan Nucomu Cafe.',
                    'image' => $img ?: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&q=80&w=800',
                ];
            });

            if ($dbMenus->isNotEmpty()) {
                $allMenu = $dbMenus->concat($configMenu)->unique('name')->values();
            } else {
                $allMenu = $configMenu;
            }
        } catch (\Throwable $e) {
            $allMenu = $configMenu;
        }

        $selectedCategory = $request->query('category', 'all');

        if ($selectedCategory !== 'all') {
            $filteredMenu = $allMenu->filter(function ($item) use ($selectedCategory) {
                return $item['category'] === $selectedCategory;
            })->values();
        } else {
            $filteredMenu = $allMenu;
        }

        return view('menu.all', compact('brand', 'contact', 'filteredMenu', 'selectedCategory'));
    }

    /**
     * Validate reservation, save to database, and generate formatted WhatsApp link.
     */
    public function submitReservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullName' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'min:9', 'max:20'],
            'date' => ['required', 'date'],
            'time' => ['required', 'string'],
            'guestCount' => ['required', 'string'],
            'serviceType' => ['required', 'string'],
            'area' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'fullName.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp aktif wajib diisi.',
            'phone.regex' => 'Format nomor WhatsApp tidak valid.',
            'date.required' => 'Tanggal acara wajib dipilih.',
            'time.required' => 'Jam acara wajib ditentukan.',
            'guestCount.required' => 'Jumlah tamu wajib ditentukan.',
            'serviceType.required' => 'Jenis acara atau layanan wajib dipilih.',
            'area.required' => 'Pilihan area (indoor/outdoor) wajib ditentukan.',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax() || $request->isJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi formulir belum terpenuhi.',
                    'errors' => $validator->errors()->all(),
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // 1. Save reservation to database so it appears in Admin Dashboard
        try {
            $guestInt = (int) preg_replace('/[^0-9]/', '', $validated['guestCount']);
            Reservation::create([
                'name' => $validated['fullName'],
                'phone' => $validated['phone'],
                'date' => $validated['date'],
                'time' => $validated['time'],
                'guests' => $guestInt > 0 ? $guestInt : 2,
                'notes' => trim("Layanan: {$validated['serviceType']} | Area: {$validated['area']}".($validated['notes'] ? " | Catatan: {$validated['notes']}" : '')),
                'status' => 'pending',
            ]);
        } catch (\Throwable $e) {
            Log::warning('Gagal menyimpan reservasi ke DB: '.$e->getMessage());
        }

        // 2. Format Indonesian date safely without requiring intl extension
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $carbonDate = Carbon::parse($validated['date']);
        $formattedDate = $carbonDate->format('d').' '.($months[(int) $carbonDate->format('m')] ?? $carbonDate->format('F')).' '.$carbonDate->format('Y');

        $destinationNumber = config('nucomu.contact.whatsapp_number', '6281234567890');

        $formattedMessage = "Halo Nucomu Cafe, saya ingin mengajukan reservasi:\n\n".
            '• Nama: '.$validated['fullName']."\n".
            '• WhatsApp: '.$validated['phone']."\n".
            '• Tanggal: '.$formattedDate."\n".
            '• Waktu: '.$validated['time']." WIB\n".
            '• Jumlah Tamu: '.$validated['guestCount']."\n".
            '• Layanan / Acara: '.$validated['serviceType']."\n".
            '• Area: '.$validated['area']."\n".
            '• Catatan Khusus: '.($validated['notes'] ? $validated['notes'] : '-')."\n\n".
            'Mohon informasi ketersediaan jadwal dan tindak lanjutnya. Terima kasih.';

        $whatsappUrl = 'https://wa.me/'.preg_replace('/[^0-9]/', '', $destinationNumber).'?text='.rawurlencode($formattedMessage);

        if ($request->wantsJson() || $request->ajax() || $request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Validasi dan penyimpanan berhasil. Mengarahkan ke WhatsApp...',
                'whatsapp_url' => $whatsappUrl,
                'data' => $validated,
            ]);
        }

        return redirect()->away($whatsappUrl);
    }
}
