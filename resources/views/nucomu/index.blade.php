<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nucomu Cafe - Story & Review Experience</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        obsidian: '#0c0c0f',
                        charcoal: '#15151c',
                        goldAccent: '#f59e0b',
                        violetAccent: '#8b5cf6',
                        cyanAccent: '#06b6d4',
                        roseAccent: '#f43f5e'
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    borderRadius: {
                        'story': '2rem',
                        'super': '2.5rem'
                    },
                    animation: {
                        'pulse-glow': 'pulseGlow 5s infinite ease-in-out',
                    },
                    keyframes: {
                        pulseGlow: {
                            '0%, 100%': { opacity: '0.25', filter: 'blur(60px)' },
                            '50%': { opacity: '0.6', filter: 'blur(90px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0c0c0f; }
        ::-webkit-scrollbar-thumb { background: #232330; border-radius: 9999px; }

        .glass-card {
            background: rgba(21, 21, 28, 0.75);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .wide-story-frame {
            aspect-ratio: 16 / 10;
            max-height: 480px;
        }

        @media (max-width: 640px) {
            .wide-story-frame {
                aspect-ratio: 4 / 3;
                max-height: 400px;
            }
        }
    </style>
</head>
<body class="bg-obsidian text-gray-100 font-sans selection:bg-goldAccent selection:text-black min-h-screen relative overflow-x-hidden">

    {{-- Ambient Glow Background --}}
    <div class="fixed top-10 left-1/4 w-96 h-96 rounded-full bg-goldAccent/15 pointer-events-none z-0 blur-3xl"></div>
    <div class="fixed top-1/2 right-10 w-[30rem] h-[30rem] rounded-full bg-violetAccent/15 pointer-events-none z-0 blur-3xl"></div>
    <div class="fixed bottom-10 left-10 w-96 h-96 rounded-full bg-cyanAccent/15 pointer-events-none z-0 blur-3xl"></div>

    {{-- HEADER / NAVIGATION --}}
    <header class="sticky top-0 z-50 glass-card border-b border-white/5 px-6 py-4">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-goldAccent via-amber-500 to-rose-500 p-0.5 shadow-lg shadow-goldAccent/20 group-hover:scale-105 transition-transform duration-300">
                    <div class="w-full h-full bg-obsidian rounded-[14px] flex items-center justify-center">
                        <i class="fa-solid fa-mug-hot text-transparent bg-clip-text bg-gradient-to-r from-goldAccent to-rose-400 text-lg"></i>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-extrabold tracking-tight text-white">NUCOMU</span>
                    <span class="text-[9px] uppercase tracking-widest text-goldAccent font-semibold -mt-1">Coffee & Stories</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                <a href="#gallery" class="hover:text-goldAccent transition-colors">Galeri Story</a>
                <a href="#reviews" class="hover:text-goldAccent transition-colors">Ulasan Pelanggan</a>
                <a href="{{ route('nucomu.order') }}" class="hover:text-goldAccent transition-colors">
                    <i class="fa-solid fa-utensils mr-1"></i> Pesan Menu
                </a>
            </nav>

            <button onclick="openReviewModal()" class="px-5 py-2.5 rounded-full font-bold text-xs uppercase tracking-wider text-black bg-gradient-to-r from-goldAccent via-amber-400 to-yellow-300 shadow-lg shadow-goldAccent/20 hover:scale-105 active:scale-95 transition-all">
                <i class="fa-solid fa-pen-nib mr-1.5"></i> Tulis Ulasan
            </button>
        </div>
    </header>

    {{-- HERO --}}
    <section class="pt-10 pb-6 px-6 text-center relative z-10">
        <div class="max-w-2xl mx-auto space-y-3">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white/5 border border-white/10 text-[11px] font-semibold text-goldAccent">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Open Daily: 08:00 - 23:00 WIB
            </div>
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                Suasana, Rasa, & <br>
                <span class="bg-gradient-to-r from-goldAccent via-rose-400 to-violetAccent bg-clip-text text-transparent">Cerita Nucomu Cafe</span>
            </h1>
            <p class="text-gray-400 text-xs sm:text-sm">Nikmati momen harian dan ulasan pengunjung yang berganti otomatis.</p>
        </div>
    </section>

    {{-- 1. GALLERY STORY --}}
    <section id="gallery" class="py-6 px-4 relative z-10">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center justify-between mb-3 px-1">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                    <h2 class="text-xs sm:text-sm font-bold tracking-wider uppercase text-white">Live Story Feed</h2>
                </div>
                <span id="storyStatusTag" class="text-[10px] font-mono text-gray-400 bg-white/5 px-3 py-1 rounded-full border border-white/10">
                    Otomatis Berganti • Hover untuk Pause
                </span>
            </div>

            <div id="storyContainer"
                 class="wide-story-frame w-full rounded-story relative overflow-hidden glass-card shadow-2xl border border-white/15 cursor-pointer group select-none transition-transform duration-300"
                 onmouseenter="pauseStory()" onmouseleave="resumeStory()">

                <img id="storyImage" src="" alt="Gallery Image" class="w-full h-full object-cover transition-all duration-700 ease-in-out transform scale-100 group-hover:scale-105">

                <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-black/90 pointer-events-none"></div>

                <div class="absolute top-0 left-0 right-0 p-5 sm:p-6 z-20 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full p-0.5 bg-gradient-to-tr from-goldAccent via-rose-500 to-violetAccent shadow-lg">
                            <img id="storyAvatar" src="" class="w-full h-full rounded-full object-cover">
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span id="storyAuthor" class="text-xs sm:text-sm font-bold text-white drop-shadow-md">Barista Corner</span>
                                <i class="fa-solid fa-circle-check text-cyan-400 text-xs"></i>
                            </div>
                            <span id="storyTime" class="text-[10px] sm:text-xs text-gray-300 drop-shadow">Baru saja</span>
                        </div>
                    </div>
                    <span id="storyCategoryBadge" class="text-[10px] sm:text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full bg-black/60 backdrop-blur border border-white/20 text-goldAccent shadow-lg">#CraftCoffee</span>
                </div>

                <div class="absolute inset-0 z-10 flex">
                    <div class="w-1/3 h-full" onclick="prevStory(event)"></div>
                    <div class="w-2/3 h-full" onclick="nextStory(event)"></div>
                </div>

                <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 z-20 space-y-2 pointer-events-none">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur border border-white/15 text-[10px] sm:text-xs font-semibold text-white">
                        <i class="fa-solid fa-location-dot text-rose-400"></i> Nucomu Main Bar
                    </div>
                    <h3 id="storyTitle" class="text-lg sm:text-2xl font-black text-white leading-tight drop-shadow-md">Loading Story...</h3>
                    <p id="storyDesc" class="text-xs sm:text-sm text-gray-200 leading-relaxed drop-shadow max-w-xl">Please wait...</p>
                </div>

                <div class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/50 backdrop-blur border border-white/15 flex items-center justify-center text-white/70 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </div>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/50 backdrop-blur border border-white/15 flex items-center justify-center text-white/70 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Pesan Menu --}}
    <section class="py-4 px-4 relative z-10">
        <div class="max-w-3xl mx-auto">
            <a href="{{ route('nucomu.order') }}"
               class="flex items-center justify-between w-full px-6 py-4 rounded-2xl glass-card border border-goldAccent/20 hover:border-goldAccent/50 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-goldAccent/10 border border-goldAccent/20 flex items-center justify-center text-goldAccent">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                    <div>
                        <span class="font-bold text-white block text-sm">Pesan Menu Sekarang</span>
                        <span class="text-xs text-gray-400">Pilih menu, isi nama — tampilkan ke kasir</span>
                    </div>
                </div>
                <i class="fa-solid fa-arrow-right text-goldAccent group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </section>

    {{-- 2. REVIEWS --}}
    <section id="reviews" class="py-12 px-4 relative z-10">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-6 space-y-1">
                <div class="inline-flex items-center gap-1.5 text-goldAccent text-xs font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-star"></i> Ulasan Pengunjung
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold text-white">Kata Mereka Tentang Nucomu</h2>
            </div>

            <div id="reviewContainer"
                 class="w-full glass-card rounded-story p-6 sm:p-10 relative overflow-hidden border border-white/15 shadow-2xl transition-all duration-500"
                 onmouseenter="pauseReview()" onmouseleave="resumeReview()">

                <div class="space-y-6 relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <img id="reviewAvatar" src="" class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover border-2 border-goldAccent/40 shadow-xl">
                            <div>
                                <h4 id="reviewName" class="text-base sm:text-lg font-bold text-white">Loading...</h4>
                                <p id="reviewHandle" class="text-xs text-goldAccent font-mono">@user</p>
                            </div>
                        </div>
                        <div id="reviewStars" class="flex text-goldAccent text-sm sm:text-base gap-1 bg-white/5 px-3 py-1.5 rounded-full border border-white/10"></div>
                    </div>

                    <div class="relative py-2">
                        <i class="fa-solid fa-quote-left text-4xl text-white/10 absolute -top-4 -left-2"></i>
                        <p id="reviewText" class="text-sm sm:text-base text-gray-200 leading-relaxed italic relative z-10 pl-3 sm:pl-4">"Ulasan..."</p>
                    </div>

                    <div class="pt-4 border-t border-white/10 flex items-center justify-between text-xs sm:text-sm">
                        <span class="text-gray-400 text-xs">Pesanan Favorit:</span>
                        <span id="reviewOrderTag" class="font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-3.5 py-1.5 rounded-full text-xs sm:text-sm">
                            <i class="fa-solid fa-mug-saucer mr-1.5"></i> Special Coffee
                        </span>
                    </div>
                </div>

                <div class="absolute -bottom-10 -right-10 w-48 h-48 rounded-full bg-goldAccent/10 blur-3xl pointer-events-none"></div>
            </div>

            <div class="text-center mt-6">
                <button onclick="openReviewModal()" class="text-xs sm:text-sm font-semibold text-goldAccent hover:text-amber-300 transition-colors inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-goldAccent/20 hover:border-goldAccent">
                    <i class="fa-solid fa-plus-circle"></i> Tambah ulasan pengalaman Anda
                </button>
            </div>
        </div>
    </section>

    {{-- MODAL ULASAN --}}
    <div id="reviewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="glass-card w-full max-w-md rounded-3xl p-6 sm:p-8 border border-white/15 shadow-2xl relative transform scale-95 transition-transform duration-300" id="modalContent">
            <button onclick="closeReviewModal()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center text-gray-400 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-goldAccent/10 border border-goldAccent/20 flex items-center justify-center text-goldAccent">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white">Tulis Ulasan Nucomu</h3>
                    <p class="text-[11px] text-gray-400">Akan langsung tampil di ulasan pengunjung</p>
                </div>
            </div>

            {{-- Flash success --}}
            @if(session('review_success'))
                <div class="mb-4 p-3 bg-emerald-950/60 border border-emerald-800 text-emerald-300 rounded-xl text-xs">
                    <i class="fa-solid fa-circle-check mr-1.5"></i> {{ session('review_success') }}
                </div>
            @endif

            <form action="{{ route('nucomu.review.store') }}" method="POST" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Nama Anda"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-goldAccent transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Username (@handle)</label>
                    <input type="text" name="handle" placeholder="contoh: @rara_coffeelover"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-goldAccent transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Pesanan Anda</label>
                    <input type="text" name="order_tag" placeholder="Misal: Iced Palm Sugar Latte"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-goldAccent transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Rating Bintang</label>
                    <div class="flex gap-2 text-lg text-gray-600" id="starRating">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-solid fa-star cursor-pointer hover:text-goldAccent transition-colors" onclick="setRating({{ $i }})"></i>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="5">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-300 mb-1">Ulasan Singkat</label>
                    <textarea name="content" rows="3" required placeholder="Tuliskan kesan rasa kopi dan suasana di Nucomu Cafe..."
                        class="w-full bg-black/40 border border-white/10 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-goldAccent transition-colors resize-none"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-goldAccent via-amber-400 to-yellow-300 text-black font-bold text-xs uppercase tracking-wider hover:opacity-90 transition-opacity shadow-md">
                    Kirim Ulasan Sekarang
                </button>
            </form>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="mt-12 border-t border-white/5 py-6 px-6 text-center text-[11px] text-gray-500 relative z-10">
        <p>© {{ date('Y') }} Nucomu Cafe. Crafted for coffee lovers.</p>
        <div class="mt-2 flex items-center justify-center gap-4">
            <a href="{{ route('home') }}" class="hover:text-goldAccent transition-colors">← Kembali ke Beranda</a>
            <a href="{{ route('nucomu.order') }}" class="hover:text-goldAccent transition-colors">Pesan Menu</a>
        </div>
    </footer>

    <script>
        // ============================================
        // DATA FROM SERVER (PHP → JS)
        // ============================================
        const stories = @json($stories);
        const reviews = @json($reviews);

        const DEFAULT_STORY_IMAGE = 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=1200&q=80';
        const DEFAULT_AVATAR = 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80';

        // ============================================
        // 1. GALLERY STORY
        // ============================================
        let storyIndex = 0;
        let storyTimer = null;
        let isStoryPaused = false;

        function updateStoryUI() {
            if (!stories.length) return;
            const s = stories[storyIndex];
            document.getElementById('storyImage').src = s.image || DEFAULT_STORY_IMAGE;
            document.getElementById('storyAvatar').src = s.avatar_url || DEFAULT_AVATAR;
            document.getElementById('storyAuthor').innerText = s.author || 'Barista Corner';
            document.getElementById('storyTime').innerText = s.time_label || 'Baru saja';
            document.getElementById('storyCategoryBadge').innerText = s.category_badge || '#Nucomu';
            document.getElementById('storyTitle').innerText = s.caption || 'Nucomu Story';
            document.getElementById('storyDesc').innerText = s.description || '';
        }

        function startStoryLoop() {
            clearInterval(storyTimer);
            storyTimer = setInterval(() => {
                if (!isStoryPaused && stories.length > 1) {
                    storyIndex = (storyIndex + 1) % stories.length;
                    updateStoryUI();
                }
            }, 4000);
        }

        function nextStory(e) { if (e) e.stopPropagation(); storyIndex = (storyIndex + 1) % stories.length; updateStoryUI(); }
        function prevStory(e) { if (e) e.stopPropagation(); storyIndex = (storyIndex - 1 + stories.length) % stories.length; updateStoryUI(); }

        function pauseStory() {
            isStoryPaused = true;
            document.getElementById('storyStatusTag').innerText = 'PAUSED (Di-hold)';
            document.getElementById('storyStatusTag').classList.add('text-goldAccent');
        }

        function resumeStory() {
            isStoryPaused = false;
            document.getElementById('storyStatusTag').innerText = 'Otomatis Berganti • Hover untuk Pause';
            document.getElementById('storyStatusTag').classList.remove('text-goldAccent');
        }

        // ============================================
        // 2. REVIEWS
        // ============================================
        let reviewIndex = 0;
        let reviewTimer = null;
        let isReviewPaused = false;

        function updateReviewUI() {
            if (!reviews.length) return;
            const r = reviews[reviewIndex];
            document.getElementById('reviewName').innerText = r.name;
            document.getElementById('reviewHandle').innerText = r.handle ? (r.handle.startsWith('@') ? r.handle : '@' + r.handle) : '@pelanggan';
            document.getElementById('reviewAvatar').src = r.avatar_url || DEFAULT_AVATAR;
            document.getElementById('reviewText').innerText = '"' + r.content + '"';
            document.getElementById('reviewOrderTag').innerHTML = '<i class="fa-solid fa-mug-saucer mr-1.5"></i> ' + (r.order_tag || 'Menu Spesial');

            const starContainer = document.getElementById('reviewStars');
            starContainer.innerHTML = '';
            for (let i = 0; i < (r.rating || 5); i++) {
                starContainer.innerHTML += '<i class="fa-solid fa-star"></i>';
            }
        }

        function startReviewLoop() {
            clearInterval(reviewTimer);
            reviewTimer = setInterval(() => {
                if (!isReviewPaused && reviews.length > 1) {
                    reviewIndex = (reviewIndex + 1) % reviews.length;
                    updateReviewUI();
                }
            }, 5000);
        }

        function pauseReview() { isReviewPaused = true; }
        function resumeReview() { isReviewPaused = false; }

        // ============================================
        // 3. MODAL & STAR RATING
        // ============================================
        let currentRating = 5;

        function openReviewModal() {
            const modal = document.getElementById('reviewModal');
            const mc = document.getElementById('modalContent');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            mc.classList.remove('scale-95');
            mc.classList.add('scale-100');
            setRating(5);
        }

        function closeReviewModal() {
            const modal = document.getElementById('reviewModal');
            const mc = document.getElementById('modalContent');
            modal.classList.add('opacity-0', 'pointer-events-none');
            mc.classList.remove('scale-100');
            mc.classList.add('scale-95');
        }

        function setRating(rating) {
            currentRating = rating;
            document.getElementById('ratingInput').value = rating;
            const stars = document.getElementById('starRating').children;
            for (let i = 0; i < stars.length; i++) {
                stars[i].classList.toggle('text-goldAccent', i < rating);
                stars[i].classList.toggle('text-gray-600', i >= rating);
            }
        }

        // Init
        window.onload = function () {
            updateStoryUI();
            startStoryLoop();
            updateReviewUI();
            startReviewLoop();
            setRating(5);

            // Auto-open modal if review_success flash was set
            @if(session('review_success'))
                openReviewModal();
            @endif
        };
    </script>
</body>
</html>
