<section id="video" class="py-10 md:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
         x-data="{ activeVideoModal: null, activeVideoEmbed: '' }">

    {{-- HEADER DOKUMENTASI KEGIATAN --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
        <div>
            <span class="inline-block px-3 py-1 rounded-full bg-forest-100 text-forest-800 text-xs font-bold uppercase tracking-wider mb-2">
                Dokumentasi Kegiatan Sekolah
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-forest-950 dark:text-white">
                Galeri Video Dokumentasi Kegiatan Siswa
            </h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Kompilasi dokumentasi resmi pembiasaan karakter, Proyek P5, kepramukaan, dan kegiatan belajar mengajar SDN Cinanggerang I
            </p>
        </div>
        <div class="text-xs font-bold text-sumedang-amber bg-amber-50 px-3 py-1 rounded-full border border-amber-200">
            Kanal Resmi TikTok @sdn.cinanggerang1
        </div>
    </div>

    {{-- GRID VIDEO TIKTOK DOKUMENTASI KEGIATAN --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($videos as $video)
            <div class="bg-white dark:bg-forest-900 rounded-2xl overflow-hidden border border-gray-100 dark:border-forest-800 shadow-sm flex flex-col group">

                {{-- Thumbnail Facade (Ringan & Cepat) --}}
                <div class="aspect-video w-full bg-forest-950 relative overflow-hidden cursor-pointer"
                     @click="activeVideoModal = '{{ addslashes($video->judul) }}'; activeVideoEmbed = $el.querySelector('.video-embed-src').innerHTML">

                    <template class="video-embed-src hidden">{!! $video->embed_html ?? '' !!}</template>

                    {{-- Cover / Thumbnail --}}
                    @if(!empty($video->thumbnail_url))
                        <img src="{{ $video->thumbnail_url }}"
                             alt="{{ $video->judul }}"
                             loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                    @else
                        <div class="w-full h-full bg-gradient-to-tr from-forest-950 to-forest-800 flex items-center justify-center">
                            <span class="text-3xl opacity-40">🎬</span>
                        </div>
                    @endif

                    {{-- Tombol Play Mengambang --}}
                    <div class="absolute inset-0 bg-forest-950/30 flex items-center justify-center group-hover:bg-forest-950/10 transition">
                        <div class="w-12 h-12 rounded-full bg-sumedang-amber text-forest-950 flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>

                    {{-- Badge Platform & Kategori --}}
                    <div class="absolute top-2.5 left-2.5 right-2.5 flex items-center justify-between pointer-events-none">
                        <span class="px-2 py-0.5 bg-forest-900/90 text-emerald-300 text-[10px] font-bold rounded-md backdrop-blur-sm shadow">
                            {{ $video->kategori }}
                        </span>
                        <span class="px-2 py-0.5 {{ $video->platform === 'TikTok' ? 'bg-[#010101]/90 text-[#25F4EE]' : 'bg-red-600/90 text-white' }} text-[10px] font-extrabold rounded-md backdrop-blur-sm shadow flex items-center gap-1">
                            @if($video->platform === 'TikTok')
                                <svg class="w-3 h-3 fill-current inline" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1.04-.1z"/></svg>
                                <span>TikTok</span>
                            @else
                                <span>{{ $video->platform }}</span>
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Keterangan Judul --}}
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] text-gray-400 font-medium">
                            {{ $video->tanggal ? \Carbon\Carbon::parse($video->tanggal)->translatedFormat('d M Y') : 'Dokumentasi' }}
                        </span>
                        <h4 class="font-bold text-xs sm:text-sm text-forest-950 dark:text-white line-clamp-2 mt-1 leading-snug">
                            {{ $video->judul }}
                        </h4>
                    </div>

                    <button type="button"
                            @click="activeVideoModal = '{{ addslashes($video->judul) }}'; activeVideoEmbed = $el.closest('.group').querySelector('.video-embed-src').innerHTML"
                            class="mt-3 text-left text-xs font-bold text-forest-800 dark:text-emerald-300 hover:text-sumedang-amber flex items-center gap-1 transition">
                        <span>Tonton Video</span>
                        <span>&rarr;</span>
                    </button>
                </div>

            </div>
        @empty
            <div class="col-span-full p-8 bg-white dark:bg-forest-900 rounded-2xl border border-gray-100 dark:border-forest-800 text-center">
                <span class="text-3xl">🎥</span>
                <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 mt-2">Belum ada video kegiatan yang disematkan.</p>
                <p class="text-xs text-gray-400">Guru dan admin dapat menempelkan link YouTube, TikTok, atau Google Drive di panel admin.</p>
            </div>
        @endforelse
    </div>

    {{-- BANNER ARSIP FOTO DOKUMENTASI GOOGLE DRIVE RESMI --}}
    <div class="mt-10 p-6 sm:p-8 bg-gradient-to-r from-forest-900 via-forest-800 to-sumedang-teal rounded-3xl text-white shadow-xl border border-emerald-500/20 relative overflow-hidden"
         x-data="{ showDriveEmbed: false }">
        <div class="absolute -right-16 -top-16 w-64 h-64 bg-sumedang-amber/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md text-sumedang-amber flex items-center justify-center flex-shrink-0 text-2xl border border-white/20 shadow-inner">
                    📁
                </div>
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-sumedang-amber/20 text-sumedang-amber text-[10px] font-extrabold uppercase tracking-wider mb-1.5 border border-sumedang-amber/30">
                        <span>Google Drive Cloud Storage</span>
                    </div>
                    <h3 class="text-lg sm:text-xl font-black text-white leading-snug">
                        Arsip Album Foto Dokumentasi Kegiatan SDN Cinanggerang I
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-200 mt-1 max-w-2xl leading-relaxed">
                        Dokumentasi foto kegiatan belajar mengajar, pembiasaan siswa, upacara, kepramukaan, dan sarana prasarana sekolah diarsipkan secara rapi dan terbuka pada Google Drive resmi sekolah.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 w-full lg:w-auto flex-shrink-0">
                <button type="button"
                        @click="showDriveEmbed = !showDriveEmbed"
                        class="w-full sm:w-auto px-4 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition flex items-center justify-center gap-2">
                    <span x-text="showDriveEmbed ? 'Tutup Pratinjau Folder' : 'Pratinjau Folder Foto'"></span>
                    <svg class="w-4 h-4 transform transition-transform" :class="showDriveEmbed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <a href="https://drive.google.com/drive/folders/14OBTBm-pTOgP-YJ7Wffzz6VCqxqwyxoG?usp=sharing"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="w-full sm:w-auto px-5 py-3 rounded-xl bg-sumedang-amber hover:bg-sumedang-gold text-forest-950 font-black text-xs shadow-lg shadow-sumedang-amber/25 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <span>Buka Album di Google Drive</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
            </div>
        </div>

        {{-- EMBED PREVIEW FOLDER GOOGLE DRIVE --}}
        <div x-show="showDriveEmbed" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="mt-6 pt-6 border-t border-white/10">
            <div class="w-full h-[460px] bg-forest-950 rounded-2xl overflow-hidden border border-white/20 shadow-2xl">
                <iframe src="https://drive.google.com/embeddedfolderview?id=14OBTBm-pTOgP-YJ7Wffzz6VCqxqwyxoG#grid"
                        class="w-full h-full border-0"
                        frameborder="0"
                        allowfullscreen></iframe>
            </div>
            <p class="text-[11px] text-gray-300 mt-2 text-center">
                *Klik foto di dalam pratinjau untuk melihat detail atau unduh langsung dokumen foto asli.
            </p>
        </div>
    </div>

    {{-- MODAL PLAYER POPUP (Hanya memuat iframe saat diklik) --}}
    <div x-show="activeVideoModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
         @keydown.escape.window="activeVideoModal = null; activeVideoEmbed = ''">

        <div class="bg-forest-950 rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl border border-forest-800 max-h-[92vh] flex flex-col"
             @click.away="activeVideoModal = null; activeVideoEmbed = ''">

            <div class="p-4 border-b border-forest-800 flex justify-between items-center text-white flex-shrink-0">
                <h3 class="font-bold text-sm truncate pr-4 text-emerald-300" x-text="activeVideoModal"></h3>
                <button type="button" @click="activeVideoModal = null; activeVideoEmbed = ''" class="text-gray-400 hover:text-white p-1 text-2xl font-bold leading-none">
                    &times;
                </button>
            </div>

            <div class="p-3 sm:p-4 bg-black overflow-y-auto flex justify-center items-center" x-html="activeVideoEmbed"></div>

        </div>
    </div>
</section>
