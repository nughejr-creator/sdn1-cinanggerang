{{-- TOPBAR INFORMASI RESMI & PRESENSI GPS --}}
<div class="bg-forest-950 text-white text-[11px] py-1.5 border-b border-forest-900/60 hidden md:block">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <div class="flex items-center gap-6">
            <span class="inline-flex items-center gap-1.5 text-gray-300">
                <svg class="w-3.5 h-3.5 text-sumedang-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <span>{{ $profile?->email ?? 'sdncinanggerang1@gmail.com' }}</span>
            </span>
            <span class="inline-flex items-center gap-1.5 text-gray-300">
                <svg class="w-3.5 h-3.5 text-sumedang-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                <span>{{ $profile?->telepon ?? '0813-2102-1585' }}</span>
            </span>
            <span class="text-gray-400">|</span>
            <span class="text-gray-300">Kec. Pamulihan, Kab. Sumedang</span>
        </div>
        <div class="flex items-center gap-3">
            {{-- Tombol Presensi GPS Bersih di Topbar --}}
            <a href="{{ route('presensi.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 hover:text-white border border-emerald-400/40 rounded-full text-[10px] font-bold transition shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>📍 Presensi GPS Guru</span>
            </a>

            <span class="px-2 py-0.5 bg-forest-800 text-emerald-200 rounded text-[10px] font-semibold tracking-wider">
                NPSN: {{ $profile?->npsn ?? '20208801' }}
            </span>
            <span class="px-2 py-0.5 bg-sumedang-amber/20 text-sumedang-amber border border-sumedang-amber/40 rounded text-[10px] font-bold">
                PPDB 2026/2027
            </span>
        </div>
    </div>
</div>

