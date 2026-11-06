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
        <div class="text-xs font-semibold text-forest-800 flex items-center gap-1">
            <span>Menampilkan {{ $posts->count() }} Warta Terbaru</span>
        </div>
    </div>

    {{-- GRID BERITA & PENGUMUMAN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
        @forelse($posts as $post)
            <article class="bg-white dark:bg-forest-900 rounded-2xl overflow-hidden border border-gray-100 dark:border-forest-800 shadow-sm hover:shadow-md transition-all flex flex-col group">
                {{-- Thumbnail Berita --}}
                <div class="aspect-video w-full overflow-hidden bg-gray-100 dark:bg-forest-800 relative">
                    @if(!empty($post->thumbnail_url))
                        <img src="{{ $post->thumbnail_url }}"
                             alt="{{ $post->judul }}"
                             loading="lazy"
                             decoding="async"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full bg-gradient-to-tr from-forest-900 to-forest-800 flex items-center justify-center text-sumedang-amber/60">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        </div>
                    @endif

                    <span class="absolute top-3 left-3 px-2.5 py-1 bg-forest-950/80 backdrop-blur-sm text-white text-[10px] font-bold rounded-lg uppercase tracking-wider">
                        {{ $post->kategori }}
                    </span>
                </div>

                {{-- Konten Ringkas --}}
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <div class="text-[11px] text-gray-400 mb-2 flex items-center gap-2">
                            <span>📅 {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->translatedFormat('d M Y') : now()->translatedFormat('d M Y') }}</span>
                            <span>•</span>
                            <span>SDN Cinanggerang I</span>
                        </div>
                        <h3 class="font-extrabold text-base text-forest-950 dark:text-white leading-snug group-hover:text-forest-800 transition">
                            {{ $post->judul }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-2 line-clamp-3 leading-relaxed">
                            {{ Str::limit(strip_tags($post->konten), 120) }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-gray-100 dark:border-forest-800 flex items-center justify-between text-xs font-bold text-forest-800 dark:text-emerald-300">
                        <span>Baca Selengkapnya</span>
                        <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </div>
                </div>
            </article>
        @empty
            <div class="md:col-span-3 p-8 bg-white dark:bg-forest-900 rounded-2xl border border-gray-100 dark:border-forest-800 text-center">
                <span class="text-3xl">📰</span>
                <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 mt-2">Belum ada warta berita yang dipublikasikan.</p>
                <p class="text-xs text-gray-400">Admin sekolah dapat menambahkan berita & artikel melalui panel admin.</p>
            </div>
        @endforelse
    </div>

    {{-- KOTAK PENGUMUMAN RESMI (JIKA ADA) --}}
    @if($announcements->count() > 0)
        <div class="mt-8 p-5 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sumedang-amber text-forest-950 font-bold flex items-center justify-center flex-shrink-0 text-lg">
                    📢
                </div>
                <div>
                    <span class="text-[10px] font-bold text-amber-800 uppercase tracking-wider">Pengumuman Kedinasan</span>
                    <h4 class="font-bold text-sm text-forest-950 dark:text-white">{{ $announcements->first()->judul }}</h4>
                </div>
            </div>
            @if(!empty($announcements->first()->file_lampiran))
                <a href="{{ asset('storage/' . $announcements->first()->file_lampiran) }}" target="_blank"
                   class="px-4 py-2 bg-forest-900 hover:bg-forest-800 text-white rounded-xl text-xs font-bold flex-shrink-0 transition">
                    Unduh Surat Edaran (PDF)
                </a>
            @endif
        </div>
    @endif
</section>
