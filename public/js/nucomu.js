/**
 * Nucomu Cafe — Interactive Client-Side Engine
 * Handles Navbar scroll-hide/show, active spy, carousels, category filtering,
 * Lightbox, Service detail modal, and WhatsApp reservation validation.
 */

document.addEventListener('DOMContentLoaded', () => {
    initHeroSlider();
    initNavbarScroll();
    initScrollSpy();
    initScrollReveal();
    initOurStory();
    initFacilitiesGallery();
    initBestMenuCarousel();
    initGalleryLightbox();
    initLayananSection();
    initKeyboardShortcuts();
    initMenuVideoLazyLoading();
});

/* ==========================================================================
   0. HERO MENU VIDEO AUTO-SLIDER (5s Auto-Transition & Dynamic Warm Palette)
   ========================================================================== */
/**
 * WAKTU PERGANTIAN SLIDE OTOMATIS (Milidetik)
 * Ubah nilai di bawah ini untuk mempercepat atau memperlambat pergantian slide.
 * Contoh: 5000 = 5 detik, 4000 = 4 detik, 7000 = 7 detik.
 */
const SLIDE_INTERVAL = 5000;

/**
 * PALET WARNA BACKGROUND HERO PER MENU
 * Urutan warna serasi dengan nuansa hangat & lembut Nucomu Cafe:
 * [0] Tiramisu   -> krem kecokelatan (#EADBC8)
 * [1] Cheesecake -> peach lembut     (#F6DFD2)
 * [2] Spaghetti  -> terakota muda    (#F1D2B8)
 * [3] Kopi Susu  -> sage kehijauan   (#D9DECF)
 */
const HERO_BG_COLORS = [
    '#EADBC8', // Slide 0: Tiramisu
    '#F6DFD2', // Slide 1: Cheesecake
    '#F1D2B8', // Slide 2: Spaghetti
    '#D9DECF'  // Slide 3: Kopi Susu
];

let heroCurrentIndex = 0;
let heroTimerId = null;
let heroIsPaused = false;
let heroIsVisibleOnScreen = true;

function initHeroSlider() {
    const heroSection = document.getElementById('home');
    if (!heroSection) return;

    const slides = heroSection.querySelectorAll('.hero-slide-item');
    if (!slides.length) return;

    // Check prefers-reduced-motion
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Set initial background color and activate slide 0
    renderHeroSlide(heroCurrentIndex);

    // Only start auto-timer if reduced motion is NOT preferred
    if (!prefersReducedMotion) {
        startHeroAutoSlide();
    }

    // Pause on desktop hover, resume on mouse leave
    heroSection.addEventListener('mouseenter', () => {
        heroIsPaused = true;
        clearHeroAutoSlide();
    });

    heroSection.addEventListener('mouseleave', () => {
        heroIsPaused = false;
        if (!prefersReducedMotion && heroIsVisibleOnScreen) {
            startHeroAutoSlide();
        }
    });

    // Pause when browser tab is not active / hidden
    document.addEventListener('visibilitychange', () => {
        const activeVideo = slides[heroCurrentIndex]?.querySelector('video');
        if (document.hidden) {
            clearHeroAutoSlide();
            if (activeVideo && !activeVideo.paused) {
                activeVideo.pause();
            }
        } else {
            if (activeVideo && heroIsVisibleOnScreen) {
                activeVideo.play().catch(() => {});
            }
            if (!prefersReducedMotion && !heroIsPaused && heroIsVisibleOnScreen) {
                startHeroAutoSlide();
            }
        }
    });

    // Pause auto-slide and video when hero is scrolled out of viewport
    if ('IntersectionObserver' in window) {
        const heroObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                heroIsVisibleOnScreen = entry.isIntersecting;
                const activeVideo = slides[heroCurrentIndex]?.querySelector('video');
                if (entry.isIntersecting) {
                    if (activeVideo && activeVideo.paused) {
                        activeVideo.play().catch(() => {});
                    }
                    if (!prefersReducedMotion && !heroIsPaused) {
                        startHeroAutoSlide();
                    }
                } else {
                    clearHeroAutoSlide();
                    if (activeVideo && !activeVideo.paused) {
                        activeVideo.pause();
                    }
                }
            });
        }, { threshold: 0.25 });

        heroObserver.observe(heroSection);
    }

    // Touch swipe support for mobile
    let touchStartX = 0;
    let touchStartY = 0;

    heroSection.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
    }, { passive: true });

    heroSection.addEventListener('touchend', (e) => {
        const touchEndX = e.changedTouches[0].clientX;
        const touchEndY = e.changedTouches[0].clientY;
        const diffX = touchStartX - touchEndX;
        const diffY = touchStartY - touchEndY;

        // Ensure horizontal swipe is dominant
        if (Math.abs(diffX) > 40 && Math.abs(diffX) > Math.abs(diffY)) {
            if (diffX > 0) {
                window.nextHeroSlide();
            } else {
                window.prevHeroSlide();
            }
        }
    }, { passive: true });
}

function startHeroAutoSlide() {
    clearHeroAutoSlide();
    heroTimerId = setInterval(() => {
        window.nextHeroSlide();
    }, SLIDE_INTERVAL);
}

function clearHeroAutoSlide() {
    if (heroTimerId) {
        clearInterval(heroTimerId);
        heroTimerId = null;
    }
}

window.nextHeroSlide = function() {
    const slides = document.querySelectorAll('.hero-slide-item');
    if (slides.length <= 1) return;
    heroCurrentIndex = (heroCurrentIndex + 1) % slides.length;
    renderHeroSlide(heroCurrentIndex);
    resetHeroTimerIfActive();
};

window.prevHeroSlide = function() {
    const slides = document.querySelectorAll('.hero-slide-item');
    if (slides.length <= 1) return;
    heroCurrentIndex = (heroCurrentIndex - 1 + slides.length) % slides.length;
    renderHeroSlide(heroCurrentIndex);
    resetHeroTimerIfActive();
};

window.goToHeroSlide = function(index) {
    const slides = document.querySelectorAll('.hero-slide-item');
    if (index < 0 || index >= slides.length || index === heroCurrentIndex) return;
    heroCurrentIndex = index;
    renderHeroSlide(heroCurrentIndex);
    resetHeroTimerIfActive();
};

function resetHeroTimerIfActive() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (!prefersReducedMotion && !heroIsPaused && heroIsVisibleOnScreen && !document.hidden) {
        startHeroAutoSlide();
    }
}

function renderHeroSlide(index) {
    const heroSection = document.getElementById('home');
    const slides = document.querySelectorAll('.hero-slide-item');
    const dots = document.querySelectorAll('.hero-dot-btn');
    if (!slides.length) return;

    // 1. Dynamic Smooth Background Transition
    if (heroSection) {
        const bgColor = HERO_BG_COLORS[index] || slides[index]?.getAttribute('data-bg-color') || '#EADBC8';
        heroSection.style.backgroundColor = bgColor;
    }

    // 2. Switch Slides & Play/Pause/Reset Videos
    slides.forEach((slide, idx) => {
        const video = slide.querySelector('video');
        const textContent = slide.querySelector('.hero-text-content');
        const videoContainer = slide.querySelector('.hero-video-container');

        if (idx === index) {
            // Activate incoming slide
            slide.classList.remove('opacity-0', 'pointer-events-none', 'z-0');
            slide.classList.add('active', 'opacity-100', 'z-10');

            if (textContent) {
                textContent.classList.remove('translate-y-3', 'opacity-0');
                textContent.classList.add('translate-y-0', 'opacity-100');
            }
            if (videoContainer) {
                videoContainer.classList.remove('scale-95', 'opacity-0');
                videoContainer.classList.add('scale-100', 'opacity-100');
            }

            if (video) {
                video.currentTime = 0;
                if (heroIsVisibleOnScreen && !document.hidden) {
                    const playPromise = video.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(() => {});
                    }
                }
            }
        } else {
            // Deactivate outgoing slide
            slide.classList.add('opacity-0', 'pointer-events-none', 'z-0');
            slide.classList.remove('active', 'opacity-100', 'z-10');

            if (textContent) {
                textContent.classList.add('translate-y-3', 'opacity-0');
                textContent.classList.remove('translate-y-0', 'opacity-100');
            }
            if (videoContainer) {
                videoContainer.classList.add('scale-95', 'opacity-0');
                videoContainer.classList.remove('scale-100', 'opacity-100');
            }

            if (video) {
                video.pause();
                video.currentTime = 0;
            }
        }
    });

    // 3. Update 4 Indicator Dots
    dots.forEach((dot, idx) => {
        if (idx === index) {
            dot.className = 'hero-dot-btn w-6 h-1.5 rounded-full bg-[#E06D24] transition-all duration-300';
            dot.setAttribute('aria-current', 'true');
        } else {
            dot.className = 'hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-600/40 hover:bg-neutral-800 transition-all duration-300';
            dot.removeAttribute('aria-current');
        }
    });
}

/* ==========================================================================
   1. NAVBAR SCROLL BEHAVIOR (Hide on Scroll Down, Show on Scroll Up)
   ========================================================================== */
function initNavbarScroll() {
    const navbar = document.getElementById('main-navbar');
    if (!navbar) return;

    let lastScrollY = window.scrollY;
    let ticking = false;
    const scrollThreshold = 10;

    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                const currentScrollY = window.scrollY;
                const scrollDifference = currentScrollY - lastScrollY;

                // Always show navbar near top of page
                if (currentScrollY < 80) {
                    navbar.classList.remove('navbar-hidden');
                    navbar.classList.add('navbar-visible');
                    navbar.classList.remove('shadow-md');
                } else if (scrollDifference > scrollThreshold) {
                    // Scrolling DOWN -> Hide navbar smoothly
                    navbar.classList.add('navbar-hidden');
                    navbar.classList.remove('navbar-visible');
                } else if (scrollDifference < -scrollThreshold) {
                    // Scrolling UP -> Show navbar smoothly
                    navbar.classList.remove('navbar-hidden');
                    navbar.classList.add('navbar-visible');
                    navbar.classList.add('shadow-md');
                }

                lastScrollY = currentScrollY;
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
}

/* ==========================================================================
   2. SCROLL SPY (Active Nav Link Indicator)
   ========================================================================== */
function initScrollSpy() {
    const navLinks = document.querySelectorAll('.desktop-nav-link');
    const sections = ['home', 'about', 'menu', 'gallery', 'contact', 'layanan'];

    const sectionElements = sections.map(id => document.getElementById(id)).filter(Boolean);

    const observerOptions = {
        root: null,
        rootMargin: '-20% 0px -70% 0px',
        threshold: 0
    };

    const spyObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const activeId = entry.target.id;
                navLinks.forEach(link => {
                    const href = link.getAttribute('href');
                    if (href === `#${activeId}` || (href && href.endsWith(`#${activeId}`))) {
                        link.classList.add('active');
                    } else {
                        link.classList.remove('active');
                    }
                });
            }
        });
    }, observerOptions);

    sectionElements.forEach(section => spyObserver.observe(section));
}

/* ==========================================================================
   3. MOBILE DRAWER NAVIGATION
   ========================================================================== */
