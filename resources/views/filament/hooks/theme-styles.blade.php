<style>
    /* ==========================================================================
       SDN CINANGGERANG I - CUSTOM FILAMENT V3 THEME (SEAMLESS FINE-TUNING)
       Warna Identitas: Deep Emerald (#064E3B) & Golden Amber (#F59E0B)
       ========================================================================== */

    /* 1. PENYATUAN LATAR BELAKANG BRAND HEADER KIRI ATAS & TOPBAR (FULL-WIDTH SEAMLESS) */
    .fi-sidebar-header,
    aside.fi-sidebar header,
    .fi-topbar,
    .fi-topbar > nav {
        background-color: #064E3B !important;
        background-image: none !important;
        border-bottom: 1px solid rgba(6, 95, 70, 0.6) !important; /* border-emerald-800/60 */
        box-shadow: none !important;
        --tw-ring-color: transparent !important;
        ring: none !important;
    }

    /* Hilangkan pemisah vertikal atau celah antara sidebar-header dan topbar */
    .fi-sidebar-header {
        border-right: none !important;
        border-left: none !important;
        border-top: none !important;
    }

    .fi-topbar > nav {
        border-left: none !important;
        border-right: none !important;
        border-top: none !important;
    }

    /* Teks Judul Sekolah & Panel GTK Putih Kontras Tinggi (#FFFFFF) */
    .fi-sidebar-header span,
    .fi-sidebar-header p,
    .fi-sidebar-header h1,
    .fi-sidebar-header h2 {
        color: #ffffff !important;
        font-weight: 600 !important;
    }

    /* Tombol Ciutkan Menu (Sidebar Collapse Chevron) */
    .fi-sidebar-header .fi-icon-btn,
    .fi-sidebar-header button {
        color: #ECFDF5 !important; /* Hijau muda lembut */
        background-color: rgba(255, 255, 255, 0.1) !important;
        border-radius: 0.5rem !important;
        transition: all 0.2s ease-in-out !important;
    }

    .fi-sidebar-header .fi-icon-btn svg,
    .fi-sidebar-header button svg {
        color: #ECFDF5 !important;
    }

    .fi-sidebar-header .fi-icon-btn:hover,
    .fi-sidebar-header button:hover {
        color: #ffffff !important;
        background-color: rgba(255, 255, 255, 0.22) !important;
    }

    .fi-sidebar-header .fi-icon-btn:hover svg,
    .fi-sidebar-header button:hover svg {
        color: #ffffff !important;
    }

    /* Konten dan Tombol Baris Topbar (Terkecualikan Dropdown Panel) */
    .fi-topbar > nav .fi-breadcrumbs a,
    .fi-topbar > nav .fi-breadcrumbs span,
    .fi-topbar > nav .fi-breadcrumbs-item-label {
        color: #ffffff !important;
        font-weight: 600 !important;
    }

    .fi-topbar > nav .fi-icon-btn,
    .fi-topbar > nav > div > button,
    .fi-topbar .fi-topbar-open-sidebar-btn,
    .fi-topbar .fi-topbar-close-sidebar-btn {
        color: #ffffff !important;
    }

    .fi-topbar > nav .fi-icon-btn:hover,
    .fi-topbar > nav > div > button:hover {
        background-color: rgba(255, 255, 255, 0.18) !important;
        color: #F59E0B !important;
    }

    /* ==========================================================================
       PERBAIKAN USER MENU DROPDOWN POPOVER (POJOK KANAN ATAS)
       Warna Teks Tegas, Kontras Jelas, dan Anti Teks Putih di Atas Putih
       ========================================================================== */
    .fi-dropdown-panel {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08) !important;
        padding: 0.35rem !important;
    }

    /* 1. Teks Default Panel Dropdown: Slate 900 */
    .fi-dropdown-panel,
    .fi-dropdown-panel *,
    .fi-dropdown-list,
    .fi-dropdown-list * {
        color: #0f172a !important; /* Slate 900 */
    }

    /* 2. Header Profil: Nama Pengguna & Email */
    .fi-dropdown-header,
    .fi-dropdown-header-label,
    .fi-dropdown-panel .fi-dropdown-header * {
        color: #1e293b !important; /* Slate 800 */
        font-weight: 600 !important;
    }

    /* 3. Item Menu & Tombol di Dalam Dropdown */
    .fi-dropdown-list-item,
    .fi-dropdown-list-item-button,
    .fi-dropdown-panel a,
    .fi-dropdown-panel button {
        color: #334155 !important; /* Slate 700 */
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        border-radius: 0.5rem !important;
        transition: all 0.15s ease-in-out !important;
    }

    .fi-dropdown-list-item .fi-dropdown-list-item-icon,
    .fi-dropdown-list-item svg,
    .fi-dropdown-panel svg {
        color: #475569 !important; /* Slate 600 */
    }

    /* Efek Hover Item Menu: Background Emerald 50 & Teks Emerald 800 */
    .fi-dropdown-list-item:hover,
    .fi-dropdown-list-item-button:hover,
    .fi-dropdown-panel a:hover,
    .fi-dropdown-panel button:hover {
        background-color: #f0fdf4 !important; /* Emerald 50 */
        color: #065f46 !important; /* Emerald 800 */
    }

    .fi-dropdown-list-item:hover *,
    .fi-dropdown-list-item-button:hover *,
    .fi-dropdown-panel a:hover *,
    .fi-dropdown-panel button:hover * {
        color: #065f46 !important;
    }

    .fi-dropdown-list-item:hover svg,
    .fi-dropdown-list-item-button:hover svg,
    .fi-dropdown-panel button:hover svg {
        color: #065f46 !important;
    }

    /* 4. Sakelar Tema (Theme Switcher / Ikon Monitor, Matahari, Bulan) */
    .fi-theme-switcher {
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.65rem !important;
        padding: 0.25rem !important;
        margin: 0.25rem !important;
    }

    .fi-theme-switcher-btn,
    .fi-theme-switcher button {
        color: #475569 !important; /* Slate 600 */
        background-color: transparent !important;
        border-radius: 0.5rem !important;
        padding: 0.35rem 0.65rem !important;
        transition: all 0.15s ease-in-out !important;
    }

    .fi-theme-switcher-btn svg,
    .fi-theme-switcher button svg {
        color: #475569 !important;
    }

    /* Tombol Tema Aktif */
    .fi-theme-switcher-btn[aria-checked="true"],
    .fi-theme-switcher-btn.fi-active,
    .fi-theme-switcher button[aria-checked="true"] {
        background-color: #dcfce7 !important; /* Emerald muda lembut */
        color: #065f46 !important; /* Emerald 800 */
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }

    .fi-theme-switcher-btn[aria-checked="true"] svg,
    .fi-theme-switcher button[aria-checked="true"] svg {
        color: #065f46 !important;
    }

    /* 5. Garis Pembatas (Divider) Halus */
    .fi-dropdown-panel hr,
    .fi-dropdown-panel .divide-y > *,
    .fi-dropdown-list-item + .fi-dropdown-list-item {
        border-color: #f1f5f9 !important; /* Slate 100 */
    }

    /* ==========================================================================
       SIDEBAR & MAIN CANVAS POLISH
       ========================================================================== */
    /* Sidebar: Subtle #F0FDF4 dengan pembatas kanan hanya pada area navigasi menu */
    aside.fi-sidebar {
        background-color: #F0FDF4 !important;
        border-right: none !important;
    }

    .dark aside.fi-sidebar {
        background-color: #022c22 !important;
        border-right: none !important;
    }

    .fi-sidebar-nav {
        background-color: #F0FDF4 !important;
        border-right: 1px solid #D1FAE5 !important; /* border-emerald-100 */
    }

    .dark .fi-sidebar-nav {
        background-color: #022c22 !important;
        border-right: 1px solid #064e3b !important;
    }

    /* Main Canvas: #F8FAFC agar kartu konten dan tabel data terlihat elegan dan timbul */
    .fi-main-ctn,
    main.fi-main,
    .fi-body,
    body {
        background-color: #F8FAFC !important;
    }

    .dark .fi-main-ctn,
    .dark main.fi-main {
        background-color: #0b1512 !important;
    }

    /* Status Menu Navigasi Aktif */
    .fi-sidebar-item.fi-active > .fi-sidebar-item-button,
    .fi-sidebar-item.fi-sidebar-item-active > .fi-sidebar-item-button,
    .fi-sidebar-item > a.fi-active {
        background: linear-gradient(135deg, #047857 0%, #065F46 100%) !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        border-radius: 0.65rem !important;
        box-shadow: 0 4px 12px rgba(4, 120, 87, 0.3) !important;
    }

    .fi-sidebar-item.fi-active > .fi-sidebar-item-button *,
    .fi-sidebar-item.fi-sidebar-item-active > .fi-sidebar-item-button *,
    .fi-sidebar-item > a.fi-active * {
        color: #ffffff !important;
    }

    .fi-sidebar-item > .fi-sidebar-item-button:hover:not(.bg-gray-100) {
        background-color: #DCFCE7 !important;
        border-radius: 0.65rem !important;
    }

    .fi-sidebar-item > .fi-sidebar-item-button:hover:not(.bg-gray-100) * {
        color: #064E3B !important;
    }

    /* Batasi Ukuran Foto Profil / Avatar (64x64px) */
    img.fi-avatar,
    .fi-avatar img,
    .filament-widget-avatar img,
    .profile-avatar-img,
    .profile-avatar-img-wrp img,
    .fi-account-widget img {
        width: 4rem !important;  /* 64px */
        height: 4rem !important; /* 64px */
        max-width: 4rem !important;
        max-height: 4rem !important;
        min-width: 4rem !important;
        min-height: 4rem !important;
        border-radius: 9999px !important;
        object-fit: cover !important;
        border: 2px solid #F59E0B !important; /* Aksen Golden Amber */
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        display: block !important;
    }

    /* Desain Tabel Data Modern */
    .fi-ta-table thead,
    .fi-ta-header,
    .fi-ta-table thead th,
    .fi-ta-header-cell {
        background-color: #ECFDF5 !important;
        color: #065F46 !important;
        font-weight: 600 !important;
        font-size: 0.75rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        border-bottom: 1px solid #A7F3D0 !important;
    }

    .fi-ta-table thead th * {
        color: #065F46 !important;
        font-weight: 600 !important;
    }

    .fi-ta-table tbody tr {
        transition: background-color 0.15s ease-in-out !important;
    }

    .fi-ta-table tbody tr:hover,
    .fi-ta-row:hover {
        background-color: rgba(236, 253, 245, 0.6) !important;
    }

    .fi-badge {
        border-radius: 9999px !important;
        padding: 0.2rem 0.65rem !important;
        font-weight: 600 !important;
        font-size: 0.75rem !important;
    }

    .fi-color-success .fi-badge,
    .fi-badge-color-success,
    .fi-badge-color-emerald {
        background-color: #ECFDF5 !important;
        color: #047857 !important;
        border: 1px solid #A7F3D0 !important;
    }

    .fi-color-danger .fi-badge,
    .fi-badge-color-danger,
    .fi-badge-color-rose {
        background-color: #FFF1F2 !important;
        color: #BE123C !important;
        border: 1px solid #FECDD3 !important;
    }

    /* Garis Aksen Emas Atas pada Seluruh Kartu Dashboard */
    .fi-wi-stats-overview-stat,
    .fi-section,
    .fi-wi-card,
    .fi-ta {
        border-top: 3px solid #F59E0B !important;
        border-radius: 1rem !important;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05) !important;
        background-color: #ffffff !important;
    }

    .dark .fi-wi-stats-overview-stat,
    .dark .fi-section,
    .dark .fi-wi-card,
    .dark .fi-ta {
        background-color: #064e3b !important;
    }

    .fi-wi-stats-overview-stat {
        transition: transform 0.2s ease, box-shadow 0.2s ease !important;
    }

    .fi-wi-stats-overview-stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -4px rgba(0, 0, 0, 0.1) !important;
    }

    /* ==========================================================================
       MS WORD-LIKE RICH TEXT EDITOR & TEXT ALIGNMENT EXTENSION
       ========================================================================== */
    /* Toolbar Kontainer Bergaya Pita MS Word */
    .fi-fo-rich-editor {
        border-radius: 0.75rem !important;
        border: 1px solid #e2e8f0 !important;
        background-color: #ffffff !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
        overflow: hidden !important;
    }

    .fi-fo-rich-editor-toolbar {
        background-color: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 0.5rem 0.65rem !important;
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 0.25rem 0.5rem !important;
        align-items: center !important;
    }

    .fi-fo-rich-editor-toolbar [data-trix-button-group] {
        display: inline-flex !important;
        align-items: center !important;
        gap: 2px !important;
        padding-right: 0.4rem !important;
        margin-right: 0.25rem !important;
        border-right: 1px solid #cbd5e1 !important;
    }

    .fi-fo-rich-editor-toolbar [data-trix-button-group]:last-child {
        border-right: none !important;
    }

    .fi-fo-rich-editor-toolbar-btn {
        width: 32px !important;
        height: 32px !important;
        min-width: 32px !important;
        border-radius: 0.375rem !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #334155 !important; /* Slate 700 */
        background-color: transparent !important;
        transition: all 0.15s ease !important;
        border: 1px solid transparent !important;
    }

    .fi-fo-rich-editor-toolbar-btn:hover {
        background-color: #ecfdf5 !important;
        color: #065F46 !important;
        border-color: #a7f3d0 !important;
    }

    .fi-fo-rich-editor-toolbar-btn.trix-active {
        background-color: #064E3B !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        border-color: #064E3B !important;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.15) !important;
    }

    .fi-fo-rich-editor-toolbar-btn.trix-active svg {
        color: #ffffff !important;
        stroke: #ffffff !important;
    }

    /* Area Teks Editor */
    trix-editor.fi-fo-rich-editor-editor {
        min-height: 120px !important;
        padding: 1rem 1.15rem !important;
        font-size: 0.95rem !important;
        line-height: 1.65 !important;
        color: #1e293b !important;
        background-color: #ffffff !important;
    }

    /* Perataan Teks (Alignment Tags) */
    align-left,
    [align-left],
    .trix-content align-left,
    trix-editor align-left {
        display: block !important;
        text-align: left !important;
    }

    align-center,
    [align-center],
    .trix-content align-center,
    trix-editor align-center {
        display: block !important;
        text-align: center !important;
    }

    align-right,
    [align-right],
    .trix-content align-right,
    trix-editor align-right {
        display: block !important;
        text-align: right !important;
    }

    align-justify,
    [align-justify],
    .trix-content align-justify,
    trix-editor align-justify {
        display: block !important;
        text-align: justify !important;
        text-justify: inter-word !important;
    }
