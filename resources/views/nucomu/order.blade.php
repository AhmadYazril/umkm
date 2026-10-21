<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Menu — Nucomu Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        obsidian: '#0c0c0f',
                        goldAccent: '#f59e0b',
                        violetAccent: '#8b5cf6',
                        cyanAccent: '#06b6d4',
                        roseAccent: '#f43f5e'
                    },
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0c0c0f; }
        ::-webkit-scrollbar-thumb { background: #232330; border-radius: 9999px; }
        .glass-card {
            background: rgba(21, 21, 28, 0.75);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .qty-btn { user-select: none; }
        .menu-card.selected { border-color: rgba(245, 158, 11, 0.7) !important; background: rgba(245, 158, 11, 0.08); }
    </style>
</head>
<body class="bg-obsidian text-gray-100 font-sans min-h-screen relative overflow-x-hidden">

    {{-- Ambient Glow --}}
    <div class="fixed top-10 left-1/4 w-96 h-96 rounded-full bg-goldAccent/10 pointer-events-none z-0 blur-3xl"></div>
    <div class="fixed bottom-10 right-10 w-96 h-96 rounded-full bg-violetAccent/10 pointer-events-none z-0 blur-3xl"></div>

    {{-- HEADER --}}
    <header class="sticky top-0 z-50 glass-card border-b border-white/5 px-4 sm:px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('nucomu.index') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-goldAccent via-amber-500 to-rose-500 p-0.5 shadow-lg shadow-goldAccent/20">
                    <div class="w-full h-full bg-obsidian rounded-[10px] flex items-center justify-center">
                        <i class="fa-solid fa-mug-hot text-goldAccent text-sm"></i>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-extrabold tracking-tight text-white">NUCOMU</span>
                    <span class="text-[9px] uppercase tracking-widest text-goldAccent font-semibold -mt-1">Coffee & Stories</span>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <button onclick="openOrderSummary()" class="relative px-4 py-2 rounded-full glass-card border border-goldAccent/30 text-goldAccent text-xs font-bold hover:border-goldAccent transition-all">
                    <i class="fa-solid fa-bag-shopping mr-1.5"></i>
                    Pesanan
                    <span id="cartBadge" class="hidden absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-goldAccent text-black text-[9px] font-black flex items-center justify-center">0</span>
                </button>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 relative z-10">

        {{-- HERO --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[11px] font-semibold text-goldAccent mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Buka Sekarang · 08:00 - 23:00
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white mb-2">Pilih <span class="text-goldAccent">Menu</span> Favorit Anda</h1>
            <p class="text-gray-400 text-sm">Pilih menu, isi nama Anda, lalu tunjukkan QR ke kasir.</p>
        </div>

        {{-- CATEGORY FILTER --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-6 no-scrollbar">
            <button onclick="filterCategory('all')" data-cat="all"
                class="cat-btn active px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap border border-goldAccent bg-goldAccent text-black transition-all">
                Semua Menu
            </button>
            @foreach($categories as $cat)
                <button onclick="filterCategory('{{ $cat->slug }}')" data-cat="{{ $cat->slug }}"
                    class="cat-btn px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap border border-white/10 text-gray-400 glass-card transition-all hover:border-goldAccent/50 hover:text-white">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            {{-- MENU GRID (3/4 width) --}}
            <div class="lg:col-span-2 xl:col-span-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4" id="menuGrid">
                    @forelse($menus as $menu)
                        <div class="menu-card glass-card rounded-2xl overflow-hidden border border-white/10 transition-all duration-200 hover:border-white/25 flex flex-col"
                             data-cat="{{ $menu->category?->slug ?? 'other' }}"
                             data-id="{{ $menu->id }}"
                             data-name="{{ $menu->name }}"
                             data-price="{{ $menu->price }}">

                            {{-- Image --}}
                            <div class="relative h-44 bg-zinc-900 overflow-hidden">
                                @if($menu->image && file_exists(public_path('storage/' . $menu->image)))
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                @elseif($menu->image && (str_starts_with($menu->image, 'http://') || str_starts_with($menu->image, 'https://')))
                                    <img src="{{ $menu->image }}" alt="{{ $menu->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-600">
                                        <i class="fa-solid fa-mug-hot text-4xl opacity-30"></i>
                                    </div>
                                @endif

                                @if($menu->is_featured)
                                    <span class="absolute top-2 left-2 bg-goldAccent text-black text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full">
                                        Unggulan
                                    </span>
                                @endif
                                @if($menu->is_best_seller)
                                    <span class="absolute top-2 right-2 bg-rose-500 text-white text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full">
                                        Best Seller
                                    </span>
                                @endif

                                {{-- Category --}}
                                <div class="absolute bottom-2 left-2">
                                    <span class="bg-black/70 backdrop-blur text-gray-300 text-[10px] px-2 py-0.5 rounded-full border border-white/10">
                                        {{ $menu->category?->name ?? 'Menu' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Info --}}
                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="font-bold text-white text-sm mb-1">{{ $menu->name }}</h3>
                                @if($menu->description)
                                    <p class="text-gray-400 text-xs leading-relaxed line-clamp-2 flex-1">{{ $menu->description }}</p>
                                @endif

                                <div class="flex items-center justify-between mt-3">
                                    <span class="text-goldAccent font-black text-base font-mono">
                                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                                    </span>
                                    <button onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})"
                                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-goldAccent text-black text-xs font-bold hover:bg-amber-400 active:scale-95 transition-all">
                                        <i class="fa-solid fa-plus text-xs"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-16 text-gray-500">
                            <i class="fa-solid fa-mug-hot text-4xl opacity-20 mb-3 block"></i>
                            <p class="text-sm">Belum ada menu tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ORDER PANEL (sticky right) --}}
            <div class="lg:col-span-1 xl:col-span-1">
                <div class="sticky top-24 space-y-4">

                    {{-- Name Input --}}
                    <div class="glass-card rounded-2xl p-5 border border-white/10">
                        <h3 class="text-sm font-bold text-white mb-3">
                            <i class="fa-solid fa-user text-goldAccent mr-2"></i> Nama Pemesan
                        </h3>
                        <input type="text" id="customerName" placeholder="Masukkan nama Anda..."
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-goldAccent transition-colors">
                        <p class="text-[10px] text-gray-500 mt-1.5">Nama ini akan muncul di slip pesanan.</p>
                    </div>

                    {{-- Cart Items --}}
                    <div class="glass-card rounded-2xl p-5 border border-white/10">
                        <h3 class="text-sm font-bold text-white mb-3">
                            <i class="fa-solid fa-list text-cyanAccent mr-2"></i> Pesanan Anda
                        </h3>

                        <div id="cartItems" class="space-y-3 min-h-[80px]">
                            <div id="emptyCart" class="text-center py-6 text-gray-500">
                                <i class="fa-solid fa-bag-shopping text-2xl opacity-30 block mb-2"></i>
                                <p class="text-xs">Belum ada menu dipilih</p>
                            </div>
                        </div>

                        {{-- Total --}}
                        <div id="cartTotal" class="hidden border-t border-white/10 pt-3 mt-3">
                            <div class="flex justify-between text-xs text-gray-400 mb-1">
                                <span>Subtotal</span>
                                <span id="totalAmount" class="font-mono text-white font-bold">Rp 0</span>
                            </div>
                            <p class="text-[10px] text-gray-500">*Bayar di kasir setelah menunjukkan slip.</p>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="glass-card rounded-2xl p-5 border border-white/10">
                        <h3 class="text-sm font-bold text-white mb-3">
                            <i class="fa-solid fa-note-sticky text-violetAccent mr-2"></i> Catatan (Opsional)
                        </h3>
                        <textarea id="orderNotes" rows="2" placeholder="Contoh: tanpa gula, extra hot..."
                            class="w-full bg-black/40 border border-white/10 rounded-xl p-3 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-goldAccent transition-colors resize-none"></textarea>
                    </div>

                    {{-- Submit --}}
                    <button onclick="submitOrder()" id="submitBtn"
                        class="w-full py-4 rounded-2xl bg-gradient-to-r from-goldAccent via-amber-400 to-yellow-300 text-black font-black text-sm uppercase tracking-wider hover:opacity-90 active:scale-98 transition-all shadow-lg shadow-goldAccent/20 disabled:opacity-50">
                        <i class="fa-solid fa-qrcode mr-2"></i> Buat Slip Pesanan
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- FLOATING ADDED NOTIFICATION (TOAST) --}}
    <div id="addedToast" class="fixed top-20 right-4 z-50 glass-card bg-[#15151c]/95 border border-goldAccent/40 shadow-2xl rounded-2xl px-4 py-3 flex items-center gap-3 transition-all duration-300 opacity-0 translate-y-4 pointer-events-none">
        <div class="w-8 h-8 rounded-full bg-goldAccent/20 flex items-center justify-center text-goldAccent">
            <i class="fa-solid fa-check text-xs"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-white"><span id="toastItemName">Menu</span> ditambahkan!</p>
            <p class="text-[10px] text-gray-400">Cek daftar pesanan Anda di sebelah kanan.</p>
        </div>
    </div>

    {{-- STICKY MOBILE BOTTOM BAR (appears when cart not empty on mobile) --}}
    <div id="mobileBottomBar" class="lg:hidden fixed bottom-0 left-0 right-0 z-40 glass-card bg-[#15151c]/95 border-t border-goldAccent/30 p-3.5 px-4 flex items-center justify-between shadow-2xl translate-y-full transition-transform duration-300">
        <div>
            <span id="mobileBarCount" class="text-[10px] uppercase font-bold text-gray-400 block font-mono">0 Item</span>
            <span id="mobileBarTotal" class="text-base font-black text-goldAccent font-mono">Rp 0</span>
        </div>
        <button onclick="openOrderSummary()" class="px-5 py-2.5 rounded-xl bg-goldAccent text-black font-black text-xs uppercase tracking-wider shadow-lg shadow-goldAccent/20">
            Lihat Pesanan & Selesai →
        </button>
    </div>

    {{-- MOBILE BOTTOM ORDER SUMMARY OVERLAY --}}
    <div id="orderSummaryOverlay" class="fixed inset-0 z-50 bg-black/80 backdrop-blur hidden" onclick="closeOrderSummary()"></div>
    <div id="orderSummaryPanel" class="fixed bottom-0 left-0 right-0 z-50 glass-card bg-[#15151c] border-t border-white/15 rounded-t-3xl p-6 translate-y-full transition-transform duration-300 max-h-[85vh] overflow-y-auto">
        <div class="w-10 h-1 bg-white/20 rounded-full mx-auto mb-4"></div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-white text-base">Pesanan Anda</h3>
            <button onclick="closeOrderSummary()" class="text-gray-400 hover:text-white text-sm"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div id="summaryItems" class="space-y-2 mb-4"></div>
        <div class="pt-3 border-t border-white/10 mb-4">
            <button onclick="closeOrderSummary(); document.getElementById('customerName').focus();" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-goldAccent via-amber-400 to-yellow-300 text-black font-black text-xs uppercase tracking-wider">
                Lanjut Isi Nama & Buat Slip
            </button>
        </div>
        <button onclick="closeOrderSummary()" class="w-full py-2.5 rounded-xl bg-white/5 border border-white/10 text-gray-400 font-bold text-xs">Tutup</button>
    </div>

    <script>
        // ============================================
        // CART STATE
        // ============================================
        let cart = {}; // { menuId: { name, price, qty } }

        function addToCart(id, name, price) {
            if (cart[id]) {
                cart[id].qty += 1;
            } else {
                cart[id] = { name, price, qty: 1 };
            }
            renderCart();
            showAddedToast(name);
        }

        function showAddedToast(itemName) {
            const toast = document.getElementById('addedToast');
            const toastText = document.getElementById('toastItemName');
            if (toast && toastText) {
                toastText.innerText = itemName;
                toast.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
                clearTimeout(window.toastTimer);
                window.toastTimer = setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
                }, 2200);
            }
        }

        function updateQty(id, delta) {
            if (!cart[id]) return;
            cart[id].qty += delta;
            if (cart[id].qty <= 0) {
                delete cart[id];
            }
            renderCart();
        }

        function renderCart() {
            const cartItemsEl = document.getElementById('cartItems');
            const summaryItemsEl = document.getElementById('summaryItems');
            const emptyEl = document.getElementById('emptyCart');
            const totalEl = document.getElementById('cartTotal');
            const badge = document.getElementById('cartBadge');
            const mobileBar = document.getElementById('mobileBottomBar');
            const mobileTotalEl = document.getElementById('mobileBarTotal');
            const mobileCountEl = document.getElementById('mobileBarCount');
            const keys = Object.keys(cart);

            if (keys.length === 0) {
                if (emptyEl) emptyEl.classList.remove('hidden');
                if (totalEl) totalEl.classList.add('hidden');
                if (badge) badge.classList.add('hidden');
                if (mobileBar) mobileBar.classList.add('translate-y-full');
                if (cartItemsEl) {
                    cartItemsEl.innerHTML = '';
                    if (emptyEl) cartItemsEl.appendChild(emptyEl);
                }
                if (summaryItemsEl) {
                    summaryItemsEl.innerHTML = '<p class="text-xs text-gray-500 text-center py-4">Belum ada menu dipilih</p>';
                }
                return;
            }

            if (emptyEl) emptyEl.classList.add('hidden');
            if (totalEl) totalEl.classList.remove('hidden');
            if (mobileBar) mobileBar.classList.remove('translate-y-full');

            let html = '';
            let total = 0;
            let totalQty = 0;

            keys.forEach(id => {
                const item = cart[id];
                const lineTotal = item.price * item.qty;
                total += lineTotal;
                totalQty += item.qty;

                html += `
                <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
                    <div class="flex-1 min-w-0 pr-2">
                        <p class="text-xs font-bold text-white truncate">${item.name}</p>
                        <p class="text-[10px] text-gray-400 font-mono">Rp ${item.price.toLocaleString('id-ID')} × ${item.qty} = <span class="text-goldAccent font-bold">Rp ${lineTotal.toLocaleString('id-ID')}</span></p>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button onclick="updateQty(${id}, -1)" class="qty-btn w-6 h-6 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs flex items-center justify-center font-bold transition-colors">−</button>
                        <span class="text-xs font-bold text-white w-5 text-center font-mono">${item.qty}</span>
                        <button onclick="updateQty(${id}, 1)" class="qty-btn w-6 h-6 rounded-full bg-goldAccent hover:bg-amber-400 text-black text-xs flex items-center justify-center font-bold transition-colors">+</button>
                    </div>
                </div>`;
            });

            if (cartItemsEl) cartItemsEl.innerHTML = html;
            if (summaryItemsEl) summaryItemsEl.innerHTML = html;

            const formattedTotal = 'Rp ' + total.toLocaleString('id-ID');
            const totalElAmount = document.getElementById('totalAmount');
            if (totalElAmount) totalElAmount.innerText = formattedTotal;
            if (mobileTotalEl) mobileTotalEl.innerText = formattedTotal;
            if (mobileCountEl) mobileCountEl.innerText = `${totalQty} Item`;

            if (badge) {
                badge.innerText = totalQty;
                badge.classList.remove('hidden');
            }
        }

        // ============================================
        // CATEGORY FILTER
        // ============================================
        function filterCategory(cat) {
            // Update buttons
            document.querySelectorAll('.cat-btn').forEach(btn => {
                const isActive = btn.dataset.cat === cat;
                btn.classList.toggle('bg-goldAccent', isActive);
                btn.classList.toggle('text-black', isActive);
                btn.classList.toggle('border-goldAccent', isActive);
                btn.classList.toggle('text-gray-400', !isActive);
                btn.classList.toggle('border-white/10', !isActive);
            });

            // Filter cards
            document.querySelectorAll('.menu-card').forEach(card => {
                if (cat === 'all' || card.dataset.cat === cat) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // ============================================
        // SUBMIT ORDER
        // ============================================
        function submitOrder() {
            const name = document.getElementById('customerName').value.trim();
            const notes = document.getElementById('orderNotes').value.trim();
            const keys = Object.keys(cart);

            if (!name) {
                document.getElementById('customerName').focus();
                document.getElementById('customerName').classList.add('border-rose-500');
                alert('Masukkan nama Anda terlebih dahulu.');
                return;
            }
            if (keys.length === 0) {
                alert('Pilih setidaknya satu menu terlebih dahulu.');
                return;
            }

            // Build items array
            const items = keys.map(id => ({
                menu_id: parseInt(id),
                menu_name: cart[id].name,
                unit_price: cart[id].price,
                qty: cart[id].qty,
                line_total: cart[id].price * cart[id].qty,
            }));

            const total = items.reduce((s, i) => s + i.line_total, 0);

            // Submit via fetch
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Memproses...';

            fetch('{{ route("nucomu.order.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ customer_name: name, notes, items, total })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.slip_url;
                } else {
                    alert(data.message || 'Terjadi kesalahan.');
                    document.getElementById('submitBtn').disabled = false;
                    document.getElementById('submitBtn').innerHTML = '<i class="fa-solid fa-qrcode mr-2"></i> Buat Slip Pesanan';
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan koneksi.');
                document.getElementById('submitBtn').disabled = false;
                document.getElementById('submitBtn').innerHTML = '<i class="fa-solid fa-qrcode mr-2"></i> Buat Slip Pesanan';
            });
        }

        // ============================================
        // MOBILE PANEL
        // ============================================
        function openOrderSummary() {
            document.getElementById('orderSummaryOverlay').classList.remove('hidden');
            document.getElementById('orderSummaryPanel').classList.remove('translate-y-full');
        }

        function closeOrderSummary() {
            document.getElementById('orderSummaryOverlay').classList.add('hidden');
            document.getElementById('orderSummaryPanel').classList.add('translate-y-full');
        }

        // Remove red border on name input
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('customerName').addEventListener('input', function() {
                this.classList.remove('border-rose-500');
            });
        });
    </script>
</body>
</html>