window.toggleMobileDrawer = function() {
    const drawer = document.getElementById('mobile-drawer');
    const panel = document.getElementById('mobile-drawer-panel');
    if (!drawer || !panel) return;

    const isOpen = !drawer.classList.contains('pointer-events-none');

    if (isOpen) {
        // Close
        drawer.classList.add('opacity-0', 'pointer-events-none');
        drawer.classList.remove('opacity-100');
        panel.classList.add('translate-x-full');
        document.body.classList.remove('overflow-hidden');
    } else {
        // Open
        drawer.classList.remove('opacity-0', 'pointer-events-none');
        drawer.classList.add('opacity-100');
        panel.classList.remove('translate-x-full');
        document.body.classList.add('overflow-hidden');
    }
};

/* ==========================================================================
   4. SCROLL REVEAL OBSERVER
   ========================================================================== */
function initScrollReveal() {
    const reveals = document.querySelectorAll('.reveal-on-scroll, .reveal-from-left, .reveal-from-right, .reveal-fade-up');
    if (!reveals.length) return;

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        reveals.forEach(el => el.classList.add('is-visible'));
        return;
    }

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12,
        rootMargin: '0px 0px -40px 0px'
    });

    reveals.forEach(el => revealObserver.observe(el));
}

/* ==========================================================================
   4B. OUR STORY / ABOUT US SECTION (SCROLL REVEAL, CAROUSEL & TYPEWRITER)
   ========================================================================== */
function initOurStory() {
    const storySection = document.getElementById('our-story-section') || document.getElementById('about');
    const quoteWrapper = document.getElementById('story-quote-wrapper');
    const typewriterTextEl = document.getElementById('story-typewriter-text');
    const typewriterCursor = document.getElementById('story-typewriter-cursor');

    if (!storySection) return;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // 1. Entrance animation for Our Story section (Single-trigger, no replay on ordinary scroll up/down)
    function triggerEntrance() {
        storySection.classList.add('story-in-view');
    }

    if (prefersReducedMotion) {
        triggerEntrance();
    } else {
        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    triggerEntrance();
                    sectionObserver.unobserve(storySection);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -40px 0px'
        });
        sectionObserver.observe(storySection);
    }

    // Support re-triggering if user navigates back to #about or #our-story-section from elsewhere
    window.addEventListener('hashchange', () => {
        if (location.hash === '#about' || location.hash === '#our-story-section') {
            if (!storySection.classList.contains('story-in-view')) {
                triggerEntrance();
            }
        }
    });

    // 2. Automatic Image Carousel with Smooth Crossfade & Manual Controls
    initStoryCarousel(prefersReducedMotion);

    // 3. Typewriter animation for Quote
    if (!quoteWrapper || !typewriterTextEl) return;

    const quoteFullText = '“Ruang ramah di Tulungagung di mana aroma kopi yang baru diseduh menyapa hangat, dan setiap cangkir disajikan dengan ketulusan tangan.”';

    if (prefersReducedMotion) {
        typewriterTextEl.textContent = quoteFullText;
        if (typewriterCursor) typewriterCursor.classList.add('hidden');
        return;
    }

    // Reset text initially so it types character by character on view
    typewriterTextEl.textContent = '';
    if (typewriterCursor) typewriterCursor.classList.remove('hidden');

    let isTypingStarted = false;

    const quoteObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting && !isTypingStarted) {
                isTypingStarted = true;
                quoteObserver.unobserve(quoteWrapper);

                let charIndex = 0;
                const typingSpeed = 26; // 26ms per char (within 20–35ms spec)

                function typeNextChar() {
                    if (charIndex < quoteFullText.length) {
                        typewriterTextEl.textContent = quoteFullText.slice(0, charIndex + 1);
                        charIndex++;
                        setTimeout(typeNextChar, typingSpeed);
                    } else {
                        // Typing complete: hold cursor briefly then smoothly fade it out
                        setTimeout(() => {
                            if (typewriterCursor) {
                                typewriterCursor.style.transition = 'opacity 0.4s ease-out';
                                typewriterCursor.style.opacity = '0';
                                setTimeout(() => {
                                    typewriterCursor.classList.add('hidden');
                                }, 400);
                            }
                        }, 700);
                    }
                }

                // Brief delay after entering view before typing starts
                setTimeout(typeNextChar, 200);
            }
        });
    }, {
        threshold: 0.2,
        rootMargin: '0px 0px -30px 0px'
    });

    quoteObserver.observe(quoteWrapper);
}

/**
 * Story Frame Image Carousel (Smooth Crossfade, 5.5s interval, Touch Swipe & Desktop Controls)
 */
function initStoryCarousel(prefersReducedMotion) {
    const frame = document.getElementById('story-carousel-frame');
    if (!frame) return;

    const slides = frame.querySelectorAll('.story-carousel-slide');
    if (!slides || slides.length <= 1) return;

    const prevBtn = document.getElementById('story-carousel-prev');
    const nextBtn = document.getElementById('story-carousel-next');

    let currentIndex = 0;
    const totalSlides = slides.length;
    const intervalMs = 5500; // 5.5s autoplay cycle
    let autoplayTimer = null;
    let isUserInteracting = false;

    function showSlide(newIndex) {
        if (newIndex === currentIndex) return;

        const normalizedIndex = (newIndex + totalSlides) % totalSlides;
        const currentSlide = slides[currentIndex];
        const nextSlide = slides[normalizedIndex];

        // Smooth Crossfade: Fade out current, Fade in next
        currentSlide.classList.remove('active', 'opacity-100', 'z-10');
        currentSlide.classList.add('opacity-0', 'z-0', 'pointer-events-none');
        currentSlide.setAttribute('aria-hidden', 'true');

        nextSlide.classList.add('active', 'opacity-100', 'z-10');
        nextSlide.classList.remove('opacity-0', 'z-0', 'pointer-events-none');
        nextSlide.setAttribute('aria-hidden', 'false');

        currentIndex = normalizedIndex;
    }

    function advanceNext() {
        showSlide(currentIndex + 1);
    }

    function advancePrev() {
        showSlide(currentIndex - 1);
    }

    function startAutoplay() {
        if (prefersReducedMotion) return;
        stopAutoplay();
        autoplayTimer = setInterval(() => {
            if (!isUserInteracting) {
                advanceNext();
            }
        }, intervalMs);
    }

    function stopAutoplay() {
        if (autoplayTimer) {
            clearInterval(autoplayTimer);
            autoplayTimer = null;
        }
    }

    // Desktop hover & focus controls
    frame.addEventListener('mouseenter', () => {
        isUserInteracting = true;
        stopAutoplay();
    });

    frame.addEventListener('mouseleave', () => {
        isUserInteracting = false;
        startAutoplay();
    });

    frame.addEventListener('focusin', () => {
        isUserInteracting = true;
        stopAutoplay();
    });

    frame.addEventListener('focusout', () => {
        isUserInteracting = false;
        startAutoplay();
    });

    // Keyboard navigation (ArrowLeft & ArrowRight)
    frame.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            advancePrev();
            stopAutoplay();
            startAutoplay();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            advanceNext();
            stopAutoplay();
            startAutoplay();
        }
    });

    // Manual navigation buttons
    if (prevBtn) {
        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            advancePrev();
            stopAutoplay();
            startAutoplay();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            advanceNext();
            stopAutoplay();
            startAutoplay();
        });
    }

    // Mobile touch swipe gestures
    let touchStartX = 0;
    let touchStartY = 0;

    frame.addEventListener('touchstart', (e) => {
        if (e.changedTouches && e.changedTouches.length > 0) {
            touchStartX = e.changedTouches[0].clientX;
            touchStartY = e.changedTouches[0].clientY;
            isUserInteracting = true;
            stopAutoplay();
        }
    }, { passive: true });

    frame.addEventListener('touchend', (e) => {
        if (e.changedTouches && e.changedTouches.length > 0) {
            const touchEndX = e.changedTouches[0].clientX;
            const touchEndY = e.changedTouches[0].clientY;
            const diffX = touchEndX - touchStartX;
            const diffY = touchEndY - touchStartY;

            // Threshold of 35px horizontal travel, ensuring horizontal intent > vertical scroll
            if (Math.abs(diffX) > 35 && Math.abs(diffX) > Math.abs(diffY)) {
                if (diffX < 0) {
                    advanceNext(); // Swiped left -> next
                } else {
                    advancePrev(); // Swiped right -> prev
                }
            }

            isUserInteracting = false;
            startAutoplay();
        }
    }, { passive: true });

    // Initial autoplay start
    startAutoplay();
}

/* ==========================================================================
   4C. INTERACTIVE FLOATING GALLERY (INDOOR / OUTDOOR — 2-SLOT ASYMMETRIC)
   ========================================================================== */
