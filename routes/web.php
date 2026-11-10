<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HelpController as AdminHelpController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NucomuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ZendoTrackingController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────
//  PUBLIC ROUTES — NUCOMU CAFE (MATCHING NYOBA)
// ─────────────────────────────────────────────
Route::get('/', [NucomuController::class, 'index'])->name('home');
Route::get('/menu', [NucomuController::class, 'allMenu'])->name('menu.all');
Route::get('/daftar-menu', [NucomuController::class, 'allMenu'])->name('menu');
Route::post('/reservasi', [NucomuController::class, 'submitReservation'])->name('reservation.submit');
Route::post('/reservasi/store', [NucomuController::class, 'submitReservation'])->name('reservation.store');

// Menu item detail
Route::get('/menu/{menu:slug}', [MenuController::class, 'show'])->name('menu.show');

// Keranjang
Route::get('/keranjang', [CartController::class, 'index'])->name('cart');
Route::post('/keranjang/tambah/{menu:slug}', [CartController::class, 'add'])->name('cart.add');
Route::post('/keranjang/hapus', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/keranjang/kosongkan', [CartController::class, 'clear'])->name('cart.clear');

// Checkout & Pesanan
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [OrderController::class, 'store'])->name('order.store');
Route::get('/pesanan/konfirmasi/{code}', [OrderController::class, 'confirmation'])->name('order.confirmation');
Route::get('/lacak-pesanan', [OrderController::class, 'track'])->name('order.track');

// Halaman Statis
Route::get('/galeri', [PageController::class, 'gallery'])->name('gallery');
Route::get('/fasilitas', [PageController::class, 'facilities'])->name('facilities');
Route::get('/reservasi', [PageController::class, 'reservation'])->name('reservation');
Route::get('/kontak', [PageController::class, 'contact'])->name('contact');
Route::get('/event', [PageController::class, 'events'])->name('events');
Route::get('/layanan', [PageController::class, 'services'])->name('services');

// ─────────────────────────────────────────────
//  NUCOMU STORY & ORDER ROUTES
// ─────────────────────────────────────────────
Route::get('/nucomu', [NucomuController::class, 'nucomuPage'])->name('nucomu.index');
Route::post('/nucomu/ulasan', [NucomuController::class, 'storeReview'])->name('nucomu.review.store');

// Nucomu Order (kasir-less ordering)
Route::get('/pesan', [NucomuController::class, 'orderPage'])->name('nucomu.order');
Route::post('/pesan', [NucomuController::class, 'storeOrder'])->name('nucomu.order.store');
Route::get('/pesan/slip/{code}', [NucomuController::class, 'orderSlip'])->name('nucomu.order.slip');

// ─────────────────────────────────────────────
//  ZENDO LIVE TRACKING & COURIER GPS WORKSPACE (GRAB-LIKE)
// ─────────────────────────────────────────────
// 1 Unified live tracking route for customer, admin, and auto-detected courier
Route::get('/zendo/lacak/{token}', [ZendoTrackingController::class, 'track'])->name('zendo.track');
Route::get('/zendo/lacak/{token}/data', [ZendoTrackingController::class, 'trackData'])->name('zendo.track.data');
Route::post('/zendo/lacak/{token}/pin', [ZendoTrackingController::class, 'verifyPin'])->name('zendo.track.pin');
Route::post('/zendo/lacak/{token}/lokasi', [ZendoTrackingController::class, 'updateLocation'])->name('zendo.track.location');
Route::post('/zendo/lacak/{token}/status', [ZendoTrackingController::class, 'updateStatus'])->name('zendo.track.status');