</style>

<script>
(function() {
    // 1. Registrasi custom tags HTML untuk alignment
    const tags = ['align-left', 'align-center', 'align-right', 'align-justify'];
    tags.forEach(tag => {
        if (window.customElements && !customElements.get(tag)) {
            try {
                customElements.define(tag, class extends HTMLElement {});
            } catch (e) {}
        }
    });

    // 2. Konfigurasi blockAttributes pada Trix Editor
    function configureTrixAttributes() {
        if (!window.Trix || !window.Trix.config || !window.Trix.config.blockAttributes) {
            return false;
        }

        window.Trix.config.blockAttributes.alignLeft = {
            tagName: "align-left",
            terminal: true,
            breakOnReturn: true,
            group: false,
            exclusive: true
        };
        window.Trix.config.blockAttributes.alignCenter = {
            tagName: "align-center",
            terminal: true,
            breakOnReturn: true,
            group: false,
            exclusive: true
        };
        window.Trix.config.blockAttributes.alignRight = {
            tagName: "align-right",
            terminal: true,
            breakOnReturn: true,
            group: false,
            exclusive: true
        };
        window.Trix.config.blockAttributes.alignJustify = {
            tagName: "align-justify",
            terminal: true,
            breakOnReturn: true,
            group: false,
            exclusive: true
        };
        return true;
    }

    // Jalankan segera dan pasang listener
    configureTrixAttributes();
    document.addEventListener("trix-before-initialize", configureTrixAttributes);
    document.addEventListener("DOMContentLoaded", configureTrixAttributes);

    // 3. Injeksi Toolbar Buttons Perataan Halaman
    function injectAlignmentButtons() {
        configureTrixAttributes();

        document.querySelectorAll('trix-toolbar').forEach(toolbar => {
            if (toolbar.querySelector('[data-trix-button-group="alignment-tools"]')) {
                return;
            }

            const headingTools = toolbar.querySelector('[data-trix-button-group="heading-tools"]');
            const blockTools = toolbar.querySelector('[data-trix-button-group="block-tools"]');
            const textTools = toolbar.querySelector('[data-trix-button-group="text-tools"]');
            const container = toolbar.querySelector('.flex.gap-x-3') || toolbar.querySelector('div') || toolbar;

            const group = document.createElement('div');
            group.className = 'flex gap-x-1 border-r border-gray-200 dark:border-white/10 pr-2 mr-1';
            group.setAttribute('data-trix-button-group', 'alignment-tools');

            group.innerHTML = `
                <button type="button" class="fi-fo-rich-editor-toolbar-btn"
                    data-trix-attribute="alignLeft"
                    title="Rata Kiri (Align Left)"
                    tabindex="-1">
                    <svg class="h-4 w-4 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="15" y2="12"></line>
                        <line x1="3" y1="18" x2="19" y2="18"></line>
                    </svg>
                </button>
                <button type="button" class="fi-fo-rich-editor-toolbar-btn"
                    data-trix-attribute="alignCenter"
                    title="Rata Tengah (Align Center)"
                    tabindex="-1">
                    <svg class="h-4 w-4 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="6" y1="12" x2="18" y2="12"></line>
                        <line x1="4" y1="18" x2="20" y2="18"></line>
                    </svg>
                </button>
                <button type="button" class="fi-fo-rich-editor-toolbar-btn"
                    data-trix-attribute="alignRight"
                    title="Rata Kanan (Align Right)"
                    tabindex="-1">
                    <svg class="h-4 w-4 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="9" y1="12" x2="21" y2="12"></line>
                        <line x1="5" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <button type="button" class="fi-fo-rich-editor-toolbar-btn"
                    data-trix-attribute="alignJustify"
                    title="Rata Kanan-Kiri (Justify)"
                    tabindex="-1">
                    <svg class="h-4 w-4 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            `;

            if (blockTools && blockTools.parentNode) {
                blockTools.parentNode.insertBefore(group, blockTools.nextSibling);
            } else if (headingTools && headingTools.parentNode) {
                headingTools.parentNode.insertBefore(group, headingTools.nextSibling);
            } else if (textTools && textTools.parentNode) {
                textTools.parentNode.insertBefore(group, textTools.nextSibling);
            } else if (container) {
                container.appendChild(group);
            }
        });
    }

    document.addEventListener("trix-initialize", injectAlignmentButtons);
    document.addEventListener("DOMContentLoaded", injectAlignmentButtons);
    document.addEventListener("livewire:navigated", injectAlignmentButtons);
    document.addEventListener("livewire:load", injectAlignmentButtons);

    const observer = new MutationObserver((mutations) => {
        for (const m of mutations) {
            if (m.addedNodes.length) {
                injectAlignmentButtons();
                break;
            }
        }
    });
    observer.observe(document.body || document.documentElement, { childList: true, subtree: true });
})();
</script>