function initFacilitiesGallery() {
    const galleryEl = document.querySelector('[data-facilities-gallery]');
    if (!galleryEl) return;

    const data = window.NUCOMU_FACILITIES_DATA;
    if (!data) return;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Elements
    const tabButtons = galleryEl.querySelectorAll('[data-facility-category-trigger]');
    const arrowPrev = document.getElementById('facility-category-prev');
    const arrowNext = document.getElementById('facility-category-next');
    const infoPane = document.getElementById('facility-info-pane');
    const infoLabel = document.getElementById('facility-info-label');
    const infoTitle = document.getElementById('facility-info-title');
    const infoDesc = document.getElementById('facility-info-desc');
    const infoFeatures = document.getElementById('facility-info-features');

    const photoStack = document.getElementById('facility-photo-stack');
    const categoryStacks = galleryEl.querySelectorAll('[data-facility-category-stack]');

    // Floating Cards A (Top-Right) & B (Bottom-Left)
    const cardA = document.getElementById('facility-card-a');
    const cardABadge = document.getElementById('facility-card-a-badge');
    const cardASubtitle = document.getElementById('facility-card-a-subtitle');

    const cardB = document.getElementById('facility-card-b');
    const cardBCaption = document.getElementById('facility-card-b-caption');
    const cardBMeta = document.getElementById('facility-card-b-meta');
    const cardBCounter = document.getElementById('facility-card-b-counter');
    const cardBDots = document.getElementById('facility-card-b-dots');

    let currentCategory = 'indoor';
    let currentPhotoIndex = 0;
    let autoplayTimer = null;
    let isPaused = false;
    let pauseResumeTimeout = null;

    const AUTOPLAY_INTERVAL = 1500; // 1,5 detik per foto sesuai instruksi user
    const MANUAL_PAUSE_DURATION = 3500; // Jeda singkat setelah klik manual sebelum lanjut autoplay

    // 0. Entrance Animation: Sisi Kiri (Teks) dari Kiri, Sisi Kanan (Foto) dari Kanan (Slow & Ultra-Smooth)
    function triggerEntrance() {
        galleryEl.classList.add('facility-in-view');
    }

    if (prefersReducedMotion) {
        triggerEntrance();
    } else {
        const galleryObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    triggerEntrance();
                    galleryObserver.unobserve(galleryEl);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -40px 0px'
        });
        galleryObserver.observe(galleryEl);
    }

    // Support re-triggering if navigated via anchor hash (#facilities)
    window.addEventListener('hashchange', () => {
        if (location.hash === '#facilities' || location.hash === '#facilities-interactive-gallery') {
            if (!galleryEl.classList.contains('facility-in-view')) {
                triggerEntrance();
            }
        }
    });

    // 1. Crossfade slide assignment logic for 2-slot asymmetric floating photo frames
    function updatePhotoSlots(categoryKey, activeIndex) {
        const stack = galleryEl.querySelector(`[data-facility-category-stack="${categoryKey}"]`);
        if (!stack) return;

        const catData = data[categoryKey];
        if (!catData || !catData.photos) return;
        const total = catData.photos.length;
        if (total === 0) return;

        const subIndex = total > 1 ? (activeIndex + 1) % total : 0;

        // A. Update Main Slides (Smooth Crossfade Fade In / Fade Out)
        const mainSlides = stack.querySelectorAll('.facility-slide-main');
        mainSlides.forEach((slide) => {
            const idx = parseInt(slide.getAttribute('data-slide-index'), 10);
            if (idx === activeIndex) {
                slide.classList.add('active', 'opacity-100', 'z-10');
                slide.classList.remove('opacity-0', 'z-0', 'pointer-events-none');
                slide.setAttribute('aria-hidden', 'false');
            } else {
                slide.classList.remove('active', 'opacity-100', 'z-10');
                slide.classList.add('opacity-0', 'z-0', 'pointer-events-none');
                slide.setAttribute('aria-hidden', 'true');
            }
        });

        // B. Update Sub Slides (Smooth Crossfade Fade In / Fade Out)
        const subSlides = stack.querySelectorAll('.facility-slide-sub');
        subSlides.forEach((slide) => {
            const idx = parseInt(slide.getAttribute('data-slide-index'), 10);
            if (idx === subIndex) {
                slide.classList.add('active', 'opacity-100', 'z-10');
                slide.classList.remove('opacity-0', 'z-0', 'pointer-events-none');
                slide.setAttribute('aria-hidden', 'false');
                const img = slide.querySelector('img');
                if (img && img.loading === 'lazy') {
                    img.loading = 'eager';
                }
            } else {
                slide.classList.remove('active', 'opacity-100', 'z-10');
                slide.classList.add('opacity-0', 'z-0', 'pointer-events-none');
                slide.setAttribute('aria-hidden', 'true');
            }
        });

        // Preload foto antrean berikutnya agar transisi tetap instan tanpa jeda load
        if (total > 2) {
            const nextQueuedIdx = (activeIndex + 2) % total;
            const queuedSlide = stack.querySelector(`.facility-slide-sub[data-slide-index="${nextQueuedIdx}"]`);
            if (queuedSlide) {
                const img = queuedSlide.querySelector('img');
                if (img && img.loading === 'lazy') {
                    img.loading = 'eager';
                }
            }
        }

        // C. Update Floating Card B (Caption & Penghitung/Dots)
        const activePhoto = catData.photos[activeIndex];
        if (activePhoto && cardBCaption) {
            cardBCaption.textContent = activePhoto.caption || '';
        }

        if (cardBMeta) {
            if (total <= 1) {
                cardBMeta.classList.add('hidden');
            } else {
                cardBMeta.classList.remove('hidden');
                if (cardBCounter) {
                    cardBCounter.textContent = `${activeIndex + 1} / ${total}`;
                }
                if (cardBDots) {
                    cardBDots.innerHTML = '';
                    for (let i = 0; i < total; i++) {
                        const dot = document.createElement('span');
                        dot.className = `facility-counter-dot transition-all duration-300 ${
                            i === activeIndex
                                ? 'w-3.5 h-1.5 bg-[#242424] rounded-full'
                                : 'w-1.5 h-1.5 bg-[#D1D5DB] rounded-full'
                        }`;
                        cardBDots.appendChild(dot);
                    }
                }
            }
        }
    }

    // 2. Select specific photo within active category (circular queue)
    function selectPhoto(targetIndex, isManual = true) {
        const catData = data[currentCategory];
        if (!catData || !catData.photos) return;
        const total = catData.photos.length;
        if (total <= 1) return;

        currentPhotoIndex = (targetIndex + total) % total;
        updatePhotoSlots(currentCategory, currentPhotoIndex);

        if (isManual) {
            // Jeda autoplay ±8 detik setelah interaksi manual
            pauseAutoplay();
            if (pauseResumeTimeout) clearTimeout(pauseResumeTimeout);
            pauseResumeTimeout = setTimeout(() => {
                if (!isPaused) {
                    startAutoplay();
                }
            }, MANUAL_PAUSE_DURATION);
        }
    }

    // 3. Category Switching logic (Indoor <-> Outdoor)
    function switchCategory(targetCategory) {
        if (!data[targetCategory] || targetCategory === currentCategory) return;

        stopAutoplay();
        if (pauseResumeTimeout) clearTimeout(pauseResumeTimeout);

        currentCategory = targetCategory;
        currentPhotoIndex = 0;

        // A. Update Tabs Styling & ARIA
        tabButtons.forEach(btn => {
            const isTarget = btn.getAttribute('data-facility-category-trigger') === targetCategory;
            btn.setAttribute('aria-selected', isTarget ? 'true' : 'false');
            if (isTarget) {
                btn.className = 'facility-category-tab px-5 py-2 rounded-full text-xs font-semibold tracking-wider uppercase transition-all duration-300 bg-[#242424] text-white shadow-xs focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2';
            } else {
                btn.className = 'facility-category-tab px-5 py-2 rounded-full text-xs font-semibold tracking-wider uppercase transition-all duration-300 bg-transparent text-[#666666] hover:text-[#1C1C1C] focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2';
            }
        });

        // B. Update Information Pane with subtle crossfade
        if (infoPane) {
            infoPane.classList.remove('opacity-100', 'translate-y-0');
            infoPane.classList.add('opacity-0', 'translate-y-2');

            setTimeout(() => {
                const catData = data[targetCategory];
                if (infoLabel) infoLabel.textContent = catData.badge;
                if (infoTitle) infoTitle.textContent = catData.title;
                if (infoDesc) infoDesc.textContent = catData.description;

                if (infoFeatures) {
                    infoFeatures.innerHTML = '';
                    catData.features.forEach(feat => {
                        const li = document.createElement('li');
                        li.className = 'flex items-center gap-3';
                        li.innerHTML = `
                            <span class="w-5 h-5 rounded-full bg-[#1C1C1C]/6 border border-[#E5E5E5] flex items-center justify-center text-[#1C1C1C] shrink-0" aria-hidden="true">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </span>
                            <span>${feat}</span>
                        `;
                        infoFeatures.appendChild(li);
                    });
                }

                infoPane.classList.remove('opacity-0', 'translate-y-2');
                infoPane.classList.add('opacity-100', 'translate-y-0');
            }, 180);
        }

        // C. Update Floating Card A (Kanan Atas) with smooth fade
        if (cardA) {
            cardA.classList.remove('opacity-100', 'translate-y-0');
            cardA.classList.add('opacity-0', '-translate-y-1');

            setTimeout(() => {
                const catData = data[targetCategory];
                if (cardABadge) {
                    cardABadge.textContent = catData.room_label;
                }
                if (cardASubtitle) {
                    cardASubtitle.textContent = catData.subtitle || catData.title;
                }
                cardA.classList.remove('opacity-0', '-translate-y-1');
                cardA.classList.add('opacity-100', 'translate-y-0');
            }, 180);
        }

        // D. Switch Category Photo Stacks
        categoryStacks.forEach(stack => {
            const isTarget = stack.getAttribute('data-facility-category-stack') === targetCategory;
            if (isTarget) {
                stack.classList.remove('opacity-0', 'pointer-events-none', 'z-0');
                stack.classList.add('opacity-100', 'pointer-events-auto', 'z-10');
                stack.setAttribute('aria-hidden', 'false');
            } else {
                stack.classList.remove('opacity-100', 'pointer-events-auto', 'z-10');
                stack.classList.add('opacity-0', 'pointer-events-none', 'z-0');
                stack.setAttribute('aria-hidden', 'true');
            }
        });

        // E. Reset photo slots for newly activated category
        updatePhotoSlots(targetCategory, 0);

        // F. Restart autoplay if category has multiple photos
        const total = data[targetCategory].photos ? data[targetCategory].photos.length : 0;
        if (total > 1 && !prefersReducedMotion && !isPaused) {
            startAutoplay();
        }
    }

    // 4. Autoplay Handlers (±6 detik)
    function startAutoplay() {
        if (prefersReducedMotion) return;
        stopAutoplay();

        const catData = data[currentCategory];
        if (!catData || !catData.photos || catData.photos.length <= 1) return;

        autoplayTimer = setInterval(() => {
            if (!isPaused && !document.hidden) {
                selectPhoto(currentPhotoIndex + 1, false);
            }
        }, AUTOPLAY_INTERVAL);
    }

    function pauseAutoplay() {
        if (autoplayTimer) {
            clearInterval(autoplayTimer);
            autoplayTimer = null;
        }
    }

    function stopAutoplay() {
        pauseAutoplay();
    }

    // 5. Wire Category Tab Click Events
    tabButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const targetCat = btn.getAttribute('data-facility-category-trigger');
            switchCategory(targetCat);
        });
    });

    // 6. Wire Category Arrow Buttons (Berpindah Kategori Indoor <-> Outdoor)
    if (arrowPrev) {
        arrowPrev.addEventListener('click', (e) => {
            e.preventDefault();
            const nextCat = currentCategory === 'indoor' ? 'outdoor' : 'indoor';
            switchCategory(nextCat);
        });
    }

    if (arrowNext) {
        arrowNext.addEventListener('click', (e) => {
            e.preventDefault();
            const nextCat = currentCategory === 'indoor' ? 'outdoor' : 'indoor';
            switchCategory(nextCat);
        });
    }

    // 7. Wire Photo Card Click Delegation (Hanya Frame SUB yang memicu pergantian ke foto berikutnya)
    if (photoStack) {
        photoStack.addEventListener('click', (e) => {
            const subFrame = e.target.closest('.facility-frame-sub');
            if (subFrame) {
                e.preventDefault();
                selectPhoto(currentPhotoIndex + 1, true);
            }
        });

        // Hover & Focus Autoplay Pause
        photoStack.addEventListener('mouseenter', () => {
            isPaused = true;
            pauseAutoplay();
        });

        photoStack.addEventListener('mouseleave', () => {
            isPaused = false;
            startAutoplay();
        });

        photoStack.addEventListener('focusin', () => {
            isPaused = true;
            pauseAutoplay();
        });

        photoStack.addEventListener('focusout', () => {
            isPaused = false;
            startAutoplay();
        });

        // Mobile Touch Gestures (Swipe Left / Right)
        let touchStartX = 0;
        let touchStartY = 0;

        photoStack.addEventListener('touchstart', (e) => {
            if (e.touches && e.touches.length > 0) {
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
                isPaused = true;
                pauseAutoplay();
            }
        }, { passive: true });

        photoStack.addEventListener('touchend', (e) => {
            if (e.changedTouches && e.changedTouches.length > 0) {
                const touchEndX = e.changedTouches[0].clientX;
                const touchEndY = e.changedTouches[0].clientY;
                const diffX = touchEndX - touchStartX;
                const diffY = touchEndY - touchStartY;

                // Threshold 35px horizontal travel, memastikan intensi horizontal > scroll vertikal
                if (Math.abs(diffX) > 35 && Math.abs(diffX) > Math.abs(diffY)) {
                    if (diffX < 0) {
                        selectPhoto(currentPhotoIndex + 1, true); // Swipe kiri -> foto berikutnya
                    } else {
                        selectPhoto(currentPhotoIndex - 1, true); // Swipe kanan -> foto sebelumnya
                    }
                }

                isPaused = false;
                startAutoplay();
            }
        }, { passive: true });
    }

    // 8. Document Visibility Change (Pause saat tab browser tidak aktif)
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            pauseAutoplay();
        } else {
            if (!isPaused) {
                startAutoplay();
            }
        }
    });

    // 9. Inisialisasi awal kategori Indoor
    updatePhotoSlots('indoor', 0);
    startAutoplay();

    // 10. Expose API
    window.nucomuGallery = {
        selectPhoto,
        switchCategory,
        getState: () => ({ currentCategory, currentPhotoIndex })
    };
}

/* ==========================================================================
   5. BEST MENU CAROUSEL & CATEGORY FILTERING
   ========================================================================== */
let bestCurrentIndex = 0;
let bestSlideTimer = null;

function initBestMenuCarousel() {
    updateBestCarousel();
    startBestAutoSlide();

    const container = document.getElementById('best-carousel-track');
    if (container) {
        container.addEventListener('mouseenter', () => clearInterval(bestSlideTimer));
        container.addEventListener('mouseleave', () => startBestAutoSlide());

        // Touch swipe support
        let startX = 0;
        container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        }, { passive: true });

        container.addEventListener('touchend', (e) => {
            const endX = e.changedTouches[0].clientX;
            const diff = startX - endX;
            if (Math.abs(diff) > 40) {
                if (diff > 0) {
                    window.nextBestSlide();
                } else {
                    window.prevBestSlide();
                }
            }
        }, { passive: true });
    }
}

function startBestAutoSlide() {
    clearInterval(bestSlideTimer);
    bestSlideTimer = setInterval(() => {
        window.nextBestSlide();
    }, 6000);
}

window.nextBestSlide = function() {
    const visibleCards = getVisibleBestCards();
    if (visibleCards.length <= 1) return;
    bestCurrentIndex = (bestCurrentIndex + 1) % visibleCards.length;
    updateBestCarousel();
};

window.prevBestSlide = function() {
    const visibleCards = getVisibleBestCards();
    if (visibleCards.length <= 1) return;
    bestCurrentIndex = (bestCurrentIndex - 1 + visibleCards.length) % visibleCards.length;
    updateBestCarousel();
};

window.goToBestSlide = function(index) {
    bestCurrentIndex = index;
    updateBestCarousel();
    startBestAutoSlide();
};

function getVisibleBestCards() {
    return Array.from(document.querySelectorAll('.best-product-card')).filter(card => {
        return card.getAttribute('data-hidden') !== 'true';
    });
}

function updateBestCarousel() {
    const visibleCards = getVisibleBestCards();
    const track = document.getElementById('best-carousel-track');
    const dotsContainer = document.getElementById('best-carousel-dots');
    if (!track || !visibleCards.length) return;

    if (bestCurrentIndex >= visibleCards.length) {
        bestCurrentIndex = 0;
    }

    // On mobile (<768px), we slide card by card. On desktop, showcase displays grid or slide.
    const isMobile = window.innerWidth < 768;
    if (isMobile) {
        track.style.transform = `translateX(-${bestCurrentIndex * 100}%)`;
    } else {
        track.style.transform = 'none';
    }

    // Update dots
    if (dotsContainer) {
        dotsContainer.innerHTML = '';
        visibleCards.forEach((_, idx) => {
            const dot = document.createElement('button');
            dot.className = `h-2 rounded-full transition-all duration-300 ${
                idx === bestCurrentIndex ? 'w-8 bg-neutral-900' : 'w-2 bg-neutral-300 hover:bg-neutral-500'
            }`;
            dot.setAttribute('aria-label', `Slide ${idx + 1}`);
            dot.onclick = () => window.goToBestSlide(idx);
            dotsContainer.appendChild(dot);
        });
    }
}

/* ==========================================================================
   4.5 BEST MENU HERO DISH SWITCHER (Matching Reference Layout)
   ========================================================================== */
window.switchBestMenuItem = function(index) {
    const items = window.nucomuBestMenuItems;
    if (!items || !items[index]) return;
    
    const item = items[index];
    
    const titleEl = document.getElementById('best-hero-title');
    const catEl = document.getElementById('best-hero-category');
    const descEl = document.getElementById('best-hero-desc');
    const priceEl = document.getElementById('best-hero-price');
    const orderBtn = document.getElementById('best-hero-order-btn');
    const imgEl = document.getElementById('best-hero-img');
    const dishWrapper = document.getElementById('best-hero-dish-wrapper');

    if (dishWrapper) {
        dishWrapper.style.transform = 'scale(0.92) rotate(-6deg)';
        dishWrapper.style.opacity = '0.3';
    }
    if (titleEl) titleEl.style.opacity = '0.2';
    if (descEl) descEl.style.opacity = '0.2';

    setTimeout(() => {
        if (titleEl) {
            titleEl.innerHTML = item.display_title || item.name;
            titleEl.style.opacity = '1';
        }
        if (catEl) catEl.textContent = item.category_label || item.category;
        if (descEl) {
            descEl.textContent = item.description;
            descEl.style.opacity = '1';
        }
        if (priceEl) priceEl.textContent = item.price_formatted;
        window.currentBestMenuIndex = index;
        if (orderBtn) {
            orderBtn.setAttribute('href', 'javascript:void(0)');
        }

        const videoEl = document.getElementById('best-hero-video');
        if (item.video && videoEl) {
            const source = videoEl.querySelector('source');
            const videoSrc = item.video.startsWith('http') || item.video.startsWith('/') ? item.video : `/${item.video}`;
            if (source && source.getAttribute('src') !== videoSrc) {
                source.setAttribute('src', videoSrc);
                videoEl.load();
            }
            videoEl.poster = item.image;
            videoEl.classList.remove('hidden');
            videoEl.currentTime = 0;
            videoEl.play().catch(() => {});
            if (imgEl) imgEl.classList.add('hidden');
        } else {
            if (videoEl) {
                videoEl.pause();
                videoEl.classList.add('hidden');
            }
            if (imgEl) {
                imgEl.src = item.image;
                imgEl.alt = item.name;
                imgEl.classList.remove('hidden');
            }
        }

        if (dishWrapper) {
            dishWrapper.style.transform = 'scale(1) rotate(0deg)';
            dishWrapper.style.opacity = '1';
        }
    }, 180);

    // Update right pills styling
    document.querySelectorAll('.best-menu-pill').forEach((pill, idx) => {
        const titleSpan = pill.querySelector('.pill-title');
        const priceSpan = pill.querySelector('.pill-price');
        
        if (idx === index) {
            pill.className = 'best-menu-pill active bg-[#E06D24] text-white shadow-lg shadow-orange-500/20 flex items-center justify-between p-3 pl-3.5 sm:pl-5 rounded-2xl sm:rounded-l-full transition-all duration-300 cursor-pointer group';
            if (titleSpan) titleSpan.className = 'pill-title text-[11px] sm:text-xs font-bold uppercase tracking-wider block leading-tight text-white';
            if (priceSpan) priceSpan.className = 'pill-price text-xs sm:text-sm font-bold block mt-0.5 text-white';
        } else {
            pill.className = 'best-menu-pill bg-white text-neutral-800 shadow-md hover:shadow-lg hover:-translate-x-1 border border-stone-100 flex items-center justify-between p-3 pl-3.5 sm:pl-5 rounded-2xl sm:rounded-l-full transition-all duration-300 cursor-pointer group';
            if (titleSpan) titleSpan.className = 'pill-title text-[11px] sm:text-xs font-bold uppercase tracking-wider block leading-tight text-neutral-800';
            if (priceSpan) priceSpan.className = 'pill-price text-xs sm:text-sm font-bold block mt-0.5 text-[#E06D24]';
        }
    });

    // Update dots
    document.querySelectorAll('.best-menu-dot').forEach((dot, idx) => {
        if (idx === index) {
            dot.className = 'best-menu-dot w-6 h-2 bg-[#E06D24] rounded-full transition-all duration-300';
        } else {
            dot.className = 'best-menu-dot w-2 h-2 bg-neutral-300 hover:bg-neutral-400 rounded-full transition-all duration-300';
        }
    });
};

/* ==========================================================================
   5. MENU RECOMMENDATIONS & CIRCULAR CATEGORY FILTERING
   ========================================================================== */
