import './bootstrap';

function setupNavbarSearch() {
    const searchInput = document.getElementById('navbar-search');
    const resultsBox = document.getElementById('navbar-search-results');

    if (!searchInput || !resultsBox) {
        return;
    }

    let debounceId = null;
    let lastQuery = '';

    const clearResults = () => {
        resultsBox.innerHTML = '';
        resultsBox.classList.add('d-none');
    };

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.trim();
        lastQuery = query;

        if (query.length < 3) {
            clearResults();
            return;
        }

        if (debounceId) {
            clearTimeout(debounceId);
        }

        debounceId = setTimeout(() => {
            window.axios
                .get('/search', {
                    params: { q: query },
                })
                .then((response) => {
                    const data = response.data;

                    if (!data || !Array.isArray(data.results) || data.results.length === 0) {
                        clearResults();
                        return;
                    }

                    // If input has changed since request was sent, ignore this response
                    if (searchInput.value.trim() !== lastQuery) {
                        return;
                    }

                    resultsBox.innerHTML = '';

                    data.results.forEach((item) => {
                        const link = document.createElement('a');
                        link.href = item.url;
                        link.className = 'navbar-search-result-item d-flex justify-content-between align-items-center';

                        const labelSpan = document.createElement('span');
                        labelSpan.textContent = item.label;

                        const typeSpan = document.createElement('span');
                        typeSpan.className = 'badge bg-secondary ms-2';
                        typeSpan.textContent = item.type;

                        link.appendChild(labelSpan);
                        link.appendChild(typeSpan);

                        resultsBox.appendChild(link);
                    });

                    resultsBox.classList.remove('d-none');
                })
                .catch(() => {
                    // Silently ignore errors for this lightweight search
                });
        }, 250);
    });

    document.addEventListener('click', (event) => {
        if (!resultsBox.contains(event.target) && event.target !== searchInput) {
            clearResults();
        }
    });
}

function setupPageTransitions() {
    const overlay = document.getElementById('page-transition-overlay');
    const appMain = document.querySelector('.app-main');

    if (overlay) {
        overlay.classList.add('is-entering');
        overlay.addEventListener(
            'animationend',
            () => {
                overlay.classList.remove('is-entering');
            },
            { once: true },
        );
    }

    if (appMain) {
        appMain.classList.add('page-enter');
        window.setTimeout(() => {
            appMain.classList.remove('page-enter');
        }, 900);
    }

    document.addEventListener('click', (event) => {
        const anchor = event.target.closest('a');
        if (!anchor) {
            return;
        }

        // Let browser handle pagination links normally (for built-in scroll behavior)
        if (anchor.closest('.pagination')) {
            return;
        }

        const href = anchor.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) {
            return;
        }

        if (anchor.target === '_blank' || anchor.hasAttribute('data-no-transition')) {
            return;
        }

        const url = anchor.href;
        if (!url || url === window.location.href) {
            return;
        }

        event.preventDefault();

        if (overlay) {
            overlay.classList.remove('is-entering');
            overlay.classList.add('is-exiting');
            overlay.addEventListener(
                'animationend',
                () => {
                    window.location.href = url;
                },
                { once: true },
            );
        } else {
            window.location.href = url;
        }
    });
}

function setupThemeToggle() {
    const body = document.body;
    const toggle = document.getElementById('theme-toggle');

    if (!body || !toggle) {
        return;
    }

    const applyTheme = (theme) => {
        const next = theme === 'mono' ? 'mono' : 'neon';
        body.setAttribute('data-theme', next);
        toggle.setAttribute('aria-pressed', next === 'mono' ? 'true' : 'false');
    };

    const saved = window.localStorage.getItem('theme');
    if (saved) {
        applyTheme(saved);
    } else {
        applyTheme(body.getAttribute('data-theme') || 'neon');
    }

    toggle.addEventListener('click', () => {
        const current = body.getAttribute('data-theme') === 'mono' ? 'mono' : 'neon';
        const next = current === 'mono' ? 'neon' : 'mono';
        window.localStorage.setItem('theme', next);
        applyTheme(next);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    setupNavbarSearch();
    setupPageTransitions();
    setupThemeToggle();
});
