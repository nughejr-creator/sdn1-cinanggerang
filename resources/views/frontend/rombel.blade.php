@extends('layouts.frontend')

@section('title', 'Rombongan Belajar (Rombel) - SD Negeri Cinanggerang I')
@section('meta_description', 'Struktur 8 Rombongan Belajar (Rombel) Kelas I hingga Kelas VI SD Negeri Cinanggerang I, Pamulihan, Sumedang beserta informasi Wali Kelas dan ruang belajar.')
@section('canonical_url', route('rombel'))

@section('content')
    {{-- Header Banner Halaman --}}
    <div class="bg-forest-950 text-white py-12 lg:py-16 border-b border-forest-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:20px_20px] opacity-30 pointer-events-none"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="inline-block px-3 py-1 rounded-full bg-forest-800 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-3">
                🏫 Data Pokok Pembelajaran
            </span>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight">
                Rombongan Belajar (Rombel)
            </h1>
            <p class="text-xs sm:text-sm text-gray-300 mt-2 max-w-2xl leading-relaxed">
                SD Negeri Cinanggerang I membina 8 Rombongan Belajar dari Kelas I hingga Kelas VI dengan lingkungan belajar yang kondusif, berpusat pada murid, dan mengimplementasikan Kurikulum Merdeka.
            </p>
        </div>
    </div>

    {{-- Ringkasan Statistik Rombel & Kesiswaan --}}
    <section class="py-8 bg-forest-900/10 dark:bg-forest-950/60 border-b border-gray-100 dark:border-forest-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-forest-900 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-forest-800 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-forest-50 dark:bg-forest-800 flex items-center justify-center text-2xl text-forest-800 dark:text-emerald-300">
                        🏫
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Rombel</p>
                        <p class="text-2xl font-black text-forest-900 dark:text-white">{{ $rombels->count() }} Kelas</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-forest-900 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-forest-800 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/50 flex items-center justify-center text-2xl text-sumedang-amber">
                        👥
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Peserta Didik Aktif</p>
                        <p class="text-2xl font-black text-forest-900 dark:text-white">{{ $totalSiswa }} Siswa</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-forest-900 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-forest-800 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/50 flex items-center justify-center text-2xl text-blue-600">
                        📘
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Kurikulum</p>
                        <p class="text-sm font-bold text-forest-900 dark:text-white">Kurikulum Merdeka</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-forest-900 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-forest-800 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/50 flex items-center justify-center text-2xl text-purple-600">
                        📅
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Tahun Ajaran</p>
                        <p class="text-sm font-bold text-forest-900 dark:text-white">2026/2027</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Grid 8 Kartu Rombongan Belajar --}}
    <section class="py-12 lg:py-16 bg-slate-50/70 dark:bg-forest-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-2xl font-black text-forest-900 dark:text-white tracking-tight">
                        Daftar Rombongan Belajar Aktif
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Pilih rombel untuk melihat daftar siswa dan rincian informasi kelas.
                    </p>
                </div>
                <a href="{{ route('siswa') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-forest-900 border border-gray-200 dark:border-forest-800 text-forest-900 dark:text-emerald-300 text-xs font-bold hover:bg-forest-50 transition shadow-sm">
                    <span>🔍 Cari Siswa / Cek NISN</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($rombels as $rombel)
                    <div class="bg-white dark:bg-forest-900 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-200/80 dark:border-forest-800 flex flex-col justify-between overflow-hidden group hover:-translate-y-1">
                        {{-- Header Kartu Kelas --}}
                        <div class="p-6 border-b border-gray-100 dark:border-forest-800/80 relative">
                            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-forest-800 via-emerald-600 to-sumedang-amber"></div>
                            
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-black bg-emerald-50 dark:bg-emerald-950/60 text-forest-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                    Tingkat {{ $rombel->tingkat }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-sumedang-amber">
                                    <span>👥</span>
                                    <span>{{ $rombel->students_count ?? 0 }} Siswa</span>
                                </span>
                            </div>

                            <h3 class="text-xl font-black text-forest-900 dark:text-white group-hover:text-forest-700 dark:group-hover:text-emerald-300 transition">
                                {{ $rombel->nama }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1">
                                <span>📍</span>
                                <span>{{ $rombel->ruangan ?: 'Ruang ' . $rombel->nama }}</span>
                            </p>
                        </div>

                        {{-- Body Kartu: Wali Kelas & Info --}}
                        <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block mb-1.5">
                                    Wali Kelas
                                </span>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-forest-100 dark:bg-forest-800 text-forest-900 dark:text-emerald-300 flex items-center justify-center font-bold text-sm shadow-inner flex-shrink-0">
                                        👨‍🏫
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-xs text-gray-900 dark:text-white truncate">
                                            {{ $rombel->teacher?->nama ?? $rombel->wali_kelas }}
                                        </p>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 truncate">
                                            {{ $rombel->teacher?->status_kepegawaian ? 'Status: ' . $rombel->teacher->status_kepegawaian : 'Guru Kelas' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Pintasan ke Halaman Siswa Terfilter --}}
                            <div class="pt-2">
                                <a href="{{ route('siswa', ['rombel_id' => $rombel->id]) }}"
                                   class="w-full py-2.5 px-4 rounded-xl bg-forest-900 hover:bg-forest-800 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-sm transition group-hover:shadow-md">
                                    <span>Lihat Siswa Kelas Ini</span>
                                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center bg-white dark:bg-forest-900 rounded-2xl border border-gray-100 dark:border-forest-800">
                        <p class="text-4xl mb-3">🏫</p>
                        <h4 class="font-bold text-base text-gray-800 dark:text-white">Data Rombel Belum Tersedia</h4>
                        <p class="text-xs text-gray-500 mt-1">Silakan sinkronkan rombongan belajar melalui panel administrasi sekolah.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Keterangan Kebijakan Pendidikan & Dapodik --}}
    <section class="py-12 bg-white dark:bg-forest-900 border-t border-gray-100 dark:border-forest-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-forest-50/70 dark:bg-forest-950/60 p-6 sm:p-8 rounded-2xl border border-emerald-100 dark:border-forest-800 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-1.5 text-center md:text-left">
                    <h3 class="text-base sm:text-lg font-black text-forest-900 dark:text-white">
                        Butuh Cek NISN Siswa untuk Keperluan Bansos / Administrasi?
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 max-w-2xl leading-relaxed">
                        SD Negeri Cinanggerang I menyediakan portal pencarian mandiri NISN resmi untuk mempermudah orang tua murid mengurus administrasi Program Indonesia Pintar (PIP), PKH, dan registrasi lanjutan.
                    </p>
                </div>
                <a href="{{ route('siswa') }}"
                   class="px-6 py-3 rounded-xl bg-forest-900 hover:bg-forest-800 text-white font-extrabold text-xs shadow-md hover:shadow-lg transition flex-shrink-0 flex items-center gap-2">
                    <span>🔍 Buka Layanan Cek NISN</span>
                </a>
            </div>
        </div>
    </section>
@endsection