{{-- STICKY NAVBAR --}}
<header x-data="{ mobileOpen: false, scrolled: false }"
        @scroll.window="scrolled = (window.pageYOffset > 20)"
        :class="scrolled ? 'bg-white/95 dark:bg-forest-950/95 backdrop-blur-md shadow-md py-2.5' : 'bg-white dark:bg-forest-950 py-3.5'"
        class="sticky top-0 z-50 transition-all duration-300 border-b border-gray-100 dark:border-forest-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">

            {{-- BRAND LOGO SEKOLAH TUNGGAL --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                {{-- Logo SDN Cinanggerang I (Satu-satunya Logo di Header) --}}
                <div class="relative flex items-center justify-center">
                    <img src="{{ $profile?->logo_url ?? asset('images/logo-official.png') }}"
                         alt="Logo SDN Cinanggerang I"
                         class="h-10 sm:h-11 w-auto object-contain transition-transform group-hover:scale-105"
                         onerror="this.onerror=null; this.src='{{ asset('images/logo-official.png') }}';">
                </div>

                {{-- Teks Nama Sekolah --}}
                <div class="flex flex-col">
                    <span class="font-extrabold text-forest-900 dark:text-white text-base sm:text-lg tracking-tight leading-tight group-hover:text-forest-800 transition">
                        SDN CINANGGERANG I
                    </span>
                    <span class="text-[10px] sm:text-[11px] text-gray-500 dark:text-gray-400 font-medium tracking-wide">
                        Kec. Pamulihan, Kab. Sumedang
                    </span>
                </div>
            </a>

            {{-- DESKTOP NAVIGATION MENU DENGAN ACTIVE LINK STATE --}}
            <nav class="hidden lg:flex items-center gap-1 font-semibold text-xs">

                {{-- Menu 1: Beranda --}}
                <a href="{{ route('home') }}"
                   class="px-3 py-2 rounded-lg transition {{ request()->routeIs('home') ? 'text-forest-900 dark:text-emerald-300 font-extrabold bg-forest-50 dark:bg-forest-900/60 border-b-2 border-forest-800' : 'text-gray-700 dark:text-gray-200 hover:text-forest-800 hover:bg-forest-50 dark:hover:bg-forest-900/40' }}">
                    Beranda
                </a>

                {{-- Menu 2: Dropdown Profil Sekolah (Profil, Guru, Rombel, Siswa) --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false" @mouseleave="open = false">
                    <button type="button"
                            @click="open = !open"
                            @mouseenter="open = true"
                            class="px-3 py-2 rounded-lg transition inline-flex items-center gap-1 {{ request()->routeIs('profil') || request()->routeIs('guru') || request()->routeIs('rombel') || request()->routeIs('siswa') ? 'text-forest-900 dark:text-emerald-300 font-extrabold bg-forest-50 dark:bg-forest-900/60 border-b-2 border-forest-800' : 'text-gray-700 dark:text-gray-200 hover:text-forest-800 hover:bg-forest-50 dark:hover:bg-forest-900/40' }}">
                        <span>Profil Sekolah</span>
                        <svg class="w-3.5 h-3.5 transform transition-transform text-gray-400" :class="open ? 'rotate-180 text-forest-800' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute left-0 mt-1 w-64 bg-white dark:bg-forest-900 rounded-2xl shadow-xl border border-gray-100 dark:border-forest-800 py-2 z-50">

                        <a href="{{ route('profil') }}" class="flex items-start gap-2.5 px-4 py-2.5 hover:bg-forest-50 dark:hover:bg-forest-800 transition {{ request()->routeIs('profil') ? 'bg-forest-50/80 dark:bg-forest-800' : '' }}">
                            <span class="text-base">🏛️</span>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-xs">Profil & Visi Misi</p>
                                <p class="text-[10px] text-gray-400">Sejarah, visi misi & sambutan</p>
                            </div>
                        </a>

                        <a href="{{ route('guru') }}" class="flex items-start gap-2.5 px-4 py-2.5 hover:bg-forest-50 dark:hover:bg-forest-800 transition {{ request()->routeIs('guru') ? 'bg-forest-50/80 dark:bg-forest-800' : '' }}">
                            <span class="text-base">👨‍🏫</span>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-xs">Direktori Pendidik & Tendik</p>
                                <p class="text-[10px] text-gray-400">11 GTK profesional sekolah</p>
                            </div>
                        </a>

                        <a href="{{ route('rombel') }}" class="flex items-start gap-2.5 px-4 py-2.5 hover:bg-forest-50 dark:hover:bg-forest-800 transition {{ request()->routeIs('rombel') ? 'bg-forest-50/80 dark:bg-forest-800' : '' }}">
                            <span class="text-base">🏫</span>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-xs">Rombongan Belajar (Rombel)</p>
                                <p class="text-[10px] text-gray-400">Kelas I - VI, wali kelas & ruangan</p>
                            </div>
                        </a>

                        <a href="{{ route('siswa') }}" class="flex items-start gap-2.5 px-4 py-2.5 hover:bg-forest-50 dark:hover:bg-forest-800 transition {{ request()->routeIs('siswa') ? 'bg-forest-50/80 dark:bg-forest-800' : '' }}">
                            <span class="text-base">🔍</span>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-xs">Daftar Siswa & Cek NISN</p>
                                <p class="text-[10px] text-gray-400">Cek NISN mandiri bansos/administrasi</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Menu 4: Dropdown E-Learning --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false" @mouseleave="open = false">
                    <button type="button"
                            @click="open = !open"
                            @mouseenter="open = true"
                            class="px-3 py-2 rounded-lg transition inline-flex items-center gap-1 text-gray-700 dark:text-gray-200 hover:text-forest-800 hover:bg-forest-50 dark:hover:bg-forest-900/40">
                        <span>E-Learning</span>
                        <svg class="w-3.5 h-3.5 transform transition-transform text-gray-400" :class="open ? 'rotate-180 text-forest-800' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute left-0 mt-1 w-56 bg-white dark:bg-forest-900 rounded-2xl shadow-xl border border-gray-100 dark:border-forest-800 py-2 z-50">

                        <a href="{{ url('/admin/teaching-documents') }}" class="flex items-start gap-2.5 px-4 py-2.5 hover:bg-forest-50 dark:hover:bg-forest-800 transition">
                            <span class="text-base">📁</span>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-xs">Bank Perangkat Ajar</p>
                                <p class="text-[10px] text-gray-400">Modul ajar, ATP & silabus guru</p>
                            </div>
                        </a>

                        <a href="{{ url('/admin/lessons') }}" class="flex items-start gap-2.5 px-4 py-2.5 hover:bg-forest-50 dark:hover:bg-forest-800 transition">
                            <span class="text-base">📖</span>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-xs">Materi Belajar</p>
                                <p class="text-[10px] text-gray-400">Materi pembelajaran per rombel</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Menu 5: Dropdown Publikasi --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false" @mouseleave="open = false">
                    <button type="button"
                            @click="open = !open"
                            @mouseenter="open = true"
                            class="px-3 py-2 rounded-lg transition inline-flex items-center gap-1 {{ request()->routeIs('warta') || request()->routeIs('dokumentasi') ? 'text-forest-900 dark:text-emerald-300 font-extrabold bg-forest-50 dark:bg-forest-900/60 border-b-2 border-forest-800' : 'text-gray-700 dark:text-gray-200 hover:text-forest-800 hover:bg-forest-50 dark:hover:bg-forest-900/40' }}">
                        <span>Publikasi</span>
                        <svg class="w-3.5 h-3.5 transform transition-transform text-gray-400" :class="open ? 'rotate-180 text-forest-800' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute left-0 mt-1 w-56 bg-white dark:bg-forest-900 rounded-2xl shadow-xl border border-gray-100 dark:border-forest-800 py-2 z-50">

                        <a href="{{ route('warta') }}" class="flex items-start gap-2.5 px-4 py-2.5 hover:bg-forest-50 dark:hover:bg-forest-800 transition {{ request()->routeIs('warta') ? 'bg-forest-50 dark:bg-forest-800' : '' }}">
                            <span class="text-base">📰</span>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-xs">Berita & Artikel</p>
                                <p class="text-[10px] text-gray-400">Warta kegiatan & prestasi siswa</p>
                            </div>
                        </a>

                        <a href="{{ route('warta') }}" class="flex items-start gap-2.5 px-4 py-2.5 hover:bg-forest-50 dark:hover:bg-forest-800 transition">
                            <span class="text-base">📢</span>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-xs">Pengumuman</p>
                                <p class="text-[10px] text-gray-400">Surat edaran kedinasan</p>
                            </div>
                        </a>

                        <a href="{{ route('dokumentasi') }}" class="flex items-start gap-2.5 px-4 py-2.5 hover:bg-forest-50 dark:hover:bg-forest-800 transition {{ request()->routeIs('dokumentasi') ? 'bg-forest-50 dark:bg-forest-800' : '' }}">
                            <span class="text-base">🎥</span>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-xs">Galeri Foto & Video</p>
                                <p class="text-[10px] text-gray-400">Dokumentasi TikTok & Drive</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Menu 6: Dropdown Layanan Digital --}}
                <div class="relative" x-data="{ open: false }" @click.away="open = false" @mouseleave="open = false">
                    <button type="button"
                            @click="open = !open"
                            @mouseenter="open = true"
                            class="px-3 py-2 rounded-lg transition inline-flex items-center gap-1 font-bold {{ request()->routeIs('pkks') ? 'text-forest-900 dark:text-emerald-300 font-extrabold bg-forest-50 dark:bg-forest-900/60 border-b-2 border-forest-800' : 'text-forest-800 dark:text-emerald-300 hover:bg-forest-50 dark:hover:bg-forest-900/40' }}">
                        <span>Layanan Digital</span>
                        <svg class="w-3.5 h-3.5 transform transition-transform text-gray-400" :class="open ? 'rotate-180 text-forest-800' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute left-0 mt-1 w-64 bg-white dark:bg-forest-900 rounded-2xl shadow-xl border border-gray-100 dark:border-forest-800 py-2 z-50">

                        <a href="{{ route('presensi.index') }}" class="flex items-start gap-2.5 px-4 py-2.5 hover:bg-forest-50 dark:hover:bg-forest-800 transition">
                            <span class="text-base">📍</span>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-xs">Presensi GPS Guru</p>
                                <p class="text-[10px] text-gray-400">Absen Datang (≤07:30) & Pulang (≥12:00)</p>
                            </div>
                        </a>

                        <a href="{{ url('/admin/student-attendances') }}" class="flex items-start gap-2.5 px-4 py-2.5 hover:bg-forest-50 dark:hover:bg-forest-800 transition">
                            <span class="text-base">👥</span>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-xs">Presensi Siswa</p>
                                <p class="text-[10px] text-gray-400">Monitoring kehadiran peserta didik</p>
                            </div>
                        </a>

                        <a href="{{ route('pkks') }}" class="flex items-start gap-2.5 px-4 py-2.5 hover:bg-forest-50 dark:hover:bg-forest-800 transition {{ request()->routeIs('pkks') ? 'bg-forest-50 dark:bg-forest-800' : '' }}">
                            <span class="text-base">📊</span>
                            <div>
                                <p class="font-bold text-gray-900 dark:text-white text-xs">E-PKKS Digital</p>
                                <p class="text-[10px] text-gray-400">12 Indikator Permendikdasmen 23/2026</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Menu 7: PPDB Online (Teks Emas Amber) --}}
                <a href="{{ route('ppdb') }}"
                   class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('ppdb') ? 'text-sumedang-gold font-black bg-amber-100/70 dark:bg-amber-950/50 border-b-2 border-sumedang-amber' : 'text-sumedang-amber hover:text-sumedang-gold font-extrabold hover:bg-amber-50/60 dark:hover:bg-amber-950/30' }}">
                    PPDB Online
                </a>

            </nav>

            {{-- SINGLE ACTION BUTTON: PORTAL GTK (LOGIN SISTEM) --}}
            <div class="hidden sm:flex items-center gap-3">
                <a href="{{ url('/admin') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-forest-900 hover:bg-forest-800 text-white text-xs font-bold shadow-md shadow-forest-900/20 transition-all hover:shadow-lg transform hover:-translate-y-0.5 border border-forest-800">
                    <svg class="w-4 h-4 text-sumedang-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span>Portal GTK</span>
                </a>
            </div>

            {{-- MOBILE ACTION & HAMBURGER BUTTON --}}
            <div class="flex lg:hidden items-center gap-2">
                <a href="{{ route('presensi.index') }}"
                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-500/10 text-forest-900 dark:text-emerald-300 font-bold text-xs border border-emerald-400/30">
                    <span>📍</span>
                    <span>Presensi</span>
                </a>

                <button type="button"
                        @click="mobileOpen = !mobileOpen"
                        class="p-2 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-forest-900 transition"
                        aria-label="Toggle Navigation">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

        </div>
    </div>

    {{-- MOBILE DRAWER SLIDEOUT DENGAN ACCORDION ALPINES.JS --}}
    <div x-show="mobileOpen" x-cloak
         x-data="{ mobileAccordion: null }"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="lg:hidden bg-white dark:bg-forest-950 border-b border-gray-200 dark:border-forest-900 px-4 pt-3 pb-6 space-y-2 shadow-2xl max-h-[85vh] overflow-y-auto">

        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-forest-900 text-xs">
            <span class="text-gray-500 font-medium">Satuan Pendidikan Resmi</span>
            <span class="px-2.5 py-0.5 bg-forest-100 dark:bg-forest-900 text-forest-800 dark:text-emerald-300 font-bold rounded-full text-[11px]">NPSN: {{ $profile?->npsn ?? '20208801' }}</span>
        </div>

        {{-- Nav Item: Beranda --}}
        <a @click="mobileOpen = false" href="{{ route('home') }}"
           class="block px-3 py-2.5 rounded-lg text-sm font-semibold {{ request()->routeIs('home') ? 'bg-forest-100 dark:bg-forest-900 text-forest-900 dark:text-emerald-300 font-bold' : 'hover:bg-forest-50 dark:hover:bg-forest-900' }}">
            🏠 Beranda
        </a>

        {{-- Accordion Mobile: Profil Sekolah (Profil, Guru, Rombel, Siswa) --}}
        <div class="border-b border-gray-100 dark:border-forest-900/60 pb-1">
            <button type="button"
                    @click="mobileAccordion = (mobileAccordion === 'profil' ? null : 'profil')"
                    class="w-full px-3 py-2.5 flex items-center justify-between text-sm font-semibold hover:bg-forest-50 dark:hover:bg-forest-900 rounded-lg {{ request()->routeIs('profil') || request()->routeIs('guru') || request()->routeIs('rombel') || request()->routeIs('siswa') ? 'text-forest-900 dark:text-emerald-300 font-bold bg-forest-50 dark:bg-forest-900/40' : '' }}">
                <span class="flex items-center gap-2">🏛️ Profil Sekolah</span>
                <svg class="w-4 h-4 text-gray-400 transform transition-transform" :class="mobileAccordion === 'profil' ? 'rotate-180 text-forest-800' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="mobileAccordion === 'profil'" x-cloak class="pl-6 pr-2 py-2 space-y-1.5 bg-forest-50/50 dark:bg-forest-900/30 rounded-lg my-1">
                <a @click="mobileOpen = false" href="{{ route('profil') }}" class="block py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:text-forest-800 {{ request()->routeIs('profil') ? 'text-forest-800 dark:text-emerald-300 font-bold' : '' }}">
                    🏛️ Profil & Visi Misi
                </a>
                <a @click="mobileOpen = false" href="{{ route('guru') }}" class="block py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:text-forest-800 {{ request()->routeIs('guru') ? 'text-forest-800 dark:text-emerald-300 font-bold' : '' }}">
                    👨‍🏫 Direktori Pendidik & Tendik
                </a>
                <a @click="mobileOpen = false" href="{{ route('rombel') }}" class="block py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:text-forest-800 {{ request()->routeIs('rombel') ? 'text-forest-800 dark:text-emerald-300 font-bold' : '' }}">
                    🏫 Rombongan Belajar (Rombel)
                </a>
                <a @click="mobileOpen = false" href="{{ route('siswa') }}" class="block py-1.5 text-xs font-semibold text-forest-800 dark:text-emerald-300 {{ request()->routeIs('siswa') ? 'text-forest-800 dark:text-emerald-300 font-extrabold underline' : '' }}">
                    🔍 Daftar Siswa & Cek NISN
                </a>
            </div>
        </div>

        {{-- Accordion Mobile 1: E-Learning --}}
        <div class="border-b border-gray-100 dark:border-forest-900/60 pb-1">
            <button type="button"
                    @click="mobileAccordion = (mobileAccordion === 'elearning' ? null : 'elearning')"
                    class="w-full px-3 py-2.5 flex items-center justify-between text-sm font-semibold hover:bg-forest-50 dark:hover:bg-forest-900 rounded-lg">
                <span class="flex items-center gap-2">📚 E-Learning</span>
                <svg class="w-4 h-4 text-gray-400 transform transition-transform" :class="mobileAccordion === 'elearning' ? 'rotate-180 text-forest-800' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="mobileAccordion === 'elearning'" x-cloak class="pl-6 pr-2 py-2 space-y-1.5 bg-forest-50/50 dark:bg-forest-900/30 rounded-lg my-1">
                <a @click="mobileOpen = false" href="{{ url('/admin/teaching-documents') }}" class="block py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:text-forest-800">
                    📁 Bank Perangkat Ajar & Modul
                </a>
                <a @click="mobileOpen = false" href="{{ url('/admin/lessons') }}" class="block py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:text-forest-800">
                    📖 Materi Belajar Siswa
                </a>
            </div>
        </div>

        {{-- Accordion Mobile 2: Publikasi --}}
        <div class="border-b border-gray-100 dark:border-forest-900/60 pb-1">
            <button type="button"
                    @click="mobileAccordion = (mobileAccordion === 'publikasi' ? null : 'publikasi')"
                    class="w-full px-3 py-2.5 flex items-center justify-between text-sm font-semibold hover:bg-forest-50 dark:hover:bg-forest-900 rounded-lg">
                <span class="flex items-center gap-2">📰 Publikasi</span>
                <svg class="w-4 h-4 text-gray-400 transform transition-transform" :class="mobileAccordion === 'publikasi' ? 'rotate-180 text-forest-800' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="mobileAccordion === 'publikasi'" x-cloak class="pl-6 pr-2 py-2 space-y-1.5 bg-forest-50/50 dark:bg-forest-900/30 rounded-lg my-1">
                <a @click="mobileOpen = false" href="{{ route('warta') }}" class="block py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:text-forest-800">
                    📝 Berita & Artikel Sekolah
                </a>
                <a @click="mobileOpen = false" href="{{ route('warta') }}" class="block py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:text-forest-800">
                    📢 Pengumuman Kedinasan
                </a>
                <a @click="mobileOpen = false" href="{{ route('dokumentasi') }}" class="block py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:text-forest-800">
                    🎥 Galeri Foto & Video Kegiatan
                </a>
            </div>
        </div>

        {{-- Accordion Mobile 3: Layanan Digital --}}
        <div class="border-b border-gray-100 dark:border-forest-900/60 pb-1">
            <button type="button"
                    @click="mobileAccordion = (mobileAccordion === 'layanan' ? null : 'layanan')"
                    class="w-full px-3 py-2.5 flex items-center justify-between text-sm font-bold text-forest-800 dark:text-emerald-300 hover:bg-forest-50 dark:hover:bg-forest-900 rounded-lg">
                <span class="flex items-center gap-2">⚡ Layanan Digital</span>
                <svg class="w-4 h-4 text-gray-400 transform transition-transform" :class="mobileAccordion === 'layanan' ? 'rotate-180 text-forest-800' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div x-show="mobileAccordion === 'layanan'" x-cloak class="pl-6 pr-2 py-2 space-y-1.5 bg-forest-50/50 dark:bg-forest-900/30 rounded-lg my-1">
                <a @click="mobileOpen = false" href="{{ route('presensi.index') }}" class="block py-1.5 text-xs font-bold text-sumedang-gold hover:underline">
                    📍 Presensi GPS Guru (Datang & Pulang)
                </a>
                <a @click="mobileOpen = false" href="{{ url('/admin/student-attendances') }}" class="block py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:text-forest-800">
                    👥 Presensi Kehadiran Siswa
                </a>
                <a @click="mobileOpen = false" href="{{ route('pkks') }}" class="block py-1.5 text-xs font-bold text-forest-800 dark:text-emerald-400">
                    📊 Portofolio E-PKKS Digital
                </a>
            </div>
        </div>

        {{-- Nav Item: PPDB Online (Emas Amber) --}}
        <a @click="mobileOpen = false" href="{{ route('ppdb') }}"
           class="block px-3 py-2.5 rounded-lg text-sm font-extrabold text-sumedang-gold bg-amber-50 dark:bg-amber-950/30 border border-amber-200/50">
            🎒 Layanan PPDB Online 2026/2027
        </a>

        {{-- SINGLE MOBILE ACTION: PORTAL GTK --}}
        <div class="pt-3 border-t border-gray-100 dark:border-forest-900">
            <a href="{{ url('/admin') }}" class="w-full py-3 rounded-xl bg-forest-900 hover:bg-forest-800 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg transition">
                <svg class="w-4 h-4 text-sumedang-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                <span>Masuk ke Portal GTK (Login Admin)</span>
            </a>
        </div>
    </div>
</header>
