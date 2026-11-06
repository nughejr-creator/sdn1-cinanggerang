@extends('layouts.frontend')

@section('title', 'Direktori Pendidik & Tenaga Kependidikan - SD Negeri Cinanggerang I')
@section('meta_description', 'Daftar personil 11 Guru dan Tenaga Kependidikan resmi SD Negeri Cinanggerang I, Pamulihan, Sumedang. Berdedikasi dan tersertifikasi.')
@section('canonical_url', route('guru'))

@section('content')
    {{-- Header Banner Tunggal Ringkas --}}
    <div class="bg-forest-950 text-white py-8 md:py-10 border-b border-forest-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:20px_20px] opacity-30 pointer-events-none"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="inline-block px-3 py-1 rounded-full bg-forest-800 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-2">
                Sumber Daya Manusia Unggul
            </span>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-white">
                Direktori Guru & Tenaga Kependidikan
            </h1>
            <p class="text-sm text-emerald-100 max-w-2xl mt-1 leading-relaxed">
                Dipimpin oleh 11 tenaga pendidik dan kependidikan profesional yang berdedikasi mendidik putra-putri berkarakter Profil Pelajar Pancasila.
            </p>
        </div>
    </div>

    {{-- Grid Kartu Guru & Tendik (Langsung di bawah header tanpa duplikasi) --}}
    <section class="py-8 md:py-10 bg-forest-950 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:16px_16px] opacity-20 pointer-events-none"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($teachers as $teacher)
                    <div class="bg-forest-900/80 rounded-2xl p-4 sm:p-5 border border-forest-800 flex flex-col items-center text-center group hover:border-emerald-500/50 hover:bg-forest-900 transition-all duration-300 shadow-sm hover:shadow-md">

                        {{-- Foto Guru --}}
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden bg-forest-800 border-2 border-emerald-500/40 mb-3 relative group-hover:scale-105 transition-transform shadow-inner">
                            @if(!empty($teacher->foto))
                                <img src="{{ asset('storage/' . $teacher->foto) }}"
                                     alt="{{ $teacher->nama }}"
                                     loading="lazy"
                                     decoding="async"
                                     class="w-full h-full object-cover object-top">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-emerald-300 font-black text-xl sm:text-2xl bg-gradient-to-tr from-forest-800 to-forest-700">
                                    {{ substr($teacher->nama, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        {{-- Nama & Gelar --}}
                        <h3 class="font-bold text-xs sm:text-sm text-white group-hover:text-sumedang-amber transition leading-tight">
                            {{ $teacher->nama }}{{ $teacher->gelar ? ', ' . $teacher->gelar : '' }}
                        </h3>

                        {{-- Jabatan --}}
                        <span class="inline-block px-2 py-0.5 mt-1.5 rounded bg-forest-800 text-emerald-300 text-[10px] font-semibold">
                            {{ $teacher->jabatan }}
                        </span>

                        {{-- Mata Pelajaran / Rombel --}}
                        <p class="text-[11px] text-gray-300 mt-1 line-clamp-1">
                            {{ $teacher->mapel_diajarkan ?? 'Pendidik Satuan' }}
                        </p>

                        {{-- Status Kepegawaian --}}
                        <div class="mt-3 pt-3 border-t border-forest-800/80 w-full flex items-center justify-between text-[10px] text-gray-400">
                            <span class="font-medium">Status:</span>
                            <span class="font-bold text-gray-200">{{ $teacher->status_kepegawaian }}</span>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
