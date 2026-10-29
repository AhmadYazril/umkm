<!-- SECTION 5: LIVE STORY FEED (NUCOMU STORY EXPERIENCE - MONOCHROME EDITORIAL) -->
<section id="gallery" class="py-14 sm:py-16 lg:py-20 px-4 sm:px-6 lg:px-8 relative z-10 bg-[#FAFAFA] text-[#1C1C1C] overflow-hidden border-b border-[#E5E5E5]">
    
    <div class="max-w-3xl mx-auto relative z-10">
        
        <!-- Section Header Tag (Monochrome Editorial Pattern) -->
        <div class="flex items-center justify-between mb-4 px-1">
            <div class="flex items-center gap-2.5">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#1C1C1C] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#1C1C1C]"></span>
                </span>
                <span class="text-[11px] font-semibold text-[#8A8A8A] uppercase tracking-[0.22em] font-body">
                    04 / LIVE STORY FEED
                </span>
            </div>
            <span id="storyStatusTag" class="text-[10px] sm:text-[11px] font-medium text-[#777777] bg-white px-3.5 py-1 rounded-full border border-[#E5E5E5] shadow-2xs font-body transition-colors">
                Otomatis Berganti • Hover untuk Pause
            </span>
        </div>

        <!-- Wider Story Card Container (Max Width 760px, 16:10 Ratio, Monochrome Editorial Frame) -->
        <div class="relative group/story-outer">
            <!-- Subtle Monochrome Editorial Decorative Back-plate -->
            <div class="absolute inset-0 bg-[#E8E8E8] rounded-[2.1rem] pointer-events-none z-0 transform translate-y-1 sm:translate-y-1.5 opacity-60" aria-hidden="true"></div>

            <div id="storyContainer" 
                 class="wide-story-frame w-full rounded-[2rem] relative z-10 overflow-hidden bg-neutral-900 shadow-lg border border-[#E5E5E5] cursor-pointer group select-none transition-transform duration-300"
                 style="aspect-ratio: 16 / 10; max-height: 480px;"
                 onmouseenter="pauseHomeStory()" onmouseleave="resumeHomeStory()">

                <!-- Story Background Image -->
                <img id="homeStoryImage" src="" alt="Gallery Image" class="w-full h-full object-cover transition-all duration-700 ease-in-out transform scale-100 group-hover:scale-105">

                <!-- Dark Photographic Scrim Overlays for Readability -->
                <div class="absolute inset-0 bg-gradient-to-b from-black/65 via-black/15 to-black/85 pointer-events-none"></div>

                <!-- TOP USER INFO (Monochrome Minimalist) -->
                <div class="absolute top-0 left-0 right-0 p-5 sm:p-6 z-20 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full p-0.5 bg-white/40 backdrop-blur-md shadow-md border border-white/50">
                            <img id="homeStoryAvatar" src="" class="w-full h-full rounded-full object-cover">
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span id="homeStoryAuthor" class="text-xs sm:text-sm font-semibold text-white drop-shadow-sm font-body tracking-tight">Barista Corner</span>
                                <i class="fa-solid fa-circle-check text-white/90 text-xs"></i>
                            </div>
                            <span id="homeStoryTime" class="text-[10px] sm:text-xs text-neutral-300 drop-shadow-sm font-body">Baru saja</span>
                        </div>
                    </div>

                    <span id="homeStoryCategoryBadge" class="text-[10px] sm:text-xs font-semibold uppercase tracking-widest px-3.5 py-1.5 rounded-full bg-[#1C1C1C]/80 backdrop-blur-md border border-white/20 text-white shadow-md font-body">
                        #CraftCoffee
                    </span>
                </div>

                <!-- CLICKABLE TOUCH ZONES (LEFT / RIGHT TO CHANGE SLIDE) -->
                <div class="absolute inset-0 z-10 flex">
                    <div class="w-1/3 h-full" onclick="prevHomeStory(event)"></div>
                    <div class="w-2/3 h-full" onclick="nextHomeStory(event)"></div>
                </div>

                <!-- BOTTOM CAPTION OVERLAY -->
                <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 z-20 space-y-2 pointer-events-none">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#1C1C1C]/80 backdrop-blur-md border border-white/20 text-[10px] sm:text-xs font-semibold text-white font-body">
                        <i class="fa-solid fa-location-dot text-neutral-300"></i> Nucomu Main Bar
                    </div>
                    <h3 id="homeStoryTitle" class="text-xl sm:text-2xl lg:text-3xl font-bold text-white leading-tight drop-shadow font-editorial">
                        Loading Story...
                    </h3>
                    <p id="homeStoryDesc" class="text-xs sm:text-sm text-neutral-200 leading-relaxed drop-shadow max-w-xl font-body font-normal">
                        Please wait...
                    </p>
                </div>

                <!-- Subtle Monochrome Navigation Hover Arrows -->
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

