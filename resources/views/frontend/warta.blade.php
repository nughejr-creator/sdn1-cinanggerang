@extends('layouts.frontend')

@section('title', 'Warta Sekolah, Program Kegiatan & Prestasi - SD Negeri Cinanggerang I')
@section('meta_description', 'Kabar sekolah terkini, publikasi kegiatan ekstrakurikuler, kokurikuler, pembiasaan karakter, dan prestasi peserta didik SDN Cinanggerang I Sumedang.')
@section('canonical_url', route('warta'))

@section('content')
    {{-- Header Banner Halaman --}}
    <div class="bg-forest-950 text-white py-12 lg:py-16 border-b border-forest-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:20px_20px] opacity-30 pointer-events-none"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="inline-block px-3 py-1 rounded-full bg-forest-800 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-3">
                Kabar & Publikasi Resmi
            </span>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight">
                Warta Terkini & Program Sekolah
            </h1>
            <p class="text-xs sm:text-sm text-gray-300 mt-2 max-w-2xl leading-relaxed">
                Dokumentasi dinamis kegiatan ekstrakurikuler, kokurikuler, pembiasaan religius & literasi, serta prestasi peserta didik SDN Cinanggerang I.
            </p>
        </div>
    </div>

    {{-- NAVIGASI TAB & FILTER PROGRAM KEGIATAN SEKOLAH (INTERAKTIF & RESPONSIVE HP) --}}
    <section class="bg-white dark:bg-forest-900 border-b border-gray-200 dark:border-forest-800 sticky top-16 z-30 shadow-sm backdrop-blur-md bg-white/95 dark:bg-forest-900/95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            
            {{-- 1. TAB UTAMA KATEGORI --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar text-xs sm:text-sm font-bold">
                {{-- Tab: Semua --}}
                <a href="{{ route('warta') }}"
                   class="shrink-0 px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ empty($selectedCategory) ? 'bg-forest-900 text-white dark:bg-emerald-600 shadow-sm' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-forest-800 dark:text-gray-300' }}">
                    <span>🌐</span>
                    <span>Semua Warta</span>
                </a>

                {{-- Tab: Ekstrakurikuler --}}
                <a href="{{ route('warta', ['kategori_program' => 'ekstrakurikuler']) }}"
                   class="shrink-0 px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $selectedCategory === 'ekstrakurikuler' ? 'bg-amber-600 text-white shadow-sm ring-2 ring-amber-400' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-forest-800 dark:text-gray-300' }}">
                    <span>🏆</span>
                    <span>Ekstrakurikuler</span>
                </a>

                {{-- Tab: Kokurikuler --}}
                <a href="{{ route('warta', ['kategori_program' => 'kokurikuler']) }}"
                   class="shrink-0 px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $selectedCategory === 'kokurikuler' ? 'bg-emerald-700 text-white shadow-sm ring-2 ring-emerald-400' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-forest-800 dark:text-gray-300' }}">
                    <span>🌱</span>
                    <span>Kokurikuler</span>
                </a>

                {{-- Tab: Pembiasaan --}}
                <a href="{{ route('warta', ['kategori_program' => 'pembiasaan']) }}"
                   class="shrink-0 px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $selectedCategory === 'pembiasaan' ? 'bg-sky-700 text-white shadow-sm ring-2 ring-sky-400' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-forest-800 dark:text-gray-300' }}">
                    <span>✨</span>
                    <span>Pembiasaan</span>
                </a>
            </div>

            {{-- 2. FILTER PILLS NAMA KEGIATAN (TAMPIL DINAMIS SESUAI TAB YANG AKTIF) --}}
            @php
                $activeCategoryPrograms = isset($programs) ? $programs->when($selectedCategory, fn($q) => $q->where('category', $selectedCategory)) : collect();
            @endphp

            @if(!empty($selectedCategory) && $activeCategoryPrograms->isNotEmpty())
                <div class="mt-2.5 pt-2.5 border-t border-gray-100 dark:border-forest-800/80 flex items-center gap-1.5 overflow-x-auto pb-1 no-scrollbar text-xs">
                    <span class="text-gray-400 text-[11px] font-semibold shrink-0 pr-1">Pilih Kegiatan:</span>
                    
                    {{-- Pill: Semua Kegiatan di Kategori Ini --}}
                    <a href="{{ route('warta', ['kategori_program' => $selectedCategory]) }}"
                       class="shrink-0 px-3 py-1 rounded-full text-xs font-bold transition border {{ empty($selectedProgram) ? 'bg-gray-900 text-white border-gray-900 dark:bg-white dark:text-gray-900' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200 dark:bg-forest-800 dark:text-gray-300 dark:border-forest-700' }}">
                        Semua {{ ucfirst($selectedCategory) }}
                    </a>

                    {{-- Pill per Program (Pramuka, Olahraga, Seni, G7KAIH, Sholat Dhuha, dll) --}}
                    @foreach($activeCategoryPrograms as $prog)
                        @php
                            $isProgActive = ($selectedProgram == $prog->id || $selectedProgram == $prog->name);
                        @endphp
                        <a href="{{ route('warta', ['kategori_program' => $selectedCategory, 'program' => $prog->id]) }}"
                           class="shrink-0 px-3 py-1 rounded-full text-xs font-semibold transition border {{ $isProgActive ? ($selectedCategory === 'ekstrakurikuler' ? 'bg-amber-100 text-amber-900 border-amber-400 font-black' : ($selectedCategory === 'kokurikuler' ? 'bg-emerald-100 text-emerald-900 border-emerald-400 font-black' : 'bg-sky-100 text-sky-900 border-sky-400 font-black')) : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-200 dark:bg-forest-800 dark:text-gray-300 dark:border-forest-700' }}">
                            <span>{{ $prog->name }}</span>
                            @if($prog->posts_count > 0)
                                <span class="ml-1 opacity-75 text-[10px]">({{ $prog->posts_count }})</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </section>

    {{-- Kartu Warta Berita & Pengumuman --}}
    @include('components.frontend.news-card')
@endsection
