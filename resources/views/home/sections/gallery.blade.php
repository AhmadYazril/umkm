<!-- SECTION 5: LIVE STORY FEED (NUCOMU STORY EXPERIENCE) -->
<section id="gallery" class="py-12 px-4 relative z-10 bg-[#0c0c0f] text-gray-100 overflow-hidden">
    <!-- Ambient Glowing Background Accents -->
    <div class="absolute top-10 left-1/4 w-96 h-96 rounded-full bg-amber-500/10 pointer-events-none blur-3xl"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 rounded-full bg-violet-600/10 pointer-events-none blur-3xl"></div>

    <div class="max-w-3xl mx-auto relative z-10">
        
        <!-- Section Header Tag -->
        <div class="flex items-center justify-between mb-4 px-1">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                <h2 class="text-xs sm:text-sm font-bold tracking-wider uppercase text-white font-sans">Live Story Feed</h2>
            </div>
            <span id="storyStatusTag" class="text-[10px] font-mono text-gray-400 bg-white/5 px-3 py-1 rounded-full border border-white/10">
                Otomatis Berganti • Hover untuk Pause
            </span>
        </div>

        <!-- Wider Story Card Container (Max Width 760px, 16:10 Ratio) -->
        <div id="storyContainer" 
             class="wide-story-frame w-full rounded-[2rem] relative overflow-hidden bg-[#15151c]/80 backdrop-blur-xl shadow-2xl border border-white/15 cursor-pointer group select-none transition-transform duration-300"
             style="aspect-ratio: 16 / 10; max-height: 480px;"
             onmouseenter="pauseHomeStory()" onmouseleave="resumeHomeStory()">

            <!-- Story Background Image -->
            <img id="homeStoryImage" src="" alt="Gallery Image" class="w-full h-full object-cover transition-all duration-700 ease-in-out transform scale-100 group-hover:scale-105">

            <!-- Dark Gradient Overlays for Readability -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-black/90 pointer-events-none"></div>

            <!-- TOP USER INFO -->
            <div class="absolute top-0 left-0 right-0 p-5 sm:p-6 z-20 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full p-0.5 bg-gradient-to-tr from-amber-500 via-rose-500 to-violet-500 shadow-lg">
                        <img id="homeStoryAvatar" src="" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span id="homeStoryAuthor" class="text-xs sm:text-sm font-bold text-white drop-shadow-md">Barista Corner</span>
                            <i class="fa-solid fa-circle-check text-cyan-400 text-xs"></i>
                        </div>
                        <span id="homeStoryTime" class="text-[10px] sm:text-xs text-gray-300 drop-shadow">Baru saja</span>
                    </div>
                </div>

                <span id="homeStoryCategoryBadge" class="text-[10px] sm:text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-full bg-black/60 backdrop-blur border border-white/20 text-amber-400 shadow-lg">
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
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur border border-white/15 text-[10px] sm:text-xs font-semibold text-white">
                    <i class="fa-solid fa-location-dot text-rose-400"></i> Nucomu Main Bar
                </div>
                <h3 id="homeStoryTitle" class="text-lg sm:text-2xl font-black text-white leading-tight drop-shadow-md">
                    Loading Story...
                </h3>
                <p id="homeStoryDesc" class="text-xs sm:text-sm text-gray-200 leading-relaxed drop-shadow max-w-xl">
                    Please wait...
                </p>
            </div>

            <!-- Invisible Subtle Navigation Hover Arrows -->
            <div class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/50 backdrop-blur border border-white/15 flex items-center justify-center text-white/70 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                <i class="fa-solid fa-chevron-left text-sm"></i>
            </div>
            <div class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/50 backdrop-blur border border-white/15 flex items-center justify-center text-white/70 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                <i class="fa-solid fa-chevron-right text-sm"></i>
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
            tag.classList.add('text-amber-400');
        }
    }

    function resumeHomeStory() {
        isHomeStoryPaused = false;
        const tag = document.getElementById('storyStatusTag');
        if (tag) {
            tag.innerText = "Otomatis Berganti • Hover untuk Pause";
            tag.classList.remove('text-amber-400');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateHomeStoryUI();
        startHomeStoryLoop();
    });
</script>
