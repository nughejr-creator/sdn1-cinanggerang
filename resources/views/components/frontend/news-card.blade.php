<section id="berita" class="py-10 md:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
        <div>
            <span class="inline-block px-3 py-1 rounded-full bg-forest-100 text-forest-800 text-xs font-bold uppercase tracking-wider mb-2">
                Kabar Sekolah
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-forest-950 dark:text-white">
                Warta Terkini & Prestasi Siswa
            </h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Publikasi kegiatan akademik, ekstrakurikuler, dan prestasi SDN Cinanggerang I</p>
        </div>
        <div class="text-xs font-semibold text-forest-800 dark:text-emerald-300 flex items-center gap-1">
            <span>Menampilkan {{ $posts->count() }} Warta Terbaru</span>
        </div>
    </div>

    {{-- GRID BERITA & ARTIKEL RESMI --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
        @forelse($posts as $post)
            <article class="bg-white dark:bg-forest-900 rounded-3xl overflow-hidden border border-gray-100 dark:border-forest-800 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group transform hover:-translate-y-1">
                {{-- Thumbnail Berita (Dapat Diklik) --}}
                <a href="{{ $post->detail_url }}" class="block aspect-video w-full overflow-hidden bg-gray-100 dark:bg-forest-800 relative cursor-pointer">
                    @if(!empty($post->thumbnail_url))
                        <img src="{{ $post->thumbnail_url }}"
                             alt="{{ $post->judul }}"
                             loading="lazy"
                             decoding="async"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-tr from-forest-900 to-forest-800 flex items-center justify-center text-sumedang-amber/60">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        </div>
                    @endif

                    <span class="absolute top-3 left-3 px-2.5 py-1 bg-forest-950/80 backdrop-blur-sm text-white text-[10px] font-bold rounded-lg uppercase tracking-wider border border-white/10">
                        {{ $post->kategori }}
                    </span>
                </a>

                {{-- Konten Ringkas & Tautan Detail --}}
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <div class="text-[11px] text-gray-400 mb-2 flex items-center gap-2">
                            <span>📅 {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->translatedFormat('d M Y') : now()->translatedFormat('d M Y') }}</span>
                            <span>•</span>
                            <span>SDN Cinanggerang I</span>
                        </div>
                        <a href="{{ $post->detail_url }}" class="block group-hover:text-forest-700 dark:group-hover:text-emerald-300 transition">
                            <h3 class="font-extrabold text-base text-forest-950 dark:text-white leading-snug line-clamp-2">
                                {{ $post->judul }}
                            </h3>
                        </a>
                        <p class="text-xs text-gray-500 mt-2 line-clamp-3 leading-relaxed">
                            {{ Str::limit(strip_tags($post->konten), 130) }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-gray-100 dark:border-forest-800">
                        <a href="{{ $post->detail_url }}"
                           class="flex items-center justify-between text-xs font-bold text-forest-800 dark:text-emerald-300 hover:text-sumedang-amber transition">
                            <span>Baca Selengkapnya</span>
                            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="md:col-span-3 p-8 bg-white dark:bg-forest-900 rounded-3xl border border-gray-100 dark:border-forest-800 text-center">
                <span class="text-3xl">📰</span>
                <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 mt-2">Belum ada warta berita yang dipublikasikan.</p>
                <p class="text-xs text-gray-400">Admin sekolah dapat menambahkan berita & artikel melalui panel admin.</p>
            </div>
        @endforelse
    </div>

    {{-- Navigasi Paginasi (Jika Membuka Halaman Warta Penuh) --}}
    @if($posts instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    @endif

    {{-- KOTAK PENGUMUMAN RESMI KEDINASAN (DAPAT DIKLIK & DIBACA DETAIL) --}}
    @if(isset($announcements) && $announcements->count() > 0)
        <div class="mt-10 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-xl">📢</span>
                    <h3 class="font-extrabold text-lg text-forest-950 dark:text-white">Pengumuman Kedinasan & Surat Edaran</h3>
                </div>
                <a href="{{ route('pengumuman') }}" class="text-xs font-bold text-forest-800 dark:text-emerald-300 hover:underline">
                    Lihat Semua Pengumuman &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @foreach($announcements->take(3) as $announcement)
                    <div class="p-4 sm:p-5 bg-gradient-to-r from-amber-50 to-orange-50/60 dark:from-amber-950/40 dark:to-orange-950/20 border border-amber-200 dark:border-amber-800/80 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm hover:shadow-md transition">
                        <a href="{{ $announcement->detail_url }}" class="flex items-center gap-3.5 flex-1 group">
                            <div class="w-11 h-11 rounded-2xl bg-sumedang-amber text-forest-950 font-bold flex items-center justify-center flex-shrink-0 text-xl shadow-sm group-hover:scale-105 transition-transform">
                                📢
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-amber-800 dark:text-amber-300 uppercase tracking-wider">Pengumuman Resmi</span>
                                    <span class="text-[10px] text-gray-400 font-medium">📅 {{ $announcement->tanggal_mulai ? $announcement->tanggal_mulai->translatedFormat('d M Y') : now()->translatedFormat('d M Y') }}</span>
                                </div>
                                <h4 class="font-bold text-sm sm:text-base text-forest-950 dark:text-white group-hover:text-amber-700 dark:group-hover:text-amber-300 transition mt-0.5">
                                    {{ $announcement->judul }}
                                </h4>
                            </div>
                        </a>

                        <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto justify-end">
                            <a href="{{ $announcement->detail_url }}"
                               class="px-4 py-2 rounded-xl bg-forest-900 hover:bg-forest-800 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                                <span>Buka Isi Pengumuman</span>
                                <span>&rarr;</span>
                            </a>
                            @if(!empty($announcement->file_lampiran_url))
                                <a href="{{ $announcement->file_lampiran_url }}" target="_blank"
                                   class="px-3.5 py-2 bg-sumedang-amber hover:bg-sumedang-gold text-forest-950 rounded-xl text-xs font-extrabold transition flex items-center gap-1 shadow-sm"
                                   title="Unduh Surat Edaran Resmi PDF">
                                    <span>📄 Unduh PDF</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>