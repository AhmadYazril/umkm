<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\GalleryItem;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\ZendoDelivery;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
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

        // Fetch stories from DB or fallback
        $stories = collect();
        try {
            $stories = GalleryItem::orderBy('sort_order')->get()->map(function (GalleryItem $item) {
                $imageUrl = null;
                if ($item->image && file_exists(public_path('storage/'.$item->image))) {
                    $imageUrl = asset('storage/'.$item->image);
                } elseif ($item->image && (str_starts_with($item->image, 'http://') || str_starts_with($item->image, 'https://'))) {
                    $imageUrl = $item->image;
                }

                return [
                    'image' => $imageUrl ?? 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => $item->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                    'author' => $item->author ?? 'Barista Corner',
                    'time_label' => $item->time_label ?? 'Baru saja',
                    'category_badge' => $item->category_badge ?? ('#'.($item->category ?? 'Nucomu')),
                    'caption' => $item->caption ?? 'Nucomu Cafe',
                    'description' => $item->description ?? '',
                ];
            });
        } catch (\Throwable $e) {
        }

        if ($stories->isEmpty()) {
            $stories = collect([
                [
                    'image' => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                    'author' => 'Barista Corner',
                    'time_label' => '2 Jam Lalu',
                    'category_badge' => '#CraftCoffee',
                    'caption' => 'Manual Pour-over Beans House Blend',
                    'description' => 'Proses seduh perlahan untuk mengekstrak aroma fruity & floral yang lembut dan khas.',
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
                    'author' => 'Nucomu Nook',
                    'time_label' => '4 Jam Lalu',
                    'category_badge' => '#CozyVibes',
                    'caption' => 'Sudut Favorit Work From Cafe',
                    'description' => 'Pencahayaan hangat, musik lo-fi tenang, & colokan listrik cepat di tiap meja.',
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80',
                    'author' => 'Bakery Kitchen',
                    'time_label' => '6 Jam Lalu',
                    'category_badge' => '#FreshBakery',
                    'caption' => 'Butter Croissant Dipanggang Pagi Ini',
                    'description' => 'Tekstur luar yang renyah flaky bersanding sempurna dengan hangatnya Cappuccino.',
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80',
                    'author' => 'Night Vibe',
                    'time_label' => 'Kemarin',
                    'category_badge' => '#AcousticNight',
                    'caption' => 'Suasana Hangat Malam Hari',
                    'description' => 'Malam makin syahdu dengan racikan Signature Mocktail dan obrolan hangat.',
                ],
            ]);
        }

        // Combine all Google Maps reviews and website-submitted reviews
        $dynamicReviews = $this->getCombinedReviews();
        $eventPackages = config('nucomu.event_packages', []);
        $txSettings = $this->getTransactionSettings();
        $transactionMode = $txSettings['transaction_mode'];
        $zendoWhatsapp = $txSettings['zendo_whatsapp_clean'];

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
            'reviews',
            'stories',
            'dynamicReviews',
            'eventPackages',
            'transactionMode',
            'zendoWhatsapp'
        ));
    }

    /**
     * Combine all Google Maps reviews (live Places API if configured + config Google Maps reviews)
     * with website-submitted reviews from the local database.
     */
    protected function getCombinedReviews(): Collection
    {
        $googleMapsReviews = collect();

        // 1. Optional live Google Places API reviews if API key & Place ID are configured
        try {
            $apiKey = config('nucomu.integrations.google_places_api_key');
            $placeId = config('nucomu.integrations.google_place_id');

            if (! empty($apiKey) && ! empty($placeId)) {
                $livePlacesReviews = Cache::remember('nucomu_google_places_reviews', now()->addHours(6), function () use ($apiKey, $placeId) {
                    $response = Http::timeout(4)->get('https://maps.googleapis.com/maps/api/place/details/json', [
                        'place_id' => $placeId,
                        'fields' => 'reviews,rating,user_ratings_total',
                        'language' => 'id',
                        'key' => $apiKey,
                    ]);

                    if ($response->successful()) {
                        return $response->json('result.reviews', []);
                    }

                    return [];
                });

                if (! empty($livePlacesReviews) && is_array($livePlacesReviews)) {
                    foreach ($livePlacesReviews as $item) {
                        $googleMapsReviews->push([
                            'name' => $item['author_name'] ?? 'Pengunjung Google Maps',
                            'handle' => 'Ulasan Google Maps',
                            'avatar_url' => $item['profile_photo_url'] ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80',
                            'rating' => (int) ($item['rating'] ?? 5),
                            'content' => $item['text'] ?? 'Tempat nyaman dan kopi nikmat di Nucomu Cafe.',
                            'order_tag' => 'Google Maps Review',
                            'time_ago' => $item['relative_time_description'] ?? 'Google Maps',
                            'source' => 'google_maps',
                            'source_label' => 'Google Maps',
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Fallback to configured Google Maps reviews
        }

        // 2. Include all Google Maps reviews from config('nucomu.reviews')
        $configGoogleReviews = collect(config('nucomu.reviews', []))->map(function (array $review) {
            $rawImages = $review['review_images'] ?? (! empty($review['review_image']) ? [$review['review_image']] : []);
            $resolvedImages = collect($rawImages)->filter()->map(function (string $img) {
                if (! str_starts_with($img, 'http://') && ! str_starts_with($img, 'https://')) {
                    return asset(ltrim($img, '/'));
                }

                return $img;
            })->values()->all();

            $primaryImage = $resolvedImages[0] ?? null;

            return [
                'name' => $review['name'] ?? 'Pengunjung Nucomu',
                'handle' => $review['handle'] ?? 'Local Guide · Ulasan',
                'avatar_url' => $review['avatar'] ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80',
                'rating' => (int) ($review['rating'] ?? 5),
                'content' => $review['comment'] ?? '',
                'order_tag' => $review['order_tag'] ?? 'Menu Nucomu',
                'time_ago' => $review['time_ago'] ?? 'Baru-baru ini',
                'review_image' => $primaryImage,
                'review_images' => $resolvedImages,
                'source' => 'google_maps',
            ];
        });

        $googleMapsReviews = $configGoogleReviews->concat($googleMapsReviews)->unique(function (array $item) {
            return strtolower($item['name'].'|'.$item['content']);
        })->values();

        // 3. Website reviews stored in database (submitted via website form, not sent to Google Maps)
        $websiteReviews = collect();
        try {
            $sampleNames = ['rina s.', 'bagas p.', 'dewi a.', 'fajar m.', 'siti n.', 'reza k.', 'anisa t.', 'budi r.'];
            $websiteReviews = Testimonial::where('is_active', true)
                ->where('is_sample', false)
                ->latest()
                ->get()
                ->reject(function (Testimonial $t) use ($sampleNames) {
                    return in_array(strtolower(trim($t->name)), $sampleNames, true);
                })
                ->map(function (Testimonial $t) {
                    $imgUrl = $t->review_image ?? null;
                    if ($imgUrl && ! str_starts_with($imgUrl, 'http://') && ! str_starts_with($imgUrl, 'https://')) {
                        $imgUrl = asset(ltrim($imgUrl, '/'));
                    }

                    return [
                        'name' => $t->name,
                        'handle' => $t->handle ?? 'Pengunjung Nucomu',
                        'avatar_url' => $t->avatar_url ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80',
                        'rating' => (int) $t->rating,
                        'content' => $t->content,
                        'order_tag' => $t->order_tag ?? 'Menu Nucomu',
                        'time_ago' => $t->created_at ? $t->created_at->diffForHumans() : 'Baru saja',
                        'review_image' => $imgUrl,
                        'review_images' => $imgUrl ? [$imgUrl] : [],
                        'source' => $t->source ?: 'website',
                    ];
                });
        } catch (\Throwable $e) {
            // Keep empty if DB is unavailable
        }

        // Put all reviews together in one unified list (Google Maps reviews + Website reviews)
        if (session()->has('review_success')) {
            return $websiteReviews->concat($googleMapsReviews)->values();
        }

        return $googleMapsReviews->concat($websiteReviews)->values();
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
     * Nucomu Story & Review page — serves gallery + testimonials from DB.
     */
    public function nucomuPage()
    {
        // Gallery stories from DB
        $stories = collect();
        try {
            $stories = GalleryItem::orderBy('sort_order')->get()->map(function (GalleryItem $item) {
                $imageUrl = null;
                if ($item->image && file_exists(public_path('storage/'.$item->image))) {
                    $imageUrl = asset('storage/'.$item->image);
                } elseif ($item->image && (str_starts_with($item->image, 'http://') || str_starts_with($item->image, 'https://'))) {
                    $imageUrl = $item->image;
                }

                return [
                    'image' => $imageUrl ?? 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => $item->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                    'author' => $item->author ?? 'Barista Corner',
                    'time_label' => $item->time_label ?? 'Baru saja',
                    'category_badge' => $item->category_badge ?? ('#'.($item->category ?? 'Nucomu')),
                    'caption' => $item->caption ?? 'Nucomu Cafe',
                    'description' => $item->description ?? '',
                ];
            });
        } catch (\Throwable $e) {
            // Keep empty
        }

        // Fallback stories if DB has none
        if ($stories->isEmpty()) {
            $stories = collect([
                [
                    'image' => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                    'author' => 'Barista Corner',
                    'time_label' => '2 Jam Lalu',
                    'category_badge' => '#CraftCoffee',
                    'caption' => 'Manual Pour-over Beans House Blend',
                    'description' => 'Proses seduh perlahan untuk mengekstrak aroma fruity & floral yang lembut dan khas.',
                ],
                [
                    'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80',
                    'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
                    'author' => 'Nucomu Nook',
                    'time_label' => '4 Jam Lalu',
                    'category_badge' => '#CozyVibes',
                    'caption' => 'Sudut Favorit Work From Cafe',
                    'description' => 'Pencahayaan hangat, musik lo-fi tenang, & colokan listrik cepat di tiap meja.',
                ],
            ]);
        }

        // Combine all Google Maps reviews and website-submitted reviews
        $reviews = $this->getCombinedReviews();

        return view('nucomu.index', compact('stories', 'reviews'));
    }

    /**
     * Resolve transaction process settings (kasir / zendo / both), Zendo couriers list & WhatsApp numbers,
     * cafe coordinates, auto-send gateway settings, and Zendo distance tariff tiers.
     */
    public function getTransactionSettings(): array
    {
        $mode = config('nucomu.contact.transaction_mode', 'both');
        $zendoWa = config('nucomu.contact.zendo_whatsapp', config('nucomu.contact.whatsapp_number', '6281234567890'));
        $zendoSendMode = 'auto_direct';
        $zendoPublicUrl = '';
        $zendoApiUrl = 'https://api.fonnte.com/send';
        $zendoApiToken = '';
        $cafeLat = (float) config('nucomu.contact.cafe_lat', -8.0765099);
        $cafeLng = (float) config('nucomu.contact.cafe_lng', 111.918712);

        $defaultTariff = config('nucomu.contact.zendo_tariff', [
            'tier_0_3' => 10000,
            'tier_3_4' => 12000,
            'tier_4_5' => 15000,
            'tier_5_6' => 17000,
            'tier_6_7' => 18000,
            'extra_per_km' => 2500,
        ]);

        $tariff = [
            'tier_0_3' => (int) ($defaultTariff['tier_0_3'] ?? 10000),
            'tier_3_4' => (int) ($defaultTariff['tier_3_4'] ?? 12000),
            'tier_4_5' => (int) ($defaultTariff['tier_4_5'] ?? 15000),
            'tier_5_6' => (int) ($defaultTariff['tier_5_6'] ?? 17000),
            'tier_6_7' => (int) ($defaultTariff['tier_6_7'] ?? 18000),
            'extra_per_km' => (int) ($defaultTariff['extra_per_km'] ?? 2500),
        ];

        $rawCouriers = [];
        $courierCount = 1;
        $dbSettings = collect();

        try {
            $dbSettings = Setting::pluck('value', 'key');

            $dbMode = $dbSettings['transaction_mode'] ?? null;
            if (in_array($dbMode, ['kasir', 'zendo', 'both'], true)) {
                $mode = $dbMode;
            }

            if (! empty($dbSettings['zendo_whatsapp'])) {
                $zendoWa = $dbSettings['zendo_whatsapp'];
            }
            if (! empty($dbSettings['zendo_couriers'])) {
                $decoded = json_decode((string) $dbSettings['zendo_couriers'], true);
                if (is_array($decoded)) {
                    $rawCouriers = array_values($decoded);
                }
            }
            if (isset($dbSettings['zendo_courier_count']) && is_numeric($dbSettings['zendo_courier_count'])) {
                $courierCount = max(1, min(50, (int) $dbSettings['zendo_courier_count']));
            } elseif (! empty($rawCouriers)) {
                $courierCount = count($rawCouriers);
            }

            if (in_array($dbSettings['zendo_send_mode'] ?? null, ['auto_direct', 'wa_redirect'], true)) {
                $zendoSendMode = $dbSettings['zendo_send_mode'];
            }
            if (! empty($dbSettings['zendo_public_url'])) {
                $zendoPublicUrl = trim((string) $dbSettings['zendo_public_url']);
            }
            if (! empty($dbSettings['zendo_wa_api_url'])) {
                $zendoApiUrl = $dbSettings['zendo_wa_api_url'];
            }
            if (! empty($dbSettings['zendo_wa_api_token'])) {
                $zendoApiToken = $dbSettings['zendo_wa_api_token'];
            }
            if (isset($dbSettings['cafe_lat']) && is_numeric($dbSettings['cafe_lat'])) {
                $cafeLat = (float) $dbSettings['cafe_lat'];
            }
            if (isset($dbSettings['cafe_lng']) && is_numeric($dbSettings['cafe_lng'])) {
                $cafeLng = (float) $dbSettings['cafe_lng'];
            }

            foreach (['tier_0_3', 'tier_3_4', 'tier_4_5', 'tier_5_6', 'tier_6_7', 'extra_per_km'] as $tierKey) {
                $settingKey = 'zendo_'.$tierKey;
                if (isset($dbSettings[$settingKey]) && is_numeric($dbSettings[$settingKey])) {
                    $tariff[$tierKey] = max(0, (int) $dbSettings[$settingKey]);
                }
            }
        } catch (\Throwable $e) {
            // Fallback to config defaults if DB is unreachable
        }

        $couriers = [];
        for ($i = 0; $i < $courierCount; $i++) {
            $idx = $i + 1;
            $item = $rawCouriers[$i] ?? [];

            $indName = isset($dbSettings["zendo_courier_{$idx}_name"]) ? trim((string) $dbSettings["zendo_courier_{$idx}_name"]) : '';
            $indPhone = isset($dbSettings["zendo_courier_{$idx}_phone"]) ? trim((string) $dbSettings["zendo_courier_{$idx}_phone"]) : '';
            $indStatus = isset($dbSettings["zendo_courier_{$idx}_status"]) ? trim((string) $dbSettings["zendo_courier_{$idx}_status"]) : '';
            $indLastOrderAt = isset($dbSettings["zendo_courier_{$idx}_last_order_at"]) ? trim((string) $dbSettings["zendo_courier_{$idx}_last_order_at"]) : '';

            $name = $indName !== ''
                ? $indName
                : (trim((string) ($item['name'] ?? '')) ?: "Kurir Zendo {$idx}");

            $phone = $indPhone !== ''
                ? $indPhone
                : (trim((string) ($item['phone'] ?? '')) ?: ($idx === 1 ? (string) $zendoWa : ''));

            $status = in_array($indStatus, ['idle', 'delivering', 'almost_done'], true)
                ? $indStatus
                : (in_array($item['status'] ?? '', ['idle', 'delivering', 'almost_done'], true) ? $item['status'] : 'idle');

            $cleanPhone = $phone !== '' ? $this->normalizeWhatsappNumber($phone) : '';

            $couriers[] = [
                'index' => $idx,
                'name' => $name,
                'phone' => $phone,
                'clean_phone' => $cleanPhone,
                'status' => $status,
                'last_order_at' => $indLastOrderAt ?: ($item['last_order_at'] ?? null),
            ];
        }

        $primaryPhone = (! empty($couriers[0]['phone'])) ? $couriers[0]['phone'] : (string) $zendoWa;
        $primaryClean = (! empty($couriers[0]['clean_phone'])) ? $couriers[0]['clean_phone'] : $this->normalizeWhatsappNumber((string) $zendoWa);

        return [
            'transaction_mode' => $mode,
            'zendo_whatsapp' => $primaryPhone,
            'zendo_whatsapp_clean' => $primaryClean,
            'zendo_courier_count' => $courierCount,
            'zendo_couriers' => $couriers,
            'zendo_send_mode' => $zendoSendMode,
            'zendo_public_url' => $zendoPublicUrl,
            'zendo_wa_api_url' => $zendoApiUrl,
            'zendo_wa_api_token' => $zendoApiToken,
            'cafe_lat' => $cafeLat,
            'cafe_lng' => $cafeLng,
            'zendo_tariff' => $tariff,
        ];
    }

    /**
     * Parse structured metadata from an Order's notes field for Zendo tracking.
     *
     * @return array{
     *     is_zendo: bool,
     *     courier_name: string|null,
     *     courier_index: int|null,
     *     courier_phone: string|null,
     *     zendo_stage: string,
     *     delivery_address: string|null,
     *     distance_km: float|null,
     *     delivery_fee_text: string|null,
     *     pin: string|null,
     *     maps_url: string|null,
     *     customer_notes: string|null
     * }
     */
    public function parseZendoOrderNotes(?string $notes): array
    {
        $raw = (string) $notes;
        $isZendo = str_starts_with($raw, '[KURIR ZENDO]');

        $result = [
            'is_zendo' => $isZendo,
            'courier_name' => null,
            'courier_index' => null,
            'courier_phone' => null,
            'zendo_stage' => 'delivering',
            'delivery_address' => null,
            'distance_km' => null,
            'delivery_fee_text' => null,
            'pin' => null,
            'maps_url' => null,
            'customer_notes' => $isZendo ? null : ($raw !== '' ? $raw : null),
        ];

        if (! $isZendo) {
            return $result;
        }

        $segments = explode(' | ', $raw);
        foreach ($segments as $seg) {
            $seg = trim($seg);
            if (str_starts_with($seg, '[KURIR ZENDO] Kurir:')) {
                $courierPart = trim(substr($seg, strlen('[KURIR ZENDO] Kurir:')));
                if (preg_match('/^(.*?)\s*\(#(\d+)\)/', $courierPart, $m)) {
                    $result['courier_name'] = trim($m[1]);
                    $result['courier_index'] = (int) $m[2];
                } elseif (preg_match('/^(.*?)\s*\(\+?([0-9]+)\)/', $courierPart, $m)) {
                    $result['courier_name'] = trim($m[1]);
                    $result['courier_phone'] = $this->normalizeWhatsappNumber($m[2]);
                } else {
                    $result['courier_name'] = $courierPart;
                }
            } elseif (str_starts_with($seg, '[KURIR ZENDO] Alamat:')) {
                $result['delivery_address'] = trim(substr($seg, strlen('[KURIR ZENDO] Alamat:')));
            } elseif (str_starts_with($seg, 'HP_Kurir:')) {
                $result['courier_phone'] = $this->normalizeWhatsappNumber(trim(substr($seg, strlen('HP_Kurir:'))));
            } elseif (str_starts_with($seg, 'Tahap_Zendo:')) {
                $stage = strtolower(trim(substr($seg, strlen('Tahap_Zendo:'))));
                if (in_array($stage, ['delivering', 'almost_done', 'completed'], true)) {
                    $result['zendo_stage'] = $stage;
                }
            } elseif (str_starts_with($seg, 'Alamat:')) {
                $result['delivery_address'] = trim(substr($seg, strlen('Alamat:')));
            } elseif (str_starts_with($seg, 'Jarak:')) {
                $result['distance_km'] = (float) preg_replace('/[^0-9\.]/', '', substr($seg, strlen('Jarak:')));
            } elseif (str_starts_with($seg, 'Ongkir:')) {
                $result['delivery_fee_text'] = trim(substr($seg, strlen('Ongkir:')));
            } elseif (str_starts_with($seg, 'Pin:')) {
                $pin = trim(substr($seg, strlen('Pin:')));
                $result['pin'] = $pin;
                $result['maps_url'] = "https://www.google.com/maps?q={$pin}";
            } elseif (str_starts_with($seg, 'Catatan:')) {
                $result['customer_notes'] = trim(substr($seg, strlen('Catatan:')));
            }
        }

        return $result;
    }

    /**
     * Get all configured Zendo couriers enriched with their live deliveries and device connection status.
     *
     * Courier availability is fully driven by the courier's own actions on the courier link
     * (ambil pesanan, GPS otomatis "hampir sampai", selesai diantar) — not by Admin toggles.
     *
     * @return list<array<string, mixed>>
     */
    public function getCouriersWithLiveTracking(): array
    {
        $txSettings = $this->getTransactionSettings();
        $couriers = $txSettings['zendo_couriers'];

        $deliveries = collect();
        try {
            $deliveries = ZendoDelivery::query()
                ->where(function ($query) {
                    $query->whereIn('status', ZendoDelivery::ACTIVE_STATUSES)
                        ->orWhereDate('created_at', today());
                })
                ->latest()
                ->limit(300)
                ->get();
        } catch (\Throwable $e) {
            // Keep empty if DB / table is unavailable
        }

        $enrichedCouriers = [];
        foreach ($couriers as $courier) {
            $idx = (int) $courier['index'];
            $cleanPhone = (string) $courier['clean_phone'];

            $courierDeliveries = $deliveries->filter(function (ZendoDelivery $delivery) use ($cleanPhone, $idx) {
                return $cleanPhone !== ''
                    ? $delivery->courier_phone === $cleanPhone
                    : $delivery->courier_index === $idx;
            })->values();

            $activeDeliveries = $courierDeliveries->filter(fn (ZendoDelivery $d) => $d->isActive())->values();
            $completedTodayCount = $courierDeliveries
                ->filter(fn (ZendoDelivery $d) => $d->status === 'completed' && $d->completed_at?->isToday())
                ->count();

            $storedStatus = Setting::get("zendo_courier_{$idx}_status", 'idle');
            if ($activeDeliveries->isEmpty()) {
                $effectiveStatus = in_array($storedStatus, ['idle', 'almost_done', 'delivering'], true) ? $storedStatus : 'idle';
            } elseif ($activeDeliveries->contains(fn (ZendoDelivery $d) => $d->status === 'almost_done') || $storedStatus === 'almost_done') {
                $effectiveStatus = 'almost_done';
            } else {
                $effectiveStatus = 'delivering';
            }

            $oldestActiveTimestamp = $activeDeliveries->isNotEmpty()
                ? (int) $activeDeliveries->min(fn (ZendoDelivery $d) => $d->created_at?->timestamp ?? PHP_INT_MAX)
                : PHP_INT_MAX;

            $connectedAt = null;
            $lastPingAt = null;
            if ($cleanPhone !== '') {
                try {
                    $connectedRaw = Setting::get("zendo_connected_at_{$cleanPhone}");
                    $connectedAt = $connectedRaw ? Carbon::parse($connectedRaw) : null;
                    $pingRaw = Setting::get("zendo_last_ping_{$cleanPhone}");
                    $lastPingAt = $pingRaw ? Carbon::parse($pingRaw) : null;
                } catch (\Throwable $e) {
                    // Ignore malformed timestamps
                }
            }

            $enrichedCouriers[] = [
                'index' => $idx,
                'name' => $courier['name'],
                'phone' => $courier['phone'],
                'clean_phone' => $cleanPhone,
                'effective_status' => $effectiveStatus,
                'active_orders_count' => $activeDeliveries->count(),
                'active_deliveries' => $activeDeliveries->all(),
                'completed_today_count' => $completedTodayCount,
                'oldest_active_timestamp' => $oldestActiveTimestamp,
                'is_connected' => $connectedAt !== null,
                'connected_at' => $connectedAt,
                'last_ping_at' => $lastPingAt,
                'is_online' => $lastPingAt !== null && $lastPingAt->greaterThan(now()->subMinutes(3)),
            ];
        }

        return $enrichedCouriers;
    }

    /**
     * Automatically select a Zendo courier for a customer order:
     * 1. Random selection among couriers who have NOT received an active order yet (`effective_status === 'idle'`).
     * 2. If ALL couriers already have an order, dispatch to a courier whose delivery is almost finished (`effective_status === 'almost_done'`).
     * 3. Fallback: if all couriers are currently delivering and none is marked `almost_done` yet, pick the courier with the fewest active orders & earliest departure time.
     */
    public function selectAutoDispatchedCourier(): array
    {
        $txSettings = $this->getTransactionSettings();
        $couriers = collect($this->getCouriersWithLiveTracking())
            ->filter(fn (array $c) => ! empty($c['clean_phone']))
            ->values();

        if ($couriers->isEmpty()) {
            return [
                'index' => 1,
                'name' => 'Kurir Zendo 1',
                'phone' => $txSettings['zendo_whatsapp'],
                'clean_phone' => $txSettings['zendo_whatsapp_clean'],
                'effective_status' => 'idle',
                'dispatch_reason' => 'fallback_primary',
                'dispatch_reason_label' => 'Kurir Utama Zendo',
            ];
        }

        // Priority 1: Couriers who have NOT received an order yet (idle & 0 active orders) -> pick RANDOMLY
        $idleCouriers = $couriers->filter(function (array $c) {
            return $c['effective_status'] === 'idle' && $c['active_orders_count'] === 0;
        })->values();

        if ($idleCouriers->isNotEmpty()) {
            $randomIndex = array_rand($idleCouriers->all());
            $chosen = $idleCouriers->get($randomIndex);
            $chosen['dispatch_reason'] = 'random_idle';
            $chosen['dispatch_reason_label'] = 'Dipilih acak dari kurir yang belum mendapat pesanan';

            return $chosen;
        }

        // Priority 2: All couriers have orders -> pick courier marked "Hampir Selesai Pengiriman" (almost_done)
        $almostDoneCouriers = $couriers->filter(function (array $c) {
            return $c['effective_status'] === 'almost_done';
        })->values();

        if ($almostDoneCouriers->isNotEmpty()) {
            $minActive = $almostDoneCouriers->min('active_orders_count');
            $candidates = $almostDoneCouriers->where('active_orders_count', $minActive)->values();
            $randomIndex = array_rand($candidates->all());
            $chosen = $candidates->get($randomIndex);
            $chosen['dispatch_reason'] = 'almost_done';
            $chosen['dispatch_reason_label'] = 'Semua kurir sedang bertugas — dialihkan ke kurir yang hampir selesai pengiriman';

            return $chosen;
        }

        // Priority 3: All couriers are delivering and none marked almost_done yet -> pick fewest active orders & earliest departure (closest to finishing)
        $sortedDelivering = $couriers->sortBy([
            ['active_orders_count', 'asc'],
            ['oldest_active_timestamp', 'asc'],
        ])->values();

        $chosen = $sortedDelivering->first();
        $chosen['dispatch_reason'] = 'earliest_delivering';
        $chosen['dispatch_reason_label'] = 'Semua kurir sedang mengantar — dialihkan ke kurir keberangkatan paling awal (paling mendekati selesai)';

        return $chosen;
    }

    /**
     * Calculate Zendo delivery fee based on distance (km) from Nucomu Cafe to customer pin.
     *
     * Default tiers (Jan 2025):
     * 0 - 3 km   : Rp 10.000
     * 3,1 - 4 km : Rp 12.000
     * 4,1 - 5 km : Rp 15.000
     * 5,1 - 6 km : Rp 17.000
     * 6,1 - 7 km : Rp 18.000
     * > 7 km     : +Rp 2.500 / km
     */
    public function calculateZendoDeliveryFee(float $distanceKm, array $tariff): int
    {
        $d = max(0.0, round($distanceKm, 1));
        $tier0_3 = (int) ($tariff['tier_0_3'] ?? 10000);
        $tier3_4 = (int) ($tariff['tier_3_4'] ?? 12000);
        $tier4_5 = (int) ($tariff['tier_4_5'] ?? 15000);
        $tier5_6 = (int) ($tariff['tier_5_6'] ?? 17000);
        $tier6_7 = (int) ($tariff['tier_6_7'] ?? 18000);
        $extraPerKm = (int) ($tariff['extra_per_km'] ?? 2500);

        if ($d <= 3.0) {
            return $tier0_3;
        }
        if ($d <= 4.0) {
            return $tier3_4;
        }
        if ($d <= 5.0) {
            return $tier4_5;
        }
        if ($d <= 6.0) {
            return $tier5_6;
        }
        if ($d <= 7.0) {
            return $tier6_7;
        }

        $extraKm = (int) ceil($d - 7.0);

        return $tier6_7 + ($extraKm * $extraPerKm);
    }

    /**
     * Normalize Indonesian WhatsApp phone number to international 628xxx format.
     */
    public function normalizeWhatsappNumber(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }
        if ($digits === '') {
            $digits = '6281234567890';
        }

        return $digits;
    }

    /**
     * Build formatted WhatsApp message text for Zendo courier order.
     */
    protected function buildZendoMessageText(
        string $orderCode,
        string $customerName,
        ?string $customerPhone,
        ?string $deliveryAddress,
        array $items,
        int $subtotal,
        int $deliveryFee,
        int $grandTotal,
        ?float $distanceKm,
        ?float $customerLat,
        ?float $customerLng,
        ?string $notes,
        ?string $courierName = null,
        ?string $courierUrl = null,
        ?string $trackingUrl = null
    ): string {
        $greetingTarget = ! empty($courierName) ? "{$courierName} (Nucomu Cafe)" : 'Admin Kurir Zendo / Nucomu Cafe';
        $lines = [
            "Halo {$greetingTarget}, ada pesanan baru:",
            '',
            "*Kode Pesanan:* #{$orderCode}",
            "*Nama Pemesan:* {$customerName}",
        ];

        if (! empty($courierName)) {
            $lines[] = "*Kurir Tujuan:* {$courierName}";
        }
        if (! empty($customerPhone) && $customerPhone !== '-' && $customerPhone !== 'Via Zendo WA') {
            $lines[] = "*No. WhatsApp:* {$customerPhone}";
        }
        if (! empty($deliveryAddress)) {
            $lines[] = "*Alamat Pengiriman:* {$deliveryAddress}";
        }
        if ($distanceKm !== null && $distanceKm > 0) {
            $lines[] = '*Jarak dari Nucomu Cafe:* '.number_format($distanceKm, 1, ',', '.').' km';
        }
        if ($customerLat !== null && $customerLng !== null) {
            $lines[] = "*Titik Lokasi Peta:* https://www.google.com/maps?q={$customerLat},{$customerLng}";
        }

        $lines[] = '';
        $lines[] = '*Rincian Menu Pesanan:*';
        foreach (array_values($items) as $idx => $item) {
            $num = $idx + 1;
            $unitFormatted = 'Rp '.number_format((int) $item['unit_price'], 0, ',', '.');
            $lineFormatted = 'Rp '.number_format((int) $item['line_total'], 0, ',', '.');
            $lines[] = "{$num}. {$item['menu_name']} ({$item['qty']}x @ {$unitFormatted}) = {$lineFormatted}";
        }

        $subtotalFormatted = 'Rp '.number_format($subtotal, 0, ',', '.');
        $feeFormatted = 'Rp '.number_format($deliveryFee, 0, ',', '.');
        $grandFormatted = 'Rp '.number_format($grandTotal, 0, ',', '.');

        $lines[] = '';
        $lines[] = "*Subtotal Menu:* {$subtotalFormatted}";
        if ($deliveryFee > 0) {
            $distLabel = $distanceKm !== null ? ' ('.number_format($distanceKm, 1, ',', '.').' km)' : '';
            $lines[] = "*Ongkir Kurir Zendo{$distLabel}:* {$feeFormatted}";
        }
        $lines[] = "*Total Pembayaran:* {$grandFormatted}";
        $lines[] = '*Catatan Tambahan:* '.(! empty($notes) ? $notes : '-');
        $lines[] = '';

        if (! empty($courierUrl)) {
            $lines[] = '🛵 *LINK KHUSUS KURIR (KIRIM GPS & STATUS):*';
            $lines[] = $courierUrl;
            $lines[] = 'Khusus kurir yang ditugaskan untuk menyiarkan GPS & menyelesaikan pengantaran.';
            $lines[] = '';
        }

        if (! empty($trackingUrl)) {
            $lines[] = '📍 *LINK PELACAKAN PELANGGAN (HANYA LIHAT POSISI KURIR):*';
            $lines[] = $trackingUrl;
            $lines[] = 'Pelanggan dapat memantau pergerakan posisi kurir di peta secara realtime.';
            $lines[] = '';
        }

        $lines[] = 'Mohon diproses pengirimannya melalui Kurir Zendo. Terima kasih!';

        return implode("\n", $lines);
    }

    /**
     * Build formatted WhatsApp link for Zendo courier order.
     */
    protected function buildZendoWhatsappUrl(
        string $zendoWaClean,
        string $orderCode,
        string $customerName,
        ?string $customerPhone,
        ?string $deliveryAddress,
        array $items,
        int $subtotal,
        ?string $notes,
        int $deliveryFee = 0,
        ?float $distanceKm = null,
        ?float $customerLat = null,
        ?float $customerLng = null,
        ?string $courierName = null
    ): string {
        $grandTotal = $subtotal + $deliveryFee;
        $message = $this->buildZendoMessageText(
            $orderCode,
            $customerName,
            $customerPhone,
            $deliveryAddress,
            $items,
            $subtotal,
            $deliveryFee,
            $grandTotal,
            $distanceKm,
            $customerLat,
            $customerLng,
            $notes,
            $courierName
        );

        return 'https://wa.me/'.$zendoWaClean.'?text='.rawurlencode($message);
    }

    /**
     * Automatically dispatch order notification directly to Zendo's WhatsApp contact in the background via Gateway API.
     */
    public function dispatchZendoWhatsappMessage(string $zendoWaClean, string $message, array $txSettings): bool
    {
        try {
            $apiUrl = trim((string) ($txSettings['zendo_wa_api_url'] ?? ''));
            $apiToken = trim((string) ($txSettings['zendo_wa_api_token'] ?? ''));

            if ($apiUrl === '' || $apiToken === '') {
                return false;
            }

            $response = Http::connectTimeout(3)
                ->timeout(6)
                ->withHeaders([
                    'Authorization' => $apiToken,
                ])
                ->asForm()
                ->post($apiUrl, [
                    'target' => $zendoWaClean,
                    'phone' => $zendoWaClean,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            $json = $response->json();
            $isSuccess = $response->successful() && (($json['status'] ?? true) !== false);

            if ($isSuccess) {
                Log::info("Auto-sent Zendo order via Gateway to {$zendoWaClean}");

                return true;
            }

            Log::warning("Zendo Gateway rejected message to {$zendoWaClean}: ".json_encode($json));

            return false;
        } catch (\Throwable $e) {
            Log::warning('Zendo background auto-send notice: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Nucomu Order page — pelanggan pilih menu dan proses via Kasir atau Zendo sesuai pengaturan Admin.
     */
    public function orderPage(Request $request)
    {
        $categories = collect();
        $menus = collect();

        try {
            $categories = Category::whereHas('menus', fn ($q) => $q->where('is_available', true))->orderBy('name')->get();
            $menus = Menu::available()->with('category')->orderBy('sort_order')->get();
        } catch (\Throwable $e) {
            // Keep empty and use fallback below
        }

        // Fallback to config menu if DB has no menus yet
        if ($menus->isEmpty()) {
            $configMenus = collect(config('nucomu.all_menu', []));
            $categories = $configMenus
                ->map(fn (array $m) => (object) [
                    'slug' => $m['category'] ?? 'coffee',
                    'name' => $m['category_label'] ?? ucfirst($m['category'] ?? 'Coffee'),
                ])
                ->unique('slug')
                ->values();

            $menus = $configMenus->values()->map(function (array $m, int $index) {
                $catObj = (object) [
                    'slug' => $m['category'] ?? 'coffee',
                    'name' => $m['category_label'] ?? ucfirst($m['category'] ?? 'Coffee'),
                ];

                return (object) [
                    'id' => $index + 1,
                    'name' => $m['name'],
                    'price' => (int) $m['price'],
                    'description' => $m['short_info'] ?? '',
                    'image' => $m['image'] ?? null,
                    'is_featured' => false,
                    'is_best_seller' => false,
                    'category' => $catObj,
                ];
            });
        }

        $txSettings = $this->getTransactionSettings();
        $transactionMode = $txSettings['transaction_mode'];
        $zendoWhatsapp = $txSettings['zendo_whatsapp_clean'];
        $zendoCourierCount = $txSettings['zendo_courier_count'];
        $zendoCouriers = $txSettings['zendo_couriers'];
        $zendoSendMode = $txSettings['zendo_send_mode'];
        $cafeLat = $txSettings['cafe_lat'];
        $cafeLng = $txSettings['cafe_lng'];
        $zendoTariff = $txSettings['zendo_tariff'];

        if ($transactionMode === 'kasir') {
            $initialProcessMethod = 'kasir';
        } elseif ($transactionMode === 'zendo') {
            $initialProcessMethod = 'zendo';
        } else {
            $requestedMode = strtolower((string) $request->query('mode', 'kasir'));
            $initialProcessMethod = in_array($requestedMode, ['kasir', 'zendo'], true) ? $requestedMode : 'kasir';
        }

        return view('nucomu.order', compact(
            'categories',
            'menus',
            'transactionMode',
            'zendoWhatsapp',
            'zendoCourierCount',
            'zendoCouriers',
            'zendoSendMode',
            'cafeLat',
            'cafeLng',
            'zendoTariff',
            'initialProcessMethod'
        ));
    }

    /**
     * Store nucomu order (AJAX — for Zendo: no receipt slip, sends directly to chosen Zendo courier WA).
     */
    public function storeOrder(Request $request)
    {
        $txSettings = $this->getTransactionSettings();
        $allowedMode = $txSettings['transaction_mode'];
        $zendoCouriers = $txSettings['zendo_couriers'];
        $zendoSendMode = $txSettings['zendo_send_mode'];
        $zendoTariff = $txSettings['zendo_tariff'];

        $requestedMethod = strtolower((string) $request->input('process_method', 'kasir'));
        if ($allowedMode === 'kasir') {
            $processMethod = 'kasir';
        } elseif ($allowedMode === 'zendo') {
            $processMethod = 'zendo';
        } else {
            $processMethod = in_array($requestedMethod, ['kasir', 'zendo'], true) ? $requestedMethod : 'kasir';
        }

        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'nullable|string|max:30',
            'delivery_address' => $processMethod === 'zendo' ? 'required|string|max:500' : 'nullable|string|max:500',
            'distance_km' => 'nullable|numeric|min:0',
            'customer_lat' => 'nullable|numeric',
            'customer_lng' => 'nullable|numeric',
            'courier_index' => 'nullable|integer|min:1',
            'process_method' => 'nullable|string|in:kasir,zendo',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|integer',
            'items.*.menu_name' => 'required|string',
            'items.*.unit_price' => 'required|integer|min:0',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.line_total' => 'required|integer|min:0',
            'total' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ], [
            'delivery_address.required' => 'Alamat pengiriman wajib dipilih pada peta atau diisi untuk pengiriman Kurir Zendo.',
        ]);

        // Automatically dispatch Zendo courier (random idle first -> almost_done if all busy -> earliest delivering)
        $selectedCourier = $processMethod === 'zendo'
            ? $this->selectAutoDispatchedCourier()
            : ($zendoCouriers[0] ?? [
                'index' => 1,
                'name' => 'Kurir Zendo 1',
                'phone' => $txSettings['zendo_whatsapp'],
                'clean_phone' => $txSettings['zendo_whatsapp_clean'],
                'dispatch_reason' => 'kasir',
                'dispatch_reason_label' => 'Kasir',
            ]);

        $courierIndex = (int) ($selectedCourier['index'] ?? 1);
        $zendoWaClean = ! empty($selectedCourier['clean_phone'])
            ? $selectedCourier['clean_phone']
            : $txSettings['zendo_whatsapp_clean'];
        $courierName = $selectedCourier['name'] ?? "Kurir Zendo {$courierIndex}";
        $dispatchReason = $selectedCourier['dispatch_reason'] ?? 'random_idle';
        $dispatchReasonLabel = $selectedCourier['dispatch_reason_label'] ?? 'Dipilih otomatis';

        $deliveryAddress = trim((string) $request->input('delivery_address', ''));
        $rawNotes = trim((string) $request->input('notes', ''));
        $customerPhone = trim((string) $request->input('customer_phone', ''));
        $distanceKm = $request->filled('distance_km') ? round((float) $request->input('distance_km'), 1) : ($processMethod === 'zendo' ? 1.5 : null);
        $customerLat = $request->filled('customer_lat') ? (float) $request->input('customer_lat') : null;
        $customerLng = $request->filled('customer_lng') ? (float) $request->input('customer_lng') : null;

        $subtotal = (int) collect($request->items)->sum('line_total');
        $deliveryFee = $processMethod === 'zendo'
            ? $this->calculateZendoDeliveryFee((float) ($distanceKm ?? 1.5), $zendoTariff)
            : 0;
        $grandTotal = $subtotal + $deliveryFee;

        $storedNotes = $rawNotes !== '' ? $rawNotes : null;
        if ($processMethod === 'zendo') {
            $zendoParts = [
                "[KURIR ZENDO] Kurir: {$courierName} (#{$courierIndex})",
                "HP_Kurir: {$zendoWaClean}",
                'Tahap_Zendo: delivering',
                'Alamat: '.$deliveryAddress,
                'Jarak: '.number_format((float) $distanceKm, 1, '.', '').' km',
                'Ongkir: Rp '.number_format($deliveryFee, 0, ',', '.'),
            ];
            if ($customerLat !== null && $customerLng !== null) {
                $zendoParts[] = "Pin: {$customerLat},{$customerLng}";
            }
            if ($rawNotes !== '') {
                $zendoParts[] = 'Catatan: '.$rawNotes;
            }
            $storedNotes = implode(' | ', $zendoParts);
        }

        try {
            $order = DB::transaction(function () use ($request, $processMethod, $customerPhone, $storedNotes, $subtotal, $grandTotal) {
                $date = Carbon::now()->format('Ymd');
                $count = Order::whereDate('created_at', today())->count() + 1;
                $code = 'NCM-'.$date.'-'.str_pad($count, 3, '0', STR_PAD_LEFT);

                $order = Order::create([
                    'code' => $code,
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $customerPhone !== '' ? $customerPhone : ($processMethod === 'zendo' ? 'Via Zendo WA' : '-'),
                    'order_type' => $processMethod === 'zendo' ? 'take_away' : 'dine_in',
                    'notes' => $storedNotes,
                    'subtotal' => $subtotal,
                    'total' => $grandTotal,
                    'payment_method' => 'cash',
                    'payment_status' => 'unpaid',
                    'status' => $processMethod === 'zendo' ? 'processing' : 'pending',
                ]);

                foreach ($request->items as $item) {
                    $validMenuId = Menu::whereKey($item['menu_id'])->exists() ? $item['menu_id'] : null;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $validMenuId,
                        'menu_name' => $item['menu_name'],
                        'unit_price' => $item['unit_price'],
                        'qty' => $item['qty'],
                        'options' => null,
                        'line_total' => $item['line_total'],
                    ]);
                }

                return $order;
            });

            if ($processMethod === 'zendo') {
                try {
                    Setting::set("zendo_courier_{$courierIndex}_last_order_at", now()->toDateTimeString());
                } catch (\Throwable $settingEx) {
                    // Continue gracefully
                }
            }

            // Create live delivery tracking record (customer/admin tracking link + courier-only GPS link)
            $trackingUrl = null;
            $courierUrl = null;
            if ($processMethod === 'zendo') {
                try {
                    $delivery = ZendoDelivery::create([
                        'order_id' => $order->id,
                        'order_code' => $order->code,
                        'courier_index' => $courierIndex,
                        'courier_name' => $courierName,
                        'courier_phone' => $zendoWaClean,
                        'tracking_token' => ZendoDelivery::generateToken(),
                        'courier_token' => ZendoDelivery::generateToken(),
                        'status' => 'assigned',
                        'customer_name' => $request->customer_name,
                        'delivery_address' => $deliveryAddress,
                        'destination_lat' => $customerLat,
                        'destination_lng' => $customerLng,
                    ]);
                    $customBase = trim((string) ($txSettings['zendo_public_url'] ?? ''));
                    if ($customBase !== '') {
                        $customBase = rtrim($customBase, '/');
                        $trackingUrl = $customBase.route('zendo.track', $delivery->tracking_token, false);
                        $courierUrl = $customBase.route('zendo.courier.show', $delivery->courier_token, false);
                    } else {
                        $trackingUrl = route('zendo.track', $delivery->tracking_token);
                        $courierUrl = route('zendo.courier.show', $delivery->courier_token);
                    }
                } catch (\Throwable $deliveryEx) {
                    Log::warning('Gagal membuat data pelacakan Zendo: '.$deliveryEx->getMessage());
                }
            }

            $orderCode = $order->code;
            $messageText = $this->buildZendoMessageText(
                $orderCode,
                $request->customer_name,
                $customerPhone,
                $deliveryAddress,
                $request->items,
                $subtotal,
                $deliveryFee,
                $grandTotal,
                $distanceKm,
                $customerLat,
                $customerLng,
                $rawNotes,
                $courierName,
                $courierUrl,
                $trackingUrl
            );
            $whatsappUrl = 'https://wa.me/'.$zendoWaClean.'?text='.rawurlencode($messageText);

            $autoSent = false;
            if ($processMethod === 'zendo') {
                $autoSent = $this->dispatchZendoWhatsappMessage($zendoWaClean, $messageText, $txSettings);

                // Also send the tracking link to the customer's WhatsApp (when gateway is configured)
                if ($autoSent && $trackingUrl && $customerPhone !== '') {
                    $customerMessage = "Halo {$request->customer_name}, pesanan Nucomu Cafe #{$orderCode} sedang diproses oleh {$courierName}.\n\n"
                        ."📍 Lacak posisi kurir secara langsung:\n{$trackingUrl}\n\nTerima kasih!";
                    $this->dispatchZendoWhatsappMessage($this->normalizeWhatsappNumber($customerPhone), $customerMessage, $txSettings);
                }
            }

            return response()->json([
                'success' => true,
                'process_method' => $processMethod,
                'send_mode' => $zendoSendMode,
                'auto_sent' => $autoSent,
                'courier_index' => $courierIndex,
                'courier_name' => $courierName,
                'zendo_contact' => $zendoWaClean,
                'dispatch_reason' => $dispatchReason,
                'dispatch_reason_label' => $dispatchReasonLabel,
                'order_code' => $orderCode,
                'distance_km' => $distanceKm,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'grand_total' => $grandTotal,
                'slip_url' => $processMethod === 'kasir' ? route('nucomu.order.slip', [
                    'code' => $orderCode,
                    'method' => 'kasir',
                ]) : null,
                'tracking_url' => $trackingUrl,
                'whatsapp_url' => $whatsappUrl,
            ]);
        } catch (\Exception $e) {
            Log::error('Nucomu order store error: '.$e->getMessage());

            if ($processMethod === 'zendo') {
                $fallbackCode = 'NCM-'.Carbon::now()->format('Ymd').'-'.str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT);
                $messageText = $this->buildZendoMessageText(
                    $fallbackCode,
                    $request->customer_name,
                    $customerPhone,
                    $deliveryAddress,
                    $request->items,
                    $subtotal,
                    $deliveryFee,
                    $grandTotal,
                    $distanceKm,
                    $customerLat,
                    $customerLng,
                    $rawNotes,
                    $courierName
                );
                $whatsappUrl = 'https://wa.me/'.$zendoWaClean.'?text='.rawurlencode($messageText);
                $autoSent = $this->dispatchZendoWhatsappMessage($zendoWaClean, $messageText, $txSettings);

                return response()->json([
                    'success' => true,
                    'process_method' => 'zendo',
                    'send_mode' => $zendoSendMode,
                    'auto_sent' => $autoSent,
                    'courier_index' => $courierIndex,
                    'courier_name' => $courierName,
                    'zendo_contact' => $zendoWaClean,
                    'dispatch_reason' => $dispatchReason,
                    'dispatch_reason_label' => $dispatchReasonLabel,
                    'order_code' => $fallbackCode,
                    'distance_km' => $distanceKm,
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFee,
                    'grand_total' => $grandTotal,
                    'slip_url' => null,
                    'whatsapp_url' => $whatsappUrl,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan, silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Show the order slip page with QR code (Strictly for Kasir — Zendo does not use receipt slip).
     */
    public function orderSlip(Request $request, string $code)
    {
        $order = Order::where('code', $code)->with('items')->firstOrFail();

        $txSettings = $this->getTransactionSettings();
        $transactionMode = $txSettings['transaction_mode'];
        $zendoWaClean = $txSettings['zendo_whatsapp_clean'];

        $isZendoOrder = str_starts_with((string) $order->notes, '[KURIR ZENDO]')
            || $request->query('method') === 'zendo'
            || ($order->order_type === 'take_away' && $transactionMode === 'zendo');

        if ($isZendoOrder) {
            return redirect()->route('nucomu.order');
        }

        // Detect appropriate host for scanning via Google Lens / mobile phone
        $host = $request->getHost();
        $port = $request->getPort();
        $scheme = $request->getScheme();

        // If accessed via localhost, resolve the actual LAN IP from system network adapters
        if (in_array($host, ['localhost', '127.0.0.1', '::1'])) {
            $detectedLanIp = $this->detectLocalNetworkIp();
            if ($detectedLanIp) {
                $host = $detectedLanIp;
            }
        }

        $portSuffix = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';
        $adminOrderUrl = "{$scheme}://{$host}{$portSuffix}/qr/pesanan/{$order->code}";

        // Parse delivery address, distance, delivery fee, pin, and clean notes if stored with [KURIR ZENDO] prefix
        $deliveryAddress = null;
        $distanceKm = null;
        $deliveryFee = max(0, (int) $order->total - (int) $order->subtotal);
        $customerLat = null;
        $customerLng = null;
        $cleanNotes = $order->notes;

        if (str_starts_with((string) $order->notes, '[KURIR ZENDO]')) {
            $cleanNotes = null;
            $segments = explode(' | ', (string) $order->notes);
            foreach ($segments as $seg) {
                if (str_starts_with($seg, '[KURIR ZENDO] Alamat: ')) {
                    $deliveryAddress = trim(substr($seg, strlen('[KURIR ZENDO] Alamat: ')));
                } elseif (str_starts_with($seg, 'Jarak: ')) {
                    $distanceKm = (float) preg_replace('/[^0-9\.]/', '', substr($seg, strlen('Jarak: ')));
                } elseif (str_starts_with($seg, 'Pin: ')) {
                    $coords = explode(',', substr($seg, strlen('Pin: ')), 2);
                    if (count($coords) === 2) {
                        $customerLat = (float) trim($coords[0]);
                        $customerLng = (float) trim($coords[1]);
                    }
                } elseif (str_starts_with($seg, 'Catatan: ')) {
                    $cleanNotes = trim(substr($seg, strlen('Catatan: ')));
                }
            }
        }

        $itemsArray = $order->items->map(fn ($i) => [
            'menu_name' => $i->menu_name,
            'qty' => $i->qty,
            'unit_price' => $i->unit_price,
            'line_total' => $i->line_total,
        ])->all();

        $zendoWhatsappUrl = $this->buildZendoWhatsappUrl(
            $zendoWaClean,
            $order->code,
            $order->customer_name,
            $order->customer_phone,
            $deliveryAddress,
            $itemsArray,
            (int) $order->subtotal,
            $cleanNotes,
            $deliveryFee,
            $distanceKm,
            $customerLat,
            $customerLng
        );

        return view('nucomu.order-slip', compact(
            'order',
            'adminOrderUrl',
            'host',
            'portSuffix',
            'scheme',
            'transactionMode',
            'isZendoOrder',
            'deliveryAddress',
            'distanceKm',
            'deliveryFee',
            'customerLat',
            'customerLng',
            'cleanNotes',
            'zendoWaClean',
            'zendoWhatsappUrl'
        ));
    }

    /**
     * Helper to detect local network IP (WiFi / Ethernet) from system ipconfig.
     */
    protected function detectLocalNetworkIp(): ?string
    {
        try {
            $lines = [];
            @exec('ipconfig', $lines);
            $lanIp = null;
            $fallbackIp = null;

            foreach ($lines as $line) {
                if (preg_match('/IPv4 Address[ .:]+([\d\.]+)/i', $line, $matches)) {
                    $ip = trim($matches[1]);
                    if ($ip === '127.0.0.1' || str_starts_with($ip, '169.254.')) {
                        continue;
                    }
                    if (str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
                        $lanIp = $ip;
                        break;
                    }
                    if (! $fallbackIp) {
                        $fallbackIp = $ip;
                    }
                }
            }

            return $lanIp ?? $fallbackIp ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Store a review submitted from the Nucomu page (saved locally to website DB, not sent to Google Maps).
     */
    public function storeReview(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
            'handle' => 'nullable|string|max:50',
            'order_tag' => 'nullable|string|max:100',
            'review_image' => 'nullable|image|max:5120',
        ]);

        try {
            $uploadedImagePath = null;
            if ($request->hasFile('review_image') && $request->file('review_image')->isValid()) {
                $file = $request->file('review_image');
                $filename = 'review_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $destination = public_path('uploads/reviews');
                if (! is_dir($destination)) {
                    @mkdir($destination, 0755, true);
                }
                $file->move($destination, $filename);
                $uploadedImagePath = 'uploads/reviews/'.$filename;
            }

            // Ensure columns exist even if migrate has not been executed yet
            try {
                if (! Schema::hasColumn('testimonials', 'review_image') || ! Schema::hasColumn('testimonials', 'source')) {
                    Schema::table('testimonials', function (Blueprint $table) {
                        if (! Schema::hasColumn('testimonials', 'source')) {
                            $table->string('source')->default('website')->after('rating');
                        }
                        if (! Schema::hasColumn('testimonials', 'review_image')) {
                            $table->string('review_image')->nullable()->after('avatar_url');
                        }
                    });
                }
            } catch (\Throwable $schemaEx) {
                // Continue gracefully
            }

            $payload = [
                'name' => $request->name,
                'handle' => $request->handle ?: 'Pengunjung Nucomu',
                'order_tag' => $request->order_tag,
                'content' => $request->content,
                'rating' => $request->rating,
                'is_sample' => false,
                'is_active' => true,
            ];

            if (Schema::hasColumn('testimonials', 'source')) {
                $payload['source'] = 'website';
            }
            if ($uploadedImagePath && Schema::hasColumn('testimonials', 'review_image')) {
                $payload['review_image'] = $uploadedImagePath;
            }

            Testimonial::create($payload);
        } catch (\Throwable $e) {
            Log::error('Nucomu review store error: '.$e->getMessage());
        }

        return back()->with('review_success', 'Ulasan Anda berhasil ditambahkan! Terima kasih.');
    }

    /**
     * Validate reservation, save to database, and generate formatted WhatsApp link.
     */
    public function submitReservation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullName' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^([0-9\\s\\-\\+\\(\\)]*)$/', 'min:9', 'max:20'],
            'date' => ['required', 'date'],
            'time' => ['required', 'string'],
            'guestCount' => ['required', 'string'],
            'serviceType' => ['required', 'string'],
            'eventSection' => ['nullable', 'string', 'max:100'],
            'eventPackage' => ['nullable', 'string', 'max:255'],
            'area' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'fullName.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp aktif wajib diisi.',
            'phone.regex' => 'Format nomor WhatsApp tidak valid.',
            'date.required' => 'Tanggal acara wajib dipilih.',
            'time.required' => 'Jam acara wajib ditentukan.',
            'guestCount.required' => 'Jumlah tamu wajib ditentukan.',
            'serviceType.required' => 'Paket acara wajib dipilih.',
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
        $packageInfo = ! empty($validated['eventPackage']) ? $validated['eventPackage'] : null;

        // 1. Save reservation to database
        try {
            $guestInt = (int) preg_replace('/[^0-9]/', '', $validated['guestCount']);
            $notesSummary = "Acara: {$validated['serviceType']}";
            if ($packageInfo && ! str_contains($validated['serviceType'], $packageInfo)) {
                $notesSummary .= " | Paket: {$packageInfo}";
            }
            $notesSummary .= " | Area: {$validated['area']}";
            if (! empty($validated['notes'])) {
                $notesSummary .= " | Catatan: {$validated['notes']}";
            }

            Reservation::create([
                'name' => $validated['fullName'],
                'phone' => $validated['phone'],
                'date' => $validated['date'],
                'time' => $validated['time'],
                'guests' => $guestInt > 0 ? $guestInt : 2,
                'notes' => trim($notesSummary),
                'status' => 'pending',
            ]);
        } catch (\Throwable $e) {
            Log::warning('Gagal menyimpan reservasi ke DB: '.$e->getMessage());
        }

        // 2. Format Indonesian date
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
            '• Kategori Acara: '.$validated['serviceType']."\n".
            ($packageInfo ? '• Paket Dekorasi: '.$packageInfo."\n" : '').
            '• Area: '.$validated['area']."\n".
            '• Catatan Khusus: '.(! empty($validated['notes']) ? $validated['notes'] : '-')."\n\n".
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
