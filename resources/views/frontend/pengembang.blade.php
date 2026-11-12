@extends('layouts.frontend')

@section('title', 'Profil Pengembang Website - Agi Hamdani, M.Pd. | SDN Cinanggerang I')
@section('meta_description', 'Profil dan CV singkat Agi Hamdani, M.Pd., Guru SDN Cinanggerang I sekaligus Web Developer & inisiator sistem digitalisasi sekolah serta web CBT sasipedia.my.id.')
@section('canonical_url', route('pengembang'))

@section('content')
    {{-- Header Banner Halaman --}}
    <div class="bg-forest-950 text-white py-10 md:py-14 border-b border-forest-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:24px_24px] opacity-25 pointer-events-none"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb Navigasi --}}
            <nav class="flex items-center gap-2 text-xs text-emerald-300/80 mb-3">
                <a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a>
                <span>&rsaquo;</span>
                <span class="text-white font-semibold">Profil Pengembang Web</span>
            </nav>

            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-forest-900 border border-emerald-400/30 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-3">
                <span class="w-2 h-2 rounded-full bg-sumedang-amber animate-pulse"></span>
                <span>Inovasi Teknologi & Dedikasi Pendidikan</span>
            </span>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight text-white">
                Profil Pengembang Website
            </h1>
            <p class="text-sm md:text-base text-gray-300 mt-2 max-w-3xl leading-relaxed">
                Mengenal sosok pengembang sistem informasi dan portal digital SD Negeri Cinanggerang I: perpaduan kompetensi pedagogi sekolah dasar dengan dedikasi rekayasa teknologi digital.
            </p>
        </div>
    </div>

    {{-- Konten Utama Profil & CV --}}
    <div class="bg-slate-50 dark:bg-forest-950/40 py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

            {{-- 1. KARTU HERO IDENTITAS PENGEMBANG (HERO PROFILE CARD) --}}
            <div class="bg-white dark:bg-forest-900 rounded-3xl border border-slate-200/80 dark:border-forest-800 shadow-xl overflow-hidden">
                <div class="p-6 sm:p-8 lg:p-10">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

                        {{-- Foto Profil / Avatar Pengembang --}}
                        <div class="lg:col-span-4 flex flex-col items-center text-center">
                            <div class="relative group">
                                <div class="absolute -inset-1.5 bg-gradient-to-tr from-emerald-500 to-sumedang-amber rounded-3xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                                <div class="relative w-48 h-48 sm:w-56 sm:h-56 rounded-2xl overflow-hidden border-4 border-white dark:border-forest-800 shadow-2xl bg-forest-950">
                                    @php
                                        $devPhoto = $developer?->foto_url ?? asset('images/logo-official.png');
                                    @endphp
                                    <img src="{{ $devPhoto }}"
                                         alt="Agi Hamdani, M.Pd."
                                         class="w-full h-full object-cover object-top transition duration-500 group-hover:scale-105"
                                         onerror="this.onerror=null; this.src='{{ asset('images/logo-official.png') }}';">
                                </div>
                                <span class="absolute bottom-2 right-2 px-3 py-1 bg-emerald-600 text-white text-[11px] font-bold rounded-full shadow-md border-2 border-white dark:border-forest-900">
                                    Aktif Mengabdi
                                </span>
                            </div>

                            <div class="mt-4 space-y-1">
                                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Unit Kerja:</span>
                                <p class="text-xs font-bold text-forest-900 dark:text-emerald-300">SD Negeri Cinanggerang I</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">Kec. Pamulihan, Kab. Sumedang</p>
                            </div>
                        </div>

                        {{-- Informasi & Narasi Identitas --}}
                        <div class="lg:col-span-8 space-y-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/70 text-emerald-800 dark:text-emerald-300 text-xs font-bold">
                                    Guru SDN Cinanggerang I (2006 - Sekarang)
                                </span>
                                <span class="px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-950/70 text-amber-900 dark:text-sumedang-amber text-xs font-bold">
                                    Web Developer & IT Creator
                                </span>
                                <span class="px-3 py-1 rounded-full bg-forest-100 dark:bg-forest-800 text-forest-900 dark:text-gray-200 text-xs font-semibold">
                                    PNS • NIP {{ $developer?->nip ?? '198801182019031003' }}
                                </span>
                            </div>

                            <div>
                                <h2 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                                    Agi Hamdani, M.Pd.
                                </h2>
                                <p class="text-sm sm:text-base font-semibold text-sumedang-amber mt-0.5">
                                    Guru Kelas VI & Webmaster Digitalisasi Sekolah
                                </p>
                            </div>

                            {{-- Kutipan Visi Teknologi & Pendidikan --}}
                            <div class="p-4 rounded-2xl bg-forest-50 dark:bg-forest-950/60 border-l-4 border-emerald-500 text-gray-700 dark:text-gray-300 text-xs sm:text-sm italic leading-relaxed">
                                "Pendidikan dasar berkarakter luhur berpadu dengan akselerasi teknologi informasi yang mandiri, adaptif, dan berorientasi pada masa depan generasi bangsa. Membangun sistem yang tidak hanya mempermudah administrasi, namun juga menguatkan keterbukaan dan integritas ekosistem sekolah."
                            </div>

                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                Berpengalaman sebagai pendidik sejak tahun 2006 di SDN Cinanggerang I dengan latar belakang pendidikan magister kependidikan dasar (S2 PGSD UPI). Mengembangkan minat dan keahlian di bidang rekayasa perangkat lunak (web development), perancangan arsitektur sistem informasi, evaluasi berbasis komputer (CBT), serta otomatisasi administrasi sekolah berbasis web.
                            </p>

                            {{-- Tombol Aksi Cepat Kontak & Tautan --}}
                            <div class="pt-2 flex flex-wrap items-center gap-3">
                                <a href="https://wa.me/6282118449525" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition-all transform hover:-translate-y-0.5">
                                    <span>💬</span>
                                    <span>WhatsApp (082118449525)</span>
                                </a>

                                <a href="mailto:nughejr@gmail.com"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-forest-900 hover:bg-forest-800 text-white text-xs font-bold shadow-md shadow-forest-900/20 transition-all transform hover:-translate-y-0.5">
                                    <span>✉️</span>
                                    <span>Email</span>
                                </a>

                                <a href="https://github.com/nughejr-creator" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold transition-all transform hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                                    <span>GitHub</span>
                                </a>

                                <a href="https://sasipedia.my.id" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-forest-950 text-xs font-black transition-all transform hover:-translate-y-0.5">
                                    <span>⚡</span>
                                    <span>Web CBT: sasipedia.my.id &rarr;</span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 2. CURRICULUM VITAE (CV) SINGKAT --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- Kolom Kiri: Riwayat Pendidikan Formal --}}
                <div class="bg-white dark:bg-forest-900 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-forest-800 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-forest-800">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 flex items-center justify-center text-xl shadow-sm">
                            🎓
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">Riwayat Pendidikan Formal</h3>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">Pendidikan akademis perguruan tinggi</p>
                        </div>
                    </div>

                    <div class="relative pl-6 space-y-6 before:content-[''] before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-emerald-300 dark:before:bg-emerald-700">
                        {{-- S2 PGSD UPI Cibiru --}}
                        <div class="relative">
                            <span class="absolute -left-[27px] top-1.5 w-3.5 h-3.5 rounded-full bg-emerald-600 border-2 border-white dark:border-forest-900 ring-2 ring-emerald-300 dark:ring-emerald-800"></span>
                            <div class="bg-slate-50 dark:bg-forest-950/60 p-4 rounded-2xl border border-slate-100 dark:border-forest-800/80">
                                <span class="px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 text-[10px] font-bold">
                                    Pascasarjana (S2)
                                </span>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mt-1.5">
                                    S2 Magister PGSD (Pendidikan Guru Sekolah Dasar)
                                </h4>
                                <p class="text-xs font-semibold text-sumedang-amber mt-0.5">
                                    Universitas Pendidikan Indonesia (UPI) Kampus Cibiru
                                </p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                    Gelar: Magister Pendidikan (M.Pd.) • Fokus kajian pembelajaran dasar inovatif dan teknologi asesmen kependidikan.
                                </p>
                            </div>
                        </div>

                        {{-- S1 PGSD UPI Sumedang --}}
                        <div class="relative">
                            <span class="absolute -left-[27px] top-1.5 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white dark:border-forest-900 ring-2 ring-emerald-300 dark:ring-emerald-800"></span>
                            <div class="bg-slate-50 dark:bg-forest-950/60 p-4 rounded-2xl border border-slate-100 dark:border-forest-800/80">
                                <span class="px-2 py-0.5 rounded bg-forest-100 dark:bg-forest-800 text-forest-900 dark:text-emerald-300 text-[10px] font-bold">
                                    Sarjana (S1)
                                </span>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mt-1.5">
                                    S1 Pendidikan Guru Sekolah Dasar (PGSD)
                                </h4>
                                <p class="text-xs font-semibold text-sumedang-amber mt-0.5">
                                    Universitas Pendidikan Indonesia (UPI) Kampus Sumedang
                                </p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                    Gelar: Sarjana Pendidikan (S.Pd.) • Penguasaan pedagogi, kurikulum, dan pengajaran dasar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Pengalaman Kerja & Pengabdian --}}
                <div class="bg-white dark:bg-forest-900 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-forest-800 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-forest-800">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-sumedang-amber flex items-center justify-center text-xl shadow-sm">
                            💼
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">Riwayat Pengabdian & Pekerjaan</h3>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">Dedikasi profesional di dunia pendidikan</p>
                        </div>
                    </div>

                    <div class="relative pl-6 space-y-6 before:content-[''] before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-amber-300 dark:before:bg-amber-700">
                        {{-- Guru SDN Cinanggerang 1 --}}
                        <div class="relative">
                            <span class="absolute -left-[27px] top-1.5 w-3.5 h-3.5 rounded-full bg-amber-500 border-2 border-white dark:border-forest-900 ring-2 ring-amber-300 dark:ring-amber-800"></span>
                            <div class="bg-slate-50 dark:bg-forest-950/60 p-4 rounded-2xl border border-slate-100 dark:border-forest-800/80">
                                <div class="flex items-center justify-between">
                                    <span class="px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 text-[10px] font-bold">
                                        PNS / Guru Aktif
                                    </span>
                                    <span class="text-[11px] font-bold text-sumedang-amber">2006 – Sekarang</span>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mt-1.5">
                                    Guru SD Negeri Cinanggerang I
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    Kecamatan Pamulihan, Kabupaten Sumedang
                                </p>
                                <p class="text-[11px] text-gray-600 dark:text-gray-300 mt-2 leading-relaxed">
                                    Melaksanakan tugas pembelajaran sebagai guru kelas, pembimbing siswa, serta penggerak transformasi pembelajaran Kurikulum Merdeka di sekolah dasar.
                                </p>
                            </div>
                        </div>

                        {{-- Inisiator & Webmaster --}}
                        <div class="relative">
                            <span class="absolute -left-[27px] top-1.5 w-3.5 h-3.5 rounded-full bg-forest-700 border-2 border-white dark:border-forest-900 ring-2 ring-emerald-300 dark:ring-emerald-800"></span>
                            <div class="bg-slate-50 dark:bg-forest-950/60 p-4 rounded-2xl border border-slate-100 dark:border-forest-800/80">
                                <div class="flex items-center justify-between">
                                    <span class="px-2 py-0.5 rounded bg-forest-100 dark:bg-forest-800 text-forest-900 dark:text-emerald-300 text-[10px] font-bold">
                                        Inovasi Sistem
                                    </span>
                                    <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400">2019 – Sekarang</span>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mt-1.5">
                                    Webmaster & Pengembang Sistem Informasi Sekolah
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    Pembangunan Ekosistem Web SDN Cinanggerang I
                                </p>
                                <p class="text-[11px] text-gray-600 dark:text-gray-300 mt-2 leading-relaxed">
                                    Merancang dan mengawal arsitektur portal resmi, modul Presensi GPS Mandiri Guru & GTK, digitalisasi e-PKKS, serta integrasi layanan PPDB daring sejak 2019.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 3. KARYA & PORTOFOLIO SISTEM DIGITAL (PORTFOLIO SHOWCASE) --}}
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Karya Digital Unggulan</span>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Portofolio Sistem & Aplikasi Web</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Karya 1: Portal SDN Cinanggerang 1 --}}
                    <div class="bg-white dark:bg-forest-900 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-forest-800 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                        <div class="space-y-4">
                            <div class="flex items-start justify-between">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 flex items-center justify-center text-2xl shadow-sm">
                                    🏫
                                </div>
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200 text-[10px] font-bold">
                                    Live Production (Website Ini)
                                </span>
                            </div>

                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition">
                                    Portal Resmi & Sistem Informasi Terpadu SDN Cinanggerang I
                                </h4>
                                <p class="text-xs text-sumedang-amber font-semibold mt-0.5">
                                    Sistem Sekolah Berbasis Web Terintegrasi
                                </p>
                            </div>

                            <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                                Portal multi-fitur yang mencakup Publikasi Sekolah, Sistem Presensi Mandiri GTK berbasis Geofencing GPS dan Swafoto Kamera, Instrumen Portofolio e-PKKS Digital Pimpinan, Layanan PPDB Online, serta Bank Perangkat Ajar Kurikulum Merdeka.
                            </p>

                            {{-- Badges Tech --}}
                            <div class="flex flex-wrap gap-1.5 pt-2">
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-forest-950 text-slate-700 dark:text-gray-300 text-[10px] font-semibold">Laravel 11</span>
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-forest-950 text-slate-700 dark:text-gray-300 text-[10px] font-semibold">Filament Admin v3</span>
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-forest-950 text-slate-700 dark:text-gray-300 text-[10px] font-semibold">Tailwind CSS</span>
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-forest-950 text-slate-700 dark:text-gray-300 text-[10px] font-semibold">GPS Geofencing</span>
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-forest-950 text-slate-700 dark:text-gray-300 text-[10px] font-semibold">MySQL</span>
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-gray-100 dark:border-forest-800">
                            <a href="{{ route('home') }}"
                               class="inline-flex items-center gap-1.5 text-xs font-bold text-forest-800 dark:text-emerald-300 hover:text-sumedang-amber transition">
                                <span>Jelajahi Fitur Portal Sekolah</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>

                    {{-- Karya 2: Sasipedia CBT --}}
                    <div class="bg-white dark:bg-forest-900 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-forest-800 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                        <div class="space-y-4">
                            <div class="flex items-start justify-between">
                                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-sumedang-amber flex items-center justify-center text-2xl shadow-sm">
                                    💻
                                </div>
                                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200 text-[10px] font-bold">
                                    Platform Asesmen & Ujian Digital
                                </span>
                            </div>

                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-sumedang-amber transition">
                                    Sistem Web CBT (Computer-Based Testing) – Sasipedia
                                </h4>
                                <p class="text-xs text-sumedang-amber font-semibold mt-0.5">
                                    sasipedia.my.id • Modern Jamstack Architecture
                                </p>
                            </div>

                            <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                                Platform Computer-Based Testing (CBT) mandiri untuk penyelenggaraan ujian, kuis, asesmen formatif dan sumatif secara daring dengan keandalan tinggi di lingkungan sekolah.
                            </p>

                            {{-- Rincian Arsitektur & Karakteristik Desain CBT --}}
                            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-forest-950/70 border border-slate-200/70 dark:border-forest-800 space-y-2.5 text-xs">
                                <div>
                                    <span class="font-bold text-emerald-700 dark:text-emerald-400 block text-[11px] uppercase tracking-wider">Teknologi Utama:</span>
                                    <p class="text-gray-700 dark:text-gray-200 mt-0.5">TypeScript, React 18, Tailwind CSS, Supabase (PostgreSQL), Vite, Node.js.</p>
                                </div>
                                <div>
                                    <span class="font-bold text-amber-700 dark:text-sumedang-amber block text-[11px] uppercase tracking-wider">Model Arsitektur:</span>
                                    <p class="text-gray-700 dark:text-gray-200 mt-0.5">Modern Jamstack (SPA Frontend + BaaS Supabase + Cloudflare Edge Gateway).</p>
                                </div>
                                <div>
                                    <span class="font-bold text-forest-800 dark:text-emerald-300 block text-[11px] uppercase tracking-wider">Karakteristik Desain:</span>
                                    <p class="text-gray-700 dark:text-gray-200 mt-0.5">Sangat hemat bandwidth, aman dari kebocoran soal, tahan gangguan koneksi internet lokal di sekolah (<em>offline-resilient</em>), dan siap melayani asesmen skala besar secara bersamaan.</p>
                                </div>
                            </div>

                            {{-- Badges Tech --}}
                            <div class="flex flex-wrap gap-1.5 pt-1">
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-forest-950 text-slate-700 dark:text-gray-300 text-[10px] font-semibold">TypeScript</span>
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-forest-950 text-slate-700 dark:text-gray-300 text-[10px] font-semibold">React 18</span>
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-forest-950 text-slate-700 dark:text-gray-300 text-[10px] font-semibold">Supabase (PostgreSQL)</span>
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-forest-950 text-slate-700 dark:text-gray-300 text-[10px] font-semibold">Jamstack</span>
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-forest-950 text-slate-700 dark:text-gray-300 text-[10px] font-semibold">Cloudflare</span>
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-gray-100 dark:border-forest-800">
                            <a href="https://sasipedia.my.id" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-600 dark:text-sumedang-amber hover:underline transition">
                                <span>Buka Aplikasi Web: sasipedia.my.id</span>
                                <span class="transform group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            {{-- 4. SPESIFIKASI TEKNIS & INTEGRASI (TECH OVERVIEW) --}}
            <div class="bg-forest-900 text-white rounded-3xl p-6 sm:p-8 lg:p-10 border border-forest-800 shadow-xl relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

                <div class="relative z-10 max-w-3xl space-y-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-sumedang-amber">SPESIFIKASI TEKNIS & ALAT PENGEMBANGAN</span>
                    <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-white">
                        Sistem & Rekayasa Perangkat Lunak
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                        Penerapan arsitektur sistem berbasis web yang berfokus pada efisiensi penggunaan sumber daya, kemudahan pemeliharaan, serta aksesibilitas yang andal dan ramah bagi seluruh warga sekolah.
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4">
                        <div class="p-3.5 rounded-2xl bg-forest-950/70 border border-forest-800">
                            <p class="text-xs font-bold text-emerald-300">Backend & API</p>
                            <p class="text-[11px] text-gray-300 mt-1">PHP 8.2+, Laravel 11, RESTful Architecture</p>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-forest-950/70 border border-forest-800">
                            <p class="text-xs font-bold text-emerald-300">Frontend & UI</p>
                            <p class="text-[11px] text-gray-300 mt-1">Tailwind CSS, Alpine.js, Blade Engine</p>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-forest-950/70 border border-forest-800">
                            <p class="text-xs font-bold text-emerald-300">Basis Data & Geolocation</p>
                            <p class="text-[11px] text-gray-300 mt-1">MySQL, GPS Geofencing, Media Storage</p>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-forest-950/70 border border-forest-800">
                            <p class="text-xs font-bold text-emerald-300">Manajemen & Panel</p>
                            <p class="text-[11px] text-gray-300 mt-1">Filament PHP v3, Git Versioning, CI/CD</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. KOTAK MEDIA SOSIAL & KONTAK PENGEMBANG --}}
            <div class="bg-white dark:bg-forest-900 rounded-3xl p-6 sm:p-8 lg:p-10 border border-slate-200/80 dark:border-forest-800 shadow-md text-center space-y-6">
                <div class="max-w-xl mx-auto space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Kontak & Media Sosial</span>
                    <h3 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white">
                        Saluran Komunikasi Pengembang
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                        Silakan hubungi atau terhubung melalui media sosial dan saluran komunikasi berikut:
                    </p>
                </div>

                {{-- Badges Kontak Sosial Lengkap --}}
                <div class="flex flex-wrap items-center justify-center gap-3">
                    {{-- WhatsApp --}}
                    <a href="https://wa.me/6282118449525" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/70 hover:bg-emerald-600 hover:text-white text-emerald-800 dark:text-emerald-300 text-xs font-bold border border-emerald-300/60 dark:border-emerald-800 transition shadow-sm transform hover:-translate-y-0.5">
                        <span class="text-base">💬</span>
                        <span>WhatsApp: 082118449525</span>
                    </a>

                    {{-- Instagram --}}
                    <a href="https://instagram.com/nughe_jr" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-pink-50 dark:bg-pink-950/70 hover:bg-gradient-to-r hover:from-purple-500 hover:to-pink-500 hover:text-white text-pink-800 dark:text-pink-300 text-xs font-bold border border-pink-300/60 dark:border-pink-800 transition shadow-sm transform hover:-translate-y-0.5">
                        <span class="text-base">📸</span>
                        <span>Instagram: @nughe_jr</span>
                    </a>

                    {{-- Facebook --}}
                    <a href="https://facebook.com/search/top?q=Abdi%20Jalmi%20Alit" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-blue-50 dark:bg-blue-950/70 hover:bg-blue-600 hover:text-white text-blue-800 dark:text-blue-300 text-xs font-bold border border-blue-300/60 dark:border-blue-800 transition shadow-sm transform hover:-translate-y-0.5">
                        <span class="text-base">👥</span>
                        <span>Facebook: Abdi Jalmi Alit</span>
                    </a>

                    {{-- Email --}}
                    <a href="mailto:nughejr@gmail.com"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-amber-50 dark:bg-amber-950/70 hover:bg-amber-500 hover:text-forest-950 text-amber-900 dark:text-sumedang-amber text-xs font-bold border border-amber-300/60 dark:border-amber-800 transition shadow-sm transform hover:-translate-y-0.5">
                        <span class="text-base">✉️</span>
                        <span>nughejr@gmail.com</span>
                    </a>

                    {{-- GitHub --}}
                    <a href="https://github.com/nughejr-creator" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-900 hover:text-white text-slate-800 dark:text-slate-200 text-xs font-bold border border-slate-300 dark:border-slate-700 transition shadow-sm transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                        <span>GitHub</span>
                    </a>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-forest-800">
                    <a href="{{ route('home') }}" class="text-xs font-bold text-gray-500 hover:text-forest-900 dark:hover:text-emerald-300 transition">
                        Kembali ke Halaman Beranda Utama &uarr;
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
