<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\Setting;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    /**
     * Display the notification center with aggregated cafe events.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $readKeys = self::getReadKeys();

        $notifications = self::gatherAllNotifications();

        // Count totals
        $counts = [
            'all' => count($notifications),
            'unread' => count(array_filter($notifications, fn ($n) => ! $n['is_read'])),
            'banner' => count(array_filter($notifications, fn ($n) => $n['type'] === 'banner')),
            'reservation' => count(array_filter($notifications, fn ($n) => $n['type'] === 'reservation')),
            'order' => count(array_filter($notifications, fn ($n) => $n['type'] === 'order')),
            'review' => count(array_filter($notifications, fn ($n) => $n['type'] === 'review')),
        ];

        // Apply filter tab
        if ($filter === 'unread') {
            $notifications = array_values(array_filter($notifications, fn ($n) => ! $n['is_read']));
        } elseif (in_array($filter, ['banner', 'reservation', 'order', 'review'])) {
            $notifications = array_values(array_filter($notifications, fn ($n) => $n['type'] === $filter));
        }

        return view('admin.notifications.index', compact('notifications', 'counts', 'filter'));
    }

    /**
     * Toggle read/unread status for a specific notification key.
     */
    public function toggleRead(Request $request)
    {
        $key = trim((string) $request->input('key'));
        if (! $key) {
            return back()->with('error', 'Kunci notifikasi tidak valid.');
        }

        $readKeys = self::getReadKeys();

        if (in_array($key, $readKeys)) {
            // Remove from read keys (mark unread)
            $readKeys = array_values(array_diff($readKeys, [$key]));
            $msg = 'Notifikasi ditandai belum dibaca.';
        } else {
            // Add to read keys
            $readKeys[] = $key;
            $msg = 'Notifikasi berhasil ditandai sudah dibaca.';
        }

        self::saveReadKeys($readKeys);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'is_read' => in_array($key, $readKeys),
                'unread_count' => self::getUnreadCount(),
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Mark all current notifications as read.
     */
    public function markAllRead(Request $request)
    {
        $all = self::gatherAllNotifications();
        $keys = array_column($all, 'key');

        $readKeys = array_values(array_unique(array_merge(self::getReadKeys(), $keys)));
        self::saveReadKeys($readKeys);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi berhasil ditandai sudah dibaca.',
                'unread_count' => 0,
            ]);
        }

        return back()->with('success', 'Semua notifikasi berhasil ditandai sudah dibaca.');
    }

    /**
     * Polling endpoint for real-time unread badges without heavy payload.
     */
    public function poll(Request $request)
    {
        $all = self::gatherAllNotifications();
        $messages = array_values(array_filter($all, fn ($n) => in_array($n['type'], ['reservation', 'order', 'review'])));
        $system = array_values(array_filter($all, fn ($n) => $n['type'] === 'banner'));

        $unreadMessages = count(array_filter($messages, fn ($n) => ! $n['is_read']));
        $unreadSystem = count(array_filter($system, fn ($n) => ! $n['is_read']));
        $unreadTotal = $unreadMessages + $unreadSystem;

        return response()->json([
            'unread_total' => $unreadTotal,
            'unread_messages' => $unreadMessages,
            'unread_system' => $unreadSystem,
        ]);
    }

    /**
     * Gather all active notifications from existing models without modifying old data.
     */
    public static function gatherAllNotifications(): array
    {
        $notifications = [];
        $readKeys = self::getReadKeys();
        $now = Carbon::now();

        // 1. Expiring / Expired Banners
        try {
            $banners = Banner::all();
            foreach ($banners as $b) {
                if (! $b->end_time) {
                    continue;
                }
                $endTime = Carbon::parse($b->end_time);

                // Expiring within 30 minutes
                if ($endTime->isFuture() && $now->diffInMinutes($endTime, false) <= 30) {
                    $key = 'banner_exp_'.$b->id;
                    $mins = max(1, (int) $now->diffInMinutes($endTime));
                    $notifications[] = [
                        'key' => $key,
                        'type' => 'banner',
                        'type_label' => 'Banner Promo',
                        'title' => "Banner Segera Habis: {$b->title}",
                        'message' => "Tersisa {$mins} menit sebelum otomatis tidak tayang di beranda website.",
                        'time' => $endTime,
                        'time_ago' => "Tersisa {$mins} mnt",
                        'status_color' => 'amber',
                        'action_url' => route('admin.banners.index'),
                        'action_label' => 'Perpanjang Banner',
                        'is_read' => in_array($key, $readKeys),
                    ];
                } elseif ($endTime->isPast() && $now->diffInHours($endTime) <= 48) {
                    // Expired recently (within 48 hours)
                    $key = 'banner_end_'.$b->id;
                    $notifications[] = [
                        'key' => $key,
                        'type' => 'banner',
                        'type_label' => 'Banner Kedaluwarsa',
                        'title' => "Banner Telah Berakhir: {$b->title}",
                        'message' => "Durasi tayang telah habis pada {$endTime->isoFormat('D MMM, HH:mm')} WIB.",
                        'time' => $endTime,
                        'time_ago' => $endTime->diffForHumans(),
                        'status_color' => 'rose',
                        'action_url' => route('admin.banners.index'),
                        'action_label' => 'Lihat Banner',
                        'is_read' => in_array($key, $readKeys),
                    ];
                }
            }
        } catch (\Throwable $e) {
        }

        // 2. Pending Reservations
        try {
            $reservations = Reservation::where('status', 'pending')->latest()->take(20)->get();
            foreach ($reservations as $r) {
                $key = 'res_'.$r->id;
                $notifications[] = [
                    'key' => $key,
                    'type' => 'reservation',
                    'type_label' => 'Reservasi Meja',
                    'title' => "Reservasi Baru: {$r->name} ({$r->guests} Tamu)",
                    'message' => 'Jadwal: '.($r->date ? Carbon::parse($r->date)->isoFormat('D MMM Y') : '-').' pukul '.($r->time ?: '-').' WIB. Menunggu konfirmasi.',
                    'time' => $r->created_at ?: $now,
                    'time_ago' => $r->created_at ? $r->created_at->diffForHumans() : 'Baru saja',
                    'status_color' => 'emerald',
                    'action_url' => route('admin.reservations.index'),
                    'action_label' => 'Konfirmasi Reservasi',
                    'is_read' => in_array($key, $readKeys),
                ];
            }
        } catch (\Throwable $e) {
        }

        // 3. New Orders (Pending Status or Unpaid)
        try {
            $orders = Order::where(function ($q) {
                $q->where('status', 'pending')
                    ->orWhere('payment_status', 'unpaid');
            })->latest()->take(20)->get();

            foreach ($orders as $o) {
                $key = 'order_'.$o->id;
                $notifications[] = [
                    'key' => $key,
                    'type' => 'order',
                    'type_label' => 'Pesanan Masuk',
                    'title' => "Pesanan Baru #{$o->code}",
                    'message' => 'Total Rp '.number_format($o->total, 0, ',', '.')." ({$o->customer_name}) • ".($o->order_type === 'zendo_delivery' ? 'Antar Kurir Zendo' : 'Makan di Tempat').'.',
                    'time' => $o->created_at ?: $now,
                    'time_ago' => $o->created_at ? $o->created_at->diffForHumans() : 'Baru saja',
                    'status_color' => 'blue',
                    'action_url' => route('admin.orders.index'),
                    'action_label' => 'Buka Pesanan',
                    'is_read' => in_array($key, $readKeys),
                ];
            }
        } catch (\Throwable $e) {
        }

        // 4. New Reviews / Testimonials awaiting Pin Curation
        try {
            $reviews = Testimonial::where('is_pinned', false)->latest()->take(20)->get();
            foreach ($reviews as $t) {
                $key = 'testi_'.$t->id;
                $ratingStars = str_repeat('★', (int) $t->rating);
                $notifications[] = [
                    'key' => $key,
                    'type' => 'review',
                    'type_label' => 'Ulasan Masuk',
                    'title' => "Ulasan Baru ({$ratingStars}) dari {$t->name}",
                    'message' => '"'.Str::limit($t->content, 90).'" — Belum di-pin ke website.',
                    'time' => $t->created_at ?: $now,
                    'time_ago' => $t->created_at ? $t->created_at->diffForHumans() : 'Baru saja',
                    'status_color' => 'purple',
                    'action_url' => route('admin.testimonials.index'),
                    'action_label' => 'Kurasi & Pin',
                    'is_read' => in_array($key, $readKeys),
                ];
            }
        } catch (\Throwable $e) {
        }

        // Sort descending by time
        usort($notifications, function ($a, $b) {
            $timeA = $a['time'] instanceof Carbon ? $a['time']->timestamp : strtotime((string) $a['time']);
            $timeB = $b['time'] instanceof Carbon ? $b['time']->timestamp : strtotime((string) $b['time']);

            return $timeB <=> $timeA;
        });

        return $notifications;
    }

    /**
     * Get unread notifications count for sidebar badge.
     */
    public static function getUnreadCount(): int
    {
        $all = self::gatherAllNotifications();
        $unread = array_filter($all, fn ($n) => ! $n['is_read']);

        return count($unread);
    }

    public static function getReadKeys(): array
    {
        try {
            $raw = Setting::get('admin_read_notification_keys', '[]');
            $decoded = json_decode((string) $raw, true);

            return is_array($decoded) ? $decoded : [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    public static function saveReadKeys(array $keys): void
    {
        try {
            Setting::set('admin_read_notification_keys', json_encode(array_values(array_unique($keys))));
        } catch (\Throwable $e) {
        }
    }
}
