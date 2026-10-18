/**
 * Nucomu Cafe — Product Showcase Animation Script
 *
 * ● Floating cup + ingredients: auto-play looping (no scroll needed)
 * ● Stats callouts, gauge meters, mascot, spotlight, gallery: fade-in on scroll
 * ● Order tracker stepper: scrub on scroll
 */

document.addEventListener('DOMContentLoaded', () => {
    initFloatingIngredients();
    initScrollRevealElements();
    initOrderTracker();
});

/* ==========================================================================
   1. Auto-play Floating Cup & Ingredients (loop continuously, no scroll)
   ========================================================================== */
function initFloatingIngredients() {
    if (typeof gsap === 'undefined') return;

    // Respect prefers-reduced-motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        // Just show everything static
        document.querySelectorAll('.showcase-ingredient').forEach(el => {
            el.style.opacity = '1';
        });
        showStaticMeterValues();
        return;
    }

    // ── 1a. Entrance: ingredients fly in from outside into their positions ──
    const ingredients = [
        { sel: '.ing-bean-1',  fromX: -120, fromY: -80,  rot: -180, delay: 0    },
        { sel: '.ing-ice',     fromX:  120, fromY: -90,  rot:  200, delay: 0.1  },
        { sel: '.ing-cinnamon',fromX: -140, fromY:  30,  rot: -160, delay: 0.2  },
        { sel: '.ing-mint',    fromX:  140, fromY:  40,  rot:  130, delay: 0.15 },
        { sel: '.ing-milk',    fromX:    0, fromY: -130, rot:  -90, delay: 0.05 },
        { sel: '.ing-sugar',   fromX: -100, fromY:  80,  rot:  120, delay: 0.25 },
        { sel: '.ing-bean-2',  fromX:  100, fromY:  70,  rot: -200, delay: 0.3  },
    ];

    ingredients.forEach(({ sel, fromX, fromY, rot, delay }) => {
        const el = document.querySelector(sel);
        if (!el) return;

        // Entrance animation (play once)
        gsap.fromTo(el,
            { opacity: 0, x: fromX, y: fromY, rotation: rot, scale: 0.3 },
            {
                opacity: 1, x: 0, y: 0, rotation: 0, scale: 1,
                duration: 1.4,
                delay: 0.3 + delay,
                ease: 'back.out(1.2)',
                onComplete: () => startIdleFloat(el, sel),
            }
        );
    });

    // ── 1b. Cup entrance ──
    gsap.fromTo('.showcase-cup-image',
        { opacity: 0, scale: 0.7, y: 30 },
        { opacity: 1, scale: 1, y: 0, duration: 1.2, delay: 0.15, ease: 'back.out(1.4)' }
    );
}

/**
 * After entrance, each ingredient gets its own independent idle float loop.
 * Uses random amplitude / phase so they don't all move in sync.
 */
function startIdleFloat(el, sel) {
    const amplitude = 10 + Math.random() * 14; // 10–24px
    const duration  = 3  + Math.random() * 2;  // 3–5 s
    const rotAmt    = 6  + Math.random() * 10; // 6–16 deg
    const xDrift    = (Math.random() - 0.5) * 10;

    gsap.to(el, {
        y: `-=${amplitude}`,
        x: `+=${xDrift}`,
        rotation: `+=${rotAmt}`,
        duration: duration,
        ease: 'sine.inOut',
        yoyo: true,
        repeat: -1,
    });
}

/* ==========================================================================
   2. Scroll-triggered Reveals: stats, gauges, mascot, spotlight, gallery
   ========================================================================== */
function initScrollRevealElements() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    gsap.registerPlugin(ScrollTrigger);

    const stage = document.getElementById('product-showcase-pin');
    if (!stage) return;

    // ── Left stat callout ──
    const statCounterObj = { val: 0 };
    const statNumEl = document.getElementById('showcase-stat-num');

    gsap.fromTo('#showcase-stat-left',
        { opacity: 0, x: -30 },
        {
            opacity: 1, x: 0, duration: 0.7, ease: 'power2.out',
            scrollTrigger: { trigger: '#showcase-stat-left', start: 'top 80%', once: true },
        }
    );

    ScrollTrigger.create({
        trigger: '#showcase-stat-left',
        start: 'top 80%',
        once: true,
        onEnter: () => {
            gsap.to(statCounterObj, {
                val: 88, duration: 1.4, ease: 'power1.out',
                onUpdate: () => {
                    if (statNumEl) statNumEl.textContent = Math.round(statCounterObj.val) + '%';
                },
            });
        },
    });

    gsap.fromTo('.showcase-breakdown-item',
        { opacity: 0, y: 12 },
        {
            opacity: 1, y: 0, stagger: 0.12, duration: 0.55, ease: 'power1.out',
            scrollTrigger: { trigger: '.showcase-breakdown-item', start: 'top 82%', once: true },
        }
    );

    // ── Right circular gauge callout ──
    gsap.fromTo('#showcase-stat-right',
        { opacity: 0, x: 30 },
        {
            opacity: 1, x: 0, duration: 0.7, ease: 'power2.out',
            scrollTrigger: { trigger: '#showcase-stat-right', start: 'top 80%', once: true },
        }
    );

    // Caffeine gauge
    animateGauge('caffeine-bar', 'caffeine-val', 75);
    // Sweetness gauge
    animateGauge('sweet-bar', 'sweet-val', 40);

    // ── Mascot ──
    gsap.fromTo('#showcase-mascot',
        { opacity: 0, x: 50, scale: 0.88 },
        {
            opacity: 1, x: 0, scale: 1, duration: 0.75, ease: 'back.out(1.4)',
            scrollTrigger: { trigger: '#showcase-mascot', start: 'top 85%', once: true },
        }
    );
    gsap.fromTo('#showcase-mascot-bubble',
        { opacity: 0, y: 10, scale: 0.8 },
        {
            opacity: 1, y: 0, scale: 1, duration: 0.5, delay: 0.25, ease: 'back.out(1.6)',
            scrollTrigger: { trigger: '#showcase-mascot', start: 'top 85%', once: true },
        }
    );

    // ── Spotlight headline ──
    gsap.fromTo('#showcase-spotlight-block',
        { opacity: 0, y: 22 },
        {
            opacity: 1, y: 0, duration: 0.8, ease: 'power2.out',
            scrollTrigger: { trigger: '#showcase-spotlight-block', start: 'top 88%', once: true },
        }
    );

    // ── Gallery thumbnail stagger ──
    gsap.fromTo('.showcase-thumb-item',
        { opacity: 0, y: 26, scale: 0.96 },
        {
            opacity: 1, y: 0, scale: 1, stagger: 0.12, duration: 0.65, ease: 'power2.out',
            scrollTrigger: { trigger: '#showcase-gallery-section', start: 'top 80%', once: true },
        }
    );

    // ── Timeline dots update on scroll ──
    ScrollTrigger.create({
        trigger: stage,
        start: 'top top',
        end: 'bottom bottom',
        onUpdate: (self) => updateTimelineDots(self.progress),
    });
}

