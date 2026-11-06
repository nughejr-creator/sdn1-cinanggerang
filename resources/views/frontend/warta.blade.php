@extends('layouts.frontend')

@section('title', 'Warta Sekolah, Berita & Pengumuman - SD Negeri Cinanggerang I')
@section('meta_description', 'Kabar sekolah terkini, publikasi kegiatan akademik, ekstrakurikuler, dan prestasi peserta didik SDN Cinanggerang I Sumedang.')
@section('canonical_url', route('warta'))

@section('content')
    {{-- Header Banner Halaman --}}
    <div class="bg-forest-950 text-white py-12 lg:py-16 border-b border-forest-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:20px_20px] opacity-30 pointer-events-none"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="inline-block px-3 py-1 rounded-full bg-forest-800 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-3">
                Kabar & Publikasi Resmi
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                Warta Terkini & Prestasi Siswa
            </h1>
            <p class="text-xs sm:text-sm text-gray-300 mt-2 max-w-2xl leading-relaxed">
                Informasi aktual seputar aktivitas belajar, perayaan hari besar, surat edaran kedinasan, dan raihan prestasi sekolah.
            </p>
        </div>
    </div>

    {{-- Kartu Warta Berita & Pengumuman --}}
    @include('components.frontend.news-card')
@endsection
