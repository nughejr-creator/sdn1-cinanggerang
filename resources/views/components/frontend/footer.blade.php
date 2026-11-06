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
                    <li><a href="{{ route('dokumentasi') }}" class="hover:text-sumedang-amber transition">Galeri & Video</a></li>
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

        {{-- Copyright & Hak Cipta --}}
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500">
            <p>© {{ date('Y') }} <strong>SD Negeri Cinanggerang I</strong>. Hak Cipta Dilindungi Undang-Undang.</p>
            <div class="flex items-center gap-4">
                <a href="https://drive.google.com/drive/folders/14OBTBm-pTOgP-YJ7Wffzz6VCqxqwyxoG?usp=sharing" target="_blank" class="hover:text-sumedang-amber transition">📁 Arsip Foto Drive</a>
                <span>•</span>
                <a href="{{ url('/admin') }}" class="hover:text-sumedang-amber transition">Login Operator / Guru</a>
                <span>•</span>
                <a href="{{ route('home') }}" class="hover:text-sumedang-amber transition">Kembali ke Beranda ↑</a>
            </div>
        </div>
    </div>
</footer>