<script>
    const homeStories = @json($stories);
    let homeStoryIndex = 0;
    const HOME_STORY_INTERVAL = 4000;
    let homeStoryTimer = null;
    let isHomeStoryPaused = false;

    function updateHomeStoryUI() {
        if (!homeStories || homeStories.length === 0) return;
        const current = homeStories[homeStoryIndex];
        const imgEl = document.getElementById('homeStoryImage');
        const avatarEl = document.getElementById('homeStoryAvatar');
        const authorEl = document.getElementById('homeStoryAuthor');
        const timeEl = document.getElementById('homeStoryTime');
        const badgeEl = document.getElementById('homeStoryCategoryBadge');
        const titleEl = document.getElementById('homeStoryTitle');
        const descEl = document.getElementById('homeStoryDesc');

        if (imgEl) imgEl.src = current.image;
        if (avatarEl) avatarEl.src = current.avatar_url || current.avatar || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80';
        if (authorEl) authorEl.innerText = current.author || 'Barista Corner';
        if (timeEl) timeEl.innerText = current.time_label || current.time || 'Baru saja';
        if (badgeEl) badgeEl.innerText = current.category_badge || current.category || '#CraftCoffee';
        if (titleEl) titleEl.innerText = current.caption || current.title || 'Nucomu Story';
        if (descEl) descEl.innerText = current.description || current.desc || '';
    }

    function startHomeStoryLoop() {
        clearInterval(homeStoryTimer);
        homeStoryTimer = setInterval(() => {
            if (!isHomeStoryPaused && homeStories.length > 1) {
                nextHomeStory();
            }
        }, HOME_STORY_INTERVAL);
    }

    function nextHomeStory(e) {
        if (e) e.stopPropagation();
        if (!homeStories.length) return;
        homeStoryIndex = (homeStoryIndex + 1) % homeStories.length;
        updateHomeStoryUI();
    }

    function prevHomeStory(e) {
        if (e) e.stopPropagation();
        if (!homeStories.length) return;
        homeStoryIndex = (homeStoryIndex - 1 + homeStories.length) % homeStories.length;
        updateHomeStoryUI();
    }

    function pauseHomeStory() {
        isHomeStoryPaused = true;
        const tag = document.getElementById('storyStatusTag');
        if (tag) {
            tag.innerText = "PAUSED (Di-hold)";
            tag.classList.add('text-[#1C1C1C]', 'border-[#1C1C1C]/40', 'bg-[#F5F5F5]');
        }
    }

    function resumeHomeStory() {
        isHomeStoryPaused = false;
        const tag = document.getElementById('storyStatusTag');
        if (tag) {
            tag.innerText = "Otomatis Berganti • Hover untuk Pause";
            tag.classList.remove('text-[#1C1C1C]', 'border-[#1C1C1C]/40', 'bg-[#F5F5F5]');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateHomeStoryUI();
        startHomeStoryLoop();
    });
</script>