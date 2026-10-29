<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nucomu Cafe - Story &amp; Review Experience</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Manrope:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        warmIvory: '#FAF8F5',
                        pureWhite: '#FFFFFF',
                        inkBlack: '#191919',
                        charcoal: '#30302D',
                        warmStone: '#E5E5E5'
                    },
                    fontFamily: {
                        sans: ['Manrope', 'Plus Jakarta Sans', 'sans-serif'],
                        editorial: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                        body: ['Manrope', 'sans-serif'],
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
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #FAF8F5; }
        ::-webkit-scrollbar-thumb { background: #DAD7CF; border-radius: 9999px; }

        .glass-card {
            background: #FFFFFF;
            border: 1px solid #E5E5E5;
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
<body class="bg-[#FAF8F5] text-[#1C1C1C] font-sans selection:bg-[#1C1C1C] selection:text-white min-h-screen relative overflow-x-hidden">

    {{-- HEADER / NAVIGATION --}}
    <header class="sticky top-0 z-50 bg-[#FAF8F5]/95 backdrop-blur-md border-b border-[#E5E5E5] px-6 py-4">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl bg-[#F5F5F5] border border-[#E5E5E5] p-0.5 shadow-2xs group-hover:scale-105 transition-transform duration-300">
                    <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center">
                        <i class="fa-solid fa-mug-hot text-[#1C1C1C] text-lg"></i>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-bold tracking-tight text-[#1C1C1C] font-editorial">NUCOMU</span>
                    <span class="text-[9px] uppercase tracking-widest text-[#8A8A8A] font-semibold -mt-1 font-body">Coffee &amp; Stories</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-xs font-semibold text-[#666666] uppercase tracking-wider font-body">
                <a href="#gallery" class="hover:text-[#1C1C1C] transition-colors">Galeri Story</a>
                <a href="#reviews" class="hover:text-[#1C1C1C] transition-colors">Ulasan Pelanggan</a>
                <a href="{{ route('nucomu.order') }}" class="hover:text-[#1C1C1C] transition-colors">
                    <i class="fa-solid fa-utensils mr-1 text-[#1C1C1C]"></i> Pesan Menu
                </a>
            </nav>

            <button onclick="openReviewModal()" class="px-5 py-2.5 rounded-full font-bold text-xs uppercase tracking-wider text-white bg-[#1C1C1C] hover:bg-black shadow-xs hover:scale-105 active:scale-95 transition-all font-body">
                <i class="fa-solid fa-pen-nib mr-1.5"></i> Tulis Ulasan
            </button>
        </div>
    </header>

    {{-- HERO --}}
    <section class="pt-10 pb-6 px-6 text-center relative z-10">
        <div class="max-w-2xl mx-auto space-y-3">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-white border border-[#E5E5E5] text-[11px] font-semibold text-[#1C1C1C] shadow-2xs font-body">
                <span class="w-2 h-2 rounded-full bg-[#1C1C1C] animate-pulse"></span> Open Daily: 08:00 - 23:00 WIB
            </div>
            <h1 class="text-3xl sm:text-5xl font-semibold tracking-tight leading-tight text-[#1C1C1C] font-editorial">
                Suasana, Rasa, &amp; <br>
                <span>Cerita Nucomu Cafe</span>
            </h1>
            <p class="text-[#666666] text-xs sm:text-sm font-body">Nikmati momen harian dan ulasan pengunjung yang berganti otomatis.</p>
        </div>
    </section>

    {{-- 1. GALLERY STORY --}}
    <section id="gallery" class="py-6 px-4 relative z-10">
        <div class="max-w-3xl mx-auto">
            <div class="flex items-center justify-between mb-3 px-1">
                <div class="flex items-center gap-2.5">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#1C1C1C] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#1C1C1C]"></span>
                    </span>
                    <span class="text-[11px] font-semibold text-[#8A8A8A] uppercase tracking-[0.22em] font-body">04 / LIVE STORY FEED</span>
                </div>
                <span id="storyStatusTag" class="text-[10px] sm:text-[11px] font-medium text-[#777777] bg-white px-3.5 py-1 rounded-full border border-[#E5E5E5] shadow-2xs font-body">
                    Otomatis Berganti • Hover untuk Pause
                </span>
            </div>

            <div class="relative group/story-outer">
                <div class="absolute inset-0 bg-[#E8E8E8] rounded-[2.1rem] pointer-events-none z-0 transform translate-y-1 sm:translate-y-1.5 opacity-60" aria-hidden="true"></div>

                <div id="storyContainer"
                     class="wide-story-frame w-full rounded-story relative z-10 overflow-hidden bg-neutral-900 shadow-md border border-[#E5E5E5] cursor-pointer group select-none transition-transform duration-300"
                     onmouseenter="pauseStory()" onmouseleave="resumeStory()">

                    <img id="storyImage" src="" alt="Gallery Image" class="w-full h-full object-cover transition-all duration-700 ease-in-out transform scale-100 group-hover:scale-105">

                    <div class="absolute inset-0 bg-gradient-to-b from-black/65 via-black/15 to-black/85 pointer-events-none"></div>

                    <div class="absolute top-0 left-0 right-0 p-5 sm:p-6 z-20 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full p-0.5 bg-white/40 backdrop-blur-md shadow-md border border-white/50">
                                <img id="storyAvatar" src="" class="w-full h-full rounded-full object-cover">
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <span id="storyAuthor" class="text-xs sm:text-sm font-semibold text-white drop-shadow-sm font-body">Barista Corner</span>
                                    <i class="fa-solid fa-circle-check text-white/90 text-xs"></i>
                                </div>
                                <span id="storyTime" class="text-[10px] sm:text-xs text-neutral-300 drop-shadow-sm font-body">Baru saja</span>
                            </div>
                        </div>
                        <span id="storyCategoryBadge" class="text-[10px] sm:text-xs font-semibold uppercase tracking-widest px-3.5 py-1.5 rounded-full bg-[#1C1C1C]/80 backdrop-blur-md border border-white/20 text-white shadow-md font-body">#CraftCoffee</span>
                    </div>

                    <div class="absolute inset-0 z-10 flex">
                        <div class="w-1/3 h-full" onclick="prevStory(event)"></div>
                        <div class="w-2/3 h-full" onclick="nextStory(event)"></div>
                    </div>

                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 z-20 space-y-2 pointer-events-none">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#1C1C1C]/80 backdrop-blur-md border border-white/20 text-[10px] sm:text-xs font-semibold text-white font-body">
                            <i class="fa-solid fa-location-dot text-neutral-300"></i> Nucomu Main Bar
                        </div>
                        <h3 id="storyTitle" class="text-xl sm:text-2xl lg:text-3xl font-bold text-white leading-tight drop-shadow font-editorial">Loading Story...</h3>
                        <p id="storyDesc" class="text-xs sm:text-sm text-neutral-200 leading-relaxed drop-shadow max-w-xl font-body">Please wait...</p>
                    </div>

                    <div class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/90 hover:bg-white backdrop-blur-md shadow-lg border border-white/50 flex items-center justify-center text-[#1C1C1C] opacity-0 group-hover:opacity-100 transition-all pointer-events-none">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </div>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/90 hover:bg-white backdrop-blur-md shadow-lg border border-white/50 flex items-center justify-center text-[#1C1C1C] opacity-0 group-hover:opacity-100 transition-all pointer-events-none">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Pesan Menu --}}
    <section class="py-4 px-4 relative z-10">
        <div class="max-w-3xl mx-auto">
            <a href="{{ route('nucomu.order') }}"
               class="flex items-center justify-between w-full px-6 py-4 rounded-2xl bg-white border border-[#E5E5E5] hover:border-[#1C1C1C]/40 shadow-2xs hover:shadow-xs transition-all group font-body">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-[#F5F5F5] border border-[#E5E5E5] flex items-center justify-center text-[#1C1C1C]">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                    <div>
                        <span class="font-bold text-[#1C1C1C] block text-sm">Pesan Menu Sekarang</span>
                        <span class="text-xs text-[#666666]">Pilih menu, isi nama — tampilkan ke kasir</span>
                    </div>
                </div>
                <i class="fa-solid fa-arrow-right text-[#1C1C1C] group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </section>

    {{-- 2. REVIEWS --}}
    <section id="reviews" class="py-12 px-4 relative z-10">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-8 space-y-2">
                <div class="flex items-center justify-center gap-3">
                    <span class="text-xs sm:text-[13px] font-bold text-[#666666] uppercase tracking-[0.22em] font-body">05 / ULASAN PENGUNJUNG</span>
                    <div class="w-10 h-px bg-[#DDDDDD]" aria-hidden="true"></div>
                </div>
                <h2 class="text-2xl sm:text-3xl font-semibold text-[#1C1C1C] font-editorial">Kata Mereka Tentang Nucomu</h2>
                <p class="text-xs text-[#666666] font-body">Pengalaman langsung dari para pengunjung Nucomu Cafe Tulungagung.</p>
            </div>

            <div class="max-w-3xl mx-auto mb-8">
                <div id="reviewContainer"
                     class="w-full bg-white rounded-story p-6 sm:p-10 relative overflow-hidden border border-[#E5E5E5] shadow-sm transition-all duration-500 font-body"
                     onmouseenter="pauseReview()" onmouseleave="resumeReview()">

                    <div class="space-y-6 relative z-10">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <img id="reviewAvatar" src="" class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover border-2 border-[#E5E5E5] shadow-xs bg-neutral-100">
                                <div>
                                    <h4 id="reviewName" class="text-base sm:text-lg font-bold text-[#1C1C1C]">Loading...</h4>
                                    <p id="reviewHandle" class="text-xs text-[#8A8A8A] font-medium">Local Guide</p>
                                </div>
                            </div>
                            <div id="reviewStars" class="flex text-[#1C1C1C] text-sm sm:text-base gap-1 bg-[#F5F5F5] px-3.5 py-1.5 rounded-full border border-[#E5E5E5]"></div>
                        </div>

                        <div class="relative py-2">
                            <i class="fa-solid fa-quote-left text-3xl sm:text-4xl text-[#E5E5E5] absolute -top-4 -left-2"></i>
                            <p id="reviewText" class="text-sm sm:text-base text-[#1C1C1C] leading-relaxed italic relative z-10 pl-3 sm:pl-4">"Ulasan..."</p>
                        </div>

                        <div id="reviewPhotoWrap" class="hidden rounded-2xl overflow-hidden border border-[#E5E5E5] max-h-52 bg-neutral-100">
                            <img id="reviewPhotoImg" src="" alt="Foto Ulasan" class="w-full h-52 object-cover">
                        </div>

                        <div class="pt-4 border-t border-[#E5E5E5] flex items-center justify-between text-xs sm:text-sm">
                            <span class="text-[#8A8A8A] text-xs">Pesanan Favorit:</span>
                            <span id="reviewOrderTag" class="font-semibold text-[#1C1C1C] bg-[#F5F5F5] border border-[#E5E5E5] px-3.5 py-1.5 rounded-full text-xs sm:text-sm">
                                <i class="fa-solid fa-mug-saucer mr-1.5 text-[#1C1C1C]"></i> Special Coffee
                            </span>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <button onclick="openReviewModal()" class="text-xs sm:text-sm font-semibold text-[#1C1C1C] hover:text-black transition-colors inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white border border-[#E5E5E5] hover:border-[#1C1C1C] shadow-2xs font-body">
                        <i class="fa-solid fa-plus-circle text-[#1C1C1C]"></i> Tambah ulasan pengalaman Anda
                    </button>
                </div>
            </div>

            <div id="nucomuReviewsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 font-body">
                @foreach($reviews as $rev)
                    @php
                        $cardImages = !empty($rev['review_images']) && is_array($rev['review_images'])
                            ? array_slice($rev['review_images'], 0, 4)
                            : (!empty($rev['review_image']) ? [$rev['review_image']] : []);
                        $isHiddenInitially = $loop->index >= 3;
                    @endphp
                    <div class="bg-white rounded-2xl p-5 border border-[#E5E5E5] shadow-2xs hover:shadow-xs transition-all flex flex-col justify-between {{ $isHiddenInitially ? 'extra-nucomu-review hidden' : '' }}">
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $rev['avatar_url'] ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80' }}" referrerpolicy="no-referrer" class="w-10 h-10 rounded-full object-cover border border-[#E5E5E5] bg-neutral-100">
                                    <div>
                                        <h5 class="text-sm font-bold text-[#1C1C1C]">{{ $rev['name'] }}</h5>
                                        <span class="text-[11px] text-[#8A8A8A] block">{{ $rev['handle'] ?? 'Pengunjung Nucomu' }}</span>
                                    </div>
                                </div>
                                <span class="text-[10px] text-[#8A8A8A]">{{ $rev['time_ago'] ?? '' }}</span>
                            </div>
                            <div class="flex items-center gap-1 text-[#1C1C1C] text-xs mb-2">
                                @for($i = 0; $i < ($rev['rating'] ?? 5); $i++)
                                    <i class="fa-solid fa-star"></i>
                                @endfor
                            </div>
                            <p class="text-xs text-[#555555] leading-relaxed mb-3 whitespace-pre-line">{{ $rev['content'] }}</p>
                            @if(count($cardImages) > 1)
                                <div class="grid grid-cols-2 gap-1.5 mb-3 rounded-xl overflow-hidden border border-[#E5E5E5]">
                                    @foreach($cardImages as $imgUrl)
                                        <div class="aspect-[4/3] bg-neutral-100 overflow-hidden">
                                            <img src="{{ $imgUrl }}" alt="Foto ulasan {{ $rev['name'] }}" referrerpolicy="no-referrer" loading="lazy" class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            @elseif(count($cardImages) === 1)
                                <div class="mb-3 rounded-xl overflow-hidden border border-[#E5E5E5] h-36 bg-neutral-100">
                                    <img src="{{ $cardImages[0] }}" alt="Foto ulasan {{ $rev['name'] }}" referrerpolicy="no-referrer" loading="lazy" class="w-full h-full object-cover">
                                </div>
                            @endif
                        </div>
                        <div class="pt-2.5 border-t border-[#E5E5E5] flex items-center justify-between text-[11px]">
                            <span class="text-[#8A8A8A]">Favorit:</span>
                            <span class="text-[#1C1C1C] font-medium bg-[#F5F5F5] border border-[#E5E5E5] px-2.5 py-0.5 rounded-full">
                                <i class="fa-solid fa-mug-saucer mr-1 text-[10px] text-[#1C1C1C]"></i> {{ $rev['order_tag'] ?? 'Menu Nucomu' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(count($reviews) > 3)
                <div class="text-center mt-6">
                    <button type="button"
                            id="toggleNucomuReviewsBtn"
                            onclick="toggleNucomuReviews()"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-white hover:bg-[#1C1C1C] hover:text-white border border-[#1C1C1C] text-xs sm:text-sm font-bold text-[#1C1C1C] shadow-2xs hover:shadow-xs transition-all font-body">
                        <i class="fa-solid fa-layer-group"></i>
                        <span id="toggleNucomuReviewsText">View All Ulasan ({{ count($reviews) }} Ulasan)</span>
                        <i id="toggleNucomuReviewsChevron" class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>
                </div>
            @endif
        </div>
    </section>

    {{-- MODAL ULASAN (MONOCHROME) --}}
    <div id="reviewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#1C1C1C]/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300" onclick="closeReviewModal()">
        <div class="bg-[#FAFAFA] w-full max-w-md rounded-3xl p-6 sm:p-8 border border-[#E5E5E5] shadow-2xl relative transform scale-95 transition-transform duration-300 max-h-[92vh] overflow-y-auto font-body" id="modalContent" onclick="event.stopPropagation()">
            <button onclick="closeReviewModal()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-white border border-[#E5E5E5] flex items-center justify-center text-[#1C1C1C] hover:bg-[#1C1C1C] hover:text-white transition-colors shadow-2xs">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>

            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-[#F5F5F5] border border-[#E5E5E5] flex items-center justify-center text-[#1C1C1C]">
                    <i class="fa-solid fa-pen-to-square text-base"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-[#1C1C1C]">Tulis Ulasan Nucomu</h3>
                    <p class="text-[11px] text-[#666666]">Bagikan kesan &amp; foto pengalaman Anda</p>
                </div>
            </div>

            {{-- Flash success --}}
            @if(session('review_success'))
                <div class="mb-4 p-3.5 bg-white border border-[#E5E5E5] text-[#1C1C1C] rounded-xl text-xs font-medium">
                    <i class="fa-solid fa-circle-check mr-1.5 text-[#1C1C1C]"></i> {{ session('review_success') }}
                </div>
            @endif

            <form action="{{ route('nucomu.review.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
                @csrf
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">Nama Lengkap *</label>
                    <input type="text" name="name" required placeholder="Nama Anda"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E5E5E5] text-xs text-[#1C1C1C] placeholder-[#999999] focus:outline-none focus:border-[#1C1C1C] focus:ring-1 focus:ring-[#1C1C1C] transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">Status / Info Singkat</label>
                    <input type="text" name="handle" placeholder="Contoh: Local Guide / Pengunjung"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E5E5E5] text-xs text-[#1C1C1C] placeholder-[#999999] focus:outline-none focus:border-[#1C1C1C] focus:ring-1 focus:ring-[#1C1C1C] transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">Pesanan Anda</label>
                    <input type="text" name="order_tag" placeholder="Misal: Cheesecake & Nucomu Coffee"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-[#E5E5E5] text-xs text-[#1C1C1C] placeholder-[#999999] focus:outline-none focus:border-[#1C1C1C] focus:ring-1 focus:ring-[#1C1C1C] transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">Rating Bintang</label>
                    <div class="flex gap-2 text-lg text-stone-300" id="starRating">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-solid fa-star cursor-pointer hover:text-[#1C1C1C] transition-colors text-[#1C1C1C]" onclick="setRating({{ $i }})"></i>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="5">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">Ulasan Singkat *</label>
                    <textarea name="content" rows="3" required placeholder="Tuliskan kesan rasa kopi, dessert, dan suasana di Nucomu Cafe..."
                        class="w-full p-3 rounded-xl bg-white border border-[#E5E5E5] text-xs text-[#1C1C1C] placeholder-[#999999] focus:outline-none focus:border-[#1C1C1C] focus:ring-1 focus:ring-[#1C1C1C] transition-colors resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-[#1C1C1C] mb-1.5">Upload Foto (Opsional)</label>
                    <label class="flex items-center justify-center gap-2 w-full px-3.5 py-2.5 rounded-xl bg-white hover:bg-[#F5F5F5] border border-dashed border-[#CCCCCC] hover:border-[#1C1C1C] text-xs text-[#666666] cursor-pointer transition-colors">
                        <i class="fa-solid fa-camera text-[#1C1C1C]"></i>
                        <span id="nucomuReviewImageLabel">Pilih foto dari perangkat Anda...</span>
                        <input type="file" name="review_image" accept="image/*" class="hidden" onchange="if(this.files && this.files[0]) document.getElementById('nucomuReviewImageLabel').innerText = this.files[0].name;">
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-full bg-[#1C1C1C] hover:bg-black text-white font-bold text-xs uppercase tracking-wider transition-all duration-200 shadow-md active:scale-95">
                    Kirim Ulasan Sekarang
                </button>
            </form>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="mt-12 border-t border-[#E5E5E5] py-8 px-6 text-center text-xs text-[#777777] relative z-10 font-body">
        <p>© {{ date('Y') }} Nucomu Cafe. Crafted for coffee lovers.</p>
        <div class="mt-3 flex items-center justify-center gap-6">
            <a href="{{ route('home') }}" class="hover:text-[#1C1C1C] transition-colors">← Kembali ke Beranda</a>
            <a href="{{ route('nucomu.order') }}" class="hover:text-[#1C1C1C] transition-colors">Pesan Menu</a>
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
            const tag = document.getElementById('storyStatusTag');
            if (tag) {
                tag.innerText = 'PAUSED (Di-hold)';
                tag.classList.add('text-[#1C1C1C]', 'border-[#1C1C1C]/40', 'bg-[#F5F5F5]');
            }
        }

        function resumeStory() {
            isStoryPaused = false;
            const tag = document.getElementById('storyStatusTag');
            if (tag) {
                tag.innerText = 'Otomatis Berganti • Hover untuk Pause';
                tag.classList.remove('text-[#1C1C1C]', 'border-[#1C1C1C]/40', 'bg-[#F5F5F5]');
            }
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
            document.getElementById('reviewHandle').innerText = r.handle || 'Pengunjung Nucomu';
            document.getElementById('reviewAvatar').src = r.avatar_url || DEFAULT_AVATAR;
            document.getElementById('reviewText').innerText = '"' + r.content + '"';
            document.getElementById('reviewOrderTag').innerHTML = '<i class="fa-solid fa-mug-saucer mr-1.5 text-[#1C1C1C]"></i> ' + (r.order_tag || 'Menu Spesial');

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
                    stars[i].classList.add('text-[#1C1C1C]');
                    stars[i].classList.remove('text-stone-300');
                } else {
                    stars[i].classList.remove('text-[#1C1C1C]');
                    stars[i].classList.add('text-stone-300');
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