<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OperatingHour;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    /**
     * Display contact settings management form with social media preview.
     */
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        $operatingHours = OperatingHour::orderBy('day_of_week')->get();

        $isPlaceholder = fn (?string $val) => empty($val) || str_starts_with(trim($val), '[ISI:');

        $contact = [
            'cafe_address' => ! $isPlaceholder($settings['cafe_address'] ?? null)
                ? (string) $settings['cafe_address']
                : (string) config('nucomu.contact.address', 'Jl. Mayor Sujadi, Plosokandang, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66221'),
            'cafe_whatsapp' => ! $isPlaceholder($settings['cafe_whatsapp'] ?? null)
                ? (string) $settings['cafe_whatsapp']
                : (string) config('nucomu.contact.whatsapp_number', '6281234567890'),
            'zendo_whatsapp' => ! $isPlaceholder($settings['zendo_whatsapp'] ?? null)
                ? (string) $settings['zendo_whatsapp']
                : (string) config('nucomu.contact.zendo_whatsapp', '6285707300240'),
            'grabfood_url' => ! $isPlaceholder($settings['grabfood_url'] ?? null)
                ? (string) $settings['grabfood_url']
                : (string) config('nucomu.contact.grabfood_url', ''),
            'cafe_instagram' => ! $isPlaceholder($settings['cafe_instagram'] ?? null)
                ? (string) $settings['cafe_instagram']
                : (string) config('nucomu.contact.instagram_url', 'https://www.instagram.com/nucomu.cafe'),
            'cafe_tiktok' => ! $isPlaceholder($settings['cafe_tiktok'] ?? null)
                ? (string) $settings['cafe_tiktok']
                : (string) config('nucomu.contact.tiktok_url', 'https://www.tiktok.com/@nucomu.cafe'),
            'cafe_email' => ! $isPlaceholder($settings['cafe_email'] ?? null)
                ? (string) $settings['cafe_email']
                : '',
            'cafe_maps_url' => ! $isPlaceholder($settings['cafe_maps_url'] ?? null)
                ? (string) $settings['cafe_maps_url']
                : (string) config('nucomu.contact.google_maps_url', 'https://maps.google.com/?q=Nucomu+Cafe+Tulungagung'),
            'cafe_maps_embed' => ! $isPlaceholder($settings['cafe_maps_embed'] ?? null)
                ? (string) $settings['cafe_maps_embed']
                : '',
            'cafe_opening_hours' => ! $isPlaceholder($settings['cafe_opening_hours'] ?? null)
                ? (string) $settings['cafe_opening_hours']
                : (string) config('nucomu.contact.hours', 'Senin – Sabtu: 11:00 – 21:00 WIB | Minggu: 11:00 – 22:00 WIB'),
        ];

        return view('admin.contact.index', compact('contact', 'operatingHours'));
    }

    /**
     * Update contact settings and social links.
     */
    public function update(Request $request)
    {
        // Auto-prefix https:// or proper domain for social links if user enters username or URL without protocol
        foreach (['cafe_maps_url', 'cafe_instagram', 'cafe_tiktok', 'grabfood_url'] as $urlKey) {
            $val = trim((string) $request->input($urlKey, ''));
            if ($val !== '' && ! preg_match('/^https?:\/\//i', $val)) {
                if ($urlKey === 'cafe_instagram') {
                    $cleanUser = ltrim(str_replace(['instagram.com/', 'www.instagram.com/'], '', $val), '@/');
                    $val = 'https://www.instagram.com/'.$cleanUser;
                } elseif ($urlKey === 'cafe_tiktok') {
                    $cleanUser = ltrim(str_replace(['tiktok.com/', 'www.tiktok.com/'], '', $val), '/');
                    $cleanUser = str_starts_with($cleanUser, '@') ? $cleanUser : '@'.$cleanUser;
                    $val = 'https://www.tiktok.com/'.$cleanUser;
                } else {
                    $val = 'https://'.ltrim($val, '/');
                }
                $request->merge([$urlKey => $val]);
            }
        }

        $request->validate([
            'cafe_whatsapp' => 'required|string|max:30',
            'zendo_whatsapp' => 'nullable|string|max:30',
            'cafe_maps_url' => 'nullable|url|max:1000',
            'cafe_maps_embed' => 'nullable|string|max:2000',
            'cafe_instagram' => 'nullable|url|max:500',
            'cafe_tiktok' => 'nullable|url|max:500',
            'grabfood_url' => 'nullable|url|max:500',
            'cafe_email' => 'nullable|email|max:150',
            'cafe_address' => 'nullable|string|max:500',
            'cafe_opening_hours' => 'nullable|string|max:500',
        ], [
            'cafe_whatsapp.required' => 'Nomor WhatsApp cafe wajib diisi.',
            'cafe_maps_url.url' => 'Format link Google Maps harus berupa URL valid (diawali https:// atau http://).',
            'cafe_instagram.url' => 'Format link Instagram harus berupa URL valid (contoh: https://www.instagram.com/nucomu.cafe).',
            'cafe_tiktok.url' => 'Format link TikTok harus berupa URL valid (contoh: https://www.tiktok.com/@nucomu.cafe).',
            'grabfood_url.url' => 'Format link GrabFood harus berupa URL valid (diawali https:// atau http://).',
            'cafe_email.email' => 'Format email cafe tidak valid (contoh: halo@nucomucafe.com).',
        ]);

        // Normalize and validate cafe WhatsApp (Required)
        $cafeWa = self::normalizeAndValidatePhone($request->input('cafe_whatsapp'), 'cafe_whatsapp', 'Nomor WhatsApp cafe');
        Setting::set('cafe_whatsapp', $cafeWa);

        // Normalize and validate Zendo WhatsApp (Optional, but if filled must be valid)
        if ($request->filled('zendo_whatsapp')) {
            $zendoWa = self::normalizeAndValidatePhone($request->input('zendo_whatsapp'), 'zendo_whatsapp', 'Nomor WhatsApp Kurir Zendo');
            Setting::set('zendo_whatsapp', $zendoWa);
            // Synchronize with courier 1 phone for consistency with Modul 8
            Setting::set('zendo_courier_1_phone', $zendoWa);
        } else {
            Setting::set('zendo_whatsapp', '');
        }

        // Save URL links (trim and sanitize)
        Setting::set('cafe_maps_url', trim((string) $request->input('cafe_maps_url', '')));
        Setting::set('cafe_instagram', trim((string) $request->input('cafe_instagram', '')));
        Setting::set('cafe_tiktok', trim((string) $request->input('cafe_tiktok', '')));
        Setting::set('grabfood_url', trim((string) $request->input('grabfood_url', '')));
        Setting::set('cafe_email', trim((string) $request->input('cafe_email', '')));
        Setting::set('cafe_address', trim((string) $request->input('cafe_address', '')));
        Setting::set('cafe_opening_hours', trim((string) $request->input('cafe_opening_hours', '')));

        // Maps embed: if user pasted full <iframe>, extract src URL or store clean
        $embed = trim((string) $request->input('cafe_maps_embed', ''));
        if (preg_match('/src="([^"]+)"/i', $embed, $matches)) {
            $embed = $matches[1];
        }
        Setting::set('cafe_maps_embed', $embed);

        return back()->with('success', 'Informasi kontak, tautan media sosial, dan lokasi cafe berhasil diperbarui.');
    }

    /**
     * Normalize Indonesian phone number to 628xxx format or throw validation exception.
     */
    public static function normalizeAndValidatePhone(?string $phone, string $inputKey, string $label): string
    {
        $clean = trim((string) $phone);
        $digits = preg_replace('/[^0-9]/', '', $clean);

        if (str_starts_with($digits, '08')) {
            $digits = '628'.substr($digits, 2);
        } elseif (str_starts_with($digits, '8')) {
            $digits = '628'.substr($digits, 1);
        } elseif (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        if (! preg_match('/^628[0-9]{7,13}$/', $digits)) {
            throw ValidationException::withMessages([
                $inputKey => "Format {$label} tidak valid. Gunakan format 08xxx atau 628xxx (minimal 10 digit angka).",
            ]);
        }

        return $digits;
    }
}