window.filterRecommendations = function(category, buttonEl) {
    // 1. Update active tab button style
    document.querySelectorAll('.rec-tab-btn').forEach(btn => {
        btn.classList.remove('active', 'text-[#C59D5F]');
        btn.classList.add('text-neutral-600');
        const indicator = btn.querySelector('.tab-indicator');
        if (indicator) indicator.classList.add('hidden');
    });

    if (buttonEl) {
        buttonEl.classList.add('active', 'text-[#C59D5F]');
        buttonEl.classList.remove('text-neutral-600');
        const indicator = buttonEl.querySelector('.tab-indicator');
        if (indicator) indicator.classList.remove('hidden');
    }

    // 2. Filter the 8 recommendation cards with smooth opacity transition
    const grid = document.getElementById('recommendations-grid');
    const emptyState = document.getElementById('recommendations-empty-state');
    const cards = document.querySelectorAll('.rec-card');

    if (grid) {
        grid.style.opacity = '0.2';
        grid.style.transform = 'translateY(4px)';
    }

    setTimeout(() => {
        let matchCount = 0;
        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category') || '';
            const categories = cardCat.split(' ');
            
            if (category === 'all' || categories.includes(category)) {
                card.style.display = 'flex';
                matchCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (emptyState) {
            if (matchCount === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        if (grid) {
            grid.style.opacity = '1';
            grid.style.transform = 'translateY(0)';
        }
    }, 180);
};

window.handleCircularCategoryClick = function(categoryId, buttonEl) {
    const isCurrentlyActive = buttonEl && buttonEl.classList.contains('active-cat');

    document.querySelectorAll('.cat-circle-btn').forEach(btn => {
        btn.classList.remove('active-cat');
        btn.querySelector('.cat-circle-img-wrap')?.classList.remove('border-[#E06D24]', 'ring-2', 'ring-[#E06D24]/30', 'scale-105');
        btn.querySelector('.cat-circle-img-wrap')?.classList.add('border-neutral-200/90');
        btn.querySelector('.cat-circle-label')?.classList.remove('text-[#E06D24]', 'font-bold');
        btn.querySelector('.cat-circle-label')?.classList.add('text-neutral-800');
    });

    let targetCategory = categoryId;
    if (isCurrentlyActive) {
        targetCategory = 'all';
    } else if (buttonEl) {
        buttonEl.classList.add('active-cat');
        buttonEl.querySelector('.cat-circle-img-wrap')?.classList.add('border-[#E06D24]', 'ring-2', 'ring-[#E06D24]/30', 'scale-105');
        buttonEl.querySelector('.cat-circle-img-wrap')?.classList.remove('border-neutral-200/90');
        buttonEl.querySelector('.cat-circle-label')?.classList.add('text-[#E06D24]', 'font-bold');
        buttonEl.querySelector('.cat-circle-label')?.classList.remove('text-neutral-800');
    }

    if (targetCategory === 'snacks') targetCategory = 'meals';
    if (targetCategory === 'specials') targetCategory = 'all';

    window.filterRecommendations(targetCategory);
};

/* ==========================================================================
   6. ALL MENU CATALOG FILTERING
   ========================================================================== */
window.filterAllMenuCategory = function(category, buttonEl) {
    // Update tab indicators
    document.querySelectorAll('.all-menu-cat-btn').forEach(btn => {
        btn.classList.remove('active-cat', 'border-neutral-900', 'text-neutral-900', 'font-bold');
        btn.classList.add('border-transparent', 'text-neutral-500');
    });

    if (buttonEl) {
        buttonEl.classList.add('active-cat', 'border-neutral-900', 'text-neutral-900', 'font-bold');
        buttonEl.classList.remove('border-transparent', 'text-neutral-500');
    }

    const catalogGrid = document.getElementById('all-menu-grid');
    const emptyState = document.getElementById('all-menu-empty-state');
    const items = document.querySelectorAll('.catalog-card-item');

    if (catalogGrid) {
        catalogGrid.style.opacity = '0.3';
    }

    setTimeout(() => {
        let matchCount = 0;

        items.forEach(item => {
            const itemCat = item.getAttribute('data-category');
            if (category === 'all' || itemCat === category) {
                item.style.display = 'flex';
                matchCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (emptyState) {
            if (matchCount === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        if (catalogGrid) {
            catalogGrid.style.opacity = '1';
        }
    }, 180);
};

/* ==========================================================================
   7. GALLERY LIGHTBOX MODAL
   ========================================================================== */
let galleryItems = [];
let currentGalleryIndex = 0;

function initGalleryLightbox() {
    const images = document.querySelectorAll('.gallery-trigger-img');
    galleryItems = Array.from(images).map(img => ({
        src: img.getAttribute('data-full-src') || img.getAttribute('src'),
        title: img.getAttribute('alt') || 'Nucomu Cafe'
    }));
}

window.openLightbox = function(index) {
    if (galleryItems.length === 0) {
        initGalleryLightbox();
    }
    currentGalleryIndex = index;
    const modal = document.getElementById('lightbox-modal');
    const imgEl = document.getElementById('lightbox-image');
    const titleEl = document.getElementById('lightbox-title');
    const counterEl = document.getElementById('lightbox-counter');

    if (!modal || !imgEl) return;

    const item = galleryItems[currentGalleryIndex];
    if (item) {
        imgEl.src = item.src;
        if (titleEl) titleEl.textContent = item.title;
        if (counterEl) counterEl.textContent = `${currentGalleryIndex + 1} / ${galleryItems.length}`;
    }

    modal.classList.remove('opacity-0', 'pointer-events-none');
    modal.classList.add('opacity-100');
    document.body.classList.add('overflow-hidden');
};

window.closeLightbox = function() {
    const modal = document.getElementById('lightbox-modal');
    if (!modal) return;
    modal.classList.add('opacity-0', 'pointer-events-none');
    modal.classList.remove('opacity-100');
    document.body.classList.remove('overflow-hidden');
};

window.nextLightbox = function(event) {
    if (event) event.stopPropagation();
    if (!galleryItems.length) return;
    currentGalleryIndex = (currentGalleryIndex + 1) % galleryItems.length;
    updateLightboxContent();
};

window.prevLightbox = function(event) {
    if (event) event.stopPropagation();
    if (!galleryItems.length) return;
    currentGalleryIndex = (currentGalleryIndex - 1 + galleryItems.length) % galleryItems.length;
    updateLightboxContent();
};

function updateLightboxContent() {
    const imgEl = document.getElementById('lightbox-image');
    const titleEl = document.getElementById('lightbox-title');
    const counterEl = document.getElementById('lightbox-counter');
    const item = galleryItems[currentGalleryIndex];
    if (!item || !imgEl) return;

    imgEl.style.opacity = '0.3';
    setTimeout(() => {
        imgEl.src = item.src;
        if (titleEl) titleEl.textContent = item.title;
        if (counterEl) counterEl.textContent = `${currentGalleryIndex + 1} / ${galleryItems.length}`;
        imgEl.style.opacity = '1';
    }, 150);
}

/* ==========================================================================
   8. SERVICE DETAIL MODAL
   ========================================================================== */
window.openServiceDetailModal = function(serviceJson) {
    try {
        const service = typeof serviceJson === 'string' ? JSON.parse(serviceJson) : serviceJson;
        const modal = document.getElementById('service-detail-modal');
        if (!modal) return;

        document.getElementById('service-modal-title').textContent = service.title || 'Layanan Nucomu';
        document.getElementById('service-modal-category').textContent = service.category || 'Layanan';
        document.getElementById('service-modal-desc').textContent = service.full_desc || service.short_desc || '';
        document.getElementById('service-modal-area').textContent = service.suggested_area || 'Indoor / Outdoor';
        
        const imgEl = document.getElementById('service-modal-image');
        if (imgEl && service.image) {
            imgEl.src = service.image;
        }

        const reserveBtn = document.getElementById('service-modal-reserve-btn');
        if (reserveBtn) {
            reserveBtn.onclick = () => {
                window.closeServiceDetailModal();
                window.openReservationModal(service.title);
            };
        }

        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100');
        document.body.classList.add('overflow-hidden');
    } catch (e) {
        console.error('Error opening service modal:', e);
    }
};

window.closeServiceDetailModal = function() {
    const modal = document.getElementById('service-detail-modal');
    if (!modal) return;
    modal.classList.add('opacity-0', 'pointer-events-none');
    modal.classList.remove('opacity-100');
    document.body.classList.remove('overflow-hidden');
};

/* ==========================================================================
   9. RESERVATION MODAL & LAYANAN DETAIL PANEL
   ========================================================================== */
let currentLayananCategory = 'Bridal';

window.selectLayananCategoryChip = function(category) {
    currentLayananCategory = category;
    const btnBridal = document.getElementById('layanan-chip-bridal');
    const btnBirthday = document.getElementById('layanan-chip-birthday');
    const descBridal = document.getElementById('layanan-desc-bridal');
    const descBirthday = document.getElementById('layanan-desc-birthday');

    const activeClasses = 'layanan-chip w-full min-h-[36px] sm:min-h-[38px] px-1 sm:px-2 py-1.5 sm:py-2 rounded-full text-[9.5px] sm:text-[11px] xl:text-xs font-semibold tracking-tight sm:tracking-normal uppercase transition-all duration-200 bg-[#242424] text-white shadow-xs focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2 inline-flex items-center justify-center text-center whitespace-nowrap';
    const inactiveClasses = 'layanan-chip w-full min-h-[36px] sm:min-h-[38px] px-1 sm:px-2 py-1.5 sm:py-2 rounded-full text-[9.5px] sm:text-[11px] xl:text-xs font-semibold tracking-tight sm:tracking-normal uppercase transition-all duration-200 bg-white border border-[#CCCCCC] text-[#444444] hover:border-[#8A8A8A] hover:text-[#1C1C1C] focus:outline-none focus:ring-2 focus:ring-[#242424] focus:ring-offset-2 inline-flex items-center justify-center text-center whitespace-nowrap';

    if (category === 'Bridal') {
        if (btnBridal) {
            btnBridal.setAttribute('aria-selected', 'true');
            btnBridal.className = activeClasses;
        }
        if (btnBirthday) {
            btnBirthday.setAttribute('aria-selected', 'false');
            btnBirthday.className = inactiveClasses;
        }
        if (descBridal) {
            descBridal.classList.remove('hidden', 'opacity-0');
            descBridal.classList.add('opacity-100');
        }
        if (descBirthday) {
            descBirthday.classList.add('hidden', 'opacity-0');
            descBirthday.classList.remove('opacity-100');
        }
    } else {
        if (btnBirthday) {
            btnBirthday.setAttribute('aria-selected', 'true');
            btnBirthday.className = activeClasses;
        }
        if (btnBridal) {
            btnBridal.setAttribute('aria-selected', 'false');
            btnBridal.className = inactiveClasses;
        }
        if (descBirthday) {
            descBirthday.classList.remove('hidden', 'opacity-0');
            descBirthday.classList.add('opacity-100');
        }
        if (descBridal) {
            descBridal.classList.add('hidden', 'opacity-0');
            descBridal.classList.remove('opacity-100');
        }
    }
};

window.toggleLayananPackagePreview = window.selectLayananCategoryChip;

// Dukungan tombol panah / toggle cepat antar paket
window.toggleLayananCategory = function() {
    if (currentLayananCategory === 'Bridal') {
        window.selectLayananCategoryChip('Birthday');
    } else {
        window.selectLayananCategoryChip('Bridal');
    }
};

/* ==========================================================================
   DETAIL PANEL TOGGLE & NOTCH POSITIONING
   ========================================================================== */
window.toggleLayananDetailPanel = function() {
    const panelWrapper = document.getElementById('layanan-detail-panel');
    const toggleBtn = document.getElementById('btn-toggle-layanan-detail');
    const toggleText = document.getElementById('btn-toggle-layanan-text');
    const toggleIcon = document.getElementById('btn-toggle-layanan-icon');
    const cardReservasi = document.getElementById('layanan-card-reservasi');

    if (!panelWrapper || !toggleBtn) return;

    const isOpen = panelWrapper.classList.contains('is-open');

    if (isOpen) {
        window.closeLayananDetailPanel();
    } else {
        panelWrapper.classList.remove('hidden');
        panelWrapper.setAttribute('aria-hidden', 'false');
        toggleBtn.setAttribute('aria-expanded', 'true');
        if (toggleText) toggleText.textContent = 'Tutup Detail';
        if (toggleIcon) toggleIcon.classList.add('rotate-180');

        if (cardReservasi) {
            cardReservasi.classList.add('is-active');
        }

        requestAnimationFrame(() => {
            panelWrapper.classList.add('is-open');
            window.updateLayananNotchPosition();

            setTimeout(() => {
                const rect = panelWrapper.getBoundingClientRect();
                if (rect.bottom > window.innerHeight) {
                    panelWrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }, 300);
        });
    }
};

window.closeLayananDetailPanel = function() {
    const panelWrapper = document.getElementById('layanan-detail-panel');
    const toggleBtn = document.getElementById('btn-toggle-layanan-detail');
    const toggleText = document.getElementById('btn-toggle-layanan-text');
    const toggleIcon = document.getElementById('btn-toggle-layanan-icon');
    const cardReservasi = document.getElementById('layanan-card-reservasi');

    if (!panelWrapper || !panelWrapper.classList.contains('is-open')) return;

    panelWrapper.classList.remove('is-open');
    panelWrapper.setAttribute('aria-hidden', 'true');
    if (toggleBtn) {
        toggleBtn.setAttribute('aria-expanded', 'false');
        if (typeof toggleBtn.focus === 'function') {
            toggleBtn.focus();
        }
    }
    if (toggleText) toggleText.textContent = 'Lihat Detail';
    if (toggleIcon) toggleIcon.classList.remove('rotate-180');

    if (cardReservasi) {
        cardReservasi.classList.remove('is-active');
    }

    setTimeout(() => {
        if (!panelWrapper.classList.contains('is-open')) {
            panelWrapper.classList.add('hidden');
        }
    }, 400);
};

window.updateLayananNotchPosition = function() {
    const card = document.getElementById('layanan-card-reservasi');
    const panel = document.getElementById('layanan-detail-card');
    const notch = document.getElementById('layanan-panel-notch');
    if (!card || !panel || !notch) return;

    const cardRect = card.getBoundingClientRect();
    const panelRect = panel.getBoundingClientRect();
    const notchX = (cardRect.left + cardRect.width / 2) - panelRect.left;
    notch.style.left = `${Math.round(notchX)}px`;
};

window.addEventListener('resize', () => {
    const panel = document.getElementById('layanan-detail-panel');
    if (panel && panel.classList.contains('is-open')) {
        window.updateLayananNotchPosition();
    }
});

window.openReservationWithCurrentCategory = function() {
    const triggerBtn = document.getElementById('btn-layanan-open-reservation') || document.getElementById('btn-open-reservasi-layanan') || document.activeElement;
    window.openReservationModal(currentLayananCategory || 'Reservasi Meja & Acara Privat', triggerBtn);
};

window.pickLayananPackageAndOpenModal = function(pkgCode, pkgName, priceShort, pkgDesc) {
    const triggerEl = document.activeElement;
    window.openReservationModal(currentLayananCategory, triggerEl);
    const targetCard = document.querySelector(`.res-pkg-card[data-section="${currentLayananCategory}"][data-pkg-code="${pkgCode}"]`);
    if (targetCard) {
        window.selectReservationPackage(currentLayananCategory, pkgName, priceShort, pkgDesc, targetCard);
    }
};

/* ==========================================================================
   LAYANAN DETAIL PANEL - PHOTO GALLERY & AREA SWITCHER (INDOOR / OUTDOOR)
   ========================================================================== */
const DEFAULT_LAYANAN_PHOTOS = {
    indoor: [
        {
            src: '/images/nucomu/nucomu_story_4.png',
            alt: 'Area Meja Indoor & Sudut Nucomu Cafe',
            title: 'Area Indoor',
            subtitle: 'Bebas pilih area Indoor AC sejuk'
        },
        {
            src: '/images/nucomu/nucomu_story_2.png',
            alt: 'Indoor Lounge & Meja Kerja Nucomu Cafe',
            title: 'Area Indoor',
            subtitle: 'Bebas pilih area Indoor AC sejuk'
        },
        {
            src: '/images/nucomu/nucomu_story_1.png',
            alt: 'Espresso Bar & Counter Seduh Nucomu Cafe',
            title: 'Area Indoor',
            subtitle: 'Bebas pilih area Indoor AC sejuk'
        }
    ],
    outdoor: [
        {
            src: '/images/nucomu/nucomu_outdoor_2.png',
            alt: 'Outdoor Garden Terrace Nucomu Cafe',
            title: 'Area Outdoor',
            subtitle: 'Bebas pilih area Outdoor Garden asri'
        },
        {
            src: '/images/nucomu/nucomu_outdoor_3.png',
            alt: 'Outdoor Reserved Table Nucomu Cafe',
            title: 'Area Outdoor',
            subtitle: 'Bebas pilih area Outdoor Garden asri'
        },
        {
            src: '/images/nucomu/Beranda Kafe Nucomu yang Hangat.png',
            alt: 'Teras Depan Kafe Nucomu',
            title: 'Area Outdoor',
            subtitle: 'Bebas pilih area Outdoor Garden asri'
        }
    ]
};

let currentLayananArea = 'indoor';
let currentLayananPhotoIdx = 0;

function getLayananPhotosList() {
    const source = window.NUCOMU_LAYANAN_PHOTOS || DEFAULT_LAYANAN_PHOTOS;
    return source[currentLayananArea] || source.indoor;
}

window.renderLayananPhoto = function() {
    const list = getLayananPhotosList();
    if (!list || list.length === 0) return;

    if (currentLayananPhotoIdx >= list.length) currentLayananPhotoIdx = 0;
    if (currentLayananPhotoIdx < 0) currentLayananPhotoIdx = list.length - 1;

    const photo = list[currentLayananPhotoIdx];
    const imgEl = document.getElementById('layanan-photo-img');
    const titleEl = document.getElementById('layanan-photo-title');
    const subtitleEl = document.getElementById('layanan-photo-subtitle');
    const counterEl = document.getElementById('layanan-photo-counter');
    const toggleBtn = document.getElementById('btn-toggle-layanan-area');
    const pillEl = document.querySelector('.group\\/pill');

    if (imgEl && photo) {
        imgEl.src = photo.src;
        imgEl.alt = photo.alt;
    }
    if (titleEl && photo) {
        titleEl.textContent = photo.title;
    }
    if (subtitleEl && photo) {
        subtitleEl.textContent = photo.subtitle;
    }
    if (counterEl) {
        counterEl.textContent = `${currentLayananPhotoIdx + 1}/${list.length}`;
    }
    const nextAreaLabel = currentLayananArea === 'indoor' ? 'Area Outdoor' : 'Area Indoor';
    if (toggleBtn) {
        toggleBtn.setAttribute('title', `Ganti ke ${nextAreaLabel}`);
        toggleBtn.setAttribute('aria-label', `Ganti ke ${nextAreaLabel}`);
    }
    if (pillEl) {
        pillEl.setAttribute('aria-label', `Ganti ke ${nextAreaLabel}`);
    }
};

window.nextLayananPhoto = function() {
    const list = getLayananPhotosList();
    if (!list || list.length === 0) return;
    currentLayananPhotoIdx = (currentLayananPhotoIdx + 1) % list.length;
    window.renderLayananPhoto();
};

window.prevLayananPhoto = function() {
    const list = getLayananPhotosList();
    if (!list || list.length === 0) return;
    currentLayananPhotoIdx = (currentLayananPhotoIdx - 1 + list.length) % list.length;
    window.renderLayananPhoto();
};

window.toggleLayananPhotoArea = function() {
    currentLayananArea = (currentLayananArea === 'indoor') ? 'outdoor' : 'indoor';
    currentLayananPhotoIdx = 0;
    window.renderLayananPhoto();
};

function initLayananSection() {
    const section = document.getElementById('layanan');
    if (!section) return;

    // 1. Entrance animation on scroll into view
    function triggerEntrance() {
        section.classList.add('layanan-in-view');
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        triggerEntrance();
    } else {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    triggerEntrance();
                    observer.unobserve(section);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -30px 0px'
        });

        observer.observe(section);

        window.addEventListener('hashchange', () => {
            if (location.hash === '#layanan') {
                triggerEntrance();
            }
        });
    }

    // 2. Initialize photo display
    window.renderLayananPhoto();
}

let lastReservationTriggerElement = null;
let reservationFocusTrapHandler = null;

function setupReservationFocusTrap(modal) {
    teardownReservationFocusTrap(modal);

    const focusableSelectors = 'button:not([disabled]), [href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

    // Focus close button or first input shortly after modal opens
    setTimeout(() => {
        const firstInput = document.getElementById('res-name');
        if (firstInput && typeof firstInput.focus === 'function') {
            firstInput.focus();
        } else {
            const firstFocusable = modal.querySelector(focusableSelectors);
            if (firstFocusable && typeof firstFocusable.focus === 'function') {
                firstFocusable.focus();
            }
        }
    }, 60);

    reservationFocusTrapHandler = function(e) {
        if (e.key !== 'Tab') return;

        const focusables = Array.from(modal.querySelectorAll(focusableSelectors)).filter(
            el => el.offsetParent !== null && !el.closest('.hidden')
        );

        if (focusables.length === 0) return;

        const firstEl = focusables[0];
        const lastEl = focusables[focusables.length - 1];

        if (e.shiftKey) {
            if (document.activeElement === firstEl || !modal.contains(document.activeElement)) {
                e.preventDefault();
                lastEl.focus();
            }
        } else {
            if (document.activeElement === lastEl || !modal.contains(document.activeElement)) {
                e.preventDefault();
                firstEl.focus();
            }
        }
    };

    modal.addEventListener('keydown', reservationFocusTrapHandler);
}

function teardownReservationFocusTrap(modal) {
    if (reservationFocusTrapHandler && modal) {
        modal.removeEventListener('keydown', reservationFocusTrapHandler);
        reservationFocusTrapHandler = null;
    }
}

window.resetReservationEventCategory = function() {
    const promptEl = document.getElementById('res-empty-category-prompt');
    const summaryBar = document.getElementById('res-package-summary-bar');
    const bridalSec = document.getElementById('res-section-bridal');
    const birthdaySec = document.getElementById('res-section-birthday');
    const btnBridal = document.getElementById('btn-choose-bridal');
    const btnBirthday = document.getElementById('btn-choose-birthday');
    const iconBoxBridal = document.getElementById('icon-box-bridal');
    const iconBoxBirthday = document.getElementById('icon-box-birthday');
    const labelBridal = document.getElementById('label-cat-bridal');
    const labelBirthday = document.getElementById('label-cat-birthday');
    const titleBridal = document.getElementById('title-cat-bridal');
    const titleBirthday = document.getElementById('title-cat-birthday');
    const subBridal = document.getElementById('sub-cat-bridal');
    const subBirthday = document.getElementById('sub-cat-birthday');
    const checkBridal = document.getElementById('check-cat-bridal');
    const checkBirthday = document.getElementById('check-cat-birthday');

    if (promptEl) promptEl.classList.remove('hidden');
    if (summaryBar) {
        summaryBar.classList.add('hidden');
        summaryBar.classList.remove('flex');
    }
    if (bridalSec) {
        bridalSec.classList.add('hidden');
        bridalSec.classList.remove('is-entered');
    }
    if (birthdaySec) {
        birthdaySec.classList.add('hidden');
        birthdaySec.classList.remove('is-entered');
    }

    if (btnBridal) {
        btnBridal.className = 'res-section-tab-btn group text-left rounded-2xl p-4 border border-[#E5E5E5] hover:border-[#CCCCCC] bg-white transition-all duration-200 flex items-center justify-between gap-3 shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#242424]';
    }
    if (iconBoxBridal) {
        iconBoxBridal.className = 'w-11 h-11 rounded-xl bg-[#F5F5F5] border border-[#E5E5E5] text-[#1C1C1C] flex items-center justify-center shrink-0 transition-colors';
    }
    if (labelBridal) {
        labelBridal.className = 'text-[10px] font-semibold uppercase tracking-widest text-[#8A8A8A] block transition-colors';
    }
    if (titleBridal) {
        titleBridal.className = 'font-sans text-base sm:text-lg font-semibold text-[#1C1C1C] block leading-tight transition-colors';
    }
    if (subBridal) {
        subBridal.className = 'text-[11px] text-[#666666] block mt-0.5 transition-colors';
    }
    if (checkBridal) {
        checkBridal.className = 'w-6 h-6 rounded-full border border-[#E5E5E5] bg-white flex items-center justify-center text-xs font-bold text-transparent transition-all shrink-0';
    }

    if (btnBirthday) {
        btnBirthday.className = 'res-section-tab-btn group text-left rounded-2xl p-4 border border-[#E5E5E5] hover:border-[#CCCCCC] bg-white transition-all duration-200 flex items-center justify-between gap-3 shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#242424]';
    }
    if (iconBoxBirthday) {
        iconBoxBirthday.className = 'w-11 h-11 rounded-xl bg-[#F5F5F5] border border-[#E5E5E5] text-[#1C1C1C] flex items-center justify-center shrink-0 transition-colors';
    }
    if (labelBirthday) {
        labelBirthday.className = 'text-[10px] font-semibold uppercase tracking-widest text-[#8A8A8A] block transition-colors';
    }
    if (titleBirthday) {
        titleBirthday.className = 'font-sans text-base sm:text-lg font-semibold text-[#1C1C1C] block leading-tight transition-colors';
    }
    if (subBirthday) {
        subBirthday.className = 'text-[11px] text-[#666666] block mt-0.5 transition-colors';
    }
    if (checkBirthday) {
        checkBirthday.className = 'w-6 h-6 rounded-full border border-[#E5E5E5] bg-white flex items-center justify-center text-xs font-bold text-transparent transition-all shrink-0';
    }

    const serviceInput = document.getElementById('res-service-type');
    const sectionInput = document.getElementById('res-event-section');
    const packageInput = document.getElementById('res-event-package');
    if (serviceInput) serviceInput.value = '';
    if (sectionInput) sectionInput.value = '';
    if (packageInput) packageInput.value = '';
};

window.chooseReservationEventCategory = function(categoryKey) {
    const key = (categoryKey || '').toLowerCase();
    const promptEl = document.getElementById('res-empty-category-prompt');
    const summaryBar = document.getElementById('res-package-summary-bar');
    const bridalSec = document.getElementById('res-section-bridal');
    const birthdaySec = document.getElementById('res-section-birthday');
    const btnBridal = document.getElementById('btn-choose-bridal');
    const btnBirthday = document.getElementById('btn-choose-birthday');
    const iconBoxBridal = document.getElementById('icon-box-bridal');
    const iconBoxBirthday = document.getElementById('icon-box-birthday');
    const labelBridal = document.getElementById('label-cat-bridal');
    const labelBirthday = document.getElementById('label-cat-birthday');
    const titleBridal = document.getElementById('title-cat-bridal');
    const titleBirthday = document.getElementById('title-cat-birthday');
    const subBridal = document.getElementById('sub-cat-bridal');
    const subBirthday = document.getElementById('sub-cat-birthday');
    const checkBridal = document.getElementById('check-cat-bridal');
    const checkBirthday = document.getElementById('check-cat-birthday');

    if (promptEl) promptEl.classList.add('hidden');
    if (summaryBar) {
        summaryBar.classList.remove('hidden');
        summaryBar.classList.add('flex');
    }

    if (key === 'bridal') {
        if (birthdaySec) {
            birthdaySec.classList.add('hidden');
            birthdaySec.classList.remove('is-entered');
        }
        if (bridalSec) {
            bridalSec.classList.remove('hidden');
            bridalSec.classList.add('is-entering');
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    bridalSec.classList.remove('is-entering');
                    bridalSec.classList.add('is-entered');
                });
            });
        }

        // Active Bridal Button: #242424 with white text, and label #B5B5B5 (WCAG AA compliant)
        if (btnBridal) {
            btnBridal.className = 'res-section-tab-btn group text-left rounded-2xl p-4 border-2 border-[#242424] ring-2 ring-[#242424]/10 bg-[#242424] text-white transition-all duration-200 flex items-center justify-between gap-3 shadow-md focus:outline-none focus:ring-2 focus:ring-[#242424]';
        }
        if (iconBoxBridal) {
            iconBoxBridal.className = 'w-11 h-11 rounded-xl bg-white/10 border border-white/20 text-white flex items-center justify-center shrink-0 transition-colors';
        }
        if (labelBridal) {
            labelBridal.className = 'text-[10px] font-semibold uppercase tracking-widest text-[#B5B5B5] block transition-colors';
        }
        if (titleBridal) {
            titleBridal.className = 'font-sans text-base sm:text-lg font-semibold text-white block leading-tight transition-colors';
        }
        if (subBridal) {
            subBridal.className = 'text-[11px] text-white/75 block mt-0.5 transition-colors';
        }
        if (checkBridal) {
            checkBridal.className = 'w-6 h-6 rounded-full bg-white text-[#242424] border border-white flex items-center justify-center text-xs font-bold transition-all shrink-0 res-check-pop';
        }

        // Inactive Birthday Button: clean white card with #8A8A8A label
        if (btnBirthday) {
            btnBirthday.className = 'res-section-tab-btn group text-left rounded-2xl p-4 border border-[#E5E5E5] hover:border-[#CCCCCC] bg-white transition-all duration-200 flex items-center justify-between gap-3 shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#242424]';
        }
        if (iconBoxBirthday) {
            iconBoxBirthday.className = 'w-11 h-11 rounded-xl bg-[#F5F5F5] border border-[#E5E5E5] text-[#1C1C1C] flex items-center justify-center shrink-0 transition-colors';
        }
        if (labelBirthday) {
            labelBirthday.className = 'text-[10px] font-semibold uppercase tracking-widest text-[#8A8A8A] block transition-colors';
        }
        if (titleBirthday) {
            titleBirthday.className = 'font-sans text-base sm:text-lg font-semibold text-[#1C1C1C] block leading-tight transition-colors';
        }
        if (subBirthday) {
            subBirthday.className = 'text-[11px] text-[#666666] block mt-0.5 transition-colors';
        }
        if (checkBirthday) {
            checkBirthday.className = 'w-6 h-6 rounded-full border border-[#E5E5E5] bg-white flex items-center justify-center text-xs font-bold text-transparent transition-all shrink-0';
        }

        const firstBridalCard = document.querySelector('.res-pkg-card[data-section="Bridal"][data-pkg-code="A"]');
        if (firstBridalCard) {
            window.selectReservationPackage(
                'Bridal',
                'Paket Dekorasi A',
                '35K',
                'Dekorasi meja, lilin, dipinjami korek, lettering ucapan',
                firstBridalCard
            );
        }
    } else if (key === 'birthday') {
        if (bridalSec) {
            bridalSec.classList.add('hidden');
            bridalSec.classList.remove('is-entered');
        }
        if (birthdaySec) {
            birthdaySec.classList.remove('hidden');
            birthdaySec.classList.add('is-entering');
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    birthdaySec.classList.remove('is-entering');
                    birthdaySec.classList.add('is-entered');
                });
            });
        }

        // Active Birthday Button: #242424 with white text, and label #B5B5B5 (WCAG AA compliant)
        if (btnBirthday) {
            btnBirthday.className = 'res-section-tab-btn group text-left rounded-2xl p-4 border-2 border-[#242424] ring-2 ring-[#242424]/10 bg-[#242424] text-white transition-all duration-200 flex items-center justify-between gap-3 shadow-md focus:outline-none focus:ring-2 focus:ring-[#242424]';
        }
        if (iconBoxBirthday) {
            iconBoxBirthday.className = 'w-11 h-11 rounded-xl bg-white/10 border border-white/20 text-white flex items-center justify-center shrink-0 transition-colors';
        }
        if (labelBirthday) {
            labelBirthday.className = 'text-[10px] font-semibold uppercase tracking-widest text-[#B5B5B5] block transition-colors';
        }
        if (titleBirthday) {
            titleBirthday.className = 'font-sans text-base sm:text-lg font-semibold text-white block leading-tight transition-colors';
        }
        if (subBirthday) {
            subBirthday.className = 'text-[11px] text-white/75 block mt-0.5 transition-colors';
        }
        if (checkBirthday) {
            checkBirthday.className = 'w-6 h-6 rounded-full bg-white text-[#242424] border border-white flex items-center justify-center text-xs font-bold transition-all shrink-0 res-check-pop';
        }

        // Inactive Bridal Button: clean white card with #8A8A8A label
        if (btnBridal) {
            btnBridal.className = 'res-section-tab-btn group text-left rounded-2xl p-4 border border-[#E5E5E5] hover:border-[#CCCCCC] bg-white transition-all duration-200 flex items-center justify-between gap-3 shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#242424]';
        }
        if (iconBoxBridal) {
            iconBoxBridal.className = 'w-11 h-11 rounded-xl bg-[#F5F5F5] border border-[#E5E5E5] text-[#1C1C1C] flex items-center justify-center shrink-0 transition-colors';
        }
        if (labelBridal) {
            labelBridal.className = 'text-[10px] font-semibold uppercase tracking-widest text-[#8A8A8A] block transition-colors';
        }
        if (titleBridal) {
            titleBridal.className = 'font-sans text-base sm:text-lg font-semibold text-[#1C1C1C] block leading-tight transition-colors';
        }
        if (subBridal) {
            subBridal.className = 'text-[11px] text-[#666666] block mt-0.5 transition-colors';
        }
        if (checkBridal) {
            checkBridal.className = 'w-6 h-6 rounded-full border border-[#E5E5E5] bg-white flex items-center justify-center text-xs font-bold text-transparent transition-all shrink-0';
        }

        const firstBirthdayCard = document.querySelector('.res-pkg-card[data-section="Birthday"][data-pkg-code="A"]');
        if (firstBirthdayCard) {
            window.selectReservationPackage(
                'Birthday',
                'Paket Dekorasi A',
                '35K',
                'Dekorasi meja, lilin, dipinjami korek, lettering ucapan',
                firstBirthdayCard
            );
        }
    }
};

