<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\OrderController;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Facility;
use App\Models\GalleryItem;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reservation;
use App\Models\ReservationField;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\ZendoDelivery;
use App\Support\FileUploadHelper;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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

        // Check if database settings override brand, contact, and content info
        try {
            $dbSettings = Setting::pluck('value', 'key');
            if (! empty($dbSettings['cafe_name'])) {
                $brand['name'] = $dbSettings['cafe_name'];
            }
            if (! empty($dbSettings['cafe_tagline'])) {
                $brand['tagline'] = $dbSettings['cafe_tagline'];
            }
            if (! empty($dbSettings['hero_title'])) {
                $brand['hero_title'] = $dbSettings['hero_title'];
            }
            if (! empty($dbSettings['hero_subtitle'])) {
                $brand['hero_subtitle'] = $dbSettings['hero_subtitle'];
            }
            if (! empty($dbSettings['hero_badge'])) {
                $brand['hero_badge'] = $dbSettings['hero_badge'];
            }
            if (! empty($dbSettings['cafe_story_headline'])) {
                $brand['story_headline'] = $dbSettings['cafe_story_headline'];
            }
            if (! empty($dbSettings['cafe_story_p1'])) {
                $brand['story_p1'] = $dbSettings['cafe_story_p1'];
            }
            $brand['story_p2'] = ! empty($dbSettings['cafe_story_p2'])
                ? $dbSettings['cafe_story_p2']
                : 'Dengan paduan estetika monokrom hangat dan material alami, setiap sudut dirancang agar Anda dapat beristirahat sejenak dari kesibukan harian dan menikmati obrolan yang mengalir perlahan.';
            if (! empty($dbSettings['about_label'])) {
                $brand['about_label'] = $dbSettings['about_label'];
            }
            if (! empty($dbSettings['about_quote'])) {
                $brand['about_quote'] = $dbSettings['about_quote'];
            }
            if (! empty($dbSettings['about_button_text'])) {
                $brand['about_button_text'] = $dbSettings['about_button_text'];
            }
            if (! empty($dbSettings['about_button_link'])) {
                $brand['about_button_link'] = $dbSettings['about_button_link'];
            }
            $brand['about_media_type'] = $dbSettings['about_media_type'] ?? 'video';
            $aboutMediaPath = $dbSettings['about_media_path'] ?? null;
            if ($aboutMediaPath) {
                if (str_starts_with($aboutMediaPath, 'http://') || str_starts_with($aboutMediaPath, 'https://')) {
                    $brand['about_media_url'] = $aboutMediaPath;
                } elseif (file_exists(public_path('uploads/about/'.$aboutMediaPath))) {
                    $brand['about_media_url'] = asset('uploads/about/'.$aboutMediaPath);
                } elseif (file_exists(public_path($aboutMediaPath))) {
                    $brand['about_media_url'] = asset($aboutMediaPath);
                } else {
                    $brand['about_media_url'] = asset($aboutMediaPath);
                }
            } else {
                $brand['about_media_url'] = asset('videos/tiramisu.mp4');
            }
            if (! empty($dbSettings['cafe_story'])) {
                $brand['story'] = $dbSettings['cafe_story'];
            }
            if (! empty($dbSettings['cafe_concept'])) {
                $brand['concept'] = $dbSettings['cafe_concept'];
            }
            $contact = array_merge($contact, self::getContactSettings());
            if (! empty($dbSettings['google_places_api_key'])) {
                $integrations['google_places_api_key'] = $dbSettings['google_places_api_key'];
            }
            if (! empty($dbSettings['google_place_id'])) {
                $integrations['google_place_id'] = $dbSettings['google_place_id'];
            }
        } catch (\Throwable $e) {
            // Fallback gracefully to config
        }

        // Fetch stories from DB or fallback
        $stories = collect();
        try {
            $stories = GalleryItem::where('is_active', true)->orderBy('sort_order')->get()->map(function (GalleryItem $item) {
                return [
                    'image' => $item->image_url,
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
        // Active promotional banners scheduled for today
        $activeBanners = collect();
        try {
            $activeBanners = Banner::activeNow()->orderBy('sort_order')->orderByDesc('id')->get();
        } catch (\Throwable $e) {
            // Graceful fallback
        }

        $dbFacilities = collect();
        try {
            $dbFacilities = Facility::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        } catch (\Throwable $e) {
            // Graceful fallback
        }

        $dbCategories = collect();
        try {
            $dbCategories = Category::where('is_active', true)->orderBy('sort_order')->get();
            $liveMenus = Menu::available()->with('category')->orderBy('sort_order')->get();
            if ($liveMenus->isNotEmpty()) {
                $mappedLiveMenus = $liveMenus->map(function ($m) {
                    $catSlug = $m->category ? ($m->category->slug ?: Str::slug($m->category->name)) : 'coffee';

                    return [
                        'id' => $m->slug ?: 'menu-'.$m->id,
                        'name' => $m->name,
                        'category' => $catSlug,
                        'category_label' => $m->category->name ?? ucfirst($catSlug),
                        'price' => (int) $m->price,
                        'price_formatted' => 'Rp '.number_format($m->price, 0, ',', '.'),
                        'short_info' => $m->description ?? 'Racikan pilihan Nucomu Cafe.',
                        'image' => $m->image_url,
                    ];
                })->toArray();

                $merged = array_merge($mappedLiveMenus, config('nucomu.all_menu', []));
                $seen = [];
                $uniqueAllMenu = [];
                foreach ($merged as $item) {
                    if (! isset($seen[$item['name']])) {
                        $seen[$item['name']] = true;
                        $uniqueAllMenu[] = $item;
                    }
                }
                $allMenu = $uniqueAllMenu;
            }
        } catch (\Throwable $e) {
            // Graceful fallback
        }

        $txSettings = $this->getTransactionSettings();
        $transactionMode = $txSettings['transaction_mode'];
        $zendoWhatsapp = $txSettings['zendo_whatsapp_clean'];

        $reservationFields = ReservationField::active()->get();

        return view('home.index', compact(
            'activeBanners',
            'brand',
            'contact',
            'facilities',
            'dbFacilities',
            'dbCategories',
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
            'zendoWhatsapp',
            'txSettings',
            'reservationFields'
        ));
    }

    /**
     * Get pinned reviews from the local database that have been curated by the admin.
     * Only reviews with is_pinned = true and is_active = true are returned.
     */
    protected function getCombinedReviews(): Collection
    {
        $pinnedReviews = collect();
        try {
            $pinnedReviews = Testimonial::where('is_active', true)
                ->where('is_pinned', true)
                ->latest()
                ->get()
                ->map(function (Testimonial $t) {
                    $imgUrl = $t->review_image ?? null;
                    if ($imgUrl && ! str_starts_with($imgUrl, 'http://') && ! str_starts_with($imgUrl, 'https://')) {
                        $imgUrl = asset(ltrim($imgUrl, '/'));
                    }

                    return [
                        'id' => $t->id,
                        'name' => $t->name,
                        'handle' => $t->handle ?? 'Pengunjung Nucomu',
                        'avatar_url' => $t->avatar_url ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80',
                        'avatar' => $t->avatar_url ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80',
                        'rating' => (int) $t->rating,
                        'stars' => (int) $t->rating,
                        'content' => $t->content,
                        'text' => $t->content,
                        'comment' => $t->content,
                        'order_tag' => $t->order_tag ?? 'Menu Nucomu',
                        'time_ago' => $t->created_at ? $t->created_at->diffForHumans() : 'Baru saja',
                        'review_image' => $imgUrl,
                        'review_images' => $imgUrl ? [$imgUrl] : [],
                        'source' => $t->source ?: 'website',
                        'is_pinned' => true,
                    ];
                });
        } catch (\Throwable $e) {
            // Keep empty if DB is unavailable
        }

        return $pinnedReviews;
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

        $dbCategories = collect();
        try {
            $dbCategories = Category::where('is_active', true)->orderBy('sort_order')->get();
        } catch (\Throwable $e) {
        }

        if ($selectedCategory !== 'all') {
            $filteredMenu = $allMenu->filter(function ($item) use ($selectedCategory) {
                return $item['category'] === $selectedCategory;
            })->values();
        } else {
            $filteredMenu = $allMenu;
        }

        $txSettings = $this->getTransactionSettings();
        $transactionMode = $txSettings['transaction_mode'];
        $zendoWhatsapp = $txSettings['zendo_whatsapp_clean'];

        return view('menu.all', compact('brand', 'contact', 'filteredMenu', 'selectedCategory', 'dbCategories', 'txSettings', 'transactionMode', 'zendoWhatsapp'));
    }

    /**
     * Nucomu Story & Review page — serves gallery + testimonials from DB.
     */
    public function nucomuPage()
    {
        // Gallery stories from DB
        $stories = collect();
        try {
            $stories = GalleryItem::where('is_active', true)->orderBy('sort_order')->get()->map(function (GalleryItem $item) {
                return [
                    'image' => $item->image_url,
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
     * Get compiled contact settings merged from database and config fallbacks.
     */
    public static function getContactSettings(): array
    {
        $settings = collect();
        try {
            $settings = Setting::pluck('value', 'key');
        } catch (\Throwable $e) {
        }

        $isPlaceholder = fn (?string $val) => empty($val) || str_starts_with(trim($val), '[ISI:');

        $address = ! $isPlaceholder($settings['cafe_address'] ?? null)
            ? trim($settings['cafe_address'])
            : (string) config('nucomu.contact.address', 'Jl. Mayor Sujadi, Plosokandang, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66221');

        $rawCafeWa = ! $isPlaceholder($settings['cafe_whatsapp'] ?? null)
            ? trim($settings['cafe_whatsapp'])
            : (string) config('nucomu.contact.whatsapp_number', '6281234567890');
        $cleanCafeWa = preg_replace('/[^0-9]/', '', $rawCafeWa);
        if (str_starts_with($cleanCafeWa, '08')) {
            $cleanCafeWa = '628'.substr($cleanCafeWa, 2);
        } elseif (str_starts_with($cleanCafeWa, '8')) {
            $cleanCafeWa = '628'.substr($cleanCafeWa, 1);
        }

        $rawZendoWa = ! $isPlaceholder($settings['zendo_whatsapp'] ?? null)
            ? trim($settings['zendo_whatsapp'])
            : (string) config('nucomu.contact.zendo_whatsapp', '6285707300240');
        $cleanZendoWa = preg_replace('/[^0-9]/', '', $rawZendoWa);
        if (str_starts_with($cleanZendoWa, '08')) {
            $cleanZendoWa = '628'.substr($cleanZendoWa, 2);
        } elseif (str_starts_with($cleanZendoWa, '8')) {
            $cleanZendoWa = '628'.substr($cleanZendoWa, 1);
        }

        $instagram = ! $isPlaceholder($settings['cafe_instagram'] ?? null) ? trim($settings['cafe_instagram']) : null;
        $tiktok = ! $isPlaceholder($settings['cafe_tiktok'] ?? null) ? trim($settings['cafe_tiktok']) : null;
        $grabfood = ! $isPlaceholder($settings['grabfood_url'] ?? null) ? trim($settings['grabfood_url']) : null;
        $email = ! $isPlaceholder($settings['cafe_email'] ?? null) ? trim($settings['cafe_email']) : null;
        $mapsUrl = ! $isPlaceholder($settings['cafe_maps_url'] ?? null)
            ? trim($settings['cafe_maps_url'])
            : (string) config('nucomu.contact.google_maps_url', 'https://maps.google.com/?q=Nucomu+Cafe+Tulungagung');
        $mapsEmbed = ! $isPlaceholder($settings['cafe_maps_embed'] ?? null) ? trim($settings['cafe_maps_embed']) : null;
        $hours = ! $isPlaceholder($settings['cafe_opening_hours'] ?? null)
            ? trim($settings['cafe_opening_hours'])
            : (string) config('nucomu.contact.hours', 'Senin – Sabtu: 11:00 – 21:00 WIB | Minggu: 11:00 – 22:00 WIB');

        $displayWa = '+'.substr($cleanCafeWa, 0, 2).' '.substr($cleanCafeWa, 2, 3).'-'.substr($cleanCafeWa, 5, 4).'-'.substr($cleanCafeWa, 9);

        return [
            'address' => $address,
            'whatsapp_number' => $cleanCafeWa,
            'whatsapp_clean' => $cleanCafeWa,
            'whatsapp_display' => $displayWa,
            'zendo_whatsapp' => $cleanZendoWa,
            'zendo_whatsapp_clean' => $cleanZendoWa,
            'instagram' => $instagram,
            'instagram_url' => $instagram,
            'tiktok' => $tiktok,
            'tiktok_url' => $tiktok,
            'grabfood_url' => $grabfood,
            'email' => $email,
            'maps_url' => $mapsUrl,
            'maps_embed' => $mapsEmbed,
            'hours' => $hours,
            'opening_hours' => $hours,
        ];
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
        $hideLiveTracking = false;
        $orderModeZendo = true;
        $orderModeGrabfood = false;
        $grabfoodUrl = '';
        $zendoChatTemplate = OrderController::DEFAULT_ZENDO_TEMPLATE;

        $orderModeDineIn = true;
        $orderModeZendoTracking = false;
        $orderModeZendoChat = true;
        $orderDineInCash = true;
        $orderDineInQris = true;
        $qrisImage = null;

        try {
            $dbSettings = Setting::pluck('value', 'key');

            if (! empty($dbSettings['grabfood_url'])) {
                $grabfoodUrl = trim((string) $dbSettings['grabfood_url']);
            }
            if (! empty($dbSettings['zendo_chat_template'])) {
                $zendoChatTemplate = (string) $dbSettings['zendo_chat_template'];
            }

            $dbMode = $dbSettings['transaction_mode'] ?? null;
            if (in_array($dbMode, ['kasir', 'zendo', 'both'], true)) {
                $mode = $dbMode;
            }

            $hideLiveTracking = in_array((string) ($dbSettings['hide_live_tracking'] ?? '0'), ['1', 'true', 'yes'], true);

            // Backward-compatible reading of the 3 transaction modes
            if (isset($dbSettings['order_mode_dine_in'])) {
                $orderModeDineIn = in_array((string) $dbSettings['order_mode_dine_in'], ['1', 'true', 'yes'], true);
            } else {
                $orderModeDineIn = in_array($mode, ['kasir', 'both'], true);
            }

            if (isset($dbSettings['order_mode_zendo_tracking'])) {
                $orderModeZendoTracking = in_array((string) $dbSettings['order_mode_zendo_tracking'], ['1', 'true', 'yes'], true);
            } else {
                $orderModeZendoTracking = in_array($mode, ['zendo', 'both'], true) && ! $hideLiveTracking;
            }

            if (isset($dbSettings['order_mode_zendo_chat'])) {
                $orderModeZendoChat = in_array((string) $dbSettings['order_mode_zendo_chat'], ['1', 'true', 'yes'], true);
            } else {
                $orderModeZendoChat = in_array($mode, ['zendo', 'both'], true) && $hideLiveTracking;
            }

            // Normalisasi saling mengunci jika di DB keduanya bernilai true: pelacakan menang, chat nonaktif
            if ($orderModeZendoTracking && $orderModeZendoChat) {
                $orderModeZendoChat = false;
            }

            // Sub-pengaturan Mode 1
            $orderDineInCash = ! isset($dbSettings['order_dine_in_cash']) || in_array((string) $dbSettings['order_dine_in_cash'], ['1', 'true', 'yes'], true);
            $orderDineInQris = ! isset($dbSettings['order_dine_in_qris']) || in_array((string) $dbSettings['order_dine_in_qris'], ['1', 'true', 'yes'], true);
            $qrisImage = $dbSettings['qris_image'] ?? null;

            // Pemetaan nilai turunan untuk legacy consumer
            $orderModeZendo = $orderModeZendoTracking || $orderModeZendoChat;
            $hideLiveTracking = $orderModeZendoChat && ! $orderModeZendoTracking;

            if ($orderModeDineIn && $orderModeZendo) {
                $mode = 'both';
            } elseif ($orderModeDineIn) {
                $mode = 'kasir';
            } elseif ($orderModeZendo) {
                $mode = 'zendo';
            } else {
                $mode = 'none';
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

        $isAnyModeActive = $orderModeDineIn || $orderModeZendoTracking || $orderModeZendoChat;

        return [
            'order_mode_dine_in' => $orderModeDineIn,
            'order_mode_zendo_tracking' => $orderModeZendoTracking,
            'order_mode_zendo_chat' => $orderModeZendoChat,
            'is_any_mode_active' => $isAnyModeActive,
            'order_dine_in_cash' => $orderDineInCash,
            'order_dine_in_qris' => $orderDineInQris,
            'qris_image' => $qrisImage,
            'transaction_mode' => $mode,
            'hide_live_tracking' => $hideLiveTracking,
            'order_mode_zendo' => $orderModeZendo,
            'order_mode_grabfood' => false,
            'grabfood_url' => $grabfoodUrl,
            'zendo_chat_template' => $zendoChatTemplate,
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
     * Get the list of devices currently connected/bound as couriers.
     */
    public function getConnectedDevicesList(): array
    {
        $raw = Setting::get('zendo_connected_devices');
        $devices = [];
        if ($raw) {
            $decoded = json_decode((string) $raw, true);
            if (is_array($decoded)) {
                $devices = $decoded;
            }
        }

        // Check if any registered courier phone has an active device binding not in the JSON list yet
        $couriers = $this->getTransactionSettings()['zendo_couriers'] ?? [];
        foreach ($couriers as $courier) {
            $phone = (string) ($courier['clean_phone'] ?? '');
            if ($phone === '') {
                continue;
            }

            $connectedAt = Setting::get("zendo_connected_at_{$phone}");
            $deviceHash = Setting::get("zendo_device_{$phone}");
            if ($connectedAt && $deviceHash) {
                $alreadyExists = collect($devices)->contains(fn ($d) => ($d['courier_phone'] ?? '') === $phone);
                if (! $alreadyExists) {
                    $lastPing = Setting::get("zendo_last_ping_{$phone}") ?: $connectedAt;
                    $devices[] = [
                        'id' => 'dev_'.substr(md5($phone), 0, 12),
                        'token_hash' => (string) $deviceHash,
                        'token_preview' => substr((string) $deviceHash, 0, 8).'...',
                        'courier_phone' => $phone,
                        'courier_name' => $courier['name'] ?? 'Kurir Zendo',
                        'device_name' => 'Perangkat Kurir (Browser / HP)',
                        'ip_address' => '-',
                        'user_agent' => '',
                        'connected_at' => (string) $connectedAt,
                        'last_active_at' => (string) $lastPing,
                    ];
                }
            }
        }

        // Enrich with live online status and formatted timestamps
        foreach ($devices as &$dev) {
            $lastActive = ! empty($dev['last_active_at']) ? Carbon::parse($dev['last_active_at']) : null;
            $dev['is_online'] = $lastActive !== null && $lastActive->greaterThan(now()->subMinutes(3));
            $dev['last_active_diff'] = $lastActive ? $lastActive->diffForHumans() : 'Belum ada aktivitas';
            $dev['connected_at_formatted'] = ! empty($dev['connected_at']) ? Carbon::parse($dev['connected_at'])->format('d M Y, H:i') : '-';
        }
        unset($dev);

        return $devices;
    }

    /**
     * Record or update a connected courier device in the persistent list.
     */
    public function recordConnectedDevice(ZendoDelivery $delivery, string $deviceToken): void
    {
        $devices = $this->getConnectedDevicesList();
        $tokenHash = hash('sha256', $deviceToken);
        $userAgent = (string) (request()->userAgent() ?? '');
        $ip = (string) (request()->ip() ?? '');
        $deviceName = $this->parseDeviceName($userAgent);

        $found = false;
        foreach ($devices as &$dev) {
            if (($dev['token_hash'] ?? '') === $tokenHash) {
                $dev['token_hash'] = $tokenHash;
                $dev['token_preview'] = substr($deviceToken, 0, 8).'...';
                $dev['courier_phone'] = $delivery->courier_phone;
                $dev['courier_name'] = $delivery->courier_name;
                $dev['last_active_at'] = now()->toDateTimeString();
                $dev['ip_address'] = $ip;
                $dev['user_agent'] = $userAgent;
                $dev['device_name'] = $deviceName;
                $found = true;
                break;
            }
        }
        unset($dev);

        if (! $found) {
            array_unshift($devices, [
                'id' => 'dev_'.Str::random(12),
                'token_hash' => $tokenHash,
                'token_preview' => substr($deviceToken, 0, 8).'...',
                'courier_phone' => $delivery->courier_phone,
                'courier_name' => $delivery->courier_name,
                'device_name' => $deviceName,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'connected_at' => now()->toDateTimeString(),
                'last_active_at' => now()->toDateTimeString(),
            ]);
        }

        // Clean up formatting attributes before storing raw data
        $cleanDevices = array_map(function ($d) {
            return [
                'id' => $d['id'] ?? 'dev_'.Str::random(12),
                'token_hash' => $d['token_hash'] ?? '',
                'token_preview' => $d['token_preview'] ?? '',
                'courier_phone' => $d['courier_phone'] ?? '',
                'courier_name' => $d['courier_name'] ?? '',
                'device_name' => $d['device_name'] ?? 'Perangkat Kurir',
                'ip_address' => $d['ip_address'] ?? '-',
                'user_agent' => $d['user_agent'] ?? '',
                'connected_at' => $d['connected_at'] ?? now()->toDateTimeString(),
                'last_active_at' => $d['last_active_at'] ?? now()->toDateTimeString(),
            ];
        }, array_slice($devices, 0, 50));

        Setting::set('zendo_connected_devices', json_encode($cleanDevices, JSON_UNESCAPED_UNICODE));
    }

    /**
     * Parse User-Agent string to a human-readable device name.
     */
    public function parseDeviceName(?string $userAgent): string
    {
        if (empty($userAgent)) {
            return 'Perangkat Kurir (Web Browser)';
        }

        $platform = 'Perangkat Lain';
        if (stripos($userAgent, 'Android') !== false) {
            $platform = 'Android';
            if (stripos($userAgent, 'Samsung') !== false) {
                $platform = 'Android (Samsung)';
            } elseif (stripos($userAgent, 'Xiaomi') !== false || stripos($userAgent, 'Redmi') !== false) {
                $platform = 'Android (Xiaomi)';
            } elseif (stripos($userAgent, 'OPPO') !== false) {
                $platform = 'Android (OPPO)';
            } elseif (stripos($userAgent, 'vivo') !== false) {
                $platform = 'Android (Vivo)';
            }
        } elseif (stripos($userAgent, 'iPhone') !== false) {
            $platform = 'iPhone (iOS)';
        } elseif (stripos($userAgent, 'iPad') !== false) {
            $platform = 'iPad (iPadOS)';
        } elseif (stripos($userAgent, 'Windows') !== false) {
            $platform = 'Windows PC';
        } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS') !== false) {
            $platform = 'Mac';
        } elseif (stripos($userAgent, 'Linux') !== false) {
            $platform = 'Linux';
        }

        $browser = 'Browser';
        if (stripos($userAgent, 'Chrome') !== false && stripos($userAgent, 'Edg') === false) {
            $browser = 'Chrome';
        } elseif (stripos($userAgent, 'Safari') !== false && stripos($userAgent, 'Chrome') === false) {
            $browser = 'Safari';
        } elseif (stripos($userAgent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (stripos($userAgent, 'Edg') !== false) {
            $browser = 'Edge';
        } elseif (stripos($userAgent, 'Opera') !== false || stripos($userAgent, 'OPR') !== false) {
            $browser = 'Opera';
        }

        $isMobile = stripos($userAgent, 'Mobile') !== false;
        $mobileSuffix = $isMobile ? ' Mobile' : '';

        return "{$platform} • {$browser}{$mobileSuffix}";
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
     * Calculate geodesic distance (in kilometers) between two coordinates using the Haversine formula.
     */
    public function calculateDistanceKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371.0; // Mean Earth radius in km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 1);
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
     * Compile and format Zendo WhatsApp message from admin chat template or default template.
     */
    public function formatZendoChatMessage(
        string $orderCode,
        string $customerName,
        ?string $customerPhone,
        ?string $deliveryAddress,
        array $items,
        int $subtotal,
        int $deliveryFee,
        int $grandTotal,
        ?string $notes = null,
        ?string $trackingUrl = null,
        bool $hideLiveTracking = true
    ): string {
        $orderList = [];
        foreach (array_values($items) as $idx => $item) {
            $num = $idx + 1;
            $qty = (int) ($item['qty'] ?? 1);
            $unitPrice = (int) ($item['unit_price'] ?? ($item['price'] ?? 0));
            $lineTotal = (int) ($item['line_total'] ?? ($unitPrice * $qty));
            $name = (string) ($item['menu_name'] ?? ($item['name'] ?? 'Menu'));
            $unitFormatted = 'Rp '.number_format($unitPrice, 0, ',', '.');
            $lineFormatted = 'Rp '.number_format($lineTotal, 0, ',', '.');
            $orderList[] = "{$num}. {$name} ({$qty}x @ {$unitFormatted}) = {$lineFormatted}";
        }
        $daftarPesanan = count($orderList) > 0 ? implode("\n", $orderList) : '-';

        $template = Setting::get('zendo_chat_template') ?: OrderController::DEFAULT_ZENDO_TEMPLATE;
        // Normalize placeholders to avoid double "Rp Rp" if template has "Rp {total}"
        $template = preg_replace('/Rp\s*(\{total\}|\{subtotal\}|\{ongkir\})/i', '$1', $template);

        if ($hideLiveTracking) {
            // Sesuaikan label ongkir di mode chat Zendo jika template lama masih memakai label ongkir standar
            $template = preg_replace('/•\s*Ongkir:\s*\{ongkir\}/i', '• Ongkir (perkiraan, final dikonfirmasi kurir): {ongkir}', $template);
        }

        $replacements = [
            '{kode_pesanan}' => $orderCode,
            '{nama}' => $customerName,
            '{telepon}' => (! empty($customerPhone) && $customerPhone !== '-' && $customerPhone !== 'Via Zendo WA') ? $customerPhone : '-',
            '{alamat}' => ! empty($deliveryAddress) ? $deliveryAddress : '-',
            '{daftar_pesanan}' => $daftarPesanan,
            '{subtotal}' => 'Rp '.number_format($subtotal, 0, ',', '.'),
            '{ongkir}' => 'Rp '.number_format($deliveryFee, 0, ',', '.'),
            '{total}' => 'Rp '.number_format($grandTotal, 0, ',', '.'),
            '{catatan}' => (! empty($notes) && $notes !== '-') ? $notes : '-',
        ];

        $message = str_replace(array_keys($replacements), array_values($replacements), $template);

        if (! $hideLiveTracking && ! empty($trackingUrl)) {
            if (str_contains($message, '{link_pelacakan}')) {
                $message = str_replace('{link_pelacakan}', $trackingUrl, $message);
            } else {
                $message .= "\n\n📍 *Link Pelacakan:*\n".$trackingUrl;
            }
        } else {
            $message = str_replace('{link_pelacakan}', '', $message);
        }

        return trim($message);
    }

    /**
     * Build compact receipt-style WhatsApp message text (Client Mode: No tracking link, struk format).
     */
    protected function buildZendoReceiptText(
        string $orderCode,
        string $customerName,
        ?string $customerPhone,
        ?string $deliveryAddress,
        array $items,
        int $subtotal,
        int $deliveryFee,
        int $grandTotal,
        ?string $notes = null
    ): string {
        return $this->formatZendoChatMessage(
            $orderCode,
            $customerName,
            $customerPhone,
            $deliveryAddress,
            $items,
            $subtotal,
            $deliveryFee,
            $grandTotal,
            $notes,
            null,
            true
        );
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
        ?string $trackingUrl = null,
        bool $hideLiveTracking = false
    ): string {
        return $this->formatZendoChatMessage(
            $orderCode,
            $customerName,
            $customerPhone,
            $deliveryAddress,
            $items,
            $subtotal,
            $deliveryFee,
            $grandTotal,
            $notes,
            $trackingUrl,
            $hideLiveTracking
        );
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
        ?string $courierName = null,
        bool $hideLiveTracking = false
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
            $courierName,
            null,
            null,
            $hideLiveTracking
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

        if ($request->wantsJson()) {
            return response()->json([
                'categories' => $categories,
                'menus' => $menus,
                'transactionMode' => $transactionMode,
                'zendoWhatsapp' => $zendoWhatsapp,
                'zendoCouriers' => $zendoCouriers,
            ]);
        }

        // Standalone order page is deprecated; menu ordering is unified on the company profile
        return redirect()->route('home', ['#menu' => true]);
    }

    /**
     * Store nucomu order (AJAX — for Zendo: no receipt slip, sends directly to chosen Zendo courier WA).
     */
    public function storeOrder(Request $request)
    {
        $txSettings = $this->getTransactionSettings();
        $isAnyModeActive = (bool) ($txSettings['is_any_mode_active'] ?? true);
        $orderModeDineIn = (bool) ($txSettings['order_mode_dine_in'] ?? true);
        $orderModeZendoTracking = (bool) ($txSettings['order_mode_zendo_tracking'] ?? false);
        $orderModeZendoChat = (bool) ($txSettings['order_mode_zendo_chat'] ?? false);
        $orderModeZendo = $orderModeZendoTracking || $orderModeZendoChat;

        if (! $isAnyModeActive) {
            return response()->json([
                'success' => false,
                'message' => 'Layanan pemesanan sedang dinonaktifkan sementara oleh admin.',
            ], 422);
        }

        $allowedMode = $txSettings['transaction_mode'];
        $zendoCouriers = $txSettings['zendo_couriers'];
        $zendoSendMode = $txSettings['zendo_send_mode'];
        $zendoTariff = $txSettings['zendo_tariff'];

        $requestedMethod = strtolower((string) $request->input('process_method', 'kasir'));
        if (! in_array($requestedMethod, ['kasir', 'zendo'], true)) {
            $requestedMethod = $orderModeDineIn ? 'kasir' : 'zendo';
        }
        $processMethod = $requestedMethod;

        if ($processMethod === 'kasir' && ! $orderModeDineIn) {
            return response()->json([
                'success' => false,
                'message' => 'Layanan pemesanan makan di kafe sedang dinonaktifkan sementara oleh admin.',
            ], 422);
        }

        if ($processMethod === 'zendo' && ! $orderModeZendo) {
            return response()->json([
                'success' => false,
                'message' => 'Layanan pemesanan via Kurir Zendo sedang dinonaktifkan sementara oleh admin.',
            ], 422);
        }

        if (! $request->filled('customer_lat') && $request->filled('destination_lat')) {
            $request->merge(['customer_lat' => $request->input('destination_lat')]);
        }
        if (! $request->filled('customer_lng') && $request->filled('destination_lng')) {
            $request->merge(['customer_lng' => $request->input('destination_lng')]);
        }

        $cafeLat = (float) ($txSettings['cafe_lat'] ?? config('nucomu.contact.cafe_lat', -8.0765099));
        $cafeLng = (float) ($txSettings['cafe_lng'] ?? config('nucomu.contact.cafe_lng', 111.918712));

        $rawLat = $request->input('customer_lat');
        $rawLng = $request->input('customer_lng');
        $hasValidCoords = is_numeric($rawLat) && is_numeric($rawLng)
            && (float) $rawLat >= -90.0 && (float) $rawLat <= 90.0
            && (float) $rawLng >= -180.0 && (float) $rawLng <= 180.0;

        $customerLat = $hasValidCoords ? (float) $rawLat : null;
        $customerLng = $hasValidCoords ? (float) $rawLng : null;

        $distanceKm = null;
        $deliveryFee = 0;

        if ($processMethod === 'zendo') {
            if ($hasValidCoords) {
                // Hitung jarak garis lurus via Haversine formula
                $straightKm = $this->calculateDistanceKm($cafeLat, $cafeLng, $customerLat, $customerLng);
                $clientDist = is_numeric($request->input('distance_km')) ? (float) $request->input('distance_km') : null;

                // Jika client mengirim jarak rute jalan yang wajar (>= jarak lurus dan tidak dimanipulasi jadi 0), gunakan; selain itu pakai jarak lurus server
                if ($clientDist !== null && $clientDist >= $straightKm && $clientDist <= ($straightKm * 2.5 + 2.0)) {
                    $distanceKm = round($clientDist, 1);
                } else {
                    $distanceKm = $straightKm;
                }

                $deliveryFee = $this->calculateZendoDeliveryFee($distanceKm, $zendoTariff);
            } else {
                // Tanpa koordinat GPS (Mode 3: Hanya Chat ke Zendo), pakai tarif dasar tetap yang tersimpan di server
                $distanceKm = 1.5;
                $deliveryFee = (int) ($zendoTariff['tier_0_3'] ?? 10000);
            }
        }

        if (! $request->has('items') || ! is_array($request->input('items')) || empty($request->input('items'))) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang pesanan masih kosong.',
            ], 422);
        }

        $normalizedItems = [];
        $menuExistsInDb = Menu::exists();

        foreach ($request->input('items') as $idx => $item) {
            $rawQty = $item['qty'] ?? null;
            if (! is_numeric($rawQty) || (int) $rawQty < 1 || floor((float) $rawQty) != (float) $rawQty) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah pesanan (qty) untuk setiap menu harus berupa bilangan bulat minimal 1.',
                ], 422);
            }
            $qty = (int) $rawQty;

            $menuIdVal = $item['menu_id'] ?? $item['id'] ?? null;
            $menu = null;
            if (is_numeric($menuIdVal)) {
                $menu = Menu::find((int) $menuIdVal);
            }
            if (! $menu && ! empty($item['menu_name'] ?? $item['name'] ?? null)) {
                $menu = Menu::where('name', $item['menu_name'] ?? $item['name'])->first();
            }

            if ($menuExistsInDb) {
                if (! $menu) {
                    $itemLabel = $item['menu_name'] ?? $item['name'] ?? 'Menu #'.($idx + 1);

                    return response()->json([
                        'success' => false,
                        'message' => "Menu '{$itemLabel}' tidak ditemukan dalam daftar menu kami.",
                    ], 422);
                }

                if (! $menu->is_available) {
                    return response()->json([
                        'success' => false,
                        'message' => "Menu '{$menu->name}' sedang tidak tersedia saat ini.",
                    ], 422);
                }

                $unitPrice = (int) $menu->price;
                $menuName = $menu->name;
                $validMenuId = $menu->id;
            } else {
                $unitPrice = (int) ($item['unit_price'] ?? $item['price'] ?? 0);
                $menuName = (string) ($item['menu_name'] ?? $item['name'] ?? 'Menu');
                $validMenuId = is_numeric($menuIdVal) ? (int) $menuIdVal : null;
            }

            $lineTotal = $unitPrice * $qty;

            $normalizedItems[] = [
                'menu_id' => $validMenuId,
                'menu_name' => $menuName,
                'unit_price' => $unitPrice,
                'qty' => $qty,
                'line_total' => $lineTotal,
                'options' => $item['options'] ?? null,
            ];
        }

        $calcSubtotal = (int) collect($normalizedItems)->sum('line_total');
        $grandTotal = $calcSubtotal + $deliveryFee;

        $request->merge([
            'items' => $normalizedItems,
            'total' => $grandTotal,
            'customer_lat' => $customerLat,
            'customer_lng' => $customerLng,
            'distance_km' => $distanceKm,
        ]);

        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'nullable|string|max:30',
            'delivery_address' => $processMethod === 'zendo' ? 'required|string|max:500' : 'nullable|string|max:500',
            'distance_km' => 'nullable|numeric|min:0',
            'customer_lat' => 'nullable|numeric',
            'customer_lng' => 'nullable|numeric',
            'courier_index' => 'nullable|integer|min:1',
            'process_method' => 'nullable|string|in:kasir,zendo',
            'dine_type' => 'nullable|string|in:dine_in,take_away',
            'table_no' => 'nullable|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'nullable',
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

        $subtotal = $calcSubtotal;

        $dineType = $request->input('dine_type', 'dine_in');
        $tableNo = trim((string) $request->input('table_no', ''));
        $kasirParts = [];
        if ($processMethod === 'kasir') {
            if ($dineType === 'dine_in' && $tableNo !== '') {
                $kasirParts[] = "[Nomor Meja: {$tableNo}]";
            } elseif ($dineType === 'take_away') {
                $kasirParts[] = '[Bawa Pulang / Take Away]';
            }
        }
        if ($rawNotes !== '') {
            $kasirParts[] = $rawNotes;
        }
        $storedNotes = count($kasirParts) > 0 ? implode(' | ', $kasirParts) : null;

        $isTrackingMode = (bool) ($txSettings['order_mode_zendo_tracking'] ?? false);

        if ($processMethod === 'zendo') {
            if ($isTrackingMode) {
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
            } else {
                // Mode 3: Hanya Chat ke Zendo (tanpa live tracking kurir)
                $zendoParts = [
                    '[KURIR ZENDO] Alamat: '.$deliveryAddress,
                    'Ongkir: Rp '.number_format($deliveryFee, 0, ',', '.'),
                ];
            }
            if ($rawNotes !== '') {
                $zendoParts[] = 'Catatan: '.$rawNotes;
            }
            $storedNotes = implode(' | ', $zendoParts);
        }

        $paymentMethod = ($processMethod === 'kasir' && $request->input('payment_method') === 'qris') ? 'qris' : 'cash';

        try {
            $order = DB::transaction(function () use ($request, $processMethod, $paymentMethod, $dineType, $customerPhone, $storedNotes, $subtotal, $grandTotal, $deliveryAddress, $deliveryFee, $distanceKm, $tableNo, $customerLat, $customerLng) {
                $date = Carbon::now()->format('Ymd');
                $count = Order::whereDate('created_at', today())->count() + 1;
                $code = 'NCM-'.$date.'-'.str_pad($count, 3, '0', STR_PAD_LEFT);

                $order = Order::create([
                    'code' => $code,
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $customerPhone !== '' ? $customerPhone : ($processMethod === 'zendo' ? 'Via Zendo WA' : '-'),
                    'order_type' => $processMethod === 'zendo' ? 'take_away' : ($dineType === 'take_away' ? 'take_away' : 'dine_in'),
                    'delivery_address' => $processMethod === 'zendo' ? $deliveryAddress : null,
                    'delivery_fee' => $deliveryFee,
                    'form_data' => [
                        'customer_lat' => $customerLat,
                        'customer_lng' => $customerLng,
                        'distance_km' => $distanceKm,
                        'table_no' => $tableNo,
                        'dine_type' => $dineType,
                    ],
                    'notes' => $storedNotes,
                    'subtotal' => $subtotal,
                    'total' => $grandTotal,
                    'payment_method' => $paymentMethod,
                    'payment_status' => 'unpaid',
                    'status' => $processMethod === 'zendo' ? 'processing' : 'pending',
                ]);

                foreach ($request->items as $item) {
                    $menuIdVal = $item['menu_id'] ?? null;
                    $validMenuId = is_numeric($menuIdVal) && Menu::whereKey($menuIdVal)->exists() ? (int) $menuIdVal : null;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $validMenuId,
                        'menu_name' => $item['menu_name'],
                        'unit_price' => $item['unit_price'],
                        'qty' => $item['qty'],
                        'options' => $item['options'] ?? null,
                        'line_total' => $item['line_total'],
                    ]);
                }

                return $order;
            });

            try {
                KasirController::deductStockForOrderItems((array) $request->items);
            } catch (\Throwable $stockEx) {
                // Continue gracefully
            }

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
            $hideLiveTracking = ! $isTrackingMode || (bool) ($txSettings['hide_live_tracking'] ?? false);
            if ($processMethod === 'zendo' && $isTrackingMode) {
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
                    if (! $hideLiveTracking) {
                        $customBase = trim((string) ($txSettings['zendo_public_url'] ?? ''));
                        if ($customBase !== '') {
                            $customBase = rtrim($customBase, '/');
                            $trackingUrl = $customBase.route('zendo.track', $delivery->tracking_token, false);
                            $courierUrl = $customBase.route('zendo.courier.show', $delivery->courier_token, false);
                        } else {
                            $trackingUrl = route('zendo.track', $delivery->tracking_token);
                            $courierUrl = route('zendo.courier.show', $delivery->courier_token);
                        }
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
                $trackingUrl,
                $hideLiveTracking
            );
            $whatsappUrl = 'https://wa.me/'.$zendoWaClean.'?text='.rawurlencode($messageText);

            $autoSent = false;
            if ($processMethod === 'zendo') {
                $autoSent = $this->dispatchZendoWhatsappMessage($zendoWaClean, $messageText, $txSettings);

                // Also send the tracking link to the customer's WhatsApp (when gateway is configured and live tracking is enabled)
                if (! $hideLiveTracking && $autoSent && $trackingUrl && $customerPhone !== '') {
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
                'payment_method' => $paymentMethod,
                'qris_image_url' => (! empty($txSettings['qris_image']) && file_exists(public_path($txSettings['qris_image']))) ? asset($txSettings['qris_image']) : null,
                'slip_url' => $processMethod === 'kasir' ? route('nucomu.order.slip', [
                    'code' => $orderCode,
                    'method' => 'kasir',
                ]) : null,
                'tracking_url' => $hideLiveTracking ? null : $trackingUrl,
                'hide_live_tracking' => $hideLiveTracking,
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
                    $courierName,
                    null,
                    null,
                    $hideLiveTracking ?? false
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
                    'tracking_url' => null,
                    'hide_live_tracking' => $hideLiveTracking ?? false,
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
            'review_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'content.required' => 'Ulasan singkat wajib diisi.',
            'rating.required' => 'Rating bintang wajib dipilih.',
            'review_image.uploaded' => 'Foto ulasan gagal diunggah ke server.',
            'review_image.image' => 'Berkas foto ulasan harus berupa gambar.',
            'review_image.mimes' => 'Format foto ulasan harus JPG, PNG, atau WebP.',
            'review_image.max' => 'Ukuran foto ulasan maksimal 5 MB.',
        ]);

        try {
            $uploadedImagePath = null;
            if ($request->hasFile('review_image')) {
                $file = $request->file('review_image');
                if (! $file->isValid()) {
                    $errCode = $file->getError();
                    $errMsg = FileUploadHelper::getErrorMessageForCode($errCode, 'Foto ulasan');

                    return back()->withInput()->with('error', $errMsg);
                }

                $destination = public_path('uploads/reviews');
                FileUploadHelper::ensureDirectoryWritable($destination, 'folder upload Ulasan');

                $filename = 'review_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
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
                'is_pinned' => false,
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
        $activeFields = ReservationField::active()->get();
        $errors = [];
        $formData = [];

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        // Fallback if no active fields in database
        if ($activeFields->isEmpty()) {
            $activeFields = collect([
                (object) ['field_key' => 'name', 'label' => 'Nama Lengkap', 'type' => 'text', 'is_required' => true],
                (object) ['field_key' => 'phone', 'label' => 'Nomor WhatsApp', 'type' => 'tel', 'is_required' => true],
                (object) ['field_key' => 'date', 'label' => 'Tanggal Kedatangan', 'type' => 'date', 'is_required' => true],
                (object) ['field_key' => 'time', 'label' => 'Jam Kedatangan', 'type' => 'time', 'is_required' => true],
                (object) ['field_key' => 'guests', 'label' => 'Jumlah Tamu', 'type' => 'number', 'is_required' => true],
                (object) ['field_key' => 'notes', 'label' => 'Catatan Tambahan', 'type' => 'textarea', 'is_required' => false],
            ]);
        }

        foreach ($activeFields as $field) {
            $key = $field->field_key;
            $type = $field->type;
            $isRequired = (bool) $field->is_required;
            $label = $field->label;

            if ($type === 'photo') {
                $hasFile = $request->hasFile($key) || $request->hasFile("fields.{$key}");
                $uploadedFile = $request->file($key) ?: $request->file("fields.{$key}");

                if ($isRequired && ! $hasFile) {
                    $errors[] = "Foto {$label} wajib diunggah.";
                } elseif ($hasFile && $uploadedFile) {
                    if (! $uploadedFile->isValid()) {
                        $errCode = $uploadedFile->getError();
                        $errors[] = FileUploadHelper::getErrorMessageForCode($errCode, 'Foto '.$label);
                    } else {
                        $extension = strtolower($uploadedFile->getClientOriginalExtension());
                        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                        $fileSize = $uploadedFile->getSize(); // in bytes

                        if (! in_array($extension, $allowedExtensions, true) || $fileSize > 2048 * 1024) {
                            $errors[] = "Foto {$label} harus berformat JPG, PNG, atau WebP dengan ukuran maksimal 2 MB.";
                        } else {
                            try {
                                $uploadDir = public_path('uploads/reservations');
                                FileUploadHelper::ensureDirectoryWritable($uploadDir, 'folder upload Reservasi');

                                $safeName = 'res_'.Str::slug($key, '_').'_'.time().'_'.Str::random(6).'.'.$extension;
                                $uploadedFile->move($uploadDir, $safeName);
                                $relativePath = 'uploads/reservations/'.$safeName;
                                $fullUrl = url($relativePath);

                                $formData[] = [
                                    'key' => $key,
                                    'label' => $label,
                                    'type' => 'photo',
                                    'value' => $safeName,
                                    'path' => $relativePath,
                                    'url' => $fullUrl,
                                ];
                            } catch (\Throwable $uploadEx) {
                                $errors[] = "Gagal menyimpan foto {$label}: ".$uploadEx->getMessage();
                            }
                        }
                    }
                } else {
                    $formData[] = [
                        'key' => $key,
                        'label' => $label,
                        'type' => 'photo',
                        'value' => null,
                        'path' => null,
                        'url' => null,
                    ];
                }
            } else {
                // Determine value from request, checking direct key, fields[key], and legacy aliases
                $val = null;
                if ($request->has($key)) {
                    $val = $request->input($key);
                } elseif ($request->has("fields.{$key}")) {
                    $val = $request->input("fields.{$key}");
                } elseif ($key === 'name' && $request->has('fullName')) {
                    $val = $request->input('fullName');
                } elseif ($key === 'guests' && $request->has('guestCount')) {
                    $val = $request->input('guestCount');
                }

                if ($type === 'checkbox') {
                    $selectedValues = is_array($val) ? $val : (filled($val) ? [$val] : []);
                    if ($isRequired && empty($selectedValues)) {
                        $errors[] = "Pilihan {$label} wajib dipilih minimal satu opsi.";
                    }
                    $formData[] = [
                        'key' => $key,
                        'label' => $label,
                        'type' => 'checkbox',
                        'value' => $selectedValues,
                    ];
                } else {
                    $stringVal = is_string($val) ? trim($val) : (is_null($val) ? '' : (string) $val);

                    if ($isRequired && $stringVal === '') {
                        $errors[] = "{$label} wajib diisi.";
                    } elseif ($stringVal !== '') {
                        if ($type === 'number') {
                            $cleanNum = preg_replace('/[^0-9]/', '', $stringVal);
                            if ($cleanNum === '') {
                                $errors[] = "{$label} harus berupa angka yang valid.";
                            }
                        } elseif ($type === 'tel') {
                            $cleanPhone = preg_replace('/[^0-9]/', '', $stringVal);
                            if (strlen($cleanPhone) < 8) {
                                $errors[] = "{$label} tidak valid (minimal 8 digit).";
                            }
                        } elseif ($type === 'date') {
                            try {
                                Carbon::parse($stringVal);
                            } catch (\Throwable $e) {
                                $errors[] = "{$label} harus berupa format tanggal yang valid.";
                            }
                        }
                    }

                    $formData[] = [
                        'key' => $key,
                        'label' => $label,
                        'type' => $type,
                        'value' => $stringVal !== '' ? $stringVal : null,
                    ];
                }
            }
        }

        // Preserve additional legacy event package inputs if submitted
        if ($request->filled('serviceType')) {
            $formData[] = [
                'key' => 'serviceType',
                'label' => 'Kategori Acara',
                'type' => 'text',
                'value' => $request->input('serviceType'),
            ];
        }
        if ($request->filled('eventPackage')) {
            $formData[] = [
                'key' => 'eventPackage',
                'label' => 'Paket Dekorasi',
                'type' => 'text',
                'value' => $request->input('eventPackage'),
            ];
        }
        if ($request->filled('area')) {
            $formData[] = [
                'key' => 'area',
                'label' => 'Pilihan Area',
                'type' => 'text',
                'value' => $request->input('area'),
            ];
        }

        if (! empty($errors)) {
            if ($request->wantsJson() || $request->ajax() || $request->isJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi formulir belum terpenuhi.',
                    'errors' => $errors,
                ], 422);
            }

            return back()->withErrors($errors)->withInput();
        }

        // Map core values for Reservation model
        $customerName = 'Pelanggan';
        $customerPhone = '-';
        $reservationDate = date('Y-m-d');
        $reservationTime = '15:00';
        $guestCount = 2;
        $notesSummary = '';

        foreach ($formData as $item) {
            $itemKey = $item['key'];
            $itemVal = $item['value'];

            if (in_array($itemKey, ['name', 'fullName', 'nama', 'nama_lengkap'], true) && filled($itemVal)) {
                $customerName = (string) $itemVal;
            } elseif (in_array($itemKey, ['phone', 'whatsapp', 'nomor_whatsapp', 'telepon'], true) && filled($itemVal)) {
                $customerPhone = (string) $itemVal;
            } elseif (in_array($itemKey, ['date', 'tanggal', 'tanggal_reservasi', 'tanggal_kedatangan'], true) && filled($itemVal)) {
                $reservationDate = (string) $itemVal;
            } elseif (in_array($itemKey, ['time', 'jam', 'waktu', 'jam_kedatangan'], true) && filled($itemVal)) {
                $reservationTime = (string) $itemVal;
            } elseif (in_array($itemKey, ['guests', 'guestCount', 'jumlah_tamu', 'tamu'], true) && filled($itemVal)) {
                $cleanG = (int) preg_replace('/[^0-9]/', '', (string) $itemVal);
                $guestCount = $cleanG > 0 ? $cleanG : 2;
            } elseif (in_array($itemKey, ['notes', 'catatan', 'catatan_tambahan', 'catatan_khusus'], true) && filled($itemVal)) {
                $notesSummary = (string) $itemVal;
            }
        }

        // Save to database
        try {
            $createdReservation = Reservation::create([
                'name' => $customerName,
                'phone' => $customerPhone,
                'date' => $reservationDate,
                'time' => $reservationTime,
                'guests' => $guestCount,
                'notes' => $notesSummary ?: null,
                'form_data' => $formData,
                'status' => 'pending',
            ]);
        } catch (\Throwable $e) {
            Log::warning('Gagal menyimpan reservasi ke DB: '.$e->getMessage());
        }

        // Retrieve & normalize WhatsApp target number
        $destinationNumber = Setting::get('reservation_whatsapp')
            ?: Setting::get('cafe_whatsapp', config('nucomu.contact.whatsapp_number', '6281234567890'));

        $cleanWa = preg_replace('/[^0-9]/', '', (string) $destinationNumber);
        if (str_starts_with($cleanWa, '08')) {
            $cleanWa = '628'.substr($cleanWa, 2);
        } elseif (str_starts_with($cleanWa, '8')) {
            $cleanWa = '628'.substr($cleanWa, 1);
        }
        if (empty($cleanWa)) {
            $cleanWa = '6281234567890';
        }

        // Format WhatsApp Message
        $waLines = ['*RESERVASI BARU — NUCOMU CAFE*', ''];

        foreach ($formData as $item) {
            $label = $item['label'];
            $val = $item['value'];
            $type = $item['type'];

            if ($type === 'photo') {
                if (! empty($item['url'])) {
                    $waLines[] = "• *{$label} (Link Foto):* {$item['url']}";
                } else {
                    $waLines[] = "• *{$label}:* (Tidak melampirkan foto)";
                }
            } elseif ($type === 'date' && filled($val)) {
                try {
                    $carbonDate = Carbon::parse($val);
                    $formattedDate = $carbonDate->format('d').' '.($months[(int) $carbonDate->format('m')] ?? $carbonDate->format('F')).' '.$carbonDate->format('Y');
                    $waLines[] = "• *{$label}:* {$formattedDate}";
                } catch (\Throwable $e) {
                    $waLines[] = "• *{$label}:* {$val}";
                }
            } elseif ($type === 'time' && filled($val)) {
                $waLines[] = "• *{$label}:* {$val} WIB";
            } elseif ($type === 'checkbox') {
                $checkList = is_array($val) ? implode(', ', $val) : (string) $val;
                $waLines[] = "• *{$label}:* ".($checkList !== '' ? $checkList : '-');
            } else {
                $display = is_array($val) ? implode(', ', $val) : (string) $val;
                $waLines[] = "• *{$label}:* ".($display !== '' ? $display : '-');
            }
        }

        $waLines[] = '';
        $waLines[] = 'Mohon informasi ketersediaan jadwal meja kami. Terima kasih!';

        $fullWaMessage = implode("\n", $waLines);
        $whatsappUrl = 'https://wa.me/'.$cleanWa.'?text='.rawurlencode($fullWaMessage);

        if ($request->wantsJson() || $request->ajax() || $request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil disimpan. Mengarahkan ke WhatsApp...',
                'whatsapp_url' => $whatsappUrl,
                'data' => $formData,
            ]);
        }

        return redirect()->away($whatsappUrl);
    }
}
