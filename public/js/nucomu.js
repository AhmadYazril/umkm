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
});

/* ==========================================================================
   0. HERO MULTI-SLIDE CAROUSEL (1-Minute Auto-Transition & Progress Bar)
   ========================================================================== */
let heroCurrentSlide = 0;
const HERO_SLIDE_DURATION = 60000; // 60 seconds (1 minute)
let heroStartTime = null;
let heroRemainingTime = HERO_SLIDE_DURATION;
let heroIsPaused = false;
let heroProgressAnimFrame = null;

function initHeroSlider() {
    const heroSection = document.getElementById('home');
    if (!heroSection) return;

    const slides = heroSection.querySelectorAll('.hero-slide-item');
    if (!slides.length) return;

    // Start 1-minute countdown progress
    startHeroTimer(HERO_SLIDE_DURATION);

    // Pause countdown when mouse hovers over hero, resume when mouse leaves
    heroSection.addEventListener('mouseenter', () => {
        heroIsPaused = true;
    });
    heroSection.addEventListener('mouseleave', () => {
        heroIsPaused = false;
    });

    // Touch swipe support for mobile
    let touchStartX = 0;
    heroSection.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
    }, { passive: true });

    heroSection.addEventListener('touchend', (e) => {
        const touchEndX = e.changedTouches[0].clientX;
        const diffX = touchStartX - touchEndX;
        if (Math.abs(diffX) > 40) {
            if (diffX > 0) {
                window.nextHeroSlide();
            } else {
                window.prevHeroSlide();
            }
        }
    }, { passive: true });
}

function startHeroTimer(duration = HERO_SLIDE_DURATION) {
    if (heroProgressAnimFrame) {
        cancelAnimationFrame(heroProgressAnimFrame);
    }
    
    heroStartTime = Date.now();
    heroRemainingTime = duration;

    function tick() {
        if (!heroIsPaused) {
            const elapsed = Date.now() - heroStartTime;
            const progress = Math.min((elapsed / duration) * 100, 100);

            const progressBar = document.getElementById('hero-progress-bar');
            if (progressBar) {
                progressBar.style.width = `${progress}%`;
            }

            if (elapsed >= duration) {
                window.nextHeroSlide();
                return;
            }
        } else {
            heroStartTime = Date.now() - (duration - heroRemainingTime);
        }

        heroProgressAnimFrame = requestAnimationFrame(tick);
    }

    heroProgressAnimFrame = requestAnimationFrame(tick);
}

window.nextHeroSlide = function() {
    const slides = document.querySelectorAll('.hero-slide-item');
    if (slides.length <= 1) return;
    heroCurrentSlide = (heroCurrentSlide + 1) % slides.length;
    renderHeroSlide(heroCurrentSlide);
};

window.prevHeroSlide = function() {
    const slides = document.querySelectorAll('.hero-slide-item');
    if (slides.length <= 1) return;
    heroCurrentSlide = (heroCurrentSlide - 1 + slides.length) % slides.length;
    renderHeroSlide(heroCurrentSlide);
};

window.goToHeroSlide = function(index) {
    heroCurrentSlide = index;
    renderHeroSlide(heroCurrentSlide);
};

function renderHeroSlide(index) {
    const slides = document.querySelectorAll('.hero-slide-item');
    const tabs = document.querySelectorAll('.hero-tab-btn');
    const dots = document.querySelectorAll('.hero-dot-btn');

    slides.forEach((slide, idx) => {
        if (idx === index) {
            slide.classList.remove('opacity-0', 'pointer-events-none', 'z-0');
            slide.classList.add('opacity-100', 'z-10');
            const content = slide.querySelector('.hero-content-wrapper');
            if (content) {
                content.classList.remove('translate-y-4', 'opacity-0');
                content.classList.add('translate-y-0', 'opacity-100');
            }
        } else {
            slide.classList.add('opacity-0', 'pointer-events-none', 'z-0');
            slide.classList.remove('opacity-100', 'z-10');
            const content = slide.querySelector('.hero-content-wrapper');
            if (content) {
                content.classList.add('translate-y-4', 'opacity-0');
                content.classList.remove('translate-y-0', 'opacity-100');
            }
        }
    });

    tabs.forEach((tab, idx) => {
        if (idx === index) {
            tab.className = 'hero-tab-btn active px-4 py-2 rounded-full bg-white text-neutral-900 font-bold text-xs uppercase tracking-wider shadow-md border border-white transition-all duration-300';
        } else {
            tab.className = 'hero-tab-btn px-4 py-2 rounded-full bg-white/10 hover:bg-white/20 text-white font-medium text-xs uppercase tracking-wider border border-white/10 transition-all duration-300';
        }
    });

    dots.forEach((dot, idx) => {
        if (idx === index) {
            dot.className = 'hero-dot-btn w-6 h-1.5 rounded-full bg-[#E06D24] transition-all duration-300';
        } else {
            dot.className = 'hero-dot-btn w-2 h-1.5 rounded-full bg-neutral-400/70 hover:bg-neutral-600 transition-all duration-300';
        }
    });

    // Reset progress bar & start new 60-second cycle
    startHeroTimer(HERO_SLIDE_DURATION);
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
            orderBtn.setAttribute('onclick', `openReservationModal('Pesanan Best Menu: ${item.name}')`);
        }
        if (imgEl) {
            imgEl.src = item.image;
            imgEl.alt = item.name;
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
window.openReservationModal = function(preferredService = '') {
    const modal = document.getElementById('reservation-modal');
    if (!modal) return;

    if (preferredService) {
        const serviceSelect = document.getElementById('res-service-type');
        if (serviceSelect) {
            for (let option of serviceSelect.options) {
                if (option.text.toLowerCase().includes(preferredService.toLowerCase()) ||
                    option.value.toLowerCase().includes(preferredService.toLowerCase())) {
                    option.selected = true;
                    break;
                }
            }
        }
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
        errors.push("Jenis layanan / acara wajib dipilih.");
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
    message += `• *Jenis Acara:* ${serviceType}\n`;
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