window.switchEventPackageSectionTab = window.chooseReservationEventCategory;

window.openReservationModal = function(preferredService = '', triggerEl = null) {
    const modal = document.getElementById('reservation-modal');
    if (!modal) return;

    if (triggerEl && typeof triggerEl.focus === 'function') {
        lastReservationTriggerElement = triggerEl;
    } else if (document.activeElement && typeof document.activeElement.focus === 'function') {
        lastReservationTriggerElement = document.activeElement;
    }

    const lower = (preferredService || '').toLowerCase();
    if (lower === 'birthday' || lower.includes('ulang tahun') || lower.includes('paket birthday')) {
        window.chooseReservationEventCategory('birthday');
    } else if (lower === 'bridal' || lower.includes('paket bridal')) {
        window.chooseReservationEventCategory('bridal');
    } else {
        window.resetReservationEventCategory();
    }

    // Set minimum date to today
    const dateInput = document.getElementById('res-date');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.min = today;
        if (!dateInput.value) dateInput.value = today;
    }

    modal.classList.remove('modal-closing');
    modal.classList.remove('opacity-0', 'pointer-events-none');
    modal.classList.add('opacity-100', 'modal-open');
    document.body.classList.add('overflow-hidden');

    setupReservationFocusTrap(modal);
};

window.selectReservationPackage = function(sectionName, packageName, priceShort, packageDesc, cardElement) {
    const serviceInput = document.getElementById('res-service-type');
    const sectionInput = document.getElementById('res-event-section');
    const packageInput = document.getElementById('res-event-package');
    const summaryEl = document.getElementById('res-selected-package-summary');
    const detailEl = document.getElementById('res-selected-package-detail');

    const fullServiceLabel = `${sectionName} - ${packageName} (${priceShort})`;
    const fullPackageLabel = `${packageName} : ${priceShort} (${packageDesc})`;

    if (serviceInput) serviceInput.value = fullServiceLabel;
    if (sectionInput) sectionInput.value = sectionName;
    if (packageInput) packageInput.value = fullPackageLabel;
    if (summaryEl) summaryEl.innerText = `${sectionName} — ${packageName} : ${priceShort}`;
    if (detailEl) detailEl.innerText = packageDesc;

    // Reset all package cards to monochrome default
    const allCards = document.querySelectorAll('.res-pkg-card');
    allCards.forEach(card => {
        card.className = 'res-pkg-card cursor-pointer rounded-xl p-3.5 border border-[#E5E5E5] bg-white hover:border-[#CCCCCC] transition-all duration-200 flex flex-col justify-between relative shadow-2xs';
        const check = card.querySelector('.res-pkg-check');
        if (check) {
            check.className = 'res-pkg-check w-5 h-5 rounded-full flex items-center justify-center text-[11px] font-bold transition-all bg-[#F5F5F5] text-transparent border border-[#E5E5E5]';
        }
    });

    // Highlight active card
    if (cardElement) {
        cardElement.className = 'res-pkg-card cursor-pointer rounded-xl p-3.5 border-2 border-[#242424] bg-white ring-2 ring-[#242424]/10 shadow-sm transition-all duration-200 flex flex-col justify-between relative';
        const activeCheck = cardElement.querySelector('.res-pkg-check');
        if (activeCheck) {
            activeCheck.className = 'res-pkg-check w-5 h-5 rounded-full flex items-center justify-center text-[11px] font-bold transition-all bg-[#242424] text-white border border-[#242424] res-check-pop';
        }
    }

    // Highlight active section badge
    const bridalBadge = document.getElementById('badge-active-bridal');
    const birthdayBadge = document.getElementById('badge-active-birthday');

    if (sectionName.toLowerCase() === 'bridal') {
        if (bridalBadge) {
            bridalBadge.classList.remove('hidden');
            bridalBadge.classList.add('inline-flex');
        }
        if (birthdayBadge) {
            birthdayBadge.classList.add('hidden');
            birthdayBadge.classList.remove('inline-flex');
        }
    } else {
        if (birthdayBadge) {
            birthdayBadge.classList.remove('hidden');
            birthdayBadge.classList.add('inline-flex');
        }
        if (bridalBadge) {
            bridalBadge.classList.add('hidden');
            bridalBadge.classList.remove('inline-flex');
        }
    }
};

