@extends('layouts.frontend')

@section('title', 'Bank Perangkat Ajar & Modul Kurikulum Merdeka - SD Negeri Cinanggerang I')
@section('meta_description', 'Repositori resmi bank perangkat ajar Kurikulum Merdeka SDN Cinanggerang I: Tujuan Pembelajaran (TP), Alur Tujuan Pembelajaran (ATP), Capaian Pembelajaran (CP), Modul Ajar, Proyek P5, dan Asesmen.')
@section('canonical_url', route('perangkat-ajar'))

@section('content')
    {{-- Header Banner Halaman --}}
    <div class="bg-forest-950 text-white py-12 lg:py-16 border-b border-forest-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:20px_20px] opacity-30 pointer-events-none"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-forest-800 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-3">
                        <span>📚</span> E-Learning & Kurikulum Merdeka
                    </span>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight">
                        Bank Perangkat Ajar & Modul
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-300 mt-2 max-w-2xl leading-relaxed">
                        Repositori dokumen perencanaan pembelajaran resmi SDN Cinanggerang I: Tujuan Pembelajaran (TP), Alur Tujuan Pembelajaran (ATP), Capaian Pembelajaran (CP), Modul Ajar, Prota/Promes, modul P5, hingga instrumen asesmen.
                    </p>
                </div>

                {{-- Akses Kelola Guru --}}
                <div class="shrink-0 bg-forest-900/60 border border-forest-800 rounded-2xl p-4 max-w-xs">
                    <p class="text-xs text-emerald-300 font-semibold mb-1">Pintu Masuk Guru:</p>
                    <p class="text-[11px] text-gray-300 mb-3">Bapak/Ibu Guru dapat mengunggah dan memperbarui perangkat ajar melalui panel GTK.</p>
                    <a href="{{ url('/admin/teaching-documents') }}" 
                       class="inline-flex items-center gap-2 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold shadow-md transition transform active:scale-95">
                        <span>✍️</span> Kelola Perangkat (Panel Guru)
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- BAR TOMBOL KATEGORI CEPAT (INTERAKTIF & RESPONSIF) --}}
    <section class="bg-white dark:bg-forest-900 border-b border-gray-200 dark:border-forest-800 sticky top-16 z-30 shadow-sm backdrop-blur-md bg-white/95 dark:bg-forest-900/95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-between gap-4">
                {{-- Horizontal Pills Kategori Cepat --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar text-xs font-bold w-full">
                    
                    {{-- 1. Semua --}}
                    @php
                        $isActiveSemua = empty($selectedJenis) || $selectedJenis === 'semua';
                    @endphp
                    <a href="{{ route('perangkat-ajar', array_filter(['kelas' => $selectedKelas, 'mapel' => $selectedMapel, 'q' => $search])) }}"
                       class="shrink-0 px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 {{ $isActiveSemua ? 'bg-forest-900 text-white dark:bg-emerald-600 shadow-sm ring-2 ring-emerald-400' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-forest-800 dark:text-gray-300' }}">
                        <span>🌐</span>
                        <span>Semua Dokumen</span>
                        <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $isActiveSemua ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-forest-700 text-gray-600 dark:text-gray-300' }}">
                            {{ $categoryCounts['total'] ?? 0 }}
                        </span>
                    </a>

                    {{-- 2. TP (Tujuan Pembelajaran) --}}
                    @php
                        $isActiveTp = in_array(strtolower($selectedJenis), ['tp', 'tujuan-pembelajaran', 'tujuan pembelajaran (tp)']);
                    @endphp
                    <a href="{{ route('perangkat-ajar', array_filter(['jenis' => 'tp', 'kelas' => $selectedKelas, 'mapel' => $selectedMapel, 'q' => $search])) }}"
                       class="shrink-0 px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 {{ $isActiveTp ? 'bg-sky-600 text-white shadow-sm ring-2 ring-sky-300' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-forest-800 dark:text-gray-300' }}">
                        <span>🎯</span>
                        <span>TP (Tujuan Pembelajaran)</span>
                        <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $isActiveTp ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-forest-700 text-gray-600 dark:text-gray-300' }}">
                            {{ $categoryCounts['tp'] ?? 0 }}
                        </span>
                    </a>

                    {{-- 3. ATP (Alur Tujuan Pembelajaran) --}}
                    @php
                        $isActiveAtp = in_array(strtolower($selectedJenis), ['atp', 'alur-tujuan-pembelajaran', 'alur tujuan pembelajaran (atp)']);
                    @endphp
                    <a href="{{ route('perangkat-ajar', array_filter(['jenis' => 'atp', 'kelas' => $selectedKelas, 'mapel' => $selectedMapel, 'q' => $search])) }}"
                       class="shrink-0 px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 {{ $isActiveAtp ? 'bg-indigo-600 text-white shadow-sm ring-2 ring-indigo-300' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-forest-800 dark:text-gray-300' }}">
                        <span>🧭</span>
                        <span>ATP</span>
                        <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $isActiveAtp ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-forest-700 text-gray-600 dark:text-gray-300' }}">
                            {{ $categoryCounts['atp'] ?? 0 }}
                        </span>
                    </a>

                    {{-- 4. CP (Capaian Pembelajaran) --}}
                    @php
                        $isActiveCp = in_array(strtolower($selectedJenis), ['cp', 'capaian-pembelajaran', 'capaian pembelajaran (cp)']);
                    @endphp
                    <a href="{{ route('perangkat-ajar', array_filter(['jenis' => 'cp', 'kelas' => $selectedKelas, 'mapel' => $selectedMapel, 'q' => $search])) }}"
                       class="shrink-0 px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 {{ $isActiveCp ? 'bg-purple-600 text-white shadow-sm ring-2 ring-purple-300' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-forest-800 dark:text-gray-300' }}">
                        <span>📐</span>
                        <span>CP</span>
                        <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $isActiveCp ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-forest-700 text-gray-600 dark:text-gray-300' }}">
                            {{ $categoryCounts['cp'] ?? 0 }}
                        </span>
                    </a>

                    {{-- 5. Modul Ajar / RPP Merdeka --}}
                    @php
                        $isActiveModul = in_array(strtolower($selectedJenis), ['modul-ajar', 'rpp', 'modul ajar / rpp merdeka']);
                    @endphp
                    <a href="{{ route('perangkat-ajar', array_filter(['jenis' => 'modul-ajar', 'kelas' => $selectedKelas, 'mapel' => $selectedMapel, 'q' => $search])) }}"
                       class="shrink-0 px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 {{ $isActiveModul ? 'bg-emerald-700 text-white shadow-sm ring-2 ring-emerald-300' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-forest-800 dark:text-gray-300' }}">
                        <span>📖</span>
                        <span>Modul Ajar (RPP)</span>
                        <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $isActiveModul ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-forest-700 text-gray-600 dark:text-gray-300' }}">
                            {{ $categoryCounts['modul_ajar'] ?? 0 }}
                        </span>
                    </a>

                    {{-- 6. Modul Proyek P5 --}}
                    @php
                        $isActiveP5 = in_array(strtolower($selectedJenis), ['p5', 'proyek-p5', 'modul kokurikuler / proyek p5']);
                    @endphp
                    <a href="{{ route('perangkat-ajar', array_filter(['jenis' => 'p5', 'kelas' => $selectedKelas, 'mapel' => $selectedMapel, 'q' => $search])) }}"
                       class="shrink-0 px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 {{ $isActiveP5 ? 'bg-amber-600 text-white shadow-sm ring-2 ring-amber-300' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-forest-800 dark:text-gray-300' }}">
                        <span>🌱</span>
                        <span>Proyek P5</span>
                        <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $isActiveP5 ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-forest-700 text-gray-600 dark:text-gray-300' }}">
                            {{ $categoryCounts['p5'] ?? 0 }}
                        </span>
                    </a>

                    {{-- 7. Prota & Promes --}}
                    @php
                        $isActiveProta = in_array(strtolower($selectedJenis), ['prota', 'promes', 'program tahunan (prota) & promes']);
                    @endphp
                    <a href="{{ route('perangkat-ajar', array_filter(['jenis' => 'prota', 'kelas' => $selectedKelas, 'mapel' => $selectedMapel, 'q' => $search])) }}"
                       class="shrink-0 px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 {{ $isActiveProta ? 'bg-teal-700 text-white shadow-sm ring-2 ring-teal-300' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-forest-800 dark:text-gray-300' }}">
                        <span>📅</span>
                        <span>Prota & Promes</span>
                        <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $isActiveProta ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-forest-700 text-gray-600 dark:text-gray-300' }}">
                            {{ $categoryCounts['prota_promes'] ?? 0 }}
                        </span>
                    </a>

                    {{-- 8. LKPD --}}
                    @php
                        $isActiveLkpd = in_array(strtolower($selectedJenis), ['lkpd', 'lembar kerja peserta didik (lkpd)']);
                    @endphp
                    <a href="{{ route('perangkat-ajar', array_filter(['jenis' => 'lkpd', 'kelas' => $selectedKelas, 'mapel' => $selectedMapel, 'q' => $search])) }}"
                       class="shrink-0 px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 {{ $isActiveLkpd ? 'bg-rose-700 text-white shadow-sm ring-2 ring-rose-300' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-forest-800 dark:text-gray-300' }}">
                        <span>📝</span>
                        <span>LKPD</span>
                        <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $isActiveLkpd ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-forest-700 text-gray-600 dark:text-gray-300' }}">
                            {{ $categoryCounts['lkpd'] ?? 0 }}
                        </span>
                    </a>

                    {{-- 9. Asesmen & Bank Soal --}}
                    @php
                        $isActiveAsesmen = in_array(strtolower($selectedJenis), ['asesmen', 'bank-soal', 'soal']);
                    @endphp
                    <a href="{{ route('perangkat-ajar', array_filter(['jenis' => 'asesmen', 'kelas' => $selectedKelas, 'mapel' => $selectedMapel, 'q' => $search])) }}"
                       class="shrink-0 px-3.5 py-2 rounded-xl transition flex items-center gap-1.5 {{ $isActiveAsesmen ? 'bg-orange-700 text-white shadow-sm ring-2 ring-orange-300' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-forest-800 dark:text-gray-300' }}">
                        <span>📊</span>
                        <span>Asesmen & Bank Soal</span>
                        <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $isActiveAsesmen ? 'bg-white/20 text-white' : 'bg-gray-200 dark:bg-forest-700 text-gray-600 dark:text-gray-300' }}">
                            {{ $categoryCounts['asesmen'] ?? 0 }}
                        </span>
                    </a>

                </div>
            </div>
        </div>
    </section>

    {{-- KONTEN UTAMA: FILTER DETAIL & GRID DOKUMEN --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        {{-- FORM FILTER KELAS, MAPEL & PENCARIAN --}}
        <div class="bg-gray-50 dark:bg-forest-900/60 border border-gray-200 dark:border-forest-800 rounded-2xl p-4 sm:p-5 mb-8 shadow-sm">
            <form action="{{ route('perangkat-ajar') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 items-end">
                {{-- Hidden input kategori jenis saat submit filter kelas/mapel --}}
                @if(!empty($selectedJenis))
                    <input type="hidden" name="jenis" value="{{ $selectedJenis }}">
                @endif

                {{-- Filter Kelas --}}
                <div>
                    <label for="kelas" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                        Tingkat Kelas / Fase:
                    </label>
                    <select name="kelas" id="kelas" onchange="this.form.submit()" 
                            class="w-full text-xs font-medium rounded-xl border-gray-300 dark:border-forest-700 bg-white dark:bg-forest-800 text-gray-800 dark:text-gray-200 focus:ring-emerald-500 focus:border-emerald-500 py-2.5 px-3">
                        <option value="">Semua Kelas (I - VI)</option>
                        <option value="1" {{ $selectedKelas == '1' ? 'selected' : '' }}>Kelas I (Fase A)</option>
                        <option value="2" {{ $selectedKelas == '2' ? 'selected' : '' }}>Kelas II (Fase A)</option>
                        <option value="3" {{ $selectedKelas == '3' ? 'selected' : '' }}>Kelas III (Fase B)</option>
                        <option value="4" {{ $selectedKelas == '4' ? 'selected' : '' }}>Kelas IV (Fase B)</option>
                        <option value="5" {{ $selectedKelas == '5' ? 'selected' : '' }}>Kelas V (Fase C)</option>
                        <option value="6" {{ $selectedKelas == '6' ? 'selected' : '' }}>Kelas VI (Fase C)</option>
                    </select>
                </div>

                {{-- Filter Mapel --}}
                <div>
                    <label for="mapel" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                        Mata Pelajaran:
                    </label>
                    <select name="mapel" id="mapel" onchange="this.form.submit()" 
                            class="w-full text-xs font-medium rounded-xl border-gray-300 dark:border-forest-700 bg-white dark:bg-forest-800 text-gray-800 dark:text-gray-200 focus:ring-emerald-500 focus:border-emerald-500 py-2.5 px-3">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" {{ $selectedMapel == $c->id ? 'selected' : '' }}>
                                {{ $c->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Pencarian Judul / Guru --}}
                <div>
                    <label for="q" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                        Cari Kata Kunci:
                    </label>
                    <div class="relative">
                        <input type="text" name="q" id="q" value="{{ $search }}" 
                               placeholder="Judul, materi, atau nama guru..." 
                               class="w-full text-xs rounded-xl border-gray-300 dark:border-forest-700 bg-white dark:bg-forest-800 text-gray-800 dark:text-gray-200 focus:ring-emerald-500 focus:border-emerald-500 py-2.5 pl-3 pr-8">
                        @if(!empty($search))
                            <a href="{{ route('perangkat-ajar', array_filter(['jenis' => $selectedJenis, 'kelas' => $selectedKelas, 'mapel' => $selectedMapel])) }}" 
                               class="absolute right-2.5 top-2.5 text-gray-400 hover:text-gray-600 text-xs">✕</a>
                        @endif
                    </div>
                </div>

                {{-- Tombol Cari & Reset --}}
                <div class="flex items-center gap-2">
                    <button type="submit" 
                            class="flex-1 py-2.5 px-4 bg-forest-900 hover:bg-forest-800 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white rounded-xl text-xs font-bold shadow transition flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Terapkan</span>
                    </button>
                    @if(!empty($selectedJenis) || !empty($selectedKelas) || !empty($selectedMapel) || !empty($search))
                        <a href="{{ route('perangkat-ajar') }}" 
                           title="Reset Seluruh Filter" 
                           class="py-2.5 px-3 bg-gray-200 hover:bg-gray-300 dark:bg-forest-700 dark:hover:bg-forest-600 text-gray-700 dark:text-gray-200 rounded-xl text-xs font-bold transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- STATUS & JUMLAH DATA --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <p class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Menampilkan <span class="font-bold text-forest-900 dark:text-emerald-400">{{ $documents->total() }}</span> dokumen perangkat ajar
                    @if(!empty($selectedJenis) && $selectedJenis !== 'semua')
                        untuk kategori <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 font-bold uppercase text-[11px]">{{ $selectedJenis }}</span>
                    @endif
                    @if(!empty($selectedKelas))
                        di <span class="font-bold text-forest-800 dark:text-emerald-300">Kelas {{ $selectedKelas }}</span>
                    @endif
                </p>
            </div>

            @if(!empty($selectedJenis) || !empty($selectedKelas) || !empty($selectedMapel) || !empty($search))
                <div class="text-[11px] text-gray-500">
                    <a href="{{ route('perangkat-ajar') }}" class="text-emerald-600 hover:underline">Hapus semua filter</a>
                </div>
            @endif
        </div>

        {{-- GRID DAFTAR PERANGKAT AJAR --}}
        @if($documents->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($documents as $doc)
                    @php
                        // Tentukan style badge jenis dokumen
                        $badgeBg = match(true) {
                            str_contains($doc->jenis_dokumen, 'TP') || str_contains($doc->jenis_dokumen, 'Tujuan Pembelajaran') => 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-950/60 dark:text-sky-300 dark:border-sky-800',
                            str_contains($doc->jenis_dokumen, 'ATP') => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-950/60 dark:text-indigo-300 dark:border-indigo-800',
                            str_contains($doc->jenis_dokumen, 'CP') => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-950/60 dark:text-purple-300 dark:border-purple-800',
                            str_contains($doc->jenis_dokumen, 'Modul Ajar') || str_contains($doc->jenis_dokumen, 'RPP') => 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800',
                            str_contains($doc->jenis_dokumen, 'P5') => 'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800',
                            str_contains($doc->jenis_dokumen, 'LKPD') => 'bg-rose-50 text-rose-800 border-rose-200 dark:bg-rose-950/60 dark:text-rose-300 dark:border-rose-800',
                            default => 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-forest-800 dark:text-gray-200 dark:border-forest-700',
                        };

                        // Deteksi ekstensi file dokumen
                        $ext = strtolower(pathinfo($doc->file_dokumen ?? '', PATHINFO_EXTENSION));
                        $fileIcon = match($ext) {
                            'pdf' => '📕',
                            'doc', 'docx' => '📘',
                            'xls', 'xlsx' => '📊',
                            'ppt', 'pptx' => '📙',
                            default => '📄',
                        };
                    @endphp

                    <div class="group bg-white dark:bg-forest-900 rounded-2xl border border-gray-200/90 dark:border-forest-800 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden hover:-translate-y-1">
                        
                        <div class="p-5 sm:p-6 flex-1">
                            {{-- Header Kartu: Kategori & Kelas --}}
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg border {{ $badgeBg }}">
                                    {{ $doc->jenis_dokumen }}
                                </span>
                                <span class="px-2.5 py-1 text-[11px] font-extrabold rounded-lg bg-forest-50 text-forest-800 dark:bg-forest-800 dark:text-emerald-300">
                                    Kelas {{ $doc->tingkat_kelas }}
                                </span>
                            </div>

                            {{-- Judul Perangkat Ajar --}}
                            <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white line-clamp-2 group-hover:text-forest-700 dark:group-hover:text-emerald-400 transition leading-snug mb-2">
                                {{ $doc->judul_perangkat }}
                            </h2>

                            {{-- Mata Pelajaran & Semester --}}
                            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-3">
                                <span class="inline-flex items-center gap-1 font-semibold text-gray-700 dark:text-gray-300">
                                    <span>📖</span> {{ $doc->course?->nama ?? 'Semua Mata Pelajaran' }}
                                </span>
                                <span>•</span>
                                <span>Sem. {{ $doc->semester }}</span>
                                <span>•</span>
                                <span>T.A. {{ $doc->tahun_ajaran }}</span>
                            </div>

                            {{-- Deskripsi Singkat jika ada --}}
                            @if(!empty($doc->deskripsi))
                                <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed mb-4">
                                    {{ $doc->deskripsi }}
                                </p>
                            @endif

                            {{-- Guru Penyusun --}}
                            <div class="pt-3 border-t border-gray-100 dark:border-forest-800/80 flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-forest-100 dark:bg-forest-800 text-forest-800 dark:text-emerald-300 font-black text-xs flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($doc->teacher?->nama ?? 'Guru', 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-gray-900 dark:text-gray-100 truncate">
                                        {{ $doc->teacher?->nama ?? 'Dewan Guru SDN Cinanggerang I' }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 truncate">
                                        {{ $doc->teacher?->jabatan ?? 'Guru Pengampu' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Kartu: Tombol Aksi Akses Langsung Dokumen --}}
                        <div class="px-5 py-3.5 bg-gray-50 dark:bg-forest-950/60 border-t border-gray-100 dark:border-forest-800/80 flex items-center gap-2">
                            
                            {{-- 1. Tombol Unduh / Buka File yang Diunggah --}}
                            @if(!empty($doc->file_dokumen))
                                @php
                                    $fileUrl = asset('storage/' . $doc->file_dokumen);
                                @endphp
                                <a href="{{ $fileUrl }}" 
                                   target="_blank" 
                                   rel="noopener"
                                   class="flex-1 py-2 px-3 bg-forest-800 hover:bg-forest-700 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center justify-center gap-1.5 active:scale-95">
                                    <span>{{ $fileIcon }}</span>
                                    <span>Buka Dokumen</span>
                                </a>
                                <a href="{{ $fileUrl }}" 
                                   download
                                   title="Download File Langsung"
                                   class="p-2 bg-gray-200 hover:bg-gray-300 dark:bg-forest-800 dark:hover:bg-forest-700 text-gray-700 dark:text-gray-200 rounded-xl text-xs font-bold transition flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </a>
                            @endif

                            {{-- 2. Tombol Google Drive --}}
                            @if(!empty($doc->link_gdrive))
                                <a href="{{ $doc->link_gdrive }}" 
                                   target="_blank" 
                                   rel="noopener"
                                   class="{{ empty($doc->file_dokumen) ? 'flex-1' : '' }} py-2 px-3 bg-emerald-700 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center justify-center gap-1.5 active:scale-95"
                                   title="Buka Folder di Google Drive">
                                    <span>📁</span>
                                    <span>Google Drive</span>
                                    <svg class="w-3 h-3 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @endif

                            {{-- Jika tidak ada file maupun link gdrive --}}
                            @if(empty($doc->file_dokumen) && empty($doc->link_gdrive))
                                <div class="w-full py-1.5 text-center text-[11px] text-gray-400 font-medium italic">
                                    Tercatat di Sistem (Belum ada lampiran berkas)
                                </div>
                            @endif

                        </div>

                    </div>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            <div class="mt-8">
                {{ $documents->links() }}
            </div>

        @else
            {{-- EMPTY STATE JIKA BELUM ADA DATA DOKUMEN --}}
            <div class="bg-white dark:bg-forest-900 border border-gray-200 dark:border-forest-800 rounded-3xl p-10 sm:p-14 text-center max-w-xl mx-auto shadow-sm">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-forest-50 dark:bg-forest-800 text-forest-800 dark:text-emerald-300 flex items-center justify-center text-3xl">
                    📁
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                    Belum Ada Dokumen Ditemukan
                </h3>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                    @if(!empty($selectedJenis) || !empty($selectedKelas) || !empty($selectedMapel) || !empty($search))
                        Tidak ditemukan perangkat ajar untuk filter yang Anda pilih. Silakan coba pilih kategori lain atau reset filter pencarian.
                    @else
                        Belum ada berkas perangkat pembelajaran yang dibagikan secara publik.
                    @endif
                </p>
                <a href="{{ route('perangkat-ajar') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-forest-900 hover:bg-forest-800 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition shadow-md">
                    <span>🔄</span>
                    <span>Tampilkan Semua Dokumen</span>
                </a>
            </div>
        @endif

    </div>
@endsection
