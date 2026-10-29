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
    initBestMenuCarousel();
    initGalleryLightbox();
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
    const reveals = document.querySelectorAll('.reveal-on-scroll');
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
        if (orderBtn) {
            orderBtn.setAttribute('href', `/pesan?add=${encodeURIComponent(item.name)}`);
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
   9. RESERVATION MODAL & WHATSAPP GENERATOR
   ========================================================================== */
let currentLayananCategory = 'Bridal';

window.toggleLayananPackagePreview = function(category) {
    currentLayananCategory = category;
    const wrap = document.getElementById('layanan-package-options-wrap');
    const label = document.getElementById('layanan-selected-cat-label');
    const btnBridal = document.getElementById('layanan-btn-bridal');
    const btnBirthday = document.getElementById('layanan-btn-birthday');

    if (wrap) wrap.classList.remove('hidden');
    if (label) {
        label.innerText = `Pilihan Paket Acara ${category} — Klik salah satu paket untuk reservasi:`;
    }
    if (btnBridal && btnBirthday) {
        if (category === 'Bridal') {
            btnBridal.className = 'inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-full bg-rose-50 border-2 border-[#E06D24] text-xs font-bold text-[#2A1D17] shadow-sm transition-all';
            btnBirthday.className = 'inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-full bg-white hover:bg-amber-50 border border-stone-200 hover:border-amber-300 text-xs font-bold text-[#54392B] shadow-2xs transition-all';
        } else {
            btnBirthday.className = 'inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-full bg-amber-50 border-2 border-[#E06D24] text-xs font-bold text-[#2A1D17] shadow-sm transition-all';
            btnBridal.className = 'inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-full bg-white hover:bg-rose-50 border border-stone-200 hover:border-rose-300 text-xs font-bold text-[#54392B] shadow-2xs transition-all';
        }
    }
};

window.pickLayananPackageAndOpenModal = function(pkgCode, pkgName, priceShort, pkgDesc) {
    window.openReservationModal(currentLayananCategory);
    const targetCard = document.querySelector(`.res-pkg-card[data-section="${currentLayananCategory}"][data-pkg-code="${pkgCode}"]`);
    if (targetCard) {
        window.selectReservationPackage(currentLayananCategory, pkgName, priceShort, pkgDesc, targetCard);
    }
};

window.resetReservationEventCategory = function() {
    const promptEl = document.getElementById('res-empty-category-prompt');
    const summaryBar = document.getElementById('res-package-summary-bar');
    const bridalSec = document.getElementById('res-section-bridal');
    const birthdaySec = document.getElementById('res-section-birthday');
    const btnBridal = document.getElementById('btn-choose-bridal');
    const btnBirthday = document.getElementById('btn-choose-birthday');
    const checkBridal = document.getElementById('check-cat-bridal');
    const checkBirthday = document.getElementById('check-cat-birthday');

    if (promptEl) promptEl.classList.remove('hidden');
    if (summaryBar) {
        summaryBar.classList.add('hidden');
        summaryBar.classList.remove('flex');
    }
    if (bridalSec) bridalSec.classList.add('hidden');
    if (birthdaySec) birthdaySec.classList.add('hidden');

    if (btnBridal) {
        btnBridal.className = 'res-section-tab-btn group text-left rounded-2xl p-4 border-2 border-stone-200/90 hover:border-rose-400 bg-gradient-to-br from-[#FFFDFB] to-rose-50/40 transition-all duration-200 flex items-center justify-between gap-3 shadow-2xs';
    }
    if (btnBirthday) {
        btnBirthday.className = 'res-section-tab-btn group text-left rounded-2xl p-4 border-2 border-stone-200/90 hover:border-amber-400 bg-gradient-to-br from-[#FFFDFB] to-amber-50/40 transition-all duration-200 flex items-center justify-between gap-3 shadow-2xs';
    }
    if (checkBridal) {
        checkBridal.className = 'w-6 h-6 rounded-full border border-stone-300 bg-white flex items-center justify-center text-xs font-bold text-transparent transition-all shrink-0';
    }
    if (checkBirthday) {
        checkBirthday.className = 'w-6 h-6 rounded-full border border-stone-300 bg-white flex items-center justify-center text-xs font-bold text-transparent transition-all shrink-0';
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
    const checkBridal = document.getElementById('check-cat-bridal');
    const checkBirthday = document.getElementById('check-cat-birthday');

    if (promptEl) promptEl.classList.add('hidden');
    if (summaryBar) {
        summaryBar.classList.remove('hidden');
        summaryBar.classList.add('flex');
    }

    if (key === 'bridal') {
        if (bridalSec) bridalSec.classList.remove('hidden');
        if (birthdaySec) birthdaySec.classList.add('hidden');
        if (btnBridal) {
            btnBridal.className = 'res-section-tab-btn group text-left rounded-2xl p-4 border-2 border-[#E06D24] ring-2 ring-[#E06D24]/20 bg-gradient-to-br from-[#FFFDFB] to-rose-50/70 transition-all duration-200 flex items-center justify-between gap-3 shadow-md';
        }
        if (btnBirthday) {
            btnBirthday.className = 'res-section-tab-btn group text-left rounded-2xl p-4 border-2 border-stone-200/90 hover:border-amber-400 bg-gradient-to-br from-[#FFFDFB] to-amber-50/40 transition-all duration-200 flex items-center justify-between gap-3 shadow-2xs';
        }
        if (checkBridal) {
            checkBridal.className = 'w-6 h-6 rounded-full bg-[#E06D24] text-white flex items-center justify-center text-xs font-bold transition-all shrink-0';
        }
        if (checkBirthday) {
            checkBirthday.className = 'w-6 h-6 rounded-full border border-stone-300 bg-white flex items-center justify-center text-xs font-bold text-transparent transition-all shrink-0';
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
        if (birthdaySec) birthdaySec.classList.remove('hidden');
        if (bridalSec) bridalSec.classList.add('hidden');
        if (btnBirthday) {
            btnBirthday.className = 'res-section-tab-btn group text-left rounded-2xl p-4 border-2 border-[#E06D24] ring-2 ring-[#E06D24]/20 bg-gradient-to-br from-[#FFFDFB] to-amber-50/70 transition-all duration-200 flex items-center justify-between gap-3 shadow-md';
        }
        if (btnBridal) {
            btnBridal.className = 'res-section-tab-btn group text-left rounded-2xl p-4 border-2 border-stone-200/90 hover:border-rose-400 bg-gradient-to-br from-[#FFFDFB] to-rose-50/40 transition-all duration-200 flex items-center justify-between gap-3 shadow-2xs';
        }
        if (checkBirthday) {
            checkBirthday.className = 'w-6 h-6 rounded-full bg-[#E06D24] text-white flex items-center justify-center text-xs font-bold transition-all shrink-0';
        }
        if (checkBridal) {
            checkBridal.className = 'w-6 h-6 rounded-full border border-stone-300 bg-white flex items-center justify-center text-xs font-bold text-transparent transition-all shrink-0';
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

window.openReservationModal = function(preferredService = '') {
    const modal = document.getElementById('reservation-modal');
    if (!modal) return;

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

    modal.classList.remove('opacity-0', 'pointer-events-none');
    modal.classList.add('opacity-100');
    document.body.classList.add('overflow-hidden');
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

    // Reset all package cards
    const allCards = document.querySelectorAll('.res-pkg-card');
    allCards.forEach(card => {
        card.className = 'res-pkg-card cursor-pointer rounded-xl p-3.5 border border-stone-200/90 bg-white/80 hover:border-[#E06D24]/50 hover:bg-white transition-all duration-200 flex flex-col justify-between relative';
        const check = card.querySelector('.res-pkg-check');
        if (check) {
            check.className = 'res-pkg-check w-5 h-5 rounded-full flex items-center justify-center text-[11px] font-bold transition-all bg-stone-100 text-transparent border border-stone-300';
        }
    });

    // Highlight active card
    if (cardElement) {
        cardElement.className = 'res-pkg-card cursor-pointer rounded-xl p-3.5 border border-[#E06D24] bg-white ring-2 ring-[#E06D24]/20 shadow-md transition-all duration-200 flex flex-col justify-between relative';
        const activeCheck = cardElement.querySelector('.res-pkg-check');
        if (activeCheck) {
            activeCheck.className = 'res-pkg-check w-5 h-5 rounded-full flex items-center justify-center text-[11px] font-bold transition-all bg-[#E06D24] text-white';
        }
    }

    // Highlight active section container & badge
    const bridalSec = document.getElementById('res-section-bridal');
    const birthdaySec = document.getElementById('res-section-birthday');
    const bridalBadge = document.getElementById('badge-active-bridal');
    const birthdayBadge = document.getElementById('badge-active-birthday');

    if (sectionName.toLowerCase() === 'bridal') {
        if (bridalSec) {
            bridalSec.classList.add('border-2', 'border-[#E06D24]/30');
            bridalSec.classList.remove('border', 'border-stone-200/90');
        }
        if (birthdaySec) {
            birthdaySec.classList.remove('border-2', 'border-[#E06D24]/30');
            birthdaySec.classList.add('border', 'border-stone-200/90');
        }
        if (bridalBadge) {
            bridalBadge.classList.remove('hidden');
            bridalBadge.classList.add('inline-flex');
        }
        if (birthdayBadge) {
            birthdayBadge.classList.add('hidden');
            birthdayBadge.classList.remove('inline-flex');
        }
    } else {
        if (birthdaySec) {
            birthdaySec.classList.add('border-2', 'border-[#E06D24]/30');
            birthdaySec.classList.remove('border', 'border-stone-200/90');
        }
        if (bridalSec) {
            bridalSec.classList.remove('border-2', 'border-[#E06D24]/30');
            bridalSec.classList.add('border', 'border-stone-200/90');
        }
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
    if (!modal) return;
    modal.classList.add('opacity-0', 'pointer-events-none');
    modal.classList.remove('opacity-100');
    document.body.classList.remove('overflow-hidden');
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

    // Validation checks
    let errors = [];

    if (!fullName || fullName.length < 2) {
        errors.push("Nama pemesan wajib diisi dengan benar.");
    }

    const cleanPhone = phone.replace(/[^0-9]/g, '');
    if (!cleanPhone || cleanPhone.length < 9) {
        errors.push("Nomor WhatsApp tidak valid (minimal 9 digit).");
    }

    if (!date) {
        errors.push("Tanggal acara wajib ditentukan.");
    }

    if (!time) {
        errors.push("Waktu acara wajib dipilih.");
    }

    if (!guestCount) {
        errors.push("Jumlah tamu wajib dipilih.");
    }

    if (!serviceType) {
        errors.push("Paket acara wajib dipilih.");
    }

    if (!area) {
        errors.push("Pilihan area wajib ditentukan.");
    }

    const errorContainer = document.getElementById('res-error-box');
    if (errors.length > 0) {
        if (errorContainer) {
            errorContainer.innerHTML = errors.map(err => `<div>• ${err}</div>`).join('');
            errorContainer.classList.remove('hidden');
        } else {
            alert(errors.join("\n"));
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
            window.closeLightbox();
            window.closeServiceDetailModal();
            window.closeReservationModal();
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