window.closeReservationModal = function() {
    const modal = document.getElementById('reservation-modal');
    if (!modal || modal.classList.contains('pointer-events-none')) return;

    teardownReservationFocusTrap(modal);

    modal.classList.remove('modal-open');
    modal.classList.add('modal-closing');

    setTimeout(() => {
        modal.classList.remove('opacity-100', 'modal-closing');
        modal.classList.add('opacity-0', 'pointer-events-none');
        document.body.classList.remove('overflow-hidden');

        if (lastReservationTriggerElement && typeof lastReservationTriggerElement.focus === 'function') {
            try {
                lastReservationTriggerElement.focus();
            } catch (err) {}
            lastReservationTriggerElement = null;
        }
    }, 280);
};

window.handleReservationSubmit = function(e) {
    e.preventDefault();

    const fullName = document.getElementById('res-name').value.trim();
    const phone = document.getElementById('res-phone').value.trim();
    const date = document.getElementById('res-date').value;
    const time = document.getElementById('res-time').value;
    const guestCount = document.getElementById('res-guests').value;
    const serviceType = document.getElementById('res-service-type').value;
    const eventSection = document.getElementById('res-event-section')?.value || '';
    const eventPackage = document.getElementById('res-event-package')?.value || '';
    const area = document.getElementById('res-area').value;
    const notes = document.getElementById('res-notes').value.trim();

    // Reset previous error outlines
    ['res-name', 'res-phone', 'res-date', 'res-time', 'res-guests', 'res-area'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.remove('res-field-error');
    });

    // Validation checks
    let errors = [];
    let firstInvalidEl = null;

    if (!fullName || fullName.length < 2) {
        errors.push("Nama pemesan wajib diisi dengan benar.");
        const el = document.getElementById('res-name');
        if (el) {
            el.classList.add('res-field-error');
            if (!firstInvalidEl) firstInvalidEl = el;
        }
    }

    const cleanPhone = phone.replace(/[^0-9]/g, '');
    if (!cleanPhone || cleanPhone.length < 9) {
        errors.push("Nomor WhatsApp tidak valid (minimal 9 digit).");
        const el = document.getElementById('res-phone');
        if (el) {
            el.classList.add('res-field-error');
            if (!firstInvalidEl) firstInvalidEl = el;
        }
    }

    if (!date) {
        errors.push("Tanggal acara wajib ditentukan.");
        const el = document.getElementById('res-date');
        if (el) {
            el.classList.add('res-field-error');
            if (!firstInvalidEl) firstInvalidEl = el;
        }
    }

    if (!time) {
        errors.push("Waktu acara wajib dipilih.");
        const el = document.getElementById('res-time');
        if (el) {
            el.classList.add('res-field-error');
            if (!firstInvalidEl) firstInvalidEl = el;
        }
    }

    if (!guestCount) {
        errors.push("Jumlah tamu wajib dipilih.");
        const el = document.getElementById('res-guests');
        if (el) {
            el.classList.add('res-field-error');
            if (!firstInvalidEl) firstInvalidEl = el;
        }
    }

    if (!serviceType) {
        errors.push("Paket acara wajib dipilih.");
        const promptEl = document.getElementById('res-empty-category-prompt');
        if (promptEl) {
            promptEl.classList.add('res-field-error');
            if (!firstInvalidEl) firstInvalidEl = promptEl;
        }
    }

    if (!area) {
        errors.push("Pilihan area wajib ditentukan.");
        const el = document.getElementById('res-area');
        if (el) {
            el.classList.add('res-field-error');
            if (!firstInvalidEl) firstInvalidEl = el;
        }
    }

    const errorContainer = document.getElementById('res-error-box');
    if (errors.length > 0) {
        if (errorContainer) {
            errorContainer.innerHTML = errors.map(err => `<div>• ${err}</div>`).join('');
            errorContainer.classList.remove('hidden');
            errorContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            alert(errors.join("\n"));
        }
        if (firstInvalidEl && typeof firstInvalidEl.focus === 'function') {
            firstInvalidEl.focus();
        }
        return false;
    }

    if (errorContainer) {
        errorContainer.classList.add('hidden');
    }

    // Format Indonesian WhatsApp message
    const waNumber = window.NUCOMU_WA_NUMBER || '6281234567890';
    let message = `*RESERVASI KUNJUNGAN NUCOMU CAFE*\n\n`;
    message += `• *Nama Pemesan:* ${fullName}\n`;
    message += `• *WhatsApp:* ${phone}\n`;
    message += `• *Tanggal:* ${formatDisplayDate(date)}\n`;
    message += `• *Waktu:* ${time} WIB\n`;
    message += `• *Jumlah Tamu:* ${guestCount}\n`;
    message += `• *Kategori Acara:* ${eventSection || serviceType}\n`;
    if (eventPackage) {
        message += `• *Paket Dekorasi:* ${eventPackage}\n`;
    }
    message += `• *Pilihan Area:* ${area}\n`;
    message += `• *Catatan Khusus:* ${notes || '-'}\n\n`;
    message += `Mohon konfirmasi ketersediaan jadwal meja kami. Terima kasih!`;

    const encodedMsg = encodeURIComponent(message);
    const waUrl = `https://wa.me/${waNumber.replace(/[^0-9]/g, '')}?text=${encodedMsg}`;
    window.__lastGeneratedWaMessage = message;
    window.__lastGeneratedWaUrl = waUrl;

    // Show confirmation & redirect to WhatsApp
    const successToast = document.getElementById('res-success-toast');
    if (successToast) {
        successToast.classList.remove('hidden');
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    fetch('/reservasi', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken || ''
        },
        body: JSON.stringify({
            fullName,
            phone,
            date,
            time,
            guestCount,
            serviceType,
            eventSection,
            eventPackage,
            area,
            notes
        })
    }).then(res => res.json()).then(data => {
        const finalUrl = data.whatsapp_url || waUrl;
        window.open(finalUrl, '_blank');
        window.closeReservationModal();
        if (successToast) successToast.classList.add('hidden');
    }).catch(() => {
        window.open(waUrl, '_blank');
        window.closeReservationModal();
        if (successToast) successToast.classList.add('hidden');
    });

    return false;
};

