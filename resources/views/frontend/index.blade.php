@extends('layouts.frontend')

@section('title', 'SD Negeri Cinanggerang I | Portal Resmi & Publikasi Pendidikan Sumedang')
@section('meta_description', 'Portal Resmi SD Negeri Cinanggerang I Pamulihan Sumedang. Pusat informasi sekolah, pendaftaran PPDB online, warta prestasi, dan layanan digital.')
@section('canonical_url', route('home'))

@section('content')
<div class="bg-slate-50 dark:bg-forest-950 text-slate-800 dark:text-gray-100 min-h-screen">

    {{-- ========================================================================= --}}
    {{-- 1. TICKER PENGUMUMAN ATAS (LEMBUT, PASTEL AMBER & MENYATU DENGAN NAVBAR) --}}
    {{-- ========================================================================= --}}
    @if(($profile?->is_ticker_active ?? true) && !empty($profile?->ticker_text_display))
    <div class="bg-amber-50/90 dark:bg-amber-950/40 border-b border-amber-200/80 dark:border-amber-900/60 text-amber-950 dark:text-amber-200 py-2.5 px-4 shadow-sm relative overflow-hidden z-20 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-3 text-xs">
            
            {{-- Badge Lonceng / Megafon Rapi --}}
            <div class="flex items-center gap-2 shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-200/80 dark:bg-amber-900/80 text-amber-900 dark:text-amber-200 font-extrabold text-[11px] border border-amber-300/60 shadow-sm">
                    <span>🔔</span>
                    <span>PENGUMUMAN RESMI</span>
                </span>
            </div>

            {{-- Ticker / Teks Berjalan Dinamis --}}
            <div class="flex-1 overflow-hidden whitespace-nowrap">
                <div class="inline-block animate-marquee font-semibold text-amber-900 dark:text-amber-200 text-xs">
                    {!! $profile->ticker_text_display !!}
                </div>
            </div>

            {{-- Tombol Buka Rincian --}}
            <a href="{{ route('pengumuman') }}"
               class="shrink-0 px-3.5 py-1 rounded-full bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs transition shadow-sm flex items-center gap-1">
                <span>Rincian</span>
                <span>&rarr;</span>
            </a>

        </div>
    </div>
    @endif

    {{-- Animasi Marquee CSS --}}
    <style>
        @keyframes marquee {
            0%   { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            display: inline-block;
            white-space: nowrap;
            animation: marquee 34s linear infinite;
        }
        .animate-marquee:hover {
            animation-play-state: paused;
        }
    </style>

    {{-- ========================================================================= --}}
    {{-- 2. HERO SECTION (SPLIT 2 KOLOM: TEKS KIRI + FOTO 16:9 KANAN)              --}}
    {{-- ========================================================================= --}}
    <section class="relative bg-forest-950 text-white overflow-hidden py-10 lg:py-16 border-b border-forest-900/80">
        {{-- Pola Background Lembut --}}
        <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:24px_24px] opacity-25 pointer-events-none"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                {{-- KOLOM KIRI (7 KOLOM): IDENTITAS, JUDUL, VISI RINGKAS & TOMBOL CTA --}}
                <div class="lg:col-span-7 space-y-4">
                    
                    {{-- Badge Status Legalitas --}}
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-forest-900/90 border border-emerald-400/30 text-emerald-300 text-xs font-semibold backdrop-blur-sm shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-sumedang-amber animate-pulse"></span>
                        <span>Satuan Pendidikan Dasar Negeri Resmi • NPSN {{ $profile?->npsn ?? '20208801' }}</span>
                    </div>

                    {{-- Judul Utama --}}
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white leading-tight tracking-tight">
                        Pendidikan Unggul, Berkarakter & Ramah Anak
                    </h1>

                    {{-- Nama Sekolah & Lokasi --}}
                    <div class="text-sm sm:text-base font-bold text-sumedang-amber tracking-wide flex items-center gap-2">
                        <span>{{ $profile?->nama_sekolah ?? 'SD NEGERI CINANGGERANG I' }}</span>
                        <span class="text-xs px-2 py-0.5 rounded bg-emerald-900/80 text-emerald-200 border border-emerald-700">Kec. Pamulihan, Sumedang</span>
                    </div>

                    {{-- Ringkasan Visi Ringkas (Maksimal 2 Baris agar Tidak Sesak) --}}
                    <div class="pl-3.5 border-l-2 border-amber-400 max-w-xl my-3">
                        <p class="text-xs sm:text-sm text-emerald-100/90 italic leading-relaxed line-clamp-2">
                            "{!! strip_tags($profile?->visi) ?: 'Terwujudnya Peserta Didik yang Berakhlak Mulia, Cerdas, Terampil, Mandiri, dan Berwawasan Lingkungan.' !!}"
                        </p>
                    </div>

                    {{-- Tombol Aksi CTA --}}
                    <div class="pt-2 flex flex-wrap items-center gap-3">
                        <a href="{{ route('ppdb') }}"
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-sumedang-amber hover:bg-sumedang-gold text-forest-950 font-black text-xs sm:text-sm shadow-lg shadow-sumedang-amber/25 transition-all transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 text-forest-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            <span>Layanan PPDB Online 2026/2027</span>
                        </a>

                        <a href="{{ route('profil') }}"
                           class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm border border-white/20 backdrop-blur-md transition-all">
                            <span>Profil Sekolah</span>
                            <span>&rarr;</span>
                        </a>
                    </div>

                </div>

                {{-- KOLOM KANAN (5 KOLOM): BINGKAI FOTO UTAMA 16:9 DENGAN ROUNDED-3XL & SHADOW HALUS --}}
                <div class="lg:col-span-5">
                    <div class="relative aspect-[16/9] w-full rounded-3xl overflow-hidden border-2 border-white/20 shadow-2xl bg-forest-900 group">
                        @php
                            $heroImg = $profile?->banner_hero_url ?: 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=1200&q=80';
                        @endphp
                        <img src="{{ $heroImg }}"
                             alt="Gedung & Kegiatan Siswa SDN Cinanggerang I"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1580582932707-520aed937b7b?auto=format&fit=crop&w=1200&q=80';">
                        
                        {{-- Overlay Gradien Tipis & Label Caption --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-forest-950/80 via-transparent to-transparent flex items-end p-4">
                            <div class="flex items-center gap-2 text-white text-xs font-semibold">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                <span class="text-gray-200 text-[11px] drop-shadow">Kampus Pembelajaran Aktif & Ramah Anak</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ========================================================================= --}}
    {{-- 3. QUICK ACCESS CARDS (GAYA SERAGAM, BERSIH, SHADOW LEMBUT & TANPA BORDER KUNING) --}}
    {{-- ========================================================================= --}}
    <section class="relative z-20 -mt-6 sm:-mt-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">

            {{-- Kartu 1: PPDB Online (Gaya Seragam Putih Bersih) --}}
            <a href="{{ route('ppdb') }}"
               class="p-5 rounded-2xl bg-white dark:bg-forest-900 border border-slate-200/80 dark:border-forest-800 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-sumedang-amber flex items-center justify-center text-2xl mb-3 shadow-sm group-hover:scale-110 transition-transform">
                        🎒
                    </div>
                    <span class="text-[10px] font-bold text-amber-600 uppercase tracking-wider block">Penerimaan Siswa</span>
                    <h3 class="font-extrabold text-base text-forest-950 dark:text-white mt-0.5 group-hover:text-amber-600 transition">
                        Layanan PPDB Online
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5 leading-relaxed">
                        Pendaftaran siswa baru 2026/2027 jalur Zonasi, Afirmasi, dan Mutu secara mandiri.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-forest-800/80 flex items-center justify-between text-xs font-bold text-amber-600">
                    <span>Buka Pendaftaran</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            {{-- Kartu 2: Portal Asesmen & Ujian --}}
            <a href="{{ route('warta') }}"
               class="p-5 rounded-2xl bg-white dark:bg-forest-900 border border-slate-200/80 dark:border-forest-800 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 flex items-center justify-center text-2xl mb-3 shadow-sm group-hover:scale-110 transition-transform">
                        📝
                    </div>
                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider block">Akademik & Ujian</span>
                    <h3 class="font-extrabold text-base text-forest-950 dark:text-white mt-0.5 group-hover:text-blue-600 transition">
                        Portal Asesmen & Ujian
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5 leading-relaxed">
                        Informasi Sumatif Akhir Semester (SAS), bank perangkat ajar & modul pembelajaran.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-forest-800/80 flex items-center justify-between text-xs font-bold text-blue-600">
                    <span>Info Ujian & Belajar</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            {{-- Kartu 3: Papan Publikasi & Berita --}}
            <a href="{{ route('warta') }}"
               class="p-5 rounded-2xl bg-white dark:bg-forest-900 border border-slate-200/80 dark:border-forest-800 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 dark:bg-forest-800 dark:text-emerald-300 flex items-center justify-center text-2xl mb-3 shadow-sm group-hover:scale-110 transition-transform">
                        📰
                    </div>
                    <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block">Kabar Sekolah</span>
                    <h3 class="font-extrabold text-base text-forest-950 dark:text-white mt-0.5 group-hover:text-emerald-600 transition">
                        Warta & Prestasi Siswa
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5 leading-relaxed">
                        Publikasi kegiatan P5, aksi nyata, prestasi ekstrakurikuler & pengumuman edaran resmi.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-forest-800/80 flex items-center justify-between text-xs font-bold text-emerald-600">
                    <span>Lihat Warta Terkini</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            {{-- Kartu 4: Layanan Digital / Absensi GTK --}}
            <a href="{{ route('presensi.index') }}"
               class="p-5 rounded-2xl bg-white dark:bg-forest-900 border border-slate-200/80 dark:border-forest-800 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-700 dark:bg-teal-950/60 dark:text-teal-300 flex items-center justify-center text-2xl mb-3 shadow-sm group-hover:scale-110 transition-transform">
                        📍
                    </div>
                    <span class="text-[10px] font-bold text-teal-600 uppercase tracking-wider block">Layanan Pendidik</span>
                    <h3 class="font-extrabold text-base text-forest-950 dark:text-white mt-0.5 group-hover:text-teal-600 transition">
                        Presensi Digital GPS & GTK
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5 leading-relaxed">
                        Presensi mandiri guru datang dan pulang berbasis geofencing GPS dan swafoto digital.
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-forest-800/80 flex items-center justify-between text-xs font-bold text-teal-600">
                    <span>Buka Presensi GTK</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

        </div>
    </section>

    {{-- ========================================================================= --}}
    {{-- 4. SAMBUTAN KEPALA SEKOLAH (FRAME VERTIKAL 3:4 FOTO RESMI & KUTIPAN EDITORIAL) --}}
    {{-- ========================================================================= --}}
    <section class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-forest-900 rounded-3xl border border-slate-200/80 dark:border-forest-800 p-6 sm:p-8 md:p-10 shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                
                {{-- Foto Kepala Sekolah (Frame Vertikal Rasio 3:4, Rounded-2xl) --}}
                <div class="lg:col-span-4 flex justify-center">
                    <div class="relative w-full max-w-[240px] sm:max-w-[260px]">
                        <div class="relative aspect-[3/4] rounded-2xl overflow-hidden shadow-xl border border-slate-200 dark:border-forest-700 bg-forest-950 group">
                            @php
                                $kepalaFoto = $kepalaSekolah?->foto_url 
                                    ?: ($profile?->foto_kepala_sekolah_url 
                                    ?: (\App\Models\HeadmasterProfile::first()?->photo_url 
                                    ?: 'https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=600&q=80'));
                                $kepalaNama = $kepalaSekolah ? ($kepalaSekolah->gelar ? $kepalaSekolah->nama . ', ' . $kepalaSekolah->gelar : $kepalaSekolah->nama) : ($profile?->kepala_sekolah ?? 'Aep Yayat, S.Pd');
                                $kepalaNip = $kepalaSekolah?->nip ?: ($profile?->nip_kepala_sekolah ?? '197206102014111001');
                            @endphp
                            <img src="{{ $kepalaFoto }}"
                                 alt="Kepala Sekolah SDN Cinanggerang I - {{ $kepalaNama }}"
                                 class="w-full h-full object-cover object-top transition-transform duration-300 group-hover:scale-105"
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=600&q=80';">

                            {{-- Label Nama Mengambang Formal --}}
                            <div class="absolute bottom-0 inset-x-0 p-3.5 bg-gradient-to-t from-forest-950 via-forest-950/85 to-transparent text-white">
                                <span class="text-[9px] uppercase tracking-wider text-sumedang-amber font-bold block">Kepala Sekolah</span>
                                <h4 class="text-sm font-bold leading-tight">{{ $kepalaNama }}</h4>
                                <p class="text-[10px] text-gray-300 font-mono">NIP. {{ $kepalaNip }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Teks Sambutan Bergaya Editorial Majalah (Watermark Tanda Kutip Besar) --}}
                <div class="lg:col-span-8 relative">
                    
                    {{-- Aksen Tanda Kutip Besar di Belakang Teks --}}
                    <span class="absolute -top-8 -left-5 text-8xl text-emerald-100/70 dark:text-forest-800/60 font-serif font-black select-none pointer-events-none leading-none z-0">
                        “
                    </span>

                    <div class="relative z-10 space-y-4">
                        <div>
                            <span class="inline-block text-xs font-bold px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 uppercase tracking-wider">
                                SAMBUTAN KEPALA SEKOLAH
                            </span>
                            <h2 class="text-2xl md:text-3xl font-black text-forest-950 dark:text-white leading-snug mt-2">
                                Membangun Generasi Cerdas, Berkarakter & Adaptif Teknologi
                            </h2>
                        </div>

                        <div class="text-sm md:text-base text-slate-600 dark:text-gray-300 leading-relaxed space-y-3">
                            @if(!empty($profile?->sambutan_kepala_sekolah))
                                {!! $profile->sambutan_kepala_sekolah !!}
                            @else
                                <p>
                                    Selamat datang di Portal Resmi SD Negeri Cinanggerang I, Kecamatan Pamulihan, Kabupaten Sumedang. Di era transformasi digital dan Kurikulum Merdeka saat ini, kami berkomitmen menghadirkan ekosistem pembelajaran yang ramah anak, berakar pada keluhuran budi pekerti, serta mengedepankan keterbukaan informasi publik bagi seluruh warga sekolah dan masyarakat.
                                </p>
                                <p>
                                    Melalui portal resmi ini, seluruh layanan sekolah—mulai dari pendaftaran PPDB daring, pemantauan presensi pendidik, warta prestasi peserta didik, hingga portofolio akreditasi E-PKKS—dapat diakses dengan transparan, akuntabel, dan tepercaya.
                                </p>
                            @endif
                        </div>

                        <div class="pt-3 flex flex-wrap items-center gap-3">
                            <a href="{{ route('pkks') }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-forest-900 hover:bg-forest-800 text-sumedang-amber text-xs font-bold transition shadow-sm">
                                <span>Baca Profil & CV Kepala Sekolah</span>
                                <span>&rarr;</span>
                            </a>
                            <a href="{{ route('profil') }}"
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-forest-800 text-gray-700 dark:text-gray-300 text-xs font-bold hover:bg-slate-200 transition">
                                <span>Visi Misi & Sejarah</span>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    {{-- ========================================================================= --}}
    {{-- 5. WIDGET BERITA & PENGUMUMAN (2 KOLOM WARTA BERDAMPINGAN & AGENDA KALENDER) --}}
    {{-- ========================================================================= --}}
    <section class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- KOLOM KIRI (7 KOLOM): WARTA & PRESTASI SISWA (GRID 2 KOLOM BERDAMPINGAN) --}}
            <div class="lg:col-span-7 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-forest-800 pb-3">
                    <div>
                        <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block">Kabar Sekolah</span>
                        <h3 class="text-xl sm:text-2xl font-black text-forest-950 dark:text-white">Warta & Prestasi Siswa</h3>
                    </div>
                    <a href="{{ route('warta') }}" class="text-xs font-bold text-forest-800 dark:text-emerald-300 hover:underline flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                {{-- Grid 2 Kolom Berdampingan --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @forelse($posts->take(2) as $post)
                        @php
                            $thumb = $post->thumbnail_url ?: 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=800&q=80';
                        @endphp
                        <article class="bg-white dark:bg-forest-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-forest-800 shadow-sm hover:shadow-lg transition-all flex flex-col group">
                            
                            {{-- Foto Thumbnail Rasio 16:9 Jelas --}}
                            <a href="{{ $post->detail_url }}" class="block aspect-video w-full overflow-hidden bg-forest-950 relative">
                                <img src="{{ $thumb }}"
                                     alt="{{ $post->judul }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=800&q=80';">
                                <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 bg-forest-950/80 text-white text-[9px] font-bold rounded-md uppercase backdrop-blur-sm">
                                    {{ $post->kategori }}
                                </span>
                            </a>

                            {{-- Konten Judul & Tanggal --}}
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] text-gray-400 font-medium block mb-1">
                                        📅 {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
                                    </span>
                                    <a href="{{ $post->detail_url }}" class="block group-hover:text-forest-700 dark:group-hover:text-emerald-300 transition">
                                        <h4 class="font-extrabold text-xs sm:text-sm text-forest-950 dark:text-white line-clamp-2 leading-snug">
                                            {{ $post->judul }}
                                        </h4>
                                    </a>
                                </div>
                                <div class="mt-3 pt-2.5 border-t border-slate-100 dark:border-forest-800/80">
                                    <a href="{{ $post->detail_url }}" class="text-xs font-bold text-forest-800 dark:text-emerald-300 hover:text-sumedang-amber transition flex items-center justify-between">
                                        <span>Baca Selengkapnya</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                            </div>

                        </article>
                    @empty
                        <div class="sm:col-span-2 p-8 bg-white dark:bg-forest-900 rounded-2xl text-center text-xs text-gray-400 border border-slate-100">
                            Belum ada warta terbaru.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- KOLOM KANAN (5 KOLOM): PAPAN PENGUMUMAN DENGAN BADGE TANGGAL KALENDER TERSTRUKTUR --}}
            <div class="lg:col-span-5 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-forest-800 pb-3">
                    <div>
                        <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block">Agenda Penting</span>
                        <h3 class="text-xl sm:text-2xl font-black text-forest-950 dark:text-white">Papan Pengumuman</h3>
                    </div>
                    <a href="{{ route('pengumuman') }}" class="text-xs font-bold text-amber-700 dark:text-amber-400 hover:underline flex items-center gap-1">
                        <span>Semua Agenda</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($announcements->take(3) as $announcement)
                        @php
                            $tgl = $announcement->tanggal_mulai ?: now();
                        @endphp
                        <div class="p-3.5 rounded-2xl bg-white dark:bg-forest-900 border border-slate-200/80 dark:border-forest-800 shadow-sm hover:shadow-md transition flex items-center gap-3.5 group">
                            
                            {{-- Badge Tanggal Kalender Terstruktur (Bulan & Tanggal) --}}
                            <div class="w-14 h-14 rounded-2xl bg-amber-500/10 dark:bg-amber-950/60 border border-amber-500/25 text-amber-900 dark:text-amber-200 flex flex-col items-center justify-center shrink-0 shadow-inner">
                                <span class="text-[10px] font-black uppercase text-amber-700 dark:text-amber-400 leading-tight">
                                    {{ $tgl->translatedFormat('M') }}
                                </span>
                                <span class="text-xl font-black leading-none text-amber-950 dark:text-white mt-0.5">
                                    {{ $tgl->format('d') }}
                                </span>
                            </div>

                            {{-- Keterangan Judul Pengumuman --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.2 rounded text-[9px] font-bold bg-amber-100 text-amber-900 dark:bg-amber-950 dark:text-amber-300 uppercase">
                                        Kedinasan
                                    </span>
                                    <span class="text-[10px] text-gray-400">
                                        {{ $tgl->translatedFormat('Y') }}
                                    </span>
                                </div>
                                <a href="{{ $announcement->detail_url }}" class="block mt-0.5">
                                    <h4 class="font-extrabold text-xs sm:text-sm text-forest-950 dark:text-white group-hover:text-amber-700 dark:group-hover:text-amber-300 transition line-clamp-1 leading-snug">
                                        {{ $announcement->judul }}
                                    </h4>
                                </a>
                                <div class="mt-1 flex items-center justify-between text-[11px]">
                                    <a href="{{ $announcement->detail_url }}" class="font-bold text-forest-800 dark:text-emerald-300 hover:underline">
                                        Buka Pengumuman &rarr;
                                    </a>
                                    @if(!empty($announcement->file_lampiran_url))
                                        <a href="{{ $announcement->file_lampiran_url }}" target="_blank" class="font-bold text-amber-700 bg-amber-100 px-2 py-0.2 rounded text-[10px]">
                                            Unduh PDF
                                        </a>
                                    @endif
                                </div>
                            </div>

                        </div>
                    @empty
                        <div class="p-6 bg-white dark:bg-forest-900 rounded-2xl text-center text-xs text-gray-400 border border-slate-100">
                            Belum ada pengumuman agenda aktif.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

    {{-- ========================================================================= --}}
    {{-- 6. STATISTIK / PROFIL ANGKA SEKOLAH (COUNTER DATA SINGKAT)                 --}}
    {{-- ========================================================================= --}}
    <section class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="bg-forest-950 text-white rounded-3xl p-6 sm:p-8 border border-forest-800 shadow-xl relative overflow-hidden">
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 divide-y sm:divide-y-0 sm:divide-x divide-forest-800/80">
                
                {{-- Data 1: GTK --}}
                <div class="flex items-center gap-3.5 pt-3 sm:pt-0">
                    <div class="w-12 h-12 rounded-xl bg-forest-800 text-emerald-300 flex items-center justify-center text-xl shrink-0 shadow">
                        👥
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-sumedang-amber">{{ $stats['total_guru'] ?? 11 }}</div>
                        <p class="text-xs font-bold text-white">Guru & Tendik</p>
                        <span class="text-[10px] text-emerald-400">PNS, PPPK & Honorer</span>
                    </div>
                </div>

                {{-- Data 2: Peserta Didik --}}
                <div class="flex items-center gap-3.5 sm:pl-6 pt-3 sm:pt-0">
                    <div class="w-12 h-12 rounded-xl bg-forest-800 text-emerald-300 flex items-center justify-center text-xl shrink-0 shadow">
                        🎓
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-sumedang-amber">{{ $stats['total_siswa'] ?? 219 }}</div>
                        <p class="text-xs font-bold text-white">Peserta Didik</p>
                        <span class="text-[10px] text-emerald-400">Terdaftar Resmi Dapodik</span>
                    </div>
                </div>

                {{-- Data 3: Rombel --}}
                <div class="flex items-center gap-3.5 sm:pl-6 pt-3 sm:pt-0">
                    <div class="w-12 h-12 rounded-xl bg-forest-800 text-emerald-300 flex items-center justify-center text-xl shrink-0 shadow">
                        🏫
                    </div>
                    <div>
                        <div class="text-2xl sm:text-3xl font-black text-sumedang-amber">{{ $stats['total_rombel'] ?? 8 }}</div>
                        <p class="text-xs font-bold text-white">Rombel Kelas</p>
                        <span class="text-[10px] text-emerald-400">Kelas I s.d. Kelas VI</span>
                    </div>
                </div>

                {{-- Data 4: Akreditasi & NPSN --}}
                <div class="flex items-center gap-3.5 sm:pl-6 pt-3 sm:pt-0">
                    <div class="w-12 h-12 rounded-xl bg-forest-800 text-sumedang-amber flex items-center justify-center text-xl shrink-0 shadow">
                        🏅
                    </div>
                    <div>
                        <div class="text-xl sm:text-2xl font-black text-white">Akreditasi B</div>
                        <p class="text-xs font-bold text-emerald-300">NPSN: {{ $profile?->npsn ?? '20208801' }}</p>
                        <span class="text-[10px] text-gray-300">SD Negeri Pamulihan</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection