@extends('layouts.frontend')

@section('title', 'Galeri Foto & Video Dokumentasi Kegiatan - SD Negeri Cinanggerang I')
@section('meta_description', 'Dokumentasi resmi album foto kegiatan siswa dan galeri video TikTok/YouTube SDN Cinanggerang I Pamulihan Sumedang. Gelar karya P5, pramuka, dan pembiasaan.')
@section('canonical_url', route('dokumentasi'))

@section('content')
<div x-data="{
        activeTab: 'foto',
        selectedAlbum: null,
        selectedImage: null,
        openAlbum(album) {
            this.selectedAlbum = album;
        },
        closeAlbum() {
            this.selectedAlbum = null;
            this.selectedImage = null;
        }
     }">

    {{-- Header Banner Halaman Dokumentasi --}}
    <div class="bg-gradient-to-b from-forest-950 via-forest-900 to-forest-950 text-white py-12 lg:py-16 border-b border-forest-800 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:20px_20px] opacity-30 pointer-events-none"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="inline-block px-3.5 py-1 rounded-full bg-forest-800 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-3 border border-emerald-400/20">
                Publikasi & Dokumentasi Resmi
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                Galeri Foto & Video Kegiatan Sekolah
            </h1>
            <p class="text-xs sm:text-sm text-gray-300 mt-2 max-w-2xl leading-relaxed">
                Kompilasi dokumentasi digital aksi nyata siswa, pembiasaan karakter pagi, Gelar Karya P5, kepramukaan, dan arsip multimedia resmi SDN Cinanggerang I.
            </p>

            {{-- Navigasi Tab: Foto Kegiatan vs Video Kegiatan --}}
            <div class="mt-8 inline-flex p-1.5 rounded-2xl bg-forest-900/90 border border-forest-700/60 backdrop-blur-sm shadow-lg gap-2">
                <button type="button"
                        @click="activeTab = 'foto'"
                        :class="activeTab === 'foto' ? 'bg-sumedang-amber text-forest-950 font-black shadow-md' : 'text-gray-300 hover:text-white font-semibold'"
                        class="px-5 py-2.5 rounded-xl text-xs sm:text-sm flex items-center gap-2 transition-all">
                    <span>📸</span>
                    <span>Galeri Foto Kegiatan</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                          :class="activeTab === 'foto' ? 'bg-forest-950/20 text-forest-950' : 'bg-forest-800 text-emerald-300'">
                        {{ $galleries->count() }}
                    </span>
                </button>

                <button type="button"
                        @click="activeTab = 'video'"
                        :class="activeTab === 'video' ? 'bg-sumedang-amber text-forest-950 font-black shadow-md' : 'text-gray-300 hover:text-white font-semibold'"
                        class="px-5 py-2.5 rounded-xl text-xs sm:text-sm flex items-center gap-2 transition-all">
                    <span>🎬</span>
                    <span>Galeri Video Dokumentasi</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
                          :class="activeTab === 'video' ? 'bg-forest-950/20 text-forest-950' : 'bg-forest-800 text-emerald-300'">
                        {{ $videos->count() }}
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== TAB 1: GALERI FOTO KEGIATAN ==================== --}}
    <section x-show="activeTab === 'foto'" x-cloak class="py-12 bg-slate-50 dark:bg-forest-950 min-h-[500px]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Judul Seksi Foto --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 gap-4 border-b border-gray-200 dark:border-forest-800 pb-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-forest-950 dark:text-white">
                        Dokumentasi Album Foto Kegiatan Siswa
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Dokumentasi momen penting, prestasi, dan kegiatan edukatif di lingkungan SD Negeri Cinanggerang I
                    </p>
                </div>
                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400">
                    Total Album: <strong class="text-forest-800 dark:text-emerald-300">{{ $galleries->count() }} Kegiatan</strong>
                </div>
            </div>

            {{-- Grid Album Foto Kegiatan --}}
            @if($galleries->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach($galleries as $gallery)
                        @php
                            $coverUrl = $gallery->cover_url;
                            $fotoUrls = $gallery->foto_urls;
                            $jumlah = count($fotoUrls);
                            $albumJson = json_encode([
                                'id' => $gallery->id,
                                'judul' => $gallery->judul_kegiatan,
                                'tanggal' => $gallery->tanggal_kegiatan ? $gallery->tanggal_kegiatan->translatedFormat('d F Y') : '-',
                                'deskripsi' => $gallery->deskripsi ?? '',
                                'photos' => $fotoUrls,
                            ]);
                        @endphp

                        <div class="bg-white dark:bg-forest-900 rounded-3xl overflow-hidden border border-gray-100 dark:border-forest-800 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col group transform hover:-translate-y-1">
                            
                            {{-- Cover Foto Album --}}
                            <div class="aspect-[16/10] w-full bg-forest-950 relative overflow-hidden cursor-pointer"
                                 @click="openAlbum({{ $albumJson }})">
                                @if(!empty($coverUrl))
                                    <img src="{{ $coverUrl }}"
                                         alt="{{ $gallery->judul_kegiatan }}"
                                         loading="lazy"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                         onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                    <div class="hidden w-full h-full bg-gradient-to-tr from-forest-900 to-forest-800 flex items-center justify-center">
                                        <span class="text-4xl opacity-50">📷</span>
                                    </div>
                                @else
                                    <div class="w-full h-full bg-gradient-to-tr from-forest-900 to-forest-800 flex flex-col items-center justify-center text-center p-4">
                                        <span class="text-4xl opacity-50">📷</span>
                                        <span class="text-xs text-gray-300 mt-2">Belum ada foto</span>
                                    </div>
                                @endif

                                {{-- Badge Jumlah Foto --}}
                                <div class="absolute bottom-3 right-3 px-3 py-1 rounded-full bg-forest-950/80 backdrop-blur-md text-sumedang-amber text-xs font-black shadow-lg flex items-center gap-1.5 border border-sumedang-amber/30">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>{{ $jumlah }} Foto</span>
                                </div>

                                {{-- Overlay Efek Hover --}}
                                <div class="absolute inset-0 bg-forest-950/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="px-4 py-2 rounded-xl bg-sumedang-amber text-forest-950 text-xs font-black shadow-xl transform scale-90 group-hover:scale-100 transition-transform">
                                        🔍 Lihat Semua Foto
                                    </span>
                                </div>
                            </div>

                            {{-- Keterangan Album --}}
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center gap-2 text-[11px] text-gray-500 dark:text-gray-400 font-semibold mb-1.5">
                                        <span>📅</span>
                                        <span>{{ $gallery->tanggal_kegiatan ? $gallery->tanggal_kegiatan->translatedFormat('d F Y') : '-' }}</span>
                                    </div>

                                    <h3 class="font-extrabold text-base sm:text-lg text-forest-950 dark:text-white leading-snug group-hover:text-forest-700 dark:group-hover:text-emerald-300 transition">
                                        {{ $gallery->judul_kegiatan }}
                                    </h3>

                                    @if(!empty($gallery->deskripsi))
                                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-2 line-clamp-3 leading-relaxed">
                                            {{ $gallery->deskripsi }}
                                        </p>
                                    @endif
                                </div>

                                <div class="pt-4 mt-4 border-t border-gray-100 dark:border-forest-800">
                                    <button type="button"
                                            @click="openAlbum({{ $albumJson }})"
                                            class="w-full py-2.5 px-4 rounded-xl bg-forest-50 dark:bg-forest-800/60 hover:bg-forest-100 dark:hover:bg-forest-800 text-forest-900 dark:text-emerald-300 font-bold text-xs flex items-center justify-center gap-2 transition">
                                        <span>Buka Album Foto ({{ $jumlah }})</span>
                                        <span>&rarr;</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-12 bg-white dark:bg-forest-900 rounded-3xl border border-gray-200 dark:border-forest-800 text-center shadow-sm">
                    <span class="text-5xl">📷</span>
                    <h3 class="font-bold text-lg text-forest-950 dark:text-white mt-3">Belum Ada Album Foto Kegiatan</h3>
                    <p class="text-xs sm:text-sm text-gray-500 max-w-md mx-auto mt-1">
                        Foto dokumentasi kegiatan yang diunggah melalui menu <strong>Galeri Kegiatan</strong> di panel admin akan otomatis tampil di halaman ini.
                    </p>
                    @auth
                        <div class="mt-4">
                            <a href="{{ url('/admin/galleries/create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-forest-800 text-sumedang-amber font-bold text-xs">
                                <span>➕ Upload Dokumentasi Baru</span>
                            </a>
                        </div>
                    @endauth
                </div>
            @endif

        </div>
    </section>

    {{-- ==================== TAB 2: GALERI VIDEO DOKUMENTASI ==================== --}}
    <section x-show="activeTab === 'video'" x-cloak class="bg-white dark:bg-forest-950">
        @include('components.frontend.video-facade')
    </section>

    {{-- ==================== MODAL / LIGHTBOX VIEWER ALBUM FOTO ==================== --}}
    <div x-show="selectedAlbum" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/80 backdrop-blur-md p-4 sm:p-6 lg:p-8 flex items-center justify-center">

        <div @click.away="closeAlbum()"
             class="bg-white dark:bg-forest-900 rounded-3xl max-w-5xl w-full max-h-[90vh] overflow-hidden flex flex-col shadow-2xl border border-gray-200 dark:border-forest-700">

            {{-- Header Modal --}}
            <div class="p-5 sm:p-6 bg-forest-950 text-white flex items-center justify-between border-b border-forest-800">
                <div>
                    <span class="text-[10px] uppercase tracking-wider font-bold text-sumedang-amber" x-text="selectedAlbum ? selectedAlbum.tanggal : ''"></span>
                    <h3 class="text-lg sm:text-xl font-black text-white mt-0.5" x-text="selectedAlbum ? selectedAlbum.judul : ''"></h3>
                    <p class="text-xs text-gray-300 mt-1 line-clamp-2" x-show="selectedAlbum && selectedAlbum.deskripsi" x-text="selectedAlbum ? selectedAlbum.deskripsi : ''"></p>
                </div>
                <button type="button" @click="closeAlbum()" class="w-10 h-10 rounded-full bg-forest-800 hover:bg-forest-700 text-white flex items-center justify-center transition shrink-0 ml-4">
                    ✕
                </button>
            </div>

            {{-- Body Modal: Grid Foto --}}
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)] bg-slate-100 dark:bg-forest-950">
                <template x-if="selectedAlbum && selectedAlbum.photos && selectedAlbum.photos.length > 0">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <template x-for="(photoUrl, pIndex) in selectedAlbum.photos" :key="pIndex">
                            <div class="group relative rounded-2xl overflow-hidden shadow-md bg-forest-900 aspect-[4/3] cursor-pointer"
                                 @click="selectedImage = photoUrl">
                                <img :src="photoUrl"
                                     :alt="selectedAlbum.judul + ' Foto ' + (pIndex + 1)"
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-forest-950/20 group-hover:bg-forest-950/40 transition flex items-center justify-center">
                                    <span class="w-10 h-10 rounded-full bg-sumedang-amber text-forest-950 flex items-center justify-center opacity-0 group-hover:opacity-100 transform scale-75 group-hover:scale-100 transition shadow-lg font-bold">
                                        🔍
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="!selectedAlbum || !selectedAlbum.photos || selectedAlbum.photos.length === 0">
                    <div class="p-8 text-center text-gray-500">
                        <p class="text-sm font-semibold">Tidak ada foto yang tersimpan dalam album ini.</p>
                    </div>
                </template>
            </div>

            {{-- Footer Modal --}}
            <div class="p-4 bg-white dark:bg-forest-900 border-t border-gray-100 dark:border-forest-800 flex justify-between items-center text-xs">
                <span class="text-gray-500 dark:text-gray-400 font-semibold"
                      x-text="(selectedAlbum && selectedAlbum.photos ? selectedAlbum.photos.length : 0) + ' Foto Tersedia'"></span>
                <button type="button" @click="closeAlbum()" class="px-5 py-2 rounded-xl bg-forest-800 text-white font-bold hover:bg-forest-700 transition">
                    Tutup Album
                </button>
            </div>

        </div>

    </div>

    {{-- ==================== FULLSCREEN SINGLE IMAGE VIEWER ==================== --}}
    <div x-show="selectedImage" x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click="selectedImage = null"
         class="fixed inset-0 z-60 bg-black/95 flex items-center justify-center p-4">
        
        <button type="button" @click="selectedImage = null" class="absolute top-5 right-5 w-11 h-11 rounded-full bg-white/20 hover:bg-white/40 text-white font-black text-lg flex items-center justify-center transition">
            ✕
        </button>

        <img :src="selectedImage"
             alt="Foto Dokumentasi Penuh"
             class="max-w-full max-h-[90vh] object-contain rounded-2xl shadow-2xl"
             @click.stop>
    </div>

</div>
@endsection