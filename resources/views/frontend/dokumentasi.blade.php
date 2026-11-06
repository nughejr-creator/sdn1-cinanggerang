@extends('layouts.frontend')

@section('title', 'Dokumentasi Kegiatan & Gelar Karya Siswa - SD Negeri Cinanggerang I')
@section('meta_description', 'Galeri video kegiatan resmi TikTok dan album foto Google Drive SDN Cinanggerang I Sumedang. P5, kepramukaan, dan pembiasaan.')
@section('canonical_url', route('dokumentasi'))

@section('content')
    {{-- Header Banner Halaman --}}
    <div class="bg-forest-950 text-white py-12 lg:py-16 border-b border-forest-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:20px_20px] opacity-30 pointer-events-none"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="inline-block px-3 py-1 rounded-full bg-forest-800 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-3">
                Dokumentasi Multimedia
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                Galeri Video & Arsip Foto Sekolah
            </h1>
            <p class="text-xs sm:text-sm text-gray-300 mt-2 max-w-2xl leading-relaxed">
                Kompilasi dokumentasi digital aksi nyata siswa, pembiasaan karakter, Gelar Karya P5, dan arsip foto cloud storage resmi sekolah.
            </p>
        </div>
    </div>

    {{-- Galeri Video & Arsip Google Drive --}}
    @include('components.frontend.video-facade')
@endsection
