<section id="beranda" class="relative overflow-hidden bg-forest-950 text-white min-h-[460px] lg:min-h-[500px] flex items-center">
    {{-- Background Image dengan Overlay Gradient --}}
    <div class="absolute inset-0 z-0">
        @if(!empty($profile?->foto_bangunan) || !empty($profile?->banner_hero))
            <img src="{{ asset('storage/' . ($profile->banner_hero ?? $profile->foto_bangunan)) }}"
                 alt="Gedung SDN Cinanggerang I"
                 class="w-full h-full object-cover object-center filter brightness-[0.38] scale-105 animate-pulse duration-[10000ms]">
        @else
            <div class="w-full h-full bg-gradient-to-br from-forest-950 via-forest-900 to-sumedang-teal"></div>
        @endif
        {{-- Pola Gradient Overlay Sumedang Green --}}
        <div class="absolute inset-0 bg-gradient-to-r from-forest-950/95 via-forest-900/85 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-forest-950 via-transparent to-transparent"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">
        <div class="max-w-3xl">

            {{-- Badge Status Resmi --}}
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-forest-800/80 border border-emerald-500/30 text-emerald-300 text-xs font-semibold mb-4 backdrop-blur-sm shadow-sm">
                <span class="w-2 h-2 rounded-full bg-sumedang-amber animate-ping"></span>
                <span>Satuan Pendidikan Negeri Resmi • NPSN {{ $profile?->npsn ?? '20208801' }}</span>
            </div>

            {{-- Judul Utama Seimbang --}}
            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight mb-3">
                Pendidikan Unggul, Berkarakter & Ramah Anak
            </h1>

            <div class="text-lg sm:text-xl font-bold text-sumedang-amber mb-3 tracking-wide flex items-center gap-2">
                <span>SD NEGERI CINANGGERANG I</span>
                <span class="text-xs px-2 py-0.5 rounded bg-emerald-900/80 text-emerald-200 border border-emerald-700">Pamulihan, Sumedang</span>
            </div>

            {{-- Blok Visi Bento Box Ringkas --}}
            <div class="bg-black/30 backdrop-blur-sm border-l-4 border-amber-400 p-4 rounded-r-xl max-w-3xl text-sm md:text-base text-emerald-50 mt-4 mb-6 shadow-sm">
                <span class="text-[11px] font-bold uppercase tracking-wider text-sumedang-amber block mb-1">Visi Satuan Pendidikan:</span>
                <p class="italic leading-relaxed">
                    "{!! strip_tags($profile?->visi) ?: 'Terwujudnya Peserta Didik yang Berakhlak Mulia, Cerdas, Terampil, Mandiri, dan Berwawasan Lingkungan.' !!}"
                </p>
            </div>

            {{-- Dual CTA Buttons --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3.5">
                {{-- Tombol Primer Emas (PPDB) --}}
                <a href="{{ route('ppdb') }}" class="inline-flex items-center justify-center gap-2.5 px-6 py-3 rounded-xl bg-sumedang-amber hover:bg-sumedang-gold text-forest-950 font-extrabold text-sm shadow-lg shadow-sumedang-amber/25 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 text-forest-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    <span>Layanan PPDB Online 2026/2027</span>
                </a>

                {{-- Tombol Ghost Kaca (E-PKKS Digital) --}}
                <a href="{{ route('pkks') }}" class="inline-flex items-center justify-center gap-2.5 px-6 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm border border-white/20 backdrop-blur-md transition-all">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Showcase Portofolio E-PKKS</span>
                </a>
            </div>

            {{-- Quick Presensi Link Khusus Guru --}}
            <div class="mt-4 flex items-center gap-2 text-xs text-gray-300">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-gray-400">Khusus Pendidik:</span>
                <a href="{{ route('presensi.index') }}" class="font-bold text-sumedang-amber hover:text-white transition inline-flex items-center gap-1">
                    <span>📍 Buka Presensi GPS Digital (Datang & Pulang)</span>
                    <span>&rarr;</span>
                </a>
            </div>

            {{-- Pilar Nilai Sekolah Ringkas --}}
            <div class="mt-8 pt-5 border-t border-forest-800/60 grid grid-cols-3 gap-4 text-center sm:text-left">
                <div>
                    <p class="text-[11px] text-gray-400 font-medium">Kurikulum</p>
                    <p class="text-xs sm:text-sm font-bold text-white mt-0.5">Kurikulum Merdeka</p>
                </div>
                <div>
                    <p class="text-[11px] text-gray-400 font-medium">Fokus Karakter</p>
                    <p class="text-xs sm:text-sm font-bold text-white mt-0.5">Profil Pelajar Pancasila</p>
                </div>
                <div>
                    <p class="text-[11px] text-gray-400 font-medium">Status Akreditasi</p>
                    <p class="text-xs sm:text-sm font-bold text-emerald-300 mt-0.5">Negeri Terakreditasi</p>
                </div>
            </div>

        </div>
    </div>
</section>