function formatDisplayDate(dateStr) {
    if (!dateStr) return '';
    try {
        const parts = dateStr.split('-');
        const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return `${dateObj.getDate()} ${months[dateObj.getMonth()]} ${dateObj.getFullYear()}`;
    } catch (e) {
        return dateStr;
    }
}

/* ==========================================================================
   10. KEYBOARD SHORTCUTS (Escape & Arrows)
   ========================================================================== */
function initKeyboardShortcuts() {
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            // Bila modal reservasi sedang terbuka, Escape hanya menutup modal dulu, bukan panel detail
            const resModal = document.getElementById('reservation-modal');
            const isResModalOpen = resModal && !resModal.classList.contains('hidden') && (resModal.classList.contains('modal-open') || resModal.classList.contains('opacity-100')) && !resModal.classList.contains('pointer-events-none');
            
            if (isResModalOpen) {
                window.closeReservationModal();
                return;
            }

            window.closeLightbox();
            window.closeServiceDetailModal();
            if (typeof window.closeLayananDetailPanel === 'function') {
                window.closeLayananDetailPanel();
            }
            const drawer = document.getElementById('mobile-drawer');
            if (drawer && !drawer.classList.contains('pointer-events-none')) {
                window.toggleMobileDrawer();
            }
        } else if (e.key === 'ArrowRight') {
            const lightbox = document.getElementById('lightbox-modal');
            if (lightbox && !lightbox.classList.contains('pointer-events-none')) {
                window.nextLightbox();
            }
        } else if (e.key === 'ArrowLeft') {
            const lightbox = document.getElementById('lightbox-modal');
            if (lightbox && !lightbox.classList.contains('pointer-events-none')) {
                window.prevLightbox();
            }
        }
    });
}

/* ==========================================================================
   11. REVIEWS SLIDER (What Our Clients Say)
   ========================================================================== */
window.switchReviewSlide = function(slideIndex) {
    const slides = document.querySelectorAll('.review-slide');
    const dots = document.querySelectorAll('.review-dot');
    
    slides.forEach((slide, idx) => {
        if (idx === slideIndex) {
            slide.classList.remove('hidden');
            slide.style.opacity = '0';
            setTimeout(() => {
                slide.style.opacity = '1';
            }, 50);
        } else {
            slide.classList.add('hidden');
        }
    });

    dots.forEach((dot, idx) => {
        if (idx === slideIndex) {
            dot.className = 'review-dot w-6 h-2 bg-[#E06D24] rounded-full transition-all duration-300';
        } else {
            dot.className = 'review-dot w-2 h-2 bg-neutral-300 hover:bg-neutral-400 rounded-full transition-all duration-300';
        }
    });
};

/* ==========================================================================
   12. MENU VIDEO LAZY LOADING & VIEWPORT PLAYBACK CONTROL
   ========================================================================== */
function initMenuVideoLazyLoading() {
    if (!('IntersectionObserver' in window)) return;

    const menuVideos = document.querySelectorAll('video.menu-lazy-video');
    if (!menuVideos.length) return;

    const videoObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            const video = entry.target;
            // Never play hidden video elements (like hidden hero dish video)
            if (video.classList.contains('hidden') || video.closest('.hidden')) {
                video.pause();
                return;
            }

            if (entry.isIntersecting) {
                const playPromise = video.play();
                if (playPromise !== undefined) {
                    playPromise.catch(() => {});
                }
            } else {
                if (!video.paused) {
                    video.pause();
                }
            }
        });
    }, {
        rootMargin: '50px 0px 50px 0px',
        threshold: 0.15
    });

    menuVideos.forEach((v) => videoObserver.observe(v));
}
