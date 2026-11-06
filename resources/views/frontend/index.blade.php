@extends('layouts.frontend')

@section('title', 'SD Negeri Cinanggerang I | Portal Resmi & E-PKKS Digital Sumedang')

@section('content')
    {{-- Seksi 2: Hero Section Dinamis + Visi Sekolah + Dual CTA --}}
    @include('components.frontend.hero')

    {{-- Seksi 3: Live Counter Statistik Sekolah (Alpine.js Viewport Counter) --}}
    @include('components.frontend.stats-counter')

    {{-- Seksi 4: Sambutan Hangat Kepala Sekolah (Bpk. Aep Yayat, S.Pd) --}}
    @include('components.frontend.headmaster')

    {{-- Seksi 5: Highlight Layanan PPDB Online 2026/2027 --}}
    @include('components.frontend.ppdb-banner')

    {{-- Seksi 6: Warta Sekolah, Berita & Galeri Prestasi Siswa --}}
    @include('components.frontend.news-card')

    {{-- Seksi 7: Direktori Guru & Tendik (Privacy-First Data Protection) --}}
    @include('components.frontend.teacher-card')

    {{-- Seksi 8: Galeri Video & Gelar Karya Siswa (Facade Pattern) --}}
    @include('components.frontend.video-facade')

    {{-- Seksi 9: Showcase Portofolio Mutu Digital E-PKKS Permendikdasmen No. 23/2026 --}}
    @include('components.frontend.pkks-accordion')
@endsection
