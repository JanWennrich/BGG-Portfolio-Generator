(() => {
    'use strict';

    const root = document.documentElement;
    const nav = document.querySelector('.primary-nav');
    const navToggle = document.querySelector('.nav-toggle');
    const themeToggle = document.querySelector('.theme-toggle');

    const setTheme = (theme) => {
        root.dataset.theme = theme;
        if (themeToggle) {
            const isDark = theme === 'dark';
            themeToggle.setAttribute('aria-label', `Switch to ${isDark ? 'light' : 'dark'} mode`);
            themeToggle.setAttribute('aria-pressed', String(isDark));
        }
    };

    const preferredTheme = localStorage.getItem('portfolio-theme')
        || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    setTheme(preferredTheme);

    themeToggle?.addEventListener('click', () => {
        const nextTheme = root.dataset.theme === 'dark' ? 'light' : 'dark';
        setTheme(nextTheme);
        localStorage.setItem('portfolio-theme', nextTheme);
    });

    navToggle?.addEventListener('click', () => {
        const isOpen = nav?.classList.toggle('is-open') ?? false;
        navToggle.setAttribute('aria-expanded', String(isOpen));
    });

    nav?.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            nav.classList.remove('is-open');
            navToggle?.setAttribute('aria-expanded', 'false');
        });
    });

    const filterSection = (sectionId, query) => {
        const section = document.getElementById(sectionId);
        if (!section) return;

        const normalizedQuery = query.trim().toLocaleLowerCase();
        section.querySelectorAll('.boardgame-listing-section').forEach((group) => {
            let visibleCount = 0;
            group.querySelectorAll('.boardgame').forEach((game) => {
                const isVisible = game.dataset.gameTitle?.includes(normalizedQuery) ?? true;
                game.hidden = !isVisible;
                if (isVisible) visibleCount += 1;
            });
            const wishlistFilter = sectionId === 'wishlist'
                ? document.querySelector('[data-wishlist-filter][aria-pressed="true"]')?.dataset.wishlistFilter
                : 'all';
            const matchesWishlistFilter = wishlistFilter === 'all' || group.dataset.sectionSlug === wishlistFilter;
            group.hidden = visibleCount === 0 || !matchesWishlistFilter;
        });
    };

    document.querySelectorAll('[data-search-target]').forEach((input) => {
        input.addEventListener('input', () => filterSection(input.dataset.searchTarget, input.value));
    });

    document.querySelector('[data-sort-target="collection"]')?.addEventListener('change', (event) => {
        const groups = document.querySelector('#collection [data-listing-groups]');
        if (!groups) return;

        const direction = event.currentTarget.value === 'desc' ? -1 : 1;
        [...groups.children]
            .sort((a, b) => a.dataset.sectionSlug.localeCompare(b.dataset.sectionSlug) * direction)
            .forEach((group) => {
                const listing = group.querySelector('.boardgame-listing');
                [...listing.children]
                    .sort((a, b) => a.dataset.gameTitle.localeCompare(b.dataset.gameTitle) * direction)
                    .forEach((game) => listing.append(game));
                groups.append(group);
            });
    });

    document.querySelectorAll('[data-history-sort]').forEach((button) => {
        button.addEventListener('click', () => {
            const groups = document.querySelector('#plays [data-listing-groups]');
            if (!groups) return;

            const ascending = button.dataset.historySort === 'asc';
            document.querySelectorAll('[data-history-sort]').forEach((candidate) => {
                candidate.setAttribute('aria-pressed', String(candidate === button));
            });
            [...groups.children]
                .sort((a, b) => (Number(a.dataset.originalIndex) - Number(b.dataset.originalIndex)) * (ascending ? -1 : 1))
                .forEach((group) => groups.append(group));
        });
    });

    document.querySelectorAll('[data-wishlist-filter]').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelectorAll('[data-wishlist-filter]').forEach((candidate) => {
                candidate.setAttribute('aria-pressed', String(candidate === button));
            });
            const searchInput = document.querySelector('[data-search-target="wishlist"]');
            filterSection('wishlist', searchInput?.value ?? '');
        });
    });

    document.querySelectorAll('[data-copy-section-link]').forEach((button) => {
        button.addEventListener('click', async () => {
            const section = button.closest('.boardgame-listing-section');
            if (!section?.id) return;

            const url = new URL(window.location.href);
            url.hash = section.id;
            window.history.replaceState({}, '', url);
            section.classList.add('is-highlighted');
            window.setTimeout(() => section.classList.remove('is-highlighted'), 700);

            try {
                await navigator.clipboard.writeText(url.toString());
                button.textContent = '✓';
                button.setAttribute('aria-label', 'Link copied');
                window.setTimeout(() => {
                    button.textContent = '#';
                    button.setAttribute('aria-label', `Copy link to ${section.querySelector('h3')?.textContent ?? 'section'}`);
                }, 1600);
            } catch {
                window.location.hash = section.id;
            }
        });
    });

    const resolveLegacyHash = () => {
        if (window.location.hash === '#history') return '#plays';
        if (window.location.hash.startsWith('#history-')) {
            return window.location.hash.replace('#history-', '#plays-');
        }
        return window.location.hash;
    };

    const highlightHashTarget = () => {
        const resolvedHash = resolveLegacyHash();
        if (resolvedHash !== window.location.hash) {
            window.history.replaceState({}, '', resolvedHash);
        }
        const target = document.getElementById(resolvedHash.slice(1));
        if (!target?.classList.contains('boardgame-listing-section')) return;
        target.classList.add('is-highlighted');
        window.setTimeout(() => target.classList.remove('is-highlighted'), 700);
    };

    window.addEventListener('hashchange', highlightHashTarget);
    highlightHashTarget();
})();
