<footer class="bg-forest-950 text-white border-t border-forest-900 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 pb-12 border-b border-forest-900">

            {{-- Kolom 1: Profil Sekolah & Legalitas (4 Kolom) --}}
            <div class="lg:col-span-4 space-y-4">
                <div class="flex items-center gap-3">
                    <img src="{{ $profile?->logo_url ?? asset('images/logo-official.png') }}"
                         alt="Logo SDN Cinanggerang I"
                         class="h-12 w-auto object-contain"
                         onerror="this.onerror=null; this.src='{{ asset('images/logo-official.png') }}';">
                    <div>
                        <h3 class="font-extrabold text-lg text-white leading-tight">SD NEGERI CINANGGERANG I</h3>
                        <p class="text-xs text-emerald-400 font-semibold">NPSN: {{ $profile?->npsn ?? '20208801' }}</p>
                    </div>
                </div>

                <p class="text-xs text-gray-300 leading-relaxed max-w-sm">
                    Satuan pendidikan dasar negeri di bawah naungan Dinas Pendidikan Pemerintah Kabupaten Sumedang. Berkomitmen mewujudkan peserta didik berakhlak mulia, cerdas, terampil, mandiri, dan berwawasan lingkungan hidup.
                </p>

                {{-- Lembaga Pembina & Naungan Resmi (Tut Wuri & Pemkab Sumedang) --}}
                <div class="pt-3 border-t border-forest-900/80">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-emerald-400 block mb-2">Instansi Pembina & Naungan Resmi:</span>
                    <div class="flex flex-wrap items-center gap-2.5">
                        {{-- Logo Tut Wuri Handayani --}}
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-forest-900/80 border border-forest-800 shadow-sm">
                            <img src="{{ $profile?->logo_tutwuri_url ?? 'https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg' }}"
                                 alt="Tut Wuri Handayani"
                                 class="h-6 w-auto object-contain"
                                 onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg';">
                            <span class="text-[11px] font-medium text-gray-200">Kemendikdasmen RI</span>
                        </div>

                        {{-- Logo Pemkab Sumedang --}}
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-forest-900/80 border border-forest-800 shadow-sm">
                            <img src="{{ $profile?->logo_pemda_url ?? 'https://upload.wikimedia.org/wikipedia/commons/5/52/Lambang_Kabupaten_Sumedang.png' }}"
                                 alt="Pemerintah Kabupaten Sumedang"
                                 class="h-6 w-auto object-contain"
                                 onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/5/52/Lambang_Kabupaten_Sumedang.png';">
                            <span class="text-[11px] font-medium text-gray-200">Disdik Kab. Sumedang</span>
                        </div>
                    </div>

                    {{-- Inisiator Digitalisasi Satuan Pendidikan --}}
                    <div class="pt-3 mt-3 border-t border-forest-900/80 space-y-1">
                        <a href="{{ route('pengembang') }}" class="inline-flex items-center gap-2 text-xs text-gray-300 hover:text-sumedang-amber transition group">
                            <svg class="w-3.5 h-3.5 text-emerald-400 group-hover:text-sumedang-amber transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Inisiator: <strong class="text-white group-hover:text-sumedang-amber font-semibold">Agi Hamdani, M.Pd.</strong></span>
                        </a>
                        <p class="text-[11px] text-gray-400 flex items-center gap-1.5 pl-0.5">
                            <span class="text-xs">📍</span>
                            <span>Sumedang – Jawa Barat, Indonesia</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Kolom 2: Navigasi Halaman Cepat (2 Kolom) --}}
            <div class="lg:col-span-2 space-y-3">
                <h4 class="font-bold text-sm uppercase tracking-wider text-sumedang-amber">Menu Halaman</h4>
                <ul class="text-xs text-gray-300 space-y-2">
                    <li><a href="{{ route('home') }}" class="hover:text-sumedang-amber transition">Beranda Utama</a></li>
                    <li><a href="{{ route('profil') }}" class="hover:text-sumedang-amber transition">Profil Sekolah</a></li>
                    <li><a href="{{ route('guru') }}" class="hover:text-sumedang-amber transition">Direktori Guru</a></li>
                    <li><a href="{{ route('rombel') }}" class="hover:text-sumedang-amber transition">Rombongan Belajar</a></li>
                    <li><a href="{{ route('siswa') }}" class="hover:text-sumedang-amber transition">Daftar Siswa & NISN</a></li>
                    <li><a href="{{ route('warta') }}" class="hover:text-sumedang-amber transition">Warta & Berita</a></li>
                    <li><a href="{{ route('pengumuman') }}" class="hover:text-sumedang-amber transition">Pengumuman Kedinasan</a></li>
                    <li><a href="{{ route('dokumentasi') }}" class="hover:text-sumedang-amber transition">Galeri & Video</a></li>
                    <li><a href="{{ route('perangkat-ajar') }}" class="hover:text-sumedang-amber transition">Bank Perangkat Ajar</a></li>
                    <li><a href="{{ route('pkks') }}" class="hover:text-sumedang-amber transition">E-PKKS Digital</a></li>
                    <li><a href="{{ route('ppdb') }}" class="hover:text-sumedang-gold font-semibold text-sumedang-amber transition">PPDB Online</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Kontak & Operasional (3 Kolom) --}}
            <div class="lg:col-span-3 space-y-3">
                <h4 class="font-bold text-sm uppercase tracking-wider text-sumedang-amber">Kontak & Alamat</h4>
                <div class="text-xs text-gray-300 space-y-2.5">
                    <p class="flex items-start gap-2">
                        <span class="text-sumedang-amber font-bold">📍</span>
                        <span>{{ $profile?->alamat ?? 'Jln. Desa Cinanggerang, RT 03 / RW 04, Desa Cinanggerang, Kec. Pamulihan, Kab. Sumedang, Jawa Barat 45362' }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="text-sumedang-amber font-bold">📞</span>
                        <span>{{ $profile?->telepon ?? '0813-2102-1585' }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="text-sumedang-amber font-bold">✉️</span>
                        <span>{{ $profile?->email ?? 'sdncinanggerang1@gmail.com' }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <span class="text-sumedang-amber font-bold">⏰</span>
                        <span>Senin - Sabtu: 07:00 - 12:30 WIB</span>
                    </p>
                </div>
            </div>

            {{-- Kolom 4: Peta Lokasi Google Maps Interaktif (3 Kolom) --}}
            <div class="lg:col-span-3 space-y-3">
                <h4 class="font-bold text-sm uppercase tracking-wider text-sumedang-amber">Lokasi Satuan Pendidikan</h4>
                <div class="w-full h-40 rounded-xl overflow-hidden border border-forest-800 shadow-inner">
                    <iframe
                        class="w-full h-full border-0"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://maps.google.com/maps?q={{ $profile?->latitude ?? -6.89437395 }},{{ $profile?->longitude ?? 107.87197894 }}&hl=id&z=16&output=embed">
                    </iframe>
                </div>
                <a href="https://www.google.com/maps?q={{ $profile?->latitude ?? -6.89437395 }},{{ $profile?->longitude ?? 107.87197894 }}"
                   target="_blank"
                   class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-emerald-300 hover:text-white transition">
                    <span>Buka Rute di Aplikasi Google Maps</span>
                    <span>&rarr;</span>
                </a>
            </div>

        </div>

        {{-- Bar Statistik Pengunjung Web --}}
        @php
            $visitorStats = \App\Services\VisitorCounterService::getStats();
        @endphp
        <div class="py-6 border-b border-forest-900/80">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-4 p-4 rounded-2xl bg-forest-900/60 border border-forest-800/80 shadow-lg backdrop-blur-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-forest-800 border border-forest-700 flex items-center justify-center text-sumedang-amber shrink-0 shadow-inner">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <h5 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                            <span>Statistik Pengunjung Portal</span>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Live Aktif
                            </span>
                        </h5>
                        <p class="text-[11px] text-gray-400">Pencatatan statistik lalu lintas kunjungan situs resmi SDN Cinanggerang I</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2 w-full lg:w-auto">
                    {{-- Sedang Online --}}
                    <div class="px-3 py-2 rounded-xl bg-forest-950/70 border border-forest-800 text-center">
                        <span class="text-[10px] text-gray-400 block font-medium">Online</span>
                        <span class="text-sm font-extrabold text-emerald-400 flex items-center justify-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            {{ number_format($visitorStats['online'], 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Hari Ini --}}
                    <div class="px-3 py-2 rounded-xl bg-forest-950/70 border border-forest-800 text-center">
                        <span class="text-[10px] text-gray-400 block font-medium">Hari Ini</span>
                        <span class="text-sm font-extrabold text-white">{{ number_format($visitorStats['today'], 0, ',', '.') }}</span>
                    </div>

                    {{-- Kemarin --}}
                    <div class="px-3 py-2 rounded-xl bg-forest-950/70 border border-forest-800 text-center">
                        <span class="text-[10px] text-gray-400 block font-medium">Kemarin</span>
                        <span class="text-sm font-extrabold text-gray-300">{{ number_format($visitorStats['yesterday'], 0, ',', '.') }}</span>
                    </div>

                    {{-- Bulan Ini --}}
                    <div class="px-3 py-2 rounded-xl bg-forest-950/70 border border-forest-800 text-center">
                        <span class="text-[10px] text-gray-400 block font-medium">Bulan Ini</span>
                        <span class="text-sm font-extrabold text-sumedang-amber">{{ number_format($visitorStats['month'], 0, ',', '.') }}</span>
                    </div>

                    {{-- Total Pengunjung --}}
                    <div class="px-3 py-2 rounded-xl bg-forest-950/70 border border-forest-800 text-center">
                        <span class="text-[10px] text-gray-400 block font-medium">Pengunjung</span>
                        <span class="text-sm font-extrabold text-emerald-300">{{ number_format($visitorStats['total'], 0, ',', '.') }}</span>
                    </div>

                    {{-- Total Hits / Tayangan --}}
                    <div class="px-3 py-2 rounded-xl bg-forest-950/70 border border-forest-800 text-center">
                        <span class="text-[10px] text-gray-400 block font-medium">Total Hits</span>
                        <span class="text-sm font-extrabold text-sumedang-gold">{{ number_format($visitorStats['total_hits'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Copyright & Hak Cipta --}}
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500">
            <p>© {{ date('Y') }} <strong>SD Negeri Cinanggerang I</strong>. Hak Cipta Dilindungi Undang-Undang.</p>
            <div class="flex items-center gap-4">
                <a href="{{ url('/admin') }}" class="hover:text-sumedang-amber transition">Login Operator / Guru</a>
                <span>•</span>
                <a href="{{ route('home') }}" class="hover:text-sumedang-amber transition">Kembali ke Beranda ↑</a>
            </div>
        </div>
    </div>
</footer>