/**
 * Animate a single SVG circular gauge + numeric counter on scroll enter.
 */
function animateGauge(barId, valId, targetPct) {
    const bar = document.getElementById(barId);
    const valEl = document.getElementById(valId);
    if (!bar) return;

    const circumference = 283; // 2π × r=45
    const counter = { val: 0 };

    ScrollTrigger.create({
        trigger: bar,
        start: 'top 85%',
        once: true,
        onEnter: () => {
            gsap.to(bar, {
                strokeDashoffset: circumference - (circumference * targetPct) / 100,
                duration: 1.2,
                ease: 'power1.out',
            });
            gsap.to(counter, {
                val: targetPct,
                duration: 1.2,
                ease: 'power1.out',
                onUpdate: () => {
                    if (valEl) valEl.textContent = Math.round(counter.val) + '%';
                },
            });
        },
    });
}

/** Show correct meter values immediately for reduced-motion users. */
function showStaticMeterValues() {
    document.querySelectorAll('.circle-progress-bar').forEach(el => {
        const pct = parseFloat(el.getAttribute('data-percent') || '0');
        el.style.strokeDashoffset = String(283 - (283 * pct) / 100);
    });
    const caffeine = document.getElementById('caffeine-val');
    const sweet    = document.getElementById('sweet-val');
    if (caffeine) caffeine.textContent = '75%';
    if (sweet)    sweet.textContent    = '40%';
    const stat = document.getElementById('showcase-stat-num');
    if (stat) stat.textContent = '88%';
}

/** Update the 4 vertical timeline dots on the right edge. */
function updateTimelineDots(progress) {
    const dots = document.querySelectorAll('.showcase-timeline-dot');
    if (!dots.length) return;

    let activeIndex = 0;
    if (progress > 0.75) activeIndex = 3;
    else if (progress > 0.5) activeIndex = 2;
    else if (progress > 0.25) activeIndex = 1;

    dots.forEach((dot, idx) => {
        if (idx === activeIndex) {
            dot.classList.add('bg-neutral-900', 'w-2.5', 'h-2.5');
            dot.classList.remove('bg-neutral-300', 'w-1.5', 'h-1.5');
        } else {
            dot.classList.remove('bg-neutral-900', 'w-2.5', 'h-2.5');
            dot.classList.add('bg-neutral-300', 'w-1.5', 'h-1.5');
        }
    });
}

/* ==========================================================================
   3. Order Tracker Stepper (scroll-scrub)
   ========================================================================== */
function initOrderTracker() {
    const trackerSection = document.getElementById('order-tracker-section');
    const progressBar    = document.getElementById('tracker-progress-bar');
    const bikeIcon       = document.getElementById('tracker-bike-icon');
    const nodes          = document.querySelectorAll('.tracker-node');

    if (!trackerSection || !progressBar || !bikeIcon) return;

    if (typeof ScrollTrigger === 'undefined') {
        progressBar.style.width = '80%';
        bikeIcon.style.left     = '90%';
        nodes.forEach(n => n.classList.add('is-active'));
        return;
    }

    ScrollTrigger.create({
        trigger: trackerSection,
        start: 'top 80%',
        end: 'bottom 40%',
        scrub: 0.8,
        onUpdate: (self) => {
            const p = self.progress;
            progressBar.style.width = `${p * 80}%`;
            bikeIcon.style.left     = `${10 + p * 80}%`;

            if (nodes[0]) nodes[0].classList.toggle('is-active', p >= 0.05);
            if (nodes[1]) nodes[1].classList.toggle('is-active', p >= 0.45);
            if (nodes[2]) nodes[2].classList.toggle('is-active', p >= 0.85);
        },
    });
}
