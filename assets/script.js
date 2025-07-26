(() => {
    'use strict';

    const tabSwitches = document.querySelectorAll('.tab-switch > input[type=radio]');
    const tabPanels = document.querySelectorAll('.tab-panel');

    function showTab(tabSlug) {
        // Hide all panels
        tabPanels.forEach(panel => panel.style.display = 'none');
        // Show the selected panel
        const panel = document.querySelector(`.tab-panel[data-tab-slug="${tabSlug}"]`);
        if (panel) panel.style.display = 'block';
    }

    function selectTab(tabSlug, updateHash = true) {
        // Select the corresponding radio input
        const tabPanel = document.querySelector(`.tab-panel[data-tab-slug="${tabSlug}"]`);
        if (!tabPanel) return;
        const tabSwitch = document.querySelector(`.tab-switch > input[aria-controls="${tabPanel.id}"]`);
        if (tabSwitch) tabSwitch.checked = true;
        showTab(tabSlug);
        if (updateHash) window.history.pushState({}, '', '#' + tabSlug);
    }

    // Event listeners for tab switches
    tabSwitches.forEach(tabSwitch => {
        tabSwitch.addEventListener('change', () => {
            const panelId = tabSwitch.getAttribute('aria-controls');
            const panel = document.getElementById(panelId);
            if (panel) {
                const slug = panel.dataset.tabSlug;
                selectTab(slug);
            }
        });
    });

    // Handle initial load and hash changes
    function activateTabFromHash() {
        const [slug] = window.location.hash.substring(1).split('-');
        const panel = document.querySelector(`.tab-panel[data-tab-slug="${slug}"]`);
        if (panel) {
            selectTab(slug, false);
        } else {
            // Default to first tab
            const firstPanel = tabPanels[0];
            if (firstPanel) {
                selectTab(firstPanel.dataset.tabSlug, false);
            }
        }
    }

    function highlightSectionFromHash() {
        const hash = window.location.hash;

        if (!hash) {
            return;
        }

        const panelSlug = hash.split('-')[0];
        const sectionSlug = hash.replace(panelSlug + '-', '');

        const section = document.querySelector(`.boardgame-listing-section[data-section-slug="${sectionSlug}"]`);

        if (!section) {
            return;
        }

        section.classList.add('highlighted');
    }

    const sectionLinks = document.querySelectorAll('.boardgame-listing-section-link');
    sectionLinks.forEach(link => {
        link.addEventListener('click', event => {
            event.preventDefault();
            const section = link.parentElement;
            const panel = section.parentElement;
            const sectionSlug = section.dataset.sectionSlug;
            const panelSlug = panel.dataset.tabSlug;

            const sections = panel.querySelectorAll('.boardgame-listing-section');
            sections.forEach(section => {
                section.classList.remove('highlighted');
            });
            section.classList.add('highlighted');

            window.location.hash = `${panelSlug}-${sectionSlug}`;
        });
    });

    window.addEventListener('hashchange', activateTabFromHash);
    activateTabFromHash();

    highlightSectionFromHash();
})();