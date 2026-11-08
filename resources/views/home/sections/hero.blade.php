<!-- SECTION 1: HOME / HERO (APPROVED OVERHEAD FLAT-LAY - STATIC IMAGE AS INITIAL DESIGN) -->
<section id="home" class="relative min-h-[520px] sm:min-h-[570px] lg:min-h-[620px] flex items-center justify-center overflow-hidden border-b border-light-gray/60 select-none group/hero bg-[#FAF8F5]">

    <div class="relative w-full h-full min-h-[520px] sm:min-h-[570px] lg:min-h-[620px] bg-cover bg-center flex items-center justify-center"
         style="background-image: url('{{ asset('images/nucomu/hero_flatlay_bg.jpg') }}');">
        
        <!-- Soft feathered center ambient glow for crisp typography -->
        <div class="absolute inset-0 pointer-events-none" 
             style="background: radial-gradient(ellipse 65% 65% at 50% 50%, rgba(246, 241, 234, 0.88) 0%, rgba(246, 241, 234, 0.55) 60%, rgba(246, 241, 234, 0.3) 100%);">
        </div>

        <!-- Center Content -->
        <div class="hero-content-wrapper relative z-20 max-w-2xl mx-auto px-5 sm:px-8 text-center py-10 sm:py-12 lg:py-14 transition-all duration-700 ease-out transform translate-y-0 opacity-100">
            <!-- Cursive Script Kicker -->
            <p class="font-script text-2xl sm:text-4xl lg:text-5xl text-[#3C281E] mb-1.5 leading-tight">
                {{ $brand['hero_subtitle'] ?? 'Welcome to Our Cafe' }}
            </p>

            <!-- Bold Editorial Serif Headline -->
            <h1 class="font-editorial text-3xl sm:text-5xl lg:text-6xl font-bold text-[#2A1D17] leading-[1.08] mb-3 tracking-tight">
                @if(!empty($brand['hero_title']))
                    {!! nl2br(e($brand['hero_title'])) !!}
                @else
                    Good Coffee,<br>
                    Great Moments
                @endif
            </h1>

            <!-- Nucomu Identity Touchpoint -->
            <div class="inline-flex items-center space-x-2 mb-2.5 bg-white/60 backdrop-blur-sm px-3.5 py-0.5 rounded-full border border-[#54392B]/15">
                <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-editorial-widest text-[#54392B]">
                    {{ $brand['hero_badge'] ?? 'Nucomu Cafe • Tulungagung' }}
                </span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#E06D24]"></span>
            </div>

            <!-- Sensory Subtitle -->
            <p class="font-body text-xs sm:text-sm text-[#5C4E45] max-w-md mx-auto leading-relaxed mb-6 font-normal">
                {{ $brand['subheadline'] ?? 'Nikmati seduhan kopi single origin Sendang Tulungagung, pastry artisanal, dan waktu yang terasa lebih berarti di ruang monokrom bernapas hangat.' }}
            </p>

            <!-- Dual Pill CTAs -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="#menu" 
                   class="inline-flex items-center justify-center bg-[#E06D24] hover:bg-[#c95b17] text-white text-xs font-bold uppercase tracking-widest px-7 py-3 sm:px-8 sm:py-3 rounded-full shadow-lg shadow-orange-500/25 transition-all duration-200 active:scale-95">
                    VIEW OUR MENU
                </a>
                <button type="button" 
                        onclick="openReservationModal('Kunjungan Nucomu')" 
                        class="inline-flex items-center justify-center bg-white/90 hover:bg-white text-[#54392B] border border-[#54392B]/30 text-xs font-bold uppercase tracking-widest px-7 py-3 sm:px-8 sm:py-3 rounded-full shadow-sm hover:shadow transition-all duration-200 active:scale-95">
                    RESERVASI MEJA
                </button>
            </div>
        </div>
    </div>

</section>
