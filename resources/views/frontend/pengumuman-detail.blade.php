@extends('layouts.frontend')

@section('title', $announcement->judul . ' - Pengumuman Resmi SDN Cinanggerang I')
@section('meta_description', Str::limit(strip_tags($announcement->isi), 150))
@section('canonical_url', $announcement->detail_url)

@section('content')
<div class="bg-slate-50 dark:bg-forest-950 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb Navigasi --}}
        <nav class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-6 font-semibold overflow-x-auto whitespace-nowrap pb-2">
            <a href="{{ route('home') }}" class="hover:text-forest-800 dark:hover:text-emerald-300">Beranda</a>
            <span>/</span>
            <a href="{{ route('pengumuman') }}" class="hover:text-forest-800 dark:hover:text-emerald-300">Pengumuman Kedinasan</a>
            <span>/</span>
            <span class="text-forest-900 dark:text-white truncate max-w-xs sm:max-w-md">{{ $announcement->judul }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">

            {{-- KONTEN UTAMA PENGUMUMAN (8 KOLOM) --}}
            <main class="lg:col-span-8 bg-white dark:bg-forest-900 rounded-3xl p-6 sm:p-8 md:p-10 border border-gray-100 dark:border-forest-800 shadow-sm">
                
                {{-- Metadata Pengumuman --}}
                <div class="flex flex-wrap items-center gap-2.5 mb-4">
                    <span class="px-3.5 py-1 bg-amber-100 text-amber-900 dark:bg-amber-950/70 dark:text-amber-300 text-xs font-bold rounded-full uppercase tracking-wider flex items-center gap-1.5 border border-amber-300/40">
                        <span>📢</span>
                        <span>Surat Edaran Kedinasan</span>
                    </span>
                    <span class="text-xs text-gray-400">
                        Mulai Tayang: <strong>{{ $announcement->tanggal_mulai ? $announcement->tanggal_mulai->translatedFormat('d F Y') : '-' }}</strong>
                    </span>
                    @if($announcement->tanggal_selesai)
                        <span class="text-xs text-gray-400">•</span>
                        <span class="text-xs text-gray-400">
                            Berlaku s/d: <strong>{{ $announcement->tanggal_selesai->translatedFormat('d F Y') }}</strong>
                        </span>
                    @endif
                </div>

                {{-- Judul Pengumuman --}}
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-forest-950 dark:text-white leading-tight mb-6">
                    {{ $announcement->judul }}
                </h1>

                {{-- Box Lampiran Berkas PDF (Jika Tersedia) --}}
                @if(!empty($announcement->file_lampiran_url))
                    <div class="p-4 sm:p-5 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border-2 border-dashed border-amber-300 dark:border-amber-700/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                        <div class="flex items-center gap-3">
                            <span class="text-3xl">📄</span>
                            <div>
                                <h4 class="font-bold text-sm text-forest-950 dark:text-white">Dokumen / Surat Edaran Resmi (PDF)</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Silakan unduh atau baca dokumen pendukung pengumuman ini.</p>
                            </div>
                        </div>
                        <a href="{{ $announcement->file_lampiran_url }}" target="_blank"
                           class="px-5 py-2.5 rounded-xl bg-forest-900 hover:bg-forest-800 text-sumedang-amber font-extrabold text-xs shadow-md transition flex items-center gap-2 shrink-0">
                            <span>Buka / Unduh Berkas PDF</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                @endif

                {{-- Isi Lengkap Pengumuman --}}
                <div class="prose prose-emerald dark:prose-invert max-w-none text-slate-700 dark:text-gray-200 text-sm sm:text-base leading-relaxed space-y-4">
                    {!! $announcement->isi !!}
                </div>

                {{-- Tombol Bagikan & Kembali --}}
                <div class="mt-10 pt-6 border-t border-gray-100 dark:border-forest-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center gap-2 text-xs font-bold text-gray-500 dark:text-gray-400">
                        <span>Bagikan Informasi:</span>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($announcement->judul . ' ' . $announcement->detail_url) }}" target="_blank"
                           class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 hover:bg-emerald-100 transition flex items-center gap-1">
                            <span>💬 WhatsApp</span>
                        </a>
                    </div>

                    <a href="{{ route('pengumuman') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gray-100 dark:bg-forest-800 text-gray-700 dark:text-gray-200 text-xs font-bold hover:bg-gray-200 dark:hover:bg-forest-700 transition">
                        <span>&larr;</span>
                        <span>Semua Pengumuman</span>
                    </a>
                </div>

            </main>

            {{-- SIDEBAR KANAN (4 KOLOM) --}}
            <aside class="lg:col-span-4 space-y-6">

                {{-- Pengumuman Lainnya --}}
                @if(isset($otherAnnouncements) && $otherAnnouncements->count() > 0)
                    <div class="bg-white dark:bg-forest-900 rounded-3xl p-6 border border-gray-100 dark:border-forest-800 shadow-sm">
                        <h3 class="font-extrabold text-base text-forest-950 dark:text-white mb-4">
                            Pengumuman Kedinasan Lainnya
                        </h3>

                        <div class="space-y-3">
                            @foreach($otherAnnouncements as $other)
                                <a href="{{ $other->detail_url }}" class="block p-3 rounded-2xl bg-amber-50/50 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-900/60 hover:border-amber-400 transition group">
                                    <span class="text-[10px] text-amber-700 dark:text-amber-300 font-semibold block">
                                        📅 {{ $other->tanggal_mulai ? $other->tanggal_mulai->translatedFormat('d M Y') : '-' }}
                                    </span>
                                    <h4 class="font-bold text-xs text-forest-950 dark:text-white group-hover:text-amber-800 dark:group-hover:text-amber-300 mt-1 line-clamp-2">
                                        {{ $other->judul }}
                                    </h4>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Warta Terkini Sekolah --}}
                @if(isset($recentPosts) && $recentPosts->count() > 0)
                    <div class="bg-white dark:bg-forest-900 rounded-3xl p-6 border border-gray-100 dark:border-forest-800 shadow-sm">
                        <h3 class="font-extrabold text-base text-forest-950 dark:text-white mb-4">
                            Warta Terkini Sekolah
                        </h3>

                        <div class="space-y-4">
                            @foreach($recentPosts as $p)
                                <a href="{{ $p->detail_url }}" class="flex gap-3 group items-center">
                                    @if(!empty($p->thumbnail_url))
                                        <img src="{{ $p->thumbnail_url }}" alt="{{ $p->judul }}"
                                             class="w-16 h-16 rounded-xl object-cover shrink-0 group-hover:scale-105 transition-transform">
                                    @else
                                        <div class="w-16 h-16 rounded-xl bg-forest-100 dark:bg-forest-800 flex items-center justify-center shrink-0 text-xl">
                                            📰
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <span class="text-[10px] text-gray-400 font-semibold block">
                                            {{ $p->published_at ? \Carbon\Carbon::parse($p->published_at)->translatedFormat('d M Y') : '-' }}
                                        </span>
                                        <h4 class="font-bold text-xs text-forest-950 dark:text-white group-hover:text-forest-700 dark:group-hover:text-emerald-300 transition line-clamp-2 mt-0.5">
                                            {{ $p->judul }}
                                        </h4>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </aside>

        </div>

    </div>
</div>
@endsection