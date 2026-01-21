import './bootstrap';

let pageTransitionClickBound = false;
let pageTransitionInProgress = false;
let pageTransitionFallbackTimeoutId = null;

function resetPageTransitionState() {
    pageTransitionInProgress = false;

    if (pageTransitionFallbackTimeoutId !== null) {
        window.clearTimeout(pageTransitionFallbackTimeoutId);
        pageTransitionFallbackTimeoutId = null;
    }

    const overlay = document.getElementById('page-transition-overlay');
    if (overlay) {
        overlay.classList.remove('is-exiting', 'is-entering');
    }
}

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
    const appMain = document.querySelector('.app-main');
    const overlay = document.getElementById('page-transition-overlay');

    if (overlay) {
        overlay.classList.remove('is-exiting');
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

    if (pageTransitionClickBound) {
        return;
    }

    pageTransitionClickBound = true;

    document.addEventListener('click', (event) => {
        const anchor = event.target.closest('a');
        if (!anchor) {
            return;
        }

        if (pageTransitionInProgress) {
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

        const overlay = document.getElementById('page-transition-overlay');
        if (!overlay) {
            window.location.href = url;
            return;
        }

        pageTransitionInProgress = true;

        overlay.classList.remove('is-entering', 'is-exiting');
        void overlay.offsetWidth;
        overlay.classList.add('is-exiting');

        const navigate = () => {
            resetPageTransitionState();
            window.location.href = url;
        };

        const onAnimationEnd = () => {
            if (pageTransitionFallbackTimeoutId !== null) {
                window.clearTimeout(pageTransitionFallbackTimeoutId);
                pageTransitionFallbackTimeoutId = null;
            }

            navigate();
        };

        overlay.addEventListener('animationend', onAnimationEnd, { once: true });

        // Fallback: if the animation never fires (e.g. browser bfcache restore quirks),
        // still navigate after the expected duration.
        pageTransitionFallbackTimeoutId = window.setTimeout(() => {
            overlay.removeEventListener('animationend', onAnimationEnd);
            navigate();
        }, 850);
    });
}

function setupAutoDismissAlerts() {
    const alerts = document.querySelectorAll('.alert-auto-dismiss');
    alerts.forEach((alert) => {
        if (alert.dataset.autoDismissInitialized === 'true') {
            return;
        }

        alert.dataset.autoDismissInitialized = 'true';

        const timeoutMs = Number.parseInt(alert.dataset.autoDismiss || '0', 10);
        if (!Number.isFinite(timeoutMs) || timeoutMs <= 0) {
            return;
        }

        window.setTimeout(() => {
            alert.classList.add('is-hiding');
            window.setTimeout(() => {
                const container = alert.closest('[data-auto-dismiss-container]') || alert;
                container.remove();
            }, 350);
        }, timeoutMs);
    });
}

window.addEventListener('pagehide', resetPageTransitionState);
window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        resetPageTransitionState();
    }

    setupAutoDismissAlerts();
});

function setupThemeToggle() {
    const body = document.body;
    const toggle = document.getElementById('theme-toggle');
    const label = toggle?.querySelector('[data-theme-toggle-label]');

    if (!body || !toggle) {
        return;
    }

    const applyTheme = (theme) => {
        const next = theme === 'neon' ? 'neon' : 'mono';
        body.setAttribute('data-theme', next);
        toggle.setAttribute('aria-pressed', next === 'mono' ? 'true' : 'false');

        const nextLabel = next === 'mono' ? 'Neon mode' : 'Dark mode';
        if (label) {
            label.textContent = nextLabel;
        } else {
            toggle.textContent = nextLabel;
        }

        toggle.setAttribute('aria-label', `Switch to ${nextLabel.toLowerCase()}`);
    };

    const saved = window.localStorage.getItem('theme');
    if (saved) {
        applyTheme(saved);
    } else {
        applyTheme(body.getAttribute('data-theme') || 'mono');
    }

    toggle.addEventListener('click', () => {
        const current = body.getAttribute('data-theme') === 'neon' ? 'neon' : 'mono';
        const next = current === 'mono' ? 'neon' : 'mono';
        window.localStorage.setItem('theme', next);
        applyTheme(next);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    setupNavbarSearch();
    setupPageTransitions();
    setupAutoDismissAlerts();
    setupThemeToggle();
});
