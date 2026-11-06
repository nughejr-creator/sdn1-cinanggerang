@extends('layouts.frontend')

@section('title', 'Penerimaan Peserta Didik Baru (PPDB Online) - SD Negeri Cinanggerang I')
@section('meta_description', 'Layanan informasi, persyaratan, pendaftaran mandiri, dan cek status kelulusan siswa baru online SD Negeri Cinanggerang I Pamulihan Sumedang.')
@section('canonical_url', route('ppdb'))

@section('content')
    {{-- Header Banner Halaman --}}
    <div class="bg-forest-950 text-white py-12 lg:py-16 border-b border-forest-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:20px_20px] opacity-30 pointer-events-none"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="inline-block px-3 py-1 rounded-full bg-sumedang-amber/20 text-sumedang-amber border border-sumedang-amber/40 text-xs font-bold uppercase tracking-wider mb-3">
                Penerimaan Murid Baru {{ $setting->academic_year ?? '2026/2027' }}
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                Layanan PPDB Digital
            </h1>
            <p class="text-xs sm:text-sm text-gray-300 mt-2 max-w-2xl leading-relaxed">
                Pendaftaran peserta didik baru mudah, cepat, dan transparan bagi putra-putri tercinta di SD Negeri Cinanggerang I.
            </p>
        </div>
    </div>

    @if(session('error_closed'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-300 text-amber-900 text-xs sm:text-sm font-semibold flex items-center gap-3 shadow-sm">
                <span class="text-xl">📢</span>
                <span>{{ session('error_closed') }}</span>
            </div>
        </div>
    @endif

    {{-- Highlight, Alur Pendaftaran & Modal Cek Status PPDB --}}
    @include('components.frontend.ppdb-banner')
@endsection
