<section id="profil" class="py-10 md:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-forest-900/60 rounded-3xl border border-gray-100 dark:border-forest-800 py-8 px-6 md:p-10 shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

            {{-- KOLOM KIRI (lg:col-span-4): FOTO KEPALA SEKOLAH DENGAN FRAME ROUNDED-2XL & AKSEN BORDER EMAS --}}
            <div class="lg:col-span-4 flex justify-center">
                <div class="relative w-full max-w-[260px] sm:max-w-[280px]">
                    <div class="relative rounded-2xl overflow-hidden shadow-md border-2 border-amber-400 bg-forest-950 aspect-[4/5] group">
                        @if(!empty($profile?->foto_kepala_sekolah))
                            <img src="{{ asset('storage/' . $profile->foto_kepala_sekolah) }}"
                                 alt="Kepala Sekolah SDN Cinanggerang I"
                                 class="w-full h-full object-cover object-top transition-transform duration-300 group-hover:scale-105">
                        @else
                            {{-- Placeholder foto formal berwibawa --}}
                            <div class="w-full h-full bg-gradient-to-b from-forest-800 to-forest-950 flex flex-col items-center justify-center p-6 text-center text-white">
                                <div class="w-20 h-20 rounded-full bg-forest-700/60 flex items-center justify-center text-sumedang-amber mb-3 border border-forest-600">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-300">Kepala Sekolah</p>
                                <p class="text-sm font-bold text-sumedang-amber mt-1">Aep Yayat, S.Pd</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">NIP. 197206102014111001</p>
                            </div>
                        @endif

                        {{-- Label Nama Mengambang Elegan --}}
                        <div class="absolute bottom-0 inset-x-0 p-3.5 bg-gradient-to-t from-forest-950 via-forest-950/85 to-transparent text-white">
                            <span class="text-[9px] uppercase tracking-wider text-sumedang-amber font-bold block">Kepala Sekolah</span>
                            <h4 class="text-sm font-bold leading-tight">{{ $profile?->kepala_sekolah ?? 'Aep Yayat, S.Pd' }}</h4>
                            <p class="text-[10px] text-gray-300 font-mono">NIP. {{ $profile?->nip_kepala_sekolah ?? '197206102014111001' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (lg:col-span-8): TEKS SAMBUTAN & KOMITMEN KARAKTER --}}
            <div class="lg:col-span-8 space-y-4">
                <div>
                    <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 uppercase tracking-wider">
                        SAMBUTAN KEPALA SEKOLAH
                    </span>
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-800 dark:text-white leading-snug mt-2">
                        Membangun Generasi Cerdas, Berakhlak & Adaptif Teknologi
                    </h2>
                </div>

                {{-- Teks Pembuka & Isi Sambutan --}}
                <div class="text-sm md:text-base text-slate-600 dark:text-gray-300 leading-relaxed space-y-3 mt-4">
                    @if(!empty($profile?->sambutan_kepala_sekolah))
                        {!! $profile->sambutan_kepala_sekolah !!}
                    @else
                        <p>
                            <em>Assalamu’alaikum Warahmatullahi Wabarakatuh, Sampurasun.</em>
                        </p>
                        <p>
                            Selamat datang di Portal Resmi <strong>SD Negeri Cinanggerang I</strong>. Website ini kami hadirkan sebagai wujud transparansi mutu, keterbukaan informasi publik, serta transformasi tata kelola pendidikan berbasis digital di era Kurikulum Merdeka.
                        </p>
                        <p>
                            Kami percaya bahwa setiap anak memiliki keunikan dan potensi emas. Dengan bimbingan 11 tenaga pendidik berdedikasi serta kemitraan yang erat bersama orang tua dan komite, kami berkomitmen menciptakan ruang belajar yang menyenangkan, aman, berkarakter Pancasila, dan berwawasan lingkungan hidup di bumi Pamulihan, Sumedang.
                        </p>
                    @endif
                </div>

                {{-- Komitmen Mutu & Karakter Siswa (Highlight 4 Pilar) --}}
                <div class="pt-5 border-t border-gray-100 dark:border-forest-800">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-forest-900 dark:text-emerald-300 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-sumedang-amber animate-pulse"></span>
                            <span>Komitmen Mutu & Karakter Siswa</span>
                        </h4>
                        <a href="{{ route('profil') }}#visi-misi" class="inline-flex items-center gap-1.5 text-xs font-extrabold text-emerald-700 hover:text-sumedang-amber dark:text-emerald-400 transition-colors group">
                            <span>Visi & 7 Misi Lengkap</span>
                            <svg class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-forest-50/70 dark:bg-forest-800/40 border border-forest-100 dark:border-forest-700/50 hover:bg-forest-100/70 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-emerald-600/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21z"></path></svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-200">Karakter Religius & Ibadah Kontekstual</span>
                        </div>
                        <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-forest-50/70 dark:bg-forest-800/40 border border-forest-100 dark:border-forest-700/50 hover:bg-forest-100/70 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-blue-600/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-200">Penalaran Kritis Model PBL & PjBL</span>
                        </div>
                        <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-forest-50/70 dark:bg-forest-800/40 border border-forest-100 dark:border-forest-700/50 hover:bg-forest-100/70 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-teal-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-teal-600/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-200">Literasi Digital & Ramah Lingkungan</span>
                        </div>
                        <div class="flex items-center gap-2.5 p-2.5 rounded-xl bg-forest-50/70 dark:bg-forest-800/40 border border-forest-100 dark:border-forest-700/50 hover:bg-forest-100/70 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-amber-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-amber-600/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-200">Pola Hidup Sehat & Jiwa Mandiri</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>
