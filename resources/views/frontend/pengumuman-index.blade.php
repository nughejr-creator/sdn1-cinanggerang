@extends('layouts.frontend')

@section('title', 'Daftar Pengumuman & Surat Edaran Kedinasan - SD Negeri Cinanggerang I')
@section('meta_description', 'Papan pengumuman resmi, surat edaran kedinasan, agenda sumatif, dan informasi penting SD Negeri Cinanggerang I Sumedang.')
@section('canonical_url', route('pengumuman'))

@section('content')
<div class="bg-slate-50 dark:bg-forest-950 min-h-screen">

    {{-- Header Banner Halaman Pengumuman --}}
    <div class="bg-gradient-to-b from-forest-950 via-forest-900 to-forest-950 text-white py-12 lg:py-16 border-b border-forest-800 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:20px_20px] opacity-30 pointer-events-none"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="inline-block px-3.5 py-1 rounded-full bg-amber-500/20 text-sumedang-amber border border-amber-400/30 text-xs font-bold uppercase tracking-wider mb-3">
                📢 Papan Informasi Resmi
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                Pengumuman Kedinasan & Surat Edaran
            </h1>
            <p class="text-xs sm:text-sm text-gray-300 mt-2 max-w-2xl leading-relaxed">
                Informasi resmi kedinasan, edaran pelaksanaan asesmen/ujian, jadwal kegiatan akademik, dan pemberitahuan penting bagi orang tua dan siswa SDN Cinanggerang I.
            </p>
        </div>
    </div>

    {{-- Daftar Pengumuman --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- Kolom Kiri: Daftar Surat Edaran / Pengumuman (8 Kolom) --}}
            <div class="lg:col-span-8 space-y-4">
                @forelse($announcements as $announcement)
                    <div class="p-6 bg-white dark:bg-forest-900 rounded-3xl border border-gray-100 dark:border-forest-800 shadow-sm hover:shadow-md transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 group">
                        
                        <div class="flex items-start gap-4 flex-1">
                            <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-sumedang-amber font-bold flex items-center justify-center flex-shrink-0 text-2xl shadow-sm group-hover:scale-105 transition-transform">
                                📢
                            </div>
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 dark:bg-amber-950 dark:text-amber-300 uppercase tracking-wider">
                                        Pengumuman Resmi
                                    </span>
                                    <span class="text-xs text-gray-400 font-medium">
                                        📅 {{ $announcement->tanggal_mulai ? $announcement->tanggal_mulai->translatedFormat('d F Y') : '-' }}
                                    </span>
                                </div>
                                <a href="{{ $announcement->detail_url }}" class="block">
                                    <h3 class="font-extrabold text-base sm:text-lg text-forest-950 dark:text-white group-hover:text-amber-700 dark:group-hover:text-amber-300 transition leading-snug">
                                        {{ $announcement->judul }}
                                    </h3>
                                </a>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 line-clamp-2 leading-relaxed">
                                    {{ Str::limit(strip_tags($announcement->isi), 150) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto justify-end pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100 dark:border-forest-800">
                            <a href="{{ $announcement->detail_url }}"
                               class="px-4 py-2.5 rounded-xl bg-forest-900 hover:bg-forest-800 text-white font-bold text-xs shadow transition flex items-center gap-1.5">
                                <span>Buka Rincian</span>
                                <span>&rarr;</span>
                            </a>
                            @if(!empty($announcement->file_lampiran_url))
                                <a href="{{ $announcement->file_lampiran_url }}" target="_blank"
                                   class="px-3.5 py-2.5 rounded-xl bg-sumedang-amber hover:bg-sumedang-gold text-forest-950 font-black text-xs shadow transition flex items-center gap-1"
                                   title="Unduh Lampiran Dokumen PDF">
                                    <span>📄 PDF</span>
                                </a>
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="p-10 bg-white dark:bg-forest-900 rounded-3xl border border-gray-200 dark:border-forest-800 text-center shadow-sm">
                        <span class="text-4xl">📢</span>
                        <h3 class="font-bold text-base text-forest-950 dark:text-white mt-2">Belum Ada Pengumuman Aktif</h3>
                        <p class="text-xs text-gray-400 mt-1">Pengumuman terbaru dari pihak sekolah akan segera ditampilkan di sini.</p>
                    </div>
                @endforelse

                {{-- Pagination Pengumuman --}}
                <div class="pt-4">
                    {{ $announcements->links() }}
                </div>
            </div>

            {{-- Kolom Kanan: Warta Terkini Sekolah (4 Kolom) --}}
            <div class="lg:col-span-4 space-y-6">
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

                    <a href="{{ route('warta') }}" class="mt-4 block text-center text-xs font-bold text-forest-800 dark:text-emerald-300 hover:underline">
                        Lihat Semua Warta Berita &rarr;
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection