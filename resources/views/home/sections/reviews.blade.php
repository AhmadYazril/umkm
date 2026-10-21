<!-- SECTION 6: WIDER REVIEWS SECTION (AUTOMATIC LOOP, NO PROGRESS BAR) -->
<section id="reviews" class="py-16 px-4 relative z-10 bg-[#0c0c0f] text-gray-100 overflow-hidden border-t border-white/5">
    <!-- Decorative Ambient Glows -->
    <div class="absolute top-1/2 left-10 w-80 h-80 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-1/4 w-96 h-96 rounded-full bg-rose-500/10 blur-3xl pointer-events-none"></div>

    <div class="max-w-3xl mx-auto relative z-10">
        
        <div class="text-center mb-8 space-y-1">
            <div class="inline-flex items-center gap-1.5 text-amber-400 text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-star"></i> Ulasan Pengunjung
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Kata Mereka Tentang Nucomu</h2>
            <p class="text-gray-400 text-xs sm:text-sm">Pengalaman langsung dari pelanggan yang bersantai di Nucomu Cafe.</p>
        </div>

        <!-- Single Frame Review Container -->
        <div id="homeReviewContainer" 
             class="w-full bg-[#15151c]/80 backdrop-blur-xl rounded-[2rem] p-6 sm:p-10 relative overflow-hidden border border-white/15 shadow-2xl transition-all duration-500 select-none"
             onmouseenter="pauseHomeReview()" onmouseleave="resumeHomeReview()">

            <!-- Review Content -->
            <div class="space-y-6 relative z-10">
                <!-- User Header -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <img id="homeReviewAvatar" src="" class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover border-2 border-amber-400/40 shadow-xl">
                        <div>
                            <h4 id="homeReviewName" class="text-base sm:text-lg font-bold text-white">Loading...</h4>
                            <p id="homeReviewHandle" class="text-xs text-amber-400 font-mono">@user</p>
                        </div>
                    </div>
                    <div id="homeReviewStars" class="flex text-amber-400 text-sm sm:text-base gap-1 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">
                        <!-- Dynamic stars -->
                    </div>
                </div>

                <!-- Quote Body -->
                <div class="relative py-2">
                    <i class="fa-solid fa-quote-left text-4xl text-white/10 absolute -top-4 -left-2"></i>
                    <p id="homeReviewText" class="text-sm sm:text-base text-gray-200 leading-relaxed italic relative z-10 pl-3 sm:pl-4">
                        "Ulasan..."
                    </p>
                </div>

                <!-- Ordered Tag -->
                <div class="pt-4 border-t border-white/10 flex items-center justify-between text-xs sm:text-sm">
                    <span class="text-gray-400 text-xs">Pesanan Favorit:</span>
                    <span id="homeReviewOrderTag" class="font-semibold text-rose-400 bg-rose-500/10 border border-rose-500/20 px-3.5 py-1.5 rounded-full text-xs sm:text-sm">
                        <i class="fa-solid fa-mug-saucer mr-1.5"></i> Special Coffee
                    </span>
                </div>
            </div>

            <!-- Decorative Background Glow -->
            <div class="absolute -bottom-10 -right-10 w-48 h-48 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>
        </div>

        <!-- Add Review Action CTA -->
        <div class="text-center mt-6">
            <button onclick="openHomeReviewModal()" class="text-xs sm:text-sm font-semibold text-amber-400 hover:text-amber-300 transition-colors inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-amber-400/20 hover:border-amber-400 shadow-sm">
                <i class="fa-solid fa-plus-circle"></i> Tambah ulasan pengalaman Anda
            </button>
        </div>
    </div>
</section>

<!-- MODAL FORM ULASAN DI HOME -->
<div id="homeReviewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-[#15151c] w-full max-w-md rounded-3xl p-6 sm:p-8 border border-white/15 shadow-2xl relative transform scale-95 transition-transform duration-300" id="homeModalContent">
        <button onclick="closeHomeReviewModal()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center text-gray-400 hover:text-white transition-colors">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-white">Tulis Ulasan Nucomu</h3>
                <p class="text-[11px] text-gray-400">Akan langsung tampil di ulasan pengunjung</p>
            </div>
        </div>

        <form action="{{ route('nucomu.review.store') }}" method="POST" class="space-y-3.5">
            @csrf
            <div>
                <label class="block text-[11px] font-semibold text-gray-300 mb-1">Nama Lengkap *</label>
                <input type="text" name="name" required placeholder="Nama Anda" class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-amber-400 transition-colors">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-gray-300 mb-1">Username (@handle)</label>
                <input type="text" name="handle" placeholder="contoh: @rara_coffeelover" class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-amber-400 transition-colors">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-gray-300 mb-1">Pesanan Anda</label>
                <input type="text" name="order_tag" placeholder="Misal: Iced Palm Sugar Latte" class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2 text-xs text-white focus:outline-none focus:border-amber-400 transition-colors">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-gray-300 mb-1">Rating Bintang</label>
                <div class="flex gap-2 text-lg text-gray-600" id="homeStarRating">
                    <i class="fa-solid fa-star cursor-pointer hover:text-amber-400 transition-colors text-amber-400" onclick="setHomeRating(1)"></i>
                    <i class="fa-solid fa-star cursor-pointer hover:text-amber-400 transition-colors text-amber-400" onclick="setHomeRating(2)"></i>
                    <i class="fa-solid fa-star cursor-pointer hover:text-amber-400 transition-colors text-amber-400" onclick="setHomeRating(3)"></i>
                    <i class="fa-solid fa-star cursor-pointer hover:text-amber-400 transition-colors text-amber-400" onclick="setHomeRating(4)"></i>
                    <i class="fa-solid fa-star cursor-pointer hover:text-amber-400 transition-colors text-amber-400" onclick="setHomeRating(5)"></i>
                </div>
                <input type="hidden" name="rating" id="homeRatingInput" value="5">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-gray-300 mb-1">Ulasan Singkat *</label>
                <textarea name="content" rows="3" required placeholder="Tuliskan kesan rasa kopi dan suasana di Nucomu Cafe..." class="w-full bg-black/40 border border-white/10 rounded-xl p-3 text-xs text-white focus:outline-none focus:border-amber-400 transition-colors resize-none"></textarea>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-xl bg-gradient-to-r from-amber-400 via-amber-300 to-yellow-300 text-black font-bold text-xs uppercase tracking-wider hover:opacity-90 transition-opacity shadow-md">
                Kirim Ulasan Sekarang
            </button>
        </form>
    </div>
</div>

<script>
    const homeReviews = @json($dynamicReviews);
    let homeReviewIndex = 0;
    const HOME_REVIEW_INTERVAL = 5000;
    let homeReviewTimer = null;
    let isHomeReviewPaused = false;

    function updateHomeReviewUI() {
        if (!homeReviews || homeReviews.length === 0) return;
        const current = homeReviews[homeReviewIndex];
        const nameEl = document.getElementById('homeReviewName');
        const handleEl = document.getElementById('homeReviewHandle');
        const avatarEl = document.getElementById('homeReviewAvatar');
        const textEl = document.getElementById('homeReviewText');
        const orderEl = document.getElementById('homeReviewOrderTag');
        const starContainer = document.getElementById('homeReviewStars');

        if (nameEl) nameEl.innerText = current.name;
        if (handleEl) handleEl.innerText = current.handle ? (current.handle.startsWith('@') ? current.handle : '@' + current.handle) : '@pelanggan';
        if (avatarEl) avatarEl.src = current.avatar_url || current.avatar || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80';
        if (textEl) textEl.innerText = `"${current.content || current.text || ''}"`;
        if (orderEl) orderEl.innerHTML = `<i class="fa-solid fa-mug-saucer mr-1.5"></i> ${current.order_tag || current.order || 'Menu Nucomu'}`;

        if (starContainer) {
            starContainer.innerHTML = '';
            const stars = current.rating || current.stars || 5;
            for(let i=0; i<stars; i++) {
                starContainer.innerHTML += '<i class="fa-solid fa-star"></i>';
            }
        }
    }

    function startHomeReviewLoop() {
        clearInterval(homeReviewTimer);
        homeReviewTimer = setInterval(() => {
            if (!isHomeReviewPaused && homeReviews.length > 1) {
                homeReviewIndex = (homeReviewIndex + 1) % homeReviews.length;
                updateHomeReviewUI();
            }
        }, HOME_REVIEW_INTERVAL);
    }

    function pauseHomeReview() { isHomeReviewPaused = true; }
    function resumeHomeReview() { isHomeReviewPaused = false; }

    let homeCurrentRating = 5;
    function openHomeReviewModal() {
        const modal = document.getElementById('homeReviewModal');
        const modalContent = document.getElementById('homeModalContent');
        if (modal && modalContent) {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
            setHomeRating(5);
        }
    }

    function closeHomeReviewModal() {
        const modal = document.getElementById('homeReviewModal');
        const modalContent = document.getElementById('homeModalContent');
        if (modal && modalContent) {
            modal.classList.add('opacity-0', 'pointer-events-none');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
        }
    }

    function setHomeRating(rating) {
        homeCurrentRating = rating;
        const ratingInput = document.getElementById('homeRatingInput');
        if (ratingInput) ratingInput.value = rating;
        const stars = document.getElementById('homeStarRating').children;
        for (let i = 0; i < stars.length; i++) {
            if (i < rating) {
                stars[i].classList.add('text-amber-400');
                stars[i].classList.remove('text-gray-600');
            } else {
                stars[i].classList.remove('text-amber-400');
                stars[i].classList.add('text-gray-600');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateHomeReviewUI();
        startHomeReviewLoop();
    });
</script>