// Courier workspace routes (backward-compatible aliases)
Route::get('/zendo/kurir/{token}', [ZendoTrackingController::class, 'courier'])->name('zendo.courier.show');
Route::post('/zendo/kurir/{token}/otp', [ZendoTrackingController::class, 'sendOtp'])->name('zendo.courier.otp');
Route::post('/zendo/kurir/{token}/verifikasi', [ZendoTrackingController::class, 'verify'])->name('zendo.courier.verify');
Route::post('/zendo/kurir/{token}/lokasi', [ZendoTrackingController::class, 'updateLocation'])->name('zendo.courier.location');
Route::post('/zendo/kurir/{token}/status', [ZendoTrackingController::class, 'updateStatus'])->name('zendo.courier.status');
Route::post('/zendo/kurir/keluar', [ZendoTrackingController::class, 'unbindCourier'])->name('zendo.courier.unbind');

// QR Detail (publicly accessible — scanned by kasir via phone)
Route::get('/qr/pesanan/{code}', [AdminOrderController::class, 'qrDetail'])->name('admin.orders.qr');

// ─────────────────────────────────────────────
//  KASIR WORKSPACE ROUTES (/kasir)
// ─────────────────────────────────────────────
Route::prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/login', [KasirController::class, 'showLogin'])->name('login');
    Route::post('/login', [KasirController::class, 'login'])->name('login.post');
    Route::post('/logout', [KasirController::class, 'logout'])->name('logout');

    Route::middleware('kasir')->group(function () {
        Route::get('/', [KasirController::class, 'index'])->name('index');
        Route::get('/sinkron', [KasirController::class, 'syncData'])->name('sync');
        Route::post('/stok', [KasirController::class, 'updateStock'])->name('stock.update');
        Route::post('/pesanan', [KasirController::class, 'storeOrder'])->name('orders.store');
        Route::get('/pesanan/cari', [KasirController::class, 'lookupOrder'])->name('orders.lookup');
        Route::post('/pesanan/{order}/status', [KasirController::class, 'updateOrderStatus'])->name('orders.status');
        Route::get('/pesanan/{order}/struk', [KasirController::class, 'receipt'])->name('orders.receipt');
    });
});     

