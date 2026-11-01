@php
    try {
        $recentPopupReservations = \App\Models\Reservation::latest()->take(5)->get();
        $recentPopupOrders = \App\Models\Order::latest()->take(5)->get();
        $recentPopupTestimonials = \App\Models\Testimonial::latest()->take(4)->get();
        $pendingReservationCount = \App\Models\Reservation::where('status', 'pending')->count();
        $pendingOrderCount = \App\Models\Order::where('status', 'pending')->count();
        $pendingNotificationCount = $pendingReservationCount + $pendingOrderCount;
    } catch (\Throwable $e) {
        $recentPopupReservations = collect();
        $recentPopupOrders = collect();
        $recentPopupTestimonials = collect();
        $pendingReservationCount = 0;
        $pendingOrderCount = 0;
        $pendingNotificationCount = 0;
    }
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel — Nucomu Cafe Tulungagung')</title>
    
    <!-- Google Fonts: Cormorant Garamond, Plus Jakarta Sans, Manrope, JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=JetBrains+Mono:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS with Nucomu Warm Monochrome Palette -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ink-black': '#191919',
                        'ink-dark': '#1C1C1C',
                        'charcoal': '#30302D',
                        'charcoal-light': '#4A4A46',
                        'warm-ivory': '#FAF8F5',
                        'warm-stone': '#F5F2EC',
                        'soft-beige': '#EFECE6',
                        'pure-white': '#FFFFFF',
                        'hairline': '#E5E5E5',
                        'hairline-subtle': '#ECEBE8',
                        'medium-gray': '#8A8A86',
                        'muted-text': '#666666',
                        'accent-gold': '#C2A378',
                        'accent-terracotta': '#E06D24',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Manrope', 'sans-serif'],
                        editorial: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                        body: ['Manrope', 'sans-serif'],
                    },
                    boxShadow: {
                        'xs': '0 1px 2px 0 rgba(0, 0, 0, 0.04)',
                        'card': '0 2px 10px -2px rgba(25, 25, 25, 0.04), 0 1px 4px -1px rgba(25, 25, 25, 0.02)',
                        'elevated': '0 12px 30px -8px rgba(25, 25, 25, 0.08), 0 4px 12px -2px rgba(25, 25, 25, 0.03)',
                        'dropdown': '0 16px 36px -6px rgba(25, 25, 25, 0.12)',
                    }
                }
            }
        }
    </script>

    <style>
        /* Nucomu Editorial Monochrome Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #FAF8F5; }
        ::-webkit-scrollbar-thumb { background: #D9D6CE; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #B3AFA6; }

        /* =========================================================================
           SCROLL REVEAL (Persis seperti di Web Customer Nucomu)
           Elemen mulai kosong (transparan + sedikit turun), lalu meluncur naik saat discroll
           ========================================================================= */
        .reveal-on-scroll,
        .reveal-fade-up {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1), transform 0.75s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-on-scroll.is-visible,
        .reveal-fade-up.is-visible {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        .reveal-from-left {
            opacity: 0;
            transform: translateX(-30px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-from-left.is-visible {
            opacity: 1 !important;
            transform: translateX(0) !important;
        }

        .reveal-from-right {
            opacity: 0;
            transform: translateX(30px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }

        .reveal-from-right.is-visible {
            opacity: 1 !important;
            transform: translateX(0) !important;
        }

        .reveal-delay-1 { transition-delay: 0.08s; }
        .reveal-delay-2 { transition-delay: 0.16s; }
        .reveal-delay-3 { transition-delay: 0.24s; }
        .reveal-delay-4 { transition-delay: 0.32s; }

        /* Smooth Editorial Entrance Animations */
        @keyframes revealFadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .anim-fade-up {
            animation: revealFadeUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .anim-delay-1 { animation-delay: 0.06s; }
        .anim-delay-2 { animation-delay: 0.12s; }
        .anim-delay-3 { animation-delay: 0.18s; }
        .anim-delay-4 { animation-delay: 0.24s; }

        /* Micro Interactions */
        .btn-press {
            transition: transform 0.15s ease, background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-press:active {
            transform: scale(0.97);
        }

        .card-lift {
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.25s ease;
        }
        .card-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px -6px rgba(25, 25, 25, 0.07);
        }

        /* Glass Sidebar with Warm Ivory Tint */
        .glass-sidebar {
            background: rgba(250, 248, 245, 0.94);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-right: 1px solid rgba(229, 229, 229, 0.85);
        }

        /* Custom Switch Animation */
        .switch-track {
            transition: background-color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .switch-thumb {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* =========================================================================
           TOP-RIGHT POPUP TOAST SYSTEM (Sistem Notifikasi Melayang Kanan Atas)
           ========================================================================= */
        @keyframes toastSlideIn {
            from {
                opacity: 0;
                transform: translateX(110%) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }
        @keyframes toastSlideOut {
            from {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
            to {
                opacity: 0;
                transform: translateX(110%) scale(0.96);
            }
        }
        @keyframes toastProgress {
            from { width: 100%; }
            to { width: 0%; }
        }
        .toast-popup-item {
            animation: toastSlideIn 0.38s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, opacity;
        }
        .toast-popup-item.is-dismissing {
            animation: toastSlideOut 0.32s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .toast-progress-bar {
            animation: toastProgress linear forwards;
        }
        .toast-popup-item:hover .toast-progress-bar {
            animation-play-state: paused;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-warm-ivory text-ink-dark font-sans antialiased min-h-screen flex flex-col md:flex-row relative selection:bg-ink-black selection:text-white">

    <!-- Global Top-Right Toast Popup Container (Bisa Menampung Pesan Apa Saja dalam Kotak Persegi) -->
    <div id="adminToastContainer" class="fixed top-16 right-3.5 sm:top-20 sm:right-6 z-[99999] flex flex-col items-end gap-3 pointer-events-none select-none"></div>

    <!-- Fixed Top-Right Trigger (Kotak Pesan Kecil Persegi) -->
    <div class="fixed top-3.5 right-3.5 sm:top-5 sm:right-6 z-[60] flex items-center gap-2">
        <button type="button" onclick="toggleAdminMessagePopup()" id="btnGlobalMessagePopup"
                class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-pure-white hover:bg-warm-stone border border-hairline text-ink-black shadow-elevated backdrop-blur-md transition-all flex items-center justify-center btn-press relative select-none ring-1 ring-black/5"
                title="Buka Kotak Pesan">
            <i class="fa-regular fa-comment-dots text-base sm:text-lg text-ink-black"></i>
            <span id="globalPopupPing" class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping absolute top-1.5 right-1.5 {{ $pendingNotificationCount > 0 ? '' : 'hidden' }}"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 absolute top-1.5 right-1.5 border-2 border-white"></span>
            <span id="globalPopupBadge" class="absolute -bottom-1 -right-1 px-1.5 py-0.5 rounded-full bg-ink-black text-white text-[9px] font-mono font-bold leading-none shadow-xs {{ $pendingNotificationCount > 0 ? '' : 'hidden' }}">
                {{ $pendingNotificationCount ?: 0 }}
            </span>
        </button>
    </div>

    <!-- Backdrop for Message Dropdown -->
    <div id="adminMessageBackdrop" onclick="toggleAdminMessagePopup()" class="fixed inset-0 z-[65] bg-ink-black/25 backdrop-blur-xs hidden transition-opacity duration-300"></div>

    <!-- Top-Right Message & Notification Pop-up Modal / Dropdown Card (Kotak Pesan Persegi Terkategori) -->
    <div id="adminMessagePopupDropdown" class="hidden fixed top-16 right-3.5 sm:top-20 sm:right-6 z-[70] w-84 sm:w-92 max-w-[calc(100vw-2rem)] bg-pure-white/98 backdrop-blur-xl border border-hairline rounded-3xl shadow-dropdown overflow-hidden select-text anim-fade-up">
        
        {{-- Header bar --}}
        <div class="p-3.5 sm:p-4 border-b border-hairline bg-warm-stone/50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-xl bg-ink-black text-white flex items-center justify-center text-xs shadow-2xs">
                    <i class="fa-regular fa-comment-dots"></i>
                </div>
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-ink-black leading-tight">Kotak Pesan</h3>
                    <p class="text-[9.5px] text-muted-text font-mono">Pusat Pesan &amp; Pop-up</p>
                </div>
            </div>
            <div class="flex items-center gap-1.5">
                <button type="button" onclick="markAllPopupMessagesAsRead()" class="px-2 py-1 rounded-lg bg-pure-white hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-200 text-ink-black text-[9.5px] font-bold border border-hairline transition btn-press flex items-center gap-1" title="Tandai Semua Telah Dibaca">
                    <i class="fa-solid fa-check-double text-[9px] text-emerald-600"></i>
                    <span>Telah Dibaca</span>
                </button>
                <button type="button" onclick="testDirectToast()" class="px-2 py-1 rounded-lg bg-pure-white hover:bg-ink-black hover:text-white text-ink-black text-[9.5px] font-bold border border-hairline transition btn-press" title="Uji Tampil Pop-Up Sekarang">
                    ⚡ Uji
                </button>
                <button type="button" onclick="toggleAdminMessagePopup()" class="w-7 h-7 flex items-center justify-center text-muted-text hover:text-ink-black rounded-lg transition">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
        </div>

        {{-- Category Tabs: 4 Kategori Terpisah Rapi --}}
        <div class="flex items-center gap-1 px-2.5 py-2 bg-warm-ivory border-b border-hairline overflow-x-auto text-[10.5px] font-bold select-none no-scrollbar">
            <button type="button" onclick="switchMsgPopupCategory('kat-tamu', this)" class="msg-category-tab-btn px-2.5 py-1.5 rounded-xl bg-ink-black text-white shadow-xs transition flex items-center gap-1.5 shrink-0 whitespace-nowrap">
                <i class="fa-solid fa-user-group text-[9.5px]"></i>
                <span>Pesan Tamu</span>
                <span id="badgeCatTamu" class="px-1.5 py-0.2 rounded-full bg-white text-ink-black text-[9px] font-mono leading-none {{ $pendingReservationCount > 0 ? '' : 'hidden' }}">{{ $pendingReservationCount }}</span>
            </button>
            <button type="button" onclick="switchMsgPopupCategory('kat-pesanan', this)" class="msg-category-tab-btn px-2.5 py-1.5 rounded-xl bg-pure-white text-charcoal hover:bg-warm-stone border border-hairline transition flex items-center gap-1.5 shrink-0 whitespace-nowrap">
                <i class="fa-solid fa-receipt text-[9.5px]"></i>
                <span>Pesanan</span>
                <span id="badgeCatPesanan" class="px-1.5 py-0.2 rounded-full bg-ink-black text-white text-[9px] font-mono leading-none {{ $pendingOrderCount > 0 ? '' : 'hidden' }}">{{ $pendingOrderCount }}</span>
            </button>
            <button type="button" onclick="switchMsgPopupCategory('kat-ulasan', this)" class="msg-category-tab-btn px-2.5 py-1.5 rounded-xl bg-pure-white text-charcoal hover:bg-warm-stone border border-hairline transition flex items-center gap-1.5 shrink-0 whitespace-nowrap">
                <i class="fa-solid fa-star text-[9.5px] text-amber-500"></i>
                <span>Ulasan</span>
            </button>
            <button type="button" onclick="switchMsgPopupCategory('kat-tulis', this)" class="msg-category-tab-btn px-2.5 py-1.5 rounded-xl bg-pure-white text-charcoal hover:bg-warm-stone border border-hairline transition flex items-center gap-1.5 shrink-0 whitespace-nowrap">
                <i class="fa-solid fa-pen text-[9.5px]"></i>
                <span>Tulis Bebas</span>
            </button>
        </div>

        {{-- KATEGORI 1: Pesan Tamu (Reservasi Meja & Request Catatan Tamu) --}}
        <div id="panel-kat-tamu" class="msg-popup-category-panel p-3.5 space-y-2.5 max-h-76 overflow-y-auto">
            <div class="flex items-center justify-between text-[9.5px] font-mono font-bold uppercase tracking-wider text-muted-text px-1">
                <span>Catatan &amp; Request Meja</span>
                <span class="text-[9px] text-muted-text lowercase font-sans font-normal">klik untuk tandai</span>
            </div>
            @forelse($recentPopupReservations as $res)
                @php
                    $cleanPhone = preg_replace('/[^0-9]/', '', $res->phone);
                    if (str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                @endphp
                <div id="popup-res-{{ $res->id }}" data-msg-key="res_{{ $res->id }}" class="popup-msg-card p-3 rounded-2xl bg-warm-ivory border border-hairline hover:bg-pure-white transition space-y-1.5 relative">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-bold text-ink-black truncate">{{ $res->name }}</span>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <span class="read-status-badge hidden text-[8.5px] px-1.5 py-0.2 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold">✓ Dibaca</span>
                            <span class="font-mono text-[9px] text-muted-text">{{ date('d M, H:i', strtotime($res->date . ' ' . $res->time)) }}</span>
                        </div>
                    </div>
                    <p class="text-xs text-charcoal italic line-clamp-2 leading-relaxed">
                        "{{ $res->notes ?: 'Reservasi meja untuk ' . $res->guests . ' orang.' }}"
                    </p>
                    <div class="pt-1 flex items-center justify-between text-[10px]">
                        <button type="button" onclick="markSingleMessageAsRead('res_{{ $res->id }}', this)" class="btn-mark-item-read text-muted-text hover:text-emerald-700 font-bold flex items-center gap-1 transition">
                            <i class="fa-regular fa-circle-check text-xs"></i>
                            <span class="read-label">Tandai dibaca</span>
                        </button>
                        @if($cleanPhone)
                            <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" onclick="markSingleMessageAsRead('res_{{ $res->id }}')" class="text-emerald-800 font-bold hover:underline inline-flex items-center gap-1">
                                <i class="fa-brands fa-whatsapp text-xs"></i>
                                <span>Balas WA ↗</span>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-6 text-center text-muted-text text-[11px]">
                    <i class="fa-solid fa-inbox text-base mb-1 block"></i>
                    Belum ada pesan tamu baru.
                </div>
            @endforelse
            <div class="pt-2 text-center border-t border-hairline">
                <a href="{{ route('admin.reservations.index') }}" class="text-[10px] font-bold text-ink-black hover:text-accent-terracotta transition">
                    Buka Semua Reservasi &amp; Pesan Tamu ↗
                </a>
            </div>
        </div>

        {{-- KATEGORI 2: Pesanan Masuk (Order Kasir, Dine-In & Zendo) --}}
        <div id="panel-kat-pesanan" class="msg-popup-category-panel p-3.5 space-y-2.5 max-h-76 overflow-y-auto hidden">
            <div class="flex items-center justify-between text-[9.5px] font-mono font-bold uppercase tracking-wider text-muted-text px-1">
                <span>Slip Pesanan Terkini</span>
                <span class="text-[9px] text-muted-text lowercase font-sans font-normal">klik untuk tandai</span>
            </div>
            @forelse($recentPopupOrders as $ord)
                <div id="popup-ord-{{ $ord->id }}" data-msg-key="ord_{{ $ord->id }}" class="popup-msg-card p-2.5 rounded-xl bg-warm-ivory border border-hairline hover:bg-pure-white transition flex items-center justify-between gap-2 text-xs">
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-ink-black font-mono text-[11px]">#{{ $ord->code }}</span>
                            <span class="read-status-badge hidden text-[8.5px] px-1 py-0.2 rounded font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">✓ Dibaca</span>
                            <span class="text-[9px] px-1 py-0.2 rounded font-bold uppercase {{ $ord->status === 'completed' ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }}">
                                {{ $ord->status }}
                            </span>
                        </div>
                        <div class="text-[10px] text-muted-text truncate mt-0.5">{{ $ord->customer_name }} &bull; Rp {{ number_format($ord->total, 0, ',', '.') }}</div>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        <button type="button" onclick="markSingleMessageAsRead('ord_{{ $ord->id }}', this)" class="btn-mark-item-read p-1 text-muted-text hover:text-emerald-700 transition" title="Tandai telah dibaca">
                            <i class="fa-regular fa-circle-check text-xs"></i>
                        </button>
                        <a href="{{ route('admin.orders.show', $ord->id) }}" onclick="markSingleMessageAsRead('ord_{{ $ord->id }}')" class="px-2 py-1 rounded-lg bg-warm-stone hover:bg-ink-black hover:text-white text-ink-black text-[9.5px] font-bold transition btn-press">
                            Detail ↗
                        </a>
                    </div>
                </div>
            @empty
                <div class="py-6 text-center text-muted-text text-[11px]">
                    <i class="fa-solid fa-receipt text-base mb-1 block"></i>
                    Belum ada pesanan terbaru.
                </div>
            @endforelse
            <div class="pt-2 text-center border-t border-hairline">
                <a href="{{ route('admin.orders.index') }}" class="text-[10px] font-bold text-ink-black hover:text-accent-terracotta transition">
                    Buka Semua Pesanan Masuk ↗
                </a>
            </div>
        </div>

        {{-- KATEGORI 3: Ulasan Pelanggan (Feedback & Testimoni) --}}
        <div id="panel-kat-ulasan" class="msg-popup-category-panel p-3.5 space-y-2.5 max-h-76 overflow-y-auto hidden">
            <div class="flex items-center justify-between text-[9.5px] font-mono font-bold uppercase tracking-wider text-muted-text px-1">
                <span>Feedback &amp; Rating Tamu</span>
                <span class="text-[9px] text-muted-text lowercase font-sans font-normal">klik untuk tandai</span>
            </div>
            @forelse($recentPopupTestimonials as $tst)
                <div id="popup-tst-{{ $tst->id }}" data-msg-key="tst_{{ $tst->id }}" class="popup-msg-card p-3 rounded-2xl bg-warm-ivory border border-hairline hover:bg-pure-white transition space-y-1.5 relative">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-bold text-ink-black truncate">{{ $tst->name }}</span>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <span class="read-status-badge hidden text-[8.5px] px-1.5 py-0.2 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold">✓ Dibaca</span>
                            <div class="text-amber-500 text-[10px]">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i <= ($tst->rating ?? 5) ? 'text-amber-400' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-charcoal italic line-clamp-2 leading-relaxed">
                        "{{ $tst->content }}"
                    </p>
                    <div class="pt-1 flex items-center justify-between text-[10px]">
                        <button type="button" onclick="markSingleMessageAsRead('tst_{{ $tst->id }}', this)" class="btn-mark-item-read text-muted-text hover:text-emerald-700 font-bold flex items-center gap-1 transition">
                            <i class="fa-regular fa-circle-check text-xs"></i>
                            <span class="read-label">Tandai dibaca</span>
                        </button>
                        <span class="text-muted-text font-mono text-[9.5px]">{{ $tst->source ?: 'Ulasan Pelanggan' }}</span>
                    </div>
                </div>
            @empty
                <div class="py-6 text-center text-muted-text text-[11px]">
                    <i class="fa-regular fa-star text-base mb-1 block"></i>
                    Belum ada ulasan baru.
                </div>
            @endforelse
            <div class="pt-2 text-center border-t border-hairline">
                <a href="{{ route('admin.testimonials.index') }}" class="text-[10px] font-bold text-ink-black hover:text-accent-terracotta transition">
                    Kelola Semua Ulasan Tamu ↗
                </a>
            </div>
        </div>

        {{-- KATEGORI 4: Tulis Pop-Up Pesan Bebas ("mencakup pesan apa aja") --}}
        <div id="panel-kat-tulis" class="msg-popup-category-panel p-3.5 space-y-2.5 hidden">
            <div>
                <label class="block text-[10.5px] font-bold text-charcoal mb-1">Judul Pop-Up</label>
                <input type="text" id="customToastTitleInput" placeholder="Contoh: Info Dapur / Update Stok"
                       class="w-full bg-warm-ivory border border-hairline rounded-xl px-3 py-1.5 text-xs text-ink-black font-semibold focus:outline-none focus:border-ink-black focus:bg-white transition">
            </div>

            <div>
                <label class="block text-[10.5px] font-bold text-charcoal mb-1">Isi Pesan (Bebas apa saja)</label>
                <textarea id="customToastMessageInput" rows="2" placeholder="Ketik pesan apa saja yang ingin dimunculkan..."
                          class="w-full bg-warm-ivory border border-hairline rounded-xl px-3 py-1.5 text-xs text-ink-black focus:outline-none focus:border-ink-black focus:bg-white transition resize-none"></textarea>
            </div>

            <div class="grid grid-cols-4 gap-1 text-[9.5px] font-bold">
                <button type="button" onclick="selectToastType('success', this)" class="toast-type-pill py-1.5 rounded-lg border border-emerald-300 bg-emerald-50 text-emerald-800 text-center transition">
                    Sukses
                </button>
                <button type="button" onclick="selectToastType('info', this)" class="toast-type-pill py-1.5 rounded-lg border border-hairline bg-white text-ink-black text-center transition">
                    Info
                </button>
                <button type="button" onclick="selectToastType('warning', this)" class="toast-type-pill py-1.5 rounded-lg border border-hairline bg-white text-muted-text text-center transition">
                    Peringatan
                </button>
                <button type="button" onclick="selectToastType('error', this)" class="toast-type-pill py-1.5 rounded-lg border border-hairline bg-white text-muted-text text-center transition">
                    Error
                </button>
            </div>
            <input type="hidden" id="selectedToastType" value="success">

            <button type="button" onclick="triggerCustomAdminToast()" class="w-full py-2.5 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition shadow-xs btn-press flex items-center justify-center gap-2">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                <span>Tampilkan Pop-up Persegi</span>
            </button>
        </div>

    </div>

    <!-- Mobile Top Header Bar -->
    <header class="md:hidden sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-hairline px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <button type="button" onclick="toggleAdminDrawer()" class="p-2 text-ink-dark hover:bg-warm-stone rounded-xl transition" aria-label="Buka Menu Admin">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/nucomu/nucomu_logo_brand.png') }}" alt="Nucomu Cafe" class="h-6 w-auto object-contain">
            </a>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" target="_blank" class="px-2.5 py-1.5 rounded-full bg-warm-stone border border-hairline text-[11px] font-bold text-ink-dark hover:bg-ink-black hover:text-white transition">
                <span>Web ↗</span>
            </a>
            <a href="{{ route('admin.orders.scan') }}" class="p-2 bg-ink-black text-white rounded-xl text-xs" title="Scan QR">
                <i class="fa-solid fa-qrcode"></i>
            </a>
        </div>
    </header>

    <!-- Mobile Drawer Backdrop -->
    <div id="admin-drawer-backdrop" onclick="toggleAdminDrawer()" class="fixed inset-0 bg-ink-black/40 backdrop-blur-xs z-50 opacity-0 pointer-events-none transition-opacity duration-300 md:hidden"></div>

    <!-- Sidebar Navigation (Desktop & Slide-in Mobile) -->
    <aside id="admin-sidebar" class="fixed md:sticky top-0 left-0 bottom-0 w-72 md:w-64 glass-sidebar shrink-0 flex flex-col justify-between p-4 sm:p-5 h-screen z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto">
        <div class="space-y-6">
            
            <!-- Brand Monogram & Title -->
            <div class="flex items-center justify-between px-2 pt-1 pb-2 border-b border-hairline">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-2xl bg-ink-black flex items-center justify-center text-white shadow-xs group-hover:scale-105 transition-transform duration-200">
                        <img src="{{ asset('images/nucomu/nucomu_logo_icon.png') }}" alt="Nucomu" class="h-5 w-auto object-contain brightness-0 invert">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-extrabold tracking-tight text-ink-black leading-tight">NUCOMU CAFE</span>
                        <span class="text-[10px] uppercase tracking-widest text-muted-text font-semibold font-mono">Workspace Panel</span>
                    </div>
                </a>
                <button type="button" onclick="toggleAdminDrawer()" class="md:hidden p-1.5 text-muted-text hover:text-ink-black rounded-lg">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <!-- Live Status Indicator Pill -->
            <div class="px-2">
                <div class="flex items-center justify-between px-3 py-1.5 rounded-xl bg-pure-white border border-hairline text-[11px] shadow-xs">
                    <span class="flex items-center gap-2 font-semibold text-charcoal">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Sistem Aktif</span>
                    </span>
                    <span class="text-[10px] font-mono text-muted-text font-bold uppercase tracking-wider">v2.4 Live</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1 text-xs font-semibold">
                
                <div class="px-3 pt-1 pb-1">
                    <span class="text-[9.5px] font-mono uppercase tracking-[0.2em] text-muted-text font-bold">OPERASIONAL</span>
                </div>

                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-ink-black text-white font-bold shadow-xs' : 'text-charcoal hover:text-ink-black hover:bg-warm-stone/80' }}">
                    <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.menus.index') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.menus.*') ? 'bg-ink-black text-white font-bold shadow-xs' : 'text-charcoal hover:text-ink-black hover:bg-warm-stone/80' }}">
                    <i class="fa-solid fa-utensils w-4 text-center"></i>
                    <span>Kelola Menu</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.orders.index', 'admin.orders.show') ? 'bg-ink-black text-white font-bold shadow-xs' : 'text-charcoal hover:text-ink-black hover:bg-warm-stone/80' }}">
                    <i class="fa-solid fa-receipt w-4 text-center"></i>
                    <span>Pesanan Masuk</span>
                </a>

                <a href="{{ route('admin.orders.scan') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.orders.scan*') ? 'bg-ink-dark text-white font-bold shadow-xs ring-1 ring-hairline' : 'text-ink-dark bg-white hover:bg-warm-stone/80 border border-hairline' }}">
                    <i class="fa-solid fa-qrcode w-4 text-center"></i>
                    <span>Scan QR Kasir</span>
                </a>

                <a href="{{ route('admin.zendo.index') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.zendo.*') ? 'bg-ink-black text-white font-bold shadow-xs' : 'text-charcoal hover:text-ink-black hover:bg-warm-stone/80' }}">
                    <i class="fa-solid fa-motorcycle w-4 text-center"></i>
                    <span>Kurir &amp; Pantau Zendo</span>
                </a>

                <a href="{{ route('admin.reservations.index') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reservations.*') ? 'bg-ink-black text-white font-bold shadow-xs' : 'text-charcoal hover:text-ink-black hover:bg-warm-stone/80' }}">
                    <i class="fa-solid fa-calendar-check w-4 text-center"></i>
                    <span>Reservasi Meja</span>
                </a>

                <div class="px-3 pt-4 pb-1">
                    <span class="text-[9.5px] font-mono uppercase tracking-[0.2em] text-muted-text font-bold">KONTEN WEB CUSTOMER</span>
                </div>

                <a href="{{ route('admin.gallery.index') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.gallery.*') ? 'bg-ink-black text-white font-bold shadow-xs' : 'text-charcoal hover:text-ink-black hover:bg-warm-stone/80' }}">
                    <i class="fa-solid fa-camera-retro w-4 text-center"></i>
                    <span>Galeri Story</span>
                </a>

                <a href="{{ route('admin.testimonials.index') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.testimonials.*') ? 'bg-ink-black text-white font-bold shadow-xs' : 'text-charcoal hover:text-ink-black hover:bg-warm-stone/80' }}">
                    <i class="fa-solid fa-star w-4 text-center"></i>
                    <span>Ulasan Pelanggan</span>
                </a>

                <a href="{{ route('admin.events.index') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.events.*') ? 'bg-ink-black text-white font-bold shadow-xs' : 'text-charcoal hover:text-ink-black hover:bg-warm-stone/80' }}">
                    <i class="fa-solid fa-bullhorn w-4 text-center"></i>
                    <span>Event &amp; Promo</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" 
                   class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-ink-black text-white font-bold shadow-xs' : 'text-charcoal hover:text-ink-black hover:bg-warm-stone/80' }}">
                    <i class="fa-solid fa-sliders w-4 text-center"></i>
                    <span>Pengaturan &amp; Konten Web</span>
                </a>

            </nav>
        </div>

        <!-- Sidebar Footer: Profile, Web Link & Logout -->
        <div class="border-t border-hairline pt-4 mt-6 space-y-2.5">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-warm-stone border border-hairline flex items-center justify-center text-xs font-bold text-ink-black shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'Admin', 0, 1)) }}
                    </div>
                    <div class="truncate text-xs">
                        <span class="font-bold text-ink-dark block truncate">{{ auth()->user()->name ?? 'Admin Nucomu' }}</span>
                        <span class="text-[10px] text-muted-text font-mono block truncate">{{ auth()->user()->email ?? 'admin@nucomu.com' }}</span>
                    </div>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar Admin" class="p-2 text-muted-text hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors btn-press">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>

            <a href="{{ route('home') }}" target="_blank" class="w-full inline-flex items-center justify-center space-x-2 py-2.5 text-[11px] font-bold text-ink-dark bg-pure-white hover:bg-ink-black hover:text-white rounded-xl border border-hairline transition-all duration-200 shadow-2xs btn-press">
                <span>Buka Web Customer</span>
                <i class="fa-solid fa-arrow-up-right-from-square text-[9.5px]"></i>
            </a>
        </div>
    </aside>

    <!-- Main Content Flow Area (Scrollable Container) -->
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10 relative z-10" id="admin-main">
        @yield('content')
    </main>

    <script>
        /* =========================================================================
           GLOBAL TOP-RIGHT TOAST NOTIFICATION ENGINE
           Mampu menampung dan menampilkan segala jenis pesan (sukses, error,
           peringatan, info, atau validasi form) secara dinamis & melayang di kanan atas.
           ========================================================================= */
        function showAdminToast(message, type = 'success', title = null, duration = 4500) {
            const container = document.getElementById('adminToastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = 'toast-popup-item pointer-events-auto bg-pure-white/98 backdrop-blur-md border border-hairline rounded-2xl p-3.5 sm:p-4 shadow-dropdown w-72 sm:w-80 relative overflow-hidden flex flex-col gap-2.5 group select-text';

            // Visual configuration per notification type
            const typeConfig = {
                success: {
                    badgeClass: 'bg-emerald-50 text-emerald-800 border-emerald-200',
                    icon: 'fa-solid fa-circle-check text-emerald-600',
                    defaultTitle: 'Berhasil',
                    progressColor: 'bg-emerald-500'
                },
                error: {
                    badgeClass: 'bg-rose-50 text-rose-800 border-rose-200',
                    icon: 'fa-solid fa-circle-xmark text-rose-600',
                    defaultTitle: 'Terjadi Kesalahan',
                    progressColor: 'bg-rose-500'
                },
                warning: {
                    badgeClass: 'bg-amber-50 text-amber-900 border-amber-200',
                    icon: 'fa-solid fa-triangle-exclamation text-amber-600',
                    defaultTitle: 'Peringatan',
                    progressColor: 'bg-amber-500'
                },
                info: {
                    badgeClass: 'bg-warm-stone text-ink-black border-hairline',
                    icon: 'fa-regular fa-comment-dots text-ink-black',
                    defaultTitle: 'Kotak Pesan',
                    progressColor: 'bg-ink-black'
                }
            };

            const cfg = typeConfig[type] || typeConfig.info;
            const finalTitle = title || cfg.defaultTitle;

            toast.innerHTML = `
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="w-7 h-7 rounded-xl border flex items-center justify-center shrink-0 ${cfg.badgeClass} shadow-2xs">
                            <i class="${cfg.icon} text-xs"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="font-bold text-xs text-ink-black font-sans leading-tight block truncate">${finalTitle}</span>
                            <span class="text-[9px] font-mono text-muted-text font-semibold uppercase tracking-wider block">Baru saja</span>
                        </div>
                    </div>
                    <button type="button" class="btn-dismiss-toast text-muted-text hover:text-ink-black p-1 rounded-lg transition-colors shrink-0" title="Tutup Notifikasi">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>
                <div class="text-xs text-charcoal leading-relaxed font-normal bg-warm-ivory/80 rounded-xl p-2.5 border border-hairline/60">
                    ${message}
                </div>
                <div class="toast-progress-bar absolute bottom-0 left-0 h-0.5 ${cfg.progressColor}" style="animation-duration: ${duration}ms;"></div>
            `;

            container.appendChild(toast);

            let dismissTimer = null;
            let remainingTime = duration;
            let startTime = Date.now();

            const dismiss = () => {
                if (toast.classList.contains('is-dismissing')) return;
                toast.classList.add('is-dismissing');
                setTimeout(() => {
                    if (toast.parentElement) toast.parentElement.removeChild(toast);
                }, 350);
            };

            const dismissBtn = toast.querySelector('.btn-dismiss-toast');
            if (dismissBtn) {
                dismissBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    clearTimeout(dismissTimer);
                    dismiss();
                });
            }

            // Start auto dismiss
            dismissTimer = setTimeout(dismiss, duration);

            // Pause on hover, resume on mouse leave
            toast.addEventListener('mouseenter', () => {
                clearTimeout(dismissTimer);
                remainingTime -= (Date.now() - startTime);
            });

            toast.addEventListener('mouseleave', () => {
                startTime = Date.now();
                dismissTimer = setTimeout(dismiss, Math.max(1000, remainingTime));
            });
        }

        window.showAdminToast = showAdminToast;
        window.toast = showAdminToast;

        /* =========================================================================
           TOP-RIGHT MESSAGE CENTER DROPDOWN & CUSTOM TOAST CONTROLS
           ========================================================================= */
        function toggleAdminMessagePopup() {
            const dropdown = document.getElementById('adminMessagePopupDropdown');
            const backdrop = document.getElementById('adminMessageBackdrop');
            if (!dropdown) return;

            const isHidden = dropdown.classList.contains('hidden');
            if (isHidden) {
                dropdown.classList.remove('hidden');
                if (backdrop) backdrop.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
                if (backdrop) backdrop.classList.add('hidden');
            }
        }

        function switchMsgPopupCategory(catId, btnEl) {
            document.querySelectorAll('.msg-popup-category-panel').forEach(p => p.classList.add('hidden'));
            document.querySelectorAll('.msg-category-tab-btn').forEach(btn => {
                btn.className = 'msg-category-tab-btn px-2.5 py-1.5 rounded-xl bg-pure-white text-charcoal hover:bg-warm-stone border border-hairline transition flex items-center gap-1.5 shrink-0 whitespace-nowrap font-bold';
            });
            const targetPanel = document.getElementById('panel-' + catId);
            if (targetPanel) targetPanel.classList.remove('hidden');
            if (btnEl) {
                btnEl.className = 'msg-category-tab-btn px-2.5 py-1.5 rounded-xl bg-ink-black text-white shadow-xs transition flex items-center gap-1.5 shrink-0 whitespace-nowrap font-bold';
            }
        }
        window.switchMsgPopupTab = switchMsgPopupCategory;

        function selectToastType(type, btnEl) {
            const hiddenInput = document.getElementById('selectedToastType');
            if (hiddenInput) hiddenInput.value = type;

            document.querySelectorAll('.toast-type-pill').forEach(btn => {
                btn.className = 'toast-type-pill py-1.5 rounded-lg border border-hairline bg-white text-muted-text text-center transition';
            });

            if (btnEl) {
                const colorMap = {
                    success: 'border-emerald-300 bg-emerald-50 text-emerald-800 font-bold',
                    info: 'border-ink-black bg-warm-stone text-ink-black font-bold',
                    warning: 'border-amber-300 bg-amber-50 text-amber-900 font-bold',
                    error: 'border-rose-300 bg-rose-50 text-rose-800 font-bold'
                };
                btnEl.className = 'toast-type-pill py-1.5 rounded-lg border text-center transition ' + (colorMap[type] || 'bg-white');
            }
        }

        function triggerCustomAdminToast() {
            const titleInput = document.getElementById('customToastTitleInput');
            const msgInput = document.getElementById('customToastMessageInput');
            const typeInput = document.getElementById('selectedToastType');

            const title = (titleInput && titleInput.value.trim()) || 'Pemberitahuan Kafe';
            const message = (msgInput && msgInput.value.trim()) || 'Ini adalah contoh pesan bebas yang dapat mencakup informasi apa saja.';
            const type = (typeInput && typeInput.value) || 'success';

            showAdminToast(message, type, title);
            toggleAdminMessagePopup();
        }

        function testDirectToast() {
            showAdminToast('Pesanan baru terverifikasi lunas dari Meja #4!', 'success', 'Slip Kasir Baru');
        }

        /* =========================================================================
           SISTEM STATUS "TELAH DIBACA" (MARK AS READ ENGINE)
           Menyimpan status baca di localStorage dan mengupdate badge & tampilan
           ========================================================================= */
        function getReadMessageIds() {
            try {
                return JSON.parse(localStorage.getItem('nucomu_read_messages') || '[]');
            } catch(e) {
                return [];
            }
        }

        function saveReadMessageIds(ids) {
            try {
                localStorage.setItem('nucomu_read_messages', JSON.stringify(ids));
            } catch(e) {}
        }

        function updateUnreadBadges() {
            const readIds = getReadMessageIds();
            const allCards = document.querySelectorAll('.popup-msg-card');
            let totalUnread = 0;
            let unreadTamu = 0;
            let unreadPesanan = 0;

            allCards.forEach(card => {
                const key = card.getAttribute('data-msg-key');
                const isRead = readIds.includes(key);
                const badge = card.querySelector('.read-status-badge');
                const btnRead = card.querySelector('.btn-mark-item-read');
                const label = card.querySelector('.read-label');

                if (isRead) {
                    card.classList.add('opacity-60', 'bg-warm-stone/70');
                    if (badge) badge.classList.remove('hidden');
                    if (btnRead) {
                        btnRead.classList.add('text-emerald-700', 'pointer-events-none');
                        btnRead.classList.remove('text-muted-text');
                    }
                    if (label) label.textContent = 'Sudah dibaca';
                } else {
                    totalUnread++;
                    if (key && key.startsWith('res_')) unreadTamu++;
                    else if (key && key.startsWith('ord_')) unreadPesanan++;

                    card.classList.remove('opacity-60', 'bg-warm-stone/70');
                    if (badge) badge.classList.add('hidden');
                    if (btnRead) {
                        btnRead.classList.remove('text-emerald-700', 'pointer-events-none');
                        btnRead.classList.add('text-muted-text');
                    }
                    if (label) label.textContent = 'Tandai dibaca';
                }
            });

            // Update category badge counters
            const badgeTamu = document.getElementById('badgeCatTamu');
            if (badgeTamu) {
                badgeTamu.textContent = unreadTamu;
                if (unreadTamu <= 0) badgeTamu.classList.add('hidden');
                else badgeTamu.classList.remove('hidden');
            }

            const badgePesanan = document.getElementById('badgeCatPesanan');
            if (badgePesanan) {
                badgePesanan.textContent = unreadPesanan;
                if (unreadPesanan <= 0) badgePesanan.classList.add('hidden');
                else badgePesanan.classList.remove('hidden');
            }

            // Update badge on floating square button
            const globalBadge = document.getElementById('globalPopupBadge');
            const globalPing = document.getElementById('globalPopupPing');
            if (globalBadge) {
                globalBadge.textContent = totalUnread;
                if (totalUnread <= 0) {
                    globalBadge.classList.add('hidden');
                    if (globalPing) globalPing.classList.add('hidden');
                } else {
                    globalBadge.classList.remove('hidden');
                    if (globalPing) globalPing.classList.remove('hidden');
                }
            }

            // Update badge on dashboard action button
            const dashBadge = document.getElementById('dashboardMsgBadge');
            const dashPing = document.getElementById('dashboardMsgPing');
            if (dashBadge) {
                dashBadge.textContent = totalUnread;
                if (totalUnread <= 0) {
                    dashBadge.classList.add('hidden');
                    if (dashPing) dashPing.classList.add('hidden');
                } else {
                    dashBadge.classList.remove('hidden');
                    if (dashPing) dashPing.classList.remove('hidden');
                }
            }
        }

        function markSingleMessageAsRead(msgKey, btnEl) {
            let readIds = getReadMessageIds();
            if (!readIds.includes(msgKey)) {
                readIds.push(msgKey);
                saveReadMessageIds(readIds);
            }
            updateUnreadBadges();
            showAdminToast('Pesan berhasil ditandai telah dibaca.', 'success', 'Telah Dibaca', 2200);
        }

        function markAllPopupMessagesAsRead() {
            const allCards = document.querySelectorAll('.popup-msg-card');
            let readIds = getReadMessageIds();
            allCards.forEach(card => {
                const key = card.getAttribute('data-msg-key');
                if (key && !readIds.includes(key)) {
                    readIds.push(key);
                }
            });
            saveReadMessageIds(readIds);
            updateUnreadBadges();
            showAdminToast('Semua pesan di kotak masuk telah ditandai dibaca.', 'success', 'Semua Dibaca', 2800);
        }

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const dropdown = document.getElementById('adminMessagePopupDropdown');
                const backdrop = document.getElementById('adminMessageBackdrop');
                if (dropdown && !dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                    if (backdrop) backdrop.classList.add('hidden');
                }
            }
        });

        function toggleAdminDrawer() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('admin-drawer-backdrop');
            if (!sidebar || !backdrop) return;

            const isOpen = !sidebar.classList.contains('-translate-x-full');
            if (isOpen) {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
            } else {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
            }
        }

        /* =========================================================================
           SCROLL REVEAL OBSERVER (Sama Persis dengan Nucomu Customer Website)
           Mendeteksi elemen yang berada di bawah layar, membiarkannya kosong, lalu
           meluncurkannya naik secara elegan begitu tergulung masuk ke viewport.
           ========================================================================= */
        function initAdminScrollReveal() {
            const reveals = document.querySelectorAll('.reveal-on-scroll, .reveal-fade-up, .reveal-from-left, .reveal-from-right');
            if (!reveals.length) return;

            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                reveals.forEach(el => el.classList.add('is-visible'));
                return;
            }

            const onIntersect = (entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        obs.unobserve(entry.target);
                    }
                });
            };

            const observerOptions = {
                threshold: 0.08,
                rootMargin: '0px 0px -25px 0px'
            };

            if ('IntersectionObserver' in window) {
                // Observer for viewport (standard window scrolling)
                const viewportObserver = new IntersectionObserver(onIntersect, observerOptions);
                reveals.forEach(el => {
                    if (!el.classList.contains('is-visible')) {
                        viewportObserver.observe(el);
                    }
                });

                // Observer for #admin-main container (if inner container is scrolling)
                const mainContainer = document.getElementById('admin-main');
                if (mainContainer) {
                    const containerObserver = new IntersectionObserver(onIntersect, {
                        ...observerOptions,
                        root: mainContainer
                    });
                    reveals.forEach(el => {
                        if (!el.classList.contains('is-visible')) {
                            containerObserver.observe(el);
                        }
                    });
                }
            } else {
                reveals.forEach(el => el.classList.add('is-visible'));
            }
        }

        window.initAdminScrollReveal = initAdminScrollReveal;

        // Initialize scroll reveal & message read states on load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                initAdminScrollReveal();
                updateUnreadBadges();
            });
        } else {
            initAdminScrollReveal();
            updateUnreadBadges();
        }

        // Trigger server session flash popups or initial operational status toast
        @if(session('success'))
            showAdminToast(@json(session('success')), 'success', 'Berhasil');
        @elseif(session('error'))
            showAdminToast(@json(session('error')), 'error', 'Perhatian');
        @elseif(session('warning'))
            showAdminToast(@json(session('warning')), 'warning', 'Peringatan');
        @elseif(session('info'))
            showAdminToast(@json(session('info')), 'info', 'Informasi');
        @elseif($errors->any())
            const errorHtml = `<ul class="list-disc pl-4 space-y-0.5 mt-1 font-normal text-xs text-amber-900">@foreach($errors->all() as $err)<li>{{ addslashes($err) }}</li>@endforeach</ul>`;
            showAdminToast(errorHtml, 'warning', 'Harap Periksa Input:');
        @else
            // Munculkan pop-up pesan otomatis saat admin membuka/me-refresh halaman
            setTimeout(() => {
                const pendingCount = {{ (int)($pendingNotificationCount ?? 3) }};
                const msg = pendingCount > 0 
                    ? `Halo Admin Nucomu! Terdapat ${pendingCount} pembaruan pesanan & reservasi. Klik kotak pesan persegi di kanan atas untuk melihat atau menulis pesan bebas apa saja.`
                    : 'Halo Admin Nucomu! Sistem operasional aktif normal. Klik kotak pesan persegi di kanan atas untuk membuat pesan bebas.';
                showAdminToast(msg, 'info', 'Kotak Pesan Nucomu', 6000);
            }, 600);
        @endif
    </script>
    @stack('scripts')
</body>
</html>
