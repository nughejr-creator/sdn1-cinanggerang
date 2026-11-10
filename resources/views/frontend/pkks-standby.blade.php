@extends('layouts.frontend')

@section('title', 'Periode Penilaian Belum Dibuka | E-PKKS Digital - SDN Cinanggerang I')
@section('meta_description', 'Portal Penilaian Kinerja Kepala Sekolah (e-PKKS) SDN Cinanggerang I saat ini sedang ditutup atau belum memasuki jadwal periode asesmen aktif.')
@section('canonical_url', route('pkks'))

@section('content')
<div class="bg-gradient-to-b from-slate-50 via-forest-50/20 to-slate-100 min-h-[75vh] flex items-center justify-center py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full text-center">
        {{-- Card Standby Elegan --}}
        <div class="bg-white dark:bg-forest-950 rounded-3xl p-8 sm:p-12 shadow-2xl border border-gray-100 dark:border-forest-800 relative overflow-hidden">
            {{-- Accent Top Bar --}}
            <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-amber-400 via-amber-500 to-emerald-600"></div>

            {{-- Lingkaran Efek Cahaya Halus --}}
            <div class="absolute -top-12 -right-12 w-40 h-40 bg-amber-400/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-forest-800/10 rounded-full blur-2xl pointer-events-none"></div>

            {{-- Ikon Status Gembok & Kalender Elegan --}}
            <div class="relative mx-auto w-24 h-24 mb-6 flex items-center justify-center">
                <div class="absolute inset-0 bg-amber-50 dark:bg-amber-950/40 rounded-3xl rotate-6 border border-amber-200/50 dark:border-amber-700/30"></div>
                <div class="relative w-20 h-20 bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>

            {{-- Badge Status --}}
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-xs font-bold uppercase tracking-wider mb-4 border border-amber-200 dark:border-amber-800/60">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                <span>Portal E-PKKS Dinonaktifkan</span>
            </div>

            {{-- Judul --}}
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white tracking-tight mb-4">
                Periode Penilaian Belum Dibuka / Telah Berakhir
            </h1>

            {{-- Deskripsi Sesuai Spesifikasi --}}
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 leading-relaxed mb-8">
                Mohon maaf, portal Penilaian Kinerja Kepala Sekolah (e-PKKS) SDN Cinanggerang I saat ini sedang ditutup atau belum memasuki jadwal periode asesmen aktif. Silakan hubungi tim admin sekolah untuk informasi jadwal pembukaan instrumen.
            </p>

            {{-- Informasi Bantuan Kedinasan --}}
            <div class="bg-slate-50 dark:bg-forest-900/50 rounded-2xl p-4 mb-8 text-left border border-gray-100 dark:border-forest-800/80">
                <div class="flex items-start gap-3">
                    <span class="text-xl">🏛️</span>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        <p class="font-bold text-gray-800 dark:text-gray-200">SD Negeri Cinanggerang I • NPSN: 20208801</p>
                        <p>Kecamatan Pamulihan, Kabupaten Sumedang, Jawa Barat.</p>
                        @if(!empty($profile->email))
                            <p class="mt-1 text-emerald-700 dark:text-emerald-400 font-medium">Email Resmi: {{ $profile->email }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('home') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-forest-900 hover:bg-forest-800 text-white font-bold text-sm shadow-lg shadow-forest-900/20 transition hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali ke Beranda</span>
                </a>

                @auth
                    <a href="{{ url('/admin/school-profiles') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-forest-800 text-gray-700 dark:text-gray-200 font-semibold text-xs transition">
                        ⚙️ Buka Pengaturan Status di Panel Admin
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