// ─────────────────────────────────────────────
//  ADMIN ROUTES
// ─────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware('admin')->group(function () {

        Route::get('/', fn () => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // 1. Hero Section / Banner
        Route::get('/banner', [BannerController::class, 'index'])->name('banners.index');
        Route::get('/banner/tambah', [BannerController::class, 'create'])->name('banners.create');
        Route::post('/banner', [BannerController::class, 'store'])->name('banners.store');
        Route::get('/banner/{banner}/edit', [BannerController::class, 'edit'])->name('banners.edit');
        Route::put('/banner/{banner}', [BannerController::class, 'update'])->name('banners.update');
        Route::delete('/banner/{banner}', [BannerController::class, 'destroy'])->name('banners.destroy');
        Route::post('/banner/{banner}/perpanjang', [BannerController::class, 'extendDuration'])->name('banners.extend');
        Route::post('/banner/{banner}/toggle', [BannerController::class, 'toggleActive'])->name('banners.toggle');
        Route::get('/banner/expiring-check', [BannerController::class, 'checkExpiring'])->name('banners.checkExpiring');
        Route::get('/preview-desain', [BannerController::class, 'previewDesign'])->name('preview.design');
        Route::get('/preview-judul', [BannerController::class, 'previewDesign'])->name('preview.judul');

        // 2. About / Tentang Cafe
        Route::get('/about', [AboutController::class, 'index'])->name('about.index');
        Route::post('/about', [AboutController::class, 'update'])->name('about.update');

        // 3. Fasilitas
        Route::get('/fasilitas', [FacilityController::class, 'index'])->name('facilities.index');
        Route::get('/fasilitas/tambah', [FacilityController::class, 'create'])->name('facilities.create');
        Route::post('/fasilitas', [FacilityController::class, 'store'])->name('facilities.store');
        Route::post('/fasilitas/reorder', [FacilityController::class, 'reorder'])->name('facilities.reorder');
        Route::get('/fasilitas/{facility}/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
        Route::put('/fasilitas/{facility}', [FacilityController::class, 'update'])->name('facilities.update');
        Route::delete('/fasilitas/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
        Route::post('/fasilitas/{facility}/toggle', [FacilityController::class, 'toggleActive'])->name('facilities.toggle');

        // 9. Contact
        Route::get('/contact', [AdminContactController::class, 'index'])->name('contact.index');
        Route::post('/contact', [AdminContactController::class, 'update'])->name('contact.update');
        Route::get('/kontak', [AdminContactController::class, 'index']);
        Route::post('/kontak', [AdminContactController::class, 'update']);

        // 4. Kategori & Menu
        Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/kategori', [CategoryController::class, 'store'])->name('categories.store');
        Route::post('/kategori/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
        Route::put('/kategori/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/kategori/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('/kategori/{category}/toggle', [CategoryController::class, 'toggleActive'])->name('categories.toggle');

        Route::get('/menu', [AdminMenuController::class, 'index'])->name('menus.index');
        Route::get('/menu/tambah', [AdminMenuController::class, 'create'])->name('menus.create');
        Route::post('/menu', [AdminMenuController::class, 'store'])->name('menus.store');
        Route::get('/menu/{menu}/edit', [AdminMenuController::class, 'edit'])->name('menus.edit');
        Route::put('/menu/{menu}', [AdminMenuController::class, 'update'])->name('menus.update');
        Route::delete('/menu/{menu}', [AdminMenuController::class, 'destroy'])->name('menus.destroy');
        Route::post('/menu/{menu}/toggle-available', [AdminMenuController::class, 'toggleAvailable'])->name('menus.toggleAvailable');
        Route::post('/menu/{menu}/stock', [AdminMenuController::class, 'updateStock'])->name('menus.updateStock');
        Route::post('/menu/{menu}/toggle-featured', [AdminMenuController::class, 'toggleFeatured'])->name('menus.toggleFeatured');
        Route::post('/menu/{menu}/toggle-best-seller', [AdminMenuController::class, 'toggleBestSeller'])->name('menus.toggleBestSeller');
        Route::post('/menu/{id}/restore', [AdminMenuController::class, 'restore'])->name('menus.restore');

        // Pesanan
        Route::get('/pesanan', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/pesanan/rekap', [AdminOrderController::class, 'exportRecap'])->name('orders.export');
        Route::get('/pesanan/rekap/export', [AdminOrderController::class, 'exportRecap'])->name('orders.recap');
        Route::post('/pesanan/settings', [AdminOrderController::class, 'updateSettings'])->name('orders.updateSettings');
        Route::post('/pesanan/settings/reset-template', [AdminOrderController::class, 'resetTemplate'])->name('orders.resetTemplate');
        Route::get('/pesanan/scan', [AdminOrderController::class, 'scanPage'])->name('orders.scan');
        Route::get('/pesanan/scan/cari', [AdminOrderController::class, 'scanPage'])->name('orders.scan.search');
        Route::get('/pesanan/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/pesanan/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('/pesanan/{order}/struk', [AdminOrderController::class, 'printReceipt'])->name('orders.receipt');

        // 7. Reservasi & Form Builder
        Route::prefix('reservasi')->name('reservations.')->group(function () {
            Route::get('/', [AdminReservationController::class, 'index'])->name('index');
            Route::post('/{reservation}/status', [AdminReservationController::class, 'updateStatus'])->name('updateStatus');
            Route::delete('/{reservation}', [AdminReservationController::class, 'destroy'])->name('destroy');
            Route::post('/whatsapp', [AdminReservationController::class, 'updateWhatsapp'])->name('whatsapp.update');
            Route::post('/whatsapp/update', [AdminReservationController::class, 'updateWhatsapp'])->name('updateWhatsapp');

            // Form Builder Routes
            Route::post('/fields', [AdminReservationController::class, 'storeField'])->name('fields.store');
            Route::put('/fields/{field}', [AdminReservationController::class, 'updateField'])->name('fields.update');
            Route::delete('/fields/{field}', [AdminReservationController::class, 'destroyField'])->name('fields.destroy');
            Route::match(['post', 'patch'], '/fields/{field}/toggle', [AdminReservationController::class, 'toggleField'])->name('fields.toggle');
        });

        // Galeri
        Route::prefix('galeri')->name('gallery.')->group(function () {
            Route::get('/', [GalleryController::class, 'index'])->name('index');
            Route::post('/', [GalleryController::class, 'store'])->name('store');
            Route::get('/{galleryItem}/edit', [GalleryController::class, 'edit'])->name('edit');
            Route::put('/{galleryItem}', [GalleryController::class, 'update'])->name('update');
            Route::delete('/{galleryItem}', [GalleryController::class, 'destroy'])->name('destroy');
            Route::patch('/{galleryItem}/toggle-active', [GalleryController::class, 'toggleActive'])->name('toggle-active');
        });

        // Testimoni / Ulasan
        Route::get('/testimoni', [SettingController::class, 'testimonials'])->name('testimonials.index');
        Route::post('/testimoni', [SettingController::class, 'storeTestimonial'])->name('testimonials.store');
        Route::delete('/testimoni/{testimonial}', [SettingController::class, 'destroyTestimonial'])->name('testimonials.destroy');
        Route::post('/testimoni/hapus-data-contoh', [SettingController::class, 'deleteSampleTestimonials'])->name('testimonials.deleteSample');
        Route::post('/testimoni/{testimonial}/toggle', [SettingController::class, 'toggleTestimonial'])->name('testimonials.toggle');

        // Event
        Route::get('/event', [SettingController::class, 'events'])->name('events.index');
        Route::post('/event', [SettingController::class, 'storeEvent'])->name('events.store');
        Route::delete('/event/{event}', [SettingController::class, 'destroyEvent'])->name('events.destroy');

        // Kurir & Pemantauan Zendo (Terpisah dari Pengaturan Sistem)
        Route::get('/zendo', [SettingController::class, 'zendoIndex'])->name('zendo.index');
        Route::post('/zendo/couriers', [SettingController::class, 'updateZendoCouriers'])->name('zendo.couriers.update');
        Route::post('/zendo/couriers/{index}/status', [SettingController::class, 'updateZendoCourierStatus'])->name('zendo.couriers.status');
        Route::post('/zendo/orders/{order}/stage', [SettingController::class, 'updateZendoOrderStage'])->name('zendo.orders.stage');
        Route::post('/zendo/tariff', [SettingController::class, 'updateZendoTariff'])->name('zendo.tariff.update');
        Route::post('/zendo/devices/{id}/reset', [SettingController::class, 'resetZendoDevice'])->name('zendo.devices.reset');
        Route::post('/zendo/devices/reset-all', [SettingController::class, 'resetAllZendoDevices'])->name('zendo.devices.resetAll');

        // 10. Modul 10: Profile, Notifikasi, Bantuan, Setting
        // Profile
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/remove-avatar', [AdminProfileController::class, 'removeAvatar'])->name('profile.removeAvatar');

        // Notifikasi
        Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifikasi', [AdminNotificationController::class, 'index']);
        Route::get('/notifications/poll', [AdminNotificationController::class, 'poll'])->name('notifications.poll');
        Route::post('/notifications/toggle-read', [AdminNotificationController::class, 'toggleRead'])->name('notifications.toggleRead');
        Route::post('/notifications/mark-all-read', [AdminNotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

        // Bantuan
        Route::get('/help', [AdminHelpController::class, 'index'])->name('help.index');
        Route::get('/bantuan', [AdminHelpController::class, 'index']);

        // Pengaturan & Setting Umum
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('/pengaturan', [SettingController::class, 'index']);
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/pengaturan', [SettingController::class, 'update']);
        Route::post('/pengaturan/dashboard-chart', [SettingController::class, 'toggleDashboardChart'])->name('settings.toggleChart');
        Route::post('/pengaturan/test-wa', [SettingController::class, 'testZendoWhatsapp'])->name('settings.testWa');
        Route::post('/pengaturan/jam-operasional', [SettingController::class, 'updateHours'])->name('settings.hours');
    });
});
