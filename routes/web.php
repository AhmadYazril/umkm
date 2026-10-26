<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\CartController;
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
Route::get('/tentang-kami', [PageController::class, 'about'])->name('about');
Route::get('/galeri', [PageController::class, 'gallery'])->name('gallery');
Route::get('/fasilitas', [PageController::class, 'facilities'])->name('facilities');
Route::get('/reservasi', [PageController::class, 'reservation'])->name('reservation');
Route::get('/kontak', [PageController::class, 'contact'])->name('contact');
Route::get('/event', [PageController::class, 'events'])->name('events');

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
//  ADMIN ROUTES
// ─────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware('admin')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Menu
        Route::get('/menu', [AdminMenuController::class, 'index'])->name('menus.index');
        Route::get('/menu/tambah', [AdminMenuController::class, 'create'])->name('menus.create');
        Route::post('/menu', [AdminMenuController::class, 'store'])->name('menus.store');
        Route::get('/menu/{menu}/edit', [AdminMenuController::class, 'edit'])->name('menus.edit');
        Route::put('/menu/{menu}', [AdminMenuController::class, 'update'])->name('menus.update');
        Route::delete('/menu/{menu}', [AdminMenuController::class, 'destroy'])->name('menus.destroy');
        Route::post('/menu/{menu}/toggle-available', [AdminMenuController::class, 'toggleAvailable'])->name('menus.toggleAvailable');
        Route::post('/menu/{menu}/toggle-featured', [AdminMenuController::class, 'toggleFeatured'])->name('menus.toggleFeatured');
        Route::post('/menu/hapus-data-contoh', [AdminMenuController::class, 'deleteSampleData'])->name('menus.deleteSample');
        Route::post('/menu/{id}/restore', [AdminMenuController::class, 'restore'])->name('menus.restore');

        // Pesanan
        Route::get('/pesanan', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/pesanan/scan', [AdminOrderController::class, 'scanPage'])->name('orders.scan');
        Route::get('/pesanan/scan/cari', [AdminOrderController::class, 'scanPage'])->name('orders.scan.search');
        Route::get('/pesanan/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/pesanan/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('/pesanan/{order}/struk', [AdminOrderController::class, 'printReceipt'])->name('orders.receipt');

        // Reservasi
        Route::get('/reservasi', [SettingController::class, 'reservations'])->name('reservations.index');
        Route::post('/reservasi/{reservation}/status', [SettingController::class, 'updateReservation'])->name('reservations.updateStatus');

        // Galeri
        Route::get('/galeri', [SettingController::class, 'gallery'])->name('gallery.index');
        Route::post('/galeri', [SettingController::class, 'storeGallery'])->name('gallery.store');
        Route::delete('/galeri/{galleryItem}', [SettingController::class, 'destroyGallery'])->name('gallery.destroy');

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

        // Pengaturan
        Route::get('/pengaturan', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/pengaturan', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/pengaturan/test-wa', [SettingController::class, 'testZendoWhatsapp'])->name('settings.testWa');
        Route::post('/pengaturan/jam-operasional', [SettingController::class, 'updateHours'])->name('settings.hours');
    });
});
