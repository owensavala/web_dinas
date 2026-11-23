// Antigravity Enhanced Script: Accessibility, Live AJAX Search, Lightbox & Cream Mocha Theme
document.addEventListener('DOMContentLoaded', function () {
    // 1. Theme Management - Default to Cream Mocha warm light palette (#F6EFE6)
    const htmlEl = document.documentElement;
    // Reset any legacy stuck dark mode to ensure Cream Mocha displays cleanly
    if (localStorage.getItem('disbud_theme') === 'dark') {
        localStorage.removeItem('disbud_theme');
    }
    const currentTheme = localStorage.getItem('disbud_theme') || 'light';
    if (currentTheme === 'dark') {
        htmlEl.classList.add('dark');
        htmlEl.dataset.mode = 'dark';
    } else {
        htmlEl.classList.remove('dark');
        htmlEl.dataset.mode = 'light';
    }

    window.toggleDarkMode = function () {
        if (htmlEl.classList.contains('dark') || htmlEl.dataset.mode === 'dark') {
            htmlEl.classList.remove('dark');
            htmlEl.dataset.mode = 'light';
            localStorage.setItem('disbud_theme', 'light');
        } else {
            htmlEl.classList.add('dark');
            htmlEl.dataset.mode = 'dark';
            localStorage.setItem('disbud_theme', 'dark');
        }
    };

    // 2. Font Size Adjustment (A11y)
    let currentFontSize = parseInt(localStorage.getItem('disbud_fontsize') || '16');
    function applyFontSize(size) {
        htmlEl.style.fontSize = size + 'px';
        localStorage.setItem('disbud_fontsize', size);
    }
    applyFontSize(currentFontSize);

    window.adjustFontSize = function (delta) {
        if (delta === 0) {
            currentFontSize = 16;
        } else {
            currentFontSize = Math.min(22, Math.max(13, currentFontSize + delta));
        }
        applyFontSize(currentFontSize);
    };

    // 3. Live Instant AJAX Search Modal (Cmd+K / Ctrl+K)
    const searchModal = document.getElementById('searchModal');
    const searchInput = document.getElementById('searchModalInput');
    const searchResultsBox = document.getElementById('searchResultsBox');
    let searchDebounceTimer = null;

    window.openSearchModal = function () {
        if (searchModal) {
            searchModal.classList.remove('hidden');
            setTimeout(() => { if (searchInput) searchInput.focus(); }, 50);
        }
    };

    window.closeSearchModal = function () {
        if (searchModal) {
            searchModal.classList.add('hidden');
        }
    };

    if (searchInput && searchResultsBox) {
        searchInput.addEventListener('input', function () {
            const query = this.value.trim();
            clearTimeout(searchDebounceTimer);

            if (query.length < 2) {
                searchResultsBox.innerHTML = '';
                searchResultsBox.classList.add('hidden');
                return;
            }

            searchDebounceTimer = setTimeout(() => {
                fetch('api_search.php?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        searchResultsBox.innerHTML = '';
                        if (data.results && data.results.length > 0) {
                            searchResultsBox.classList.remove('hidden');
                            data.results.forEach(item => {
                                const el = document.createElement('a');
                                el.href = item.url;
                                el.className = 'flex items-start p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition border border-transparent hover:border-slate-100 dark:hover:border-slate-700 group';
                                el.innerHTML = `
                                    <span class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-sm mr-3 shrink-0 group-hover:scale-105 transition-transform">
                                        <i class="bi ${item.icon}"></i>
                                    </span>
                                    <div class="flex-grow overflow-hidden">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-bold text-slate-900 dark:text-white text-xs truncate">${item.title}</span>
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 uppercase">${item.badge}</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-0.5">${item.desc}</p>
                                    </div>
                                    <i class="bi bi-chevron-right text-xs text-slate-400 group-hover:translate-x-1 transition-transform ml-2"></i>
                                `;
                                searchResultsBox.appendChild(el);
                            });
                        } else {
                            searchResultsBox.classList.remove('hidden');
                            searchResultsBox.innerHTML = `
                                <div class="p-6 text-center text-xs text-slate-400">
                                    <i class="bi bi-search text-2xl mb-1 block"></i>
                                    Tidak ada hasil untuk "<strong>${query}</strong>"
                                </div>
                            `;
                        }
                    })
                    .catch(() => {
                        searchResultsBox.classList.add('hidden');
                    });
            }, 250);
        });
    }

    document.addEventListener('keydown', function (e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            openSearchModal();
        }
        if (e.key === 'Escape') {
            closeSearchModal();
            closeLightbox();
        }
    });

    // 4. Interactive Image Lightbox
    const lightboxModal = document.getElementById('lightboxModal');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxCaption = document.getElementById('lightboxCaption');

    window.openLightbox = function (src, caption) {
        if (lightboxModal && lightboxImg) {
            lightboxImg.src = src;
            if (lightboxCaption) lightboxCaption.innerText = caption || '';
            lightboxModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeLightbox = function () {
        if (lightboxModal) {
            lightboxModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    };
});
