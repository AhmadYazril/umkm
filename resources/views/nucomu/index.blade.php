<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nucomu Cafe - Story &amp; Review Experience</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,600;1,700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        warmIvory: '#F9F9F9',
                        pureWhite: '#FFFFFF',
                        inkBlack: '#191919',
                        charcoal: '#252525',
                        warmStone: '#E5E5E5'
                    },
                    fontFamily: {
                        sans: ['"Montserrat"', 'sans-serif'],
                        editorial: ['"Playfair Display"', 'Georgia', 'serif'],
                        body: ['"Montserrat"', 'sans-serif'],
                    },
                    borderRadius: {
                        'story': '2rem',
                        'super': '2.5rem'
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --bg-page: #0C0C0C;
            --bg-section-alt: #101010;
            --surface: rgba(255, 255, 255, 0.04);
            --surface-strong: rgba(255, 255, 255, 0.07);
            --border: rgba(255, 255, 255, 0.14);
            --border-soft: rgba(255, 255, 255, 0.08);
            --text: #F5F5F5;
            --text-muted: #B5B5B5;
            --text-faint: #8F8F8F;
            --accent-solid: #F5F2EE;
            --btn-outline: rgba(255, 255, 255, 0.50);
            --field-bg: #0F0F0F;
            --field-border: #434343;
            --glow: radial-gradient(60% 60% at 50% 40%, rgba(190, 172, 182, 0.18), transparent 70%);
            --shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0C0C0C; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 9999px; }

        .nu-glass-card,
        .glass-card {
            background: var(--surface);
            border: 1px solid var(--border);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: var(--shadow);
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
<body class="bg-[#0C0C0C] text-[#F5F5F5] font-sans selection:bg-[#F5F2EE] selection:text-[#111111] min-h-screen relative overflow-x-hidden">

    {{-- HEADER / NAVIGATION --}}
    <header class="sticky top-0 z-50 bg-[#0C0C0C]/85 backdrop-blur-md border-b border-[var(--border-soft)] px-6 py-4">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-[var(--surface)] border border-[var(--border)] p-0.5 shadow-2xs group-hover:scale-105 transition-transform duration-300">
                    <div class="w-full h-full bg-[#141414] rounded-[14px] flex items-center justify-center">
                        <i class="fa-solid fa-mug-hot text-[#F5F2EE] text-lg"></i>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-bold tracking-tight text-[#F5F5F5] font-editorial">NUCOMU</span>
                    <span class="text-[9px] uppercase tracking-widest text-[#B5B5B5] font-semibold -mt-1 font-body">Coffee &amp; Stories</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-xs font-semibold text-[#B5B5B5] uppercase tracking-wider font-body">
                <a href="#gallery" class="hover:text-[#F5F5F5] transition-colors">Galeri Story</a>
                <a href="#reviews" class="hover:text-[#F5F5F5] transition-colors">Ulasan Pelanggan</a>
                <a href="{{ route('nucomu.order') }}" class="hover:text-[#F5F5F5] transition-colors">
                    <i class="fa-solid fa-utensils mr-1 text-[#F5F2EE]"></i> Pesan Menu
                </a>
            </nav>

            <button onclick="openReviewModal()" class="px-5 py-2.5 rounded-full font-bold text-xs uppercase tracking-wider text-[#111111] bg-[#F5F2EE] hover:bg-white shadow-xs hover:scale-105 active:scale-95 transition-all font-body">
                <i class="fa-solid fa-pen-nib mr-1.5"></i> Tulis Ulasan
            </button>
        </div>
    </header>

    {{-- HERO --}}
    <section class="pt-10 pb-6 px-6 text-center relative z-10 bg-[var(--bg-page)]">
        <div class="max-w-4xl mx-auto flex flex-col items-center">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[var(--surface)] border border-[var(--border)] text-[11px] font-semibold text-[#F5F5F5] shadow-2xs font-body mb-3">
                <span class="w-2 h-2 rounded-full bg-[#F5F2EE] animate-pulse"></span> Open Daily: 08:00 - 23:00 WIB
            </div>
            <h1 class="text-3xl sm:text-5xl font-bold tracking-tight leading-tight text-[#F5F5F5] font-editorial mx-auto">
                Suasana, Rasa, &amp; 
                <span>Cerita Nucomu Cafe</span>
            </h1>
            <p class="text-[#B5B5B5] text-xs sm:text-sm font-body max-w-3xl mx-auto mt-3.5 leading-relaxed">Nikmati momen harian dan ulasan pengunjung yang berganti otomatis.</p>
        </div>
    </section>

    {{-- 1. GALLERY SHOWCASE (3-PHOTO STUDIO DISPLAY) --}}
    @include('home.sections.gallery')

    {{-- CTA Pesan Menu --}}
    <section class="py-4 px-4 relative z-10 bg-[var(--bg-page)]">
        <div class="max-w-3xl mx-auto">
            <a href="{{ route('nucomu.order') }}"
               class="nu-glass-card flex items-center justify-between w-full px-6 py-4 rounded-2xl bg-[var(--surface)] border border-[var(--border)] hover:border-white/30 shadow-2xs hover:shadow-xs transition-all group font-body">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white/10 border border-[var(--border)] flex items-center justify-center text-[#F5F2EE]">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                    <div>
                        <span class="font-bold text-[#F5F5F5] block text-sm">Pesan Menu Sekarang</span>
                        <span class="text-xs text-[#B5B5B5]">Pilih menu, isi nama — tampilkan ke kasir</span>
                    </div>
                </div>
                <i class="fa-solid fa-arrow-right text-[#F5F2EE] group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </section>

    {{-- 2. REVIEWS (UNIFIED MIXED-MONOCHROME INTERACTIVE ANIMATIONS) --}}
    @include('home.sections.reviews', ['dynamicReviews' => $reviews])

    {{-- MODAL ULASAN (MONOCHROME) --}}
    <div id="reviewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300" onclick="closeReviewModal()">
        <div class="bg-[#121212] w-full max-w-md rounded-3xl p-6 sm:p-8 border border-[var(--border)] shadow-[var(--shadow)] relative transform scale-95 transition-transform duration-300 max-h-[92vh] overflow-y-auto font-body" id="modalContent" onclick="event.stopPropagation()">
            <button onclick="closeReviewModal()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-white/10 border border-[var(--border)] flex items-center justify-center text-[#F5F5F5] hover:bg-[#F5F2EE] hover:text-[#111111] transition-colors shadow-2xs">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-white/10 border border-[var(--border)] flex items-center justify-center text-[#F5F2EE]">
                    <i class="fa-solid fa-pen-to-square text-base"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-[#F5F5F5]">Tulis Ulasan Nucomu</h3>
                    <p class="text-[11px] text-[#B5B5B5]">Bagikan kesan &amp; foto pengalaman Anda</p>
                </div>
            </div>

            {{-- Flash success --}}
            @if(session('review_success'))
                <div class="mb-4 p-3.5 bg-white/[0.06] border border-[var(--border)] text-[#F5F5F5] rounded-xl text-xs font-medium">
                    <i class="fa-solid fa-circle-check mr-1.5 text-[#F5F2EE]"></i> {{ session('review_success') }}
                </div>
            @endif

            {{-- Validation or upload error --}}
            @if($errors->any() || session('error'))
                <div class="mb-4 p-3.5 bg-red-950/60 border border-red-400/30 text-red-200 rounded-xl text-xs space-y-1">
                    <div class="font-bold flex items-center gap-1.5 text-red-200">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>{{ session('error') ? 'Terjadi kesalahan unggah:' : 'Terdapat kesalahan formulir:' }}</span>
                    </div>
                    @if(session('error'))
                        <p class="leading-relaxed">{{ session('error') }}</p>
                    @endif
                    @if($errors->any())
                        <ul class="list-disc list-inside space-y-0.5 pl-2 text-[11px]">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            @php
                $reviewLimits = \App\Support\FileUploadHelper::getEffectiveLimit(5 * 1024 * 1024, 'foto ulasan');
            @endphp

            <form action="{{ route('nucomu.review.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#F5F5F5] mb-1.5">Nama Lengkap *</label>
                    <input type="text" name="name" required placeholder="Nama Anda"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--field-bg)] border border-[var(--field-border)] text-xs text-[#F5F5F5] placeholder-[#8F8F8F] focus:outline-none focus:border-[#F5F5F5] focus:ring-1 focus:ring-white/25 transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#F5F5F5] mb-1.5">Status / Info Singkat</label>
                    <input type="text" name="handle" placeholder="Contoh: Local Guide / Pengunjung"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--field-bg)] border border-[var(--field-border)] text-xs text-[#F5F5F5] placeholder-[#8F8F8F] focus:outline-none focus:border-[#F5F5F5] focus:ring-1 focus:ring-white/25 transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#F5F5F5] mb-1.5">Pesanan Anda</label>
                    <input type="text" name="order_tag" placeholder="Misal: Cheesecake & Nucomu Coffee"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-[var(--field-bg)] border border-[var(--field-border)] text-xs text-[#F5F5F5] placeholder-[#8F8F8F] focus:outline-none focus:border-[#F5F5F5] focus:ring-1 focus:ring-white/25 transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#F5F5F5] mb-1.5">Rating Bintang</label>
                    <div class="flex gap-2 text-lg text-[#434343]" id="starRating">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-solid fa-star cursor-pointer hover:text-[#F5F2EE] transition-colors text-[#F5F2EE]" onclick="setRating({{ $i }})"></i>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="5">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#F5F5F5] mb-1.5">Ulasan Singkat *</label>
                    <textarea name="content" rows="3" required placeholder="Tuliskan kesan rasa kopi, dessert, dan suasana di Nucomu Cafe..."
                        class="w-full p-3 rounded-xl bg-[var(--field-bg)] border border-[var(--field-border)] text-xs text-[#F5F5F5] placeholder-[#8F8F8F] focus:outline-none focus:border-[#F5F5F5] focus:ring-1 focus:ring-white/25 transition-colors resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#F5F5F5] mb-1.5">Upload Foto (Opsional)</label>
                    <label class="flex items-center justify-center gap-2 w-full px-3.5 py-2.5 rounded-xl bg-[var(--surface)] hover:bg-[var(--surface-strong)] border border-dashed border-[var(--border)] hover:border-white/40 text-xs text-[#B5B5B5] cursor-pointer transition-colors">
                        <i class="fa-solid fa-camera text-[#F5F2EE]"></i>
                        <span id="nucomuReviewImageLabel">Pilih foto dari perangkat Anda...</span>
                        <input type="file" name="review_image" id="reviewImageInput" accept=".jpg,.jpeg,.png,.webp"
                               data-max-bytes="{{ $reviewLimits['effective_bytes'] ?? $reviewLimits['limit_bytes'] ?? 5242880 }}"
                               data-allowed-ext="jpg,jpeg,png,webp"
                               data-feedback-target="#reviewImageFeedback"
                               class="hidden" onchange="if(this.files && this.files[0]) document.getElementById('nucomuReviewImageLabel').innerText = this.files[0].name;">
                    </label>
                    <div id="reviewImageFeedback" class="mt-1 text-left"></div>
                    <p class="text-[10.5px] text-[#8F8F8F] mt-1">Format: JPG, PNG, WebP &bull; Maksimal {{ $reviewLimits['formatted'] ?? $reviewLimits['limit_formatted'] ?? '5 MB' }}.</p>
                    @if(!empty($reviewLimits['is_throttled'] ?? $reviewLimits['is_limited_by_server']))
                        <p class="text-[10px] text-amber-300 font-medium mt-0.5">
                            <i class="fa-solid fa-triangle-exclamation text-amber-300"></i> Batas server PHP saat ini ({{ $reviewLimits['server_limit_formatted'] ?? '2 MB' }}) lebih kecil dari batas aplikasi (5 MB).
                        </p>
                    @endif
                </div>

                <button type="submit" class="w-full py-3.5 rounded-full bg-[#F5F2EE] hover:bg-white text-[#111111] font-bold text-xs uppercase tracking-wider transition-all duration-200 shadow-md active:scale-95">
                    Kirim Ulasan Sekarang
                </button>
            </form>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="mt-12 border-t border-[var(--border-soft)] py-8 px-6 text-center text-xs text-[#8F8F8F] relative z-10 font-body">
        <p>© {{ date('Y') }} Nucomu Cafe. Crafted for coffee lovers.</p>
        <div class="mt-3 flex items-center justify-center gap-6">
            <a href="{{ route('home') }}" class="hover:text-[#F5F5F5] transition-colors">← Kembali ke Beranda</a>
            <a href="{{ route('nucomu.order') }}" class="hover:text-[#F5F5F5] transition-colors">Pesan Menu</a>
        </div>
    </footer>

    <script>
        // ============================================
        // DATA FROM SERVER (PHP → JS)
        // ============================================
        const stories = @json($stories);
        const reviews = @json($reviews);

        // ============================================
        // 2. REVIEWS
        // ============================================
        let reviewIndex = 0;
        let reviewTimer = null;
        let isReviewPaused = false;

        function updateReviewUI() {
            if (!reviews.length || !document.getElementById('reviewName')) return;
            const r = reviews[reviewIndex];
            document.getElementById('reviewName').innerText = r.name;
            document.getElementById('reviewHandle').innerText = r.handle || 'Pengunjung Nucomu';
            document.getElementById('reviewAvatar').src = r.avatar_url || DEFAULT_AVATAR;
            document.getElementById('reviewText').innerText = '"' + r.content + '"';
            document.getElementById('reviewOrderTag').innerHTML = '<i class="fa-solid fa-mug-saucer mr-1.5 text-[#F5F2EE]"></i> ' + (r.order_tag || 'Menu Spesial');

            const photoWrap = document.getElementById('reviewPhotoWrap');
            const photoImg = document.getElementById('reviewPhotoImg');
            if (photoWrap && photoImg) {
                if (r.review_image) {
                    photoImg.src = r.review_image;
                    photoWrap.classList.remove('hidden');
                } else {
                    photoWrap.classList.add('hidden');
                }
            }

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
                if (i < rating) {
                    stars[i].classList.add('text-[#F5F2EE]');
                    stars[i].classList.remove('text-[#434343]');
                } else {
                    stars[i].classList.remove('text-[#F5F2EE]');
                    stars[i].classList.add('text-[#434343]');
                }
            }
        }

        let isNucomuReviewsExpanded = false;
        function toggleNucomuReviews() {
            const extraCards = document.querySelectorAll('#nucomuReviewsGrid .extra-nucomu-review');
            const btnText = document.getElementById('toggleNucomuReviewsText');
            const chevron = document.getElementById('toggleNucomuReviewsChevron');
            isNucomuReviewsExpanded = !isNucomuReviewsExpanded;

            extraCards.forEach(card => {
                card.classList.toggle('hidden', !isNucomuReviewsExpanded);
            });

            if (btnText) {
                btnText.innerText = isNucomuReviewsExpanded
                    ? 'Sembunyikan Sebagian (Show Less)'
                    : `View All Ulasan (${reviews ? reviews.length : extraCards.length + 3} Ulasan)`;
            }
            if (chevron) {
                chevron.classList.toggle('rotate-180', isNucomuReviewsExpanded);
            }
        }

        // Init
        window.onload = function () {
            updateReviewUI();
            startReviewLoop();
            setRating(5);

            // Auto-open modal if review_success flash or errors were set
            @if(session('review_success') || $errors->any() || session('error'))
                openReviewModal();
            @endif
        };
    </script>
</body>
</html>