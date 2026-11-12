@extends('layouts.frontend')

@section('title', 'Profil & Visi Misi - SD Negeri Cinanggerang I Sumedang')
@section('meta_description', 'Profil lengkap, sambutan kepala sekolah, visi, misi, dan pilar keunggulan pendidikan SD Negeri Cinanggerang I, Pamulihan, Sumedang.')
@section('canonical_url', route('profil'))

@section('content')
    {{-- Header Banner Halaman Ringkas --}}
    <div class="bg-forest-950 text-white py-8 md:py-10 border-b border-forest-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:20px_20px] opacity-30 pointer-events-none"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="inline-block px-3 py-1 rounded-full bg-forest-800 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-2">
                Tentang Satuan Pendidikan
            </span>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight">
                Profil & Visi Misi Sekolah
            </h1>
            <p class="text-xs sm:text-sm text-gray-300 mt-1 max-w-2xl leading-relaxed">
                Mengenal lebih dekat sejarah, kepemimpinan, dan arah pengembangan mutu pendidikan di SD Negeri Cinanggerang I.
            </p>
        </div>
    </div>

    {{-- Sambutan Hangat & Profil Kepala Sekolah --}}
    @include('components.frontend.headmaster')

    @php
        $rawMisi = $profile?->misi ?? '';
        $misiIntro = '';
        $misiItems = [];

        // Parse HTML bertag <li> atau teks biasa bernomor / per baris
        if (str_contains($rawMisi, '<li')) {
            if (preg_match('/^(.*?)(<(?:ol|ul)>)/is', $rawMisi, $introMatch)) {
                $misiIntro = trim(strip_tags($introMatch[1]));
            }
            preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $rawMisi, $matches);
            if (!empty($matches[1])) {
                $misiItems = array_map(fn($item) => trim(strip_tags($item, '<strong><em><b><i><u>')), $matches[1]);
            }
        } else {
            $lines = preg_split('/\r\n|\r|\n/', strip_tags($rawMisi));
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (empty($trimmed)) continue;

                if (str_starts_with(strtolower($trimmed), 'dalam rangka mewujudkan') || str_contains(strtolower($trimmed), 'memiliki misi sebagai berikut')) {
                    $misiIntro = $trimmed;
                    continue;
                }

                $cleaned = preg_replace('/^(\d+[\.\)]\s*|\-\s*|\*\s*)/', '', $trimmed);
                if (!empty($cleaned)) {
                    $misiItems[] = $cleaned;
                }
            }
        }

        // Fallback default 7 poin misi sesuai kurikulum resmi
        if (empty($misiItems)) {
            $misiItems = [
                'Menyelenggarakan pembiasaan ibadah, penguatan pendidikan karakter, dan kegiatan keagamaan yang kontekstual di sekolah untuk membentuk murid yang beriman dan bertakwa kepada Tuhan YME.',
                'Melaksanakan pembelajaran dengan model PBL dan PjBL untuk meningkatkan kemampuan murid yang bernalar kritis.',
                'Mengembangkan pembelajaran dengan mengoptimalkan teknologi digital, serta pembelajaran berbasis lingkungan untuk mewujudkan murid yang kreatif dan bertanggung jawab.',
                'Mengembangkan program literasi dengan pojok baca dan numerasi melalui pembelajaran yang aktif, diskusi terbuka dan presentasi untuk meningkatkan kemampuan komunikasi siswa.',
                'Menyelenggarakan kemitraan pembelajaran untuk mewujudkan lingkungan sekolah yang bersih, sehat, aman, hijau, nyaman, dan ramah lingkungan.',
                'Menyelenggarakan kegiatan olahraga dan pola makan sehat dan bergizi serta pengelolaan lingkungan yang sehat dan bersih untuk mewujudkan murid yang sehat jasmani dan rohani.',
                'Menyelenggarakan program kewirausahaan untuk meningkatkan kemandirian siswa.'
            ];
        }

        $cardStyles = [
            [
                'category' => 'Karakter & Spiritual',
                'bg_icon' => 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-700/60',
                'badge' => 'bg-emerald-700 text-white',
                'border' => 'hover:border-emerald-500 hover:shadow-emerald-500/10',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21z"></path></svg>'
            ],
            [
                'category' => 'Penalaran Kritis & Problem Solving',
                'bg_icon' => 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-700/60',
                'badge' => 'bg-blue-700 text-white',
                'border' => 'hover:border-blue-500 hover:shadow-blue-500/10',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>'
            ],
            [
                'category' => 'Transformasi Digital & Lingkungan',
                'bg_icon' => 'bg-teal-100 dark:bg-teal-900/50 text-teal-700 dark:text-teal-300 border-teal-200 dark:border-teal-700/60',
                'badge' => 'bg-teal-700 text-white',
                'border' => 'hover:border-teal-500 hover:shadow-teal-500/10',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>'
            ],
            [
                'category' => 'Pojok Baca & Literasi-Numerasi',
                'bg_icon' => 'bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-700/60',
                'badge' => 'bg-amber-600 text-white',
                'border' => 'hover:border-amber-500 hover:shadow-amber-500/10',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>'
            ],
            [
                'category' => 'Sekolah Sehat & Ramah Anak',
                'bg_icon' => 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-700/60',
                'badge' => 'bg-indigo-700 text-white',
                'border' => 'hover:border-indigo-500 hover:shadow-indigo-500/10',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>'
            ],
            [
                'category' => 'Kebugaran Jasmani & Nutrisi Sehat',
                'bg_icon' => 'bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-700/60',
                'badge' => 'bg-rose-600 text-white',
                'border' => 'hover:border-rose-500 hover:shadow-rose-500/10',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>'
            ],
            [
                'category' => 'Kemandirian & Kewirausahaan Siswa',
                'bg_icon' => 'bg-orange-100 dark:bg-orange-900/50 text-orange-700 dark:text-orange-300 border-orange-200 dark:border-orange-700/60',
                'badge' => 'bg-orange-600 text-white',
                'border' => 'hover:border-orange-500 hover:shadow-orange-500/10',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>'
            ],
        ];
    @endphp

    {{-- Seksi Dedikasi: Visi & Misi Lengkap Satuan Pendidikan --}}
    <section id="visi-misi" class="py-10 md:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 scroll-mt-24">

        {{-- Header Judul Seksi --}}
        <div class="text-center max-w-3xl mx-auto mb-8">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-forest-100 dark:bg-forest-800 text-forest-800 dark:text-emerald-300 text-xs font-bold uppercase tracking-wider mb-2 border border-forest-200 dark:border-forest-700">
                <svg class="w-3.5 h-3.5 text-sumedang-amber" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <span>Haluan Mutu & Pedoman Kurikulum</span>
            </span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-forest-950 dark:text-white tracking-tight leading-tight">
                Visi & 7 Misi Strategis Sekolah
            </h2>
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-2 leading-relaxed">
                Komitmen nyata seluruh civitas akademika SD Negeri Cinanggerang I dalam melahirkan lulusan berkarakter Pancasila, literat teknologi, berdaya saing, dan sehat seutuhnya.
            </p>
        </div>

        {{-- 1. SHOWCASE VISI SEKOLAH --}}
        <div class="relative rounded-3xl bg-gradient-to-br from-forest-950 via-forest-900 to-forest-950 text-white p-6 sm:p-8 md:p-10 shadow-xl border-2 border-emerald-500/30 overflow-hidden mb-10">
            {{-- Watermark Ornamen Logo Sekolah / Tut Wuri Handayani di Background --}}
            <div class="absolute -right-12 -bottom-12 w-64 h-64 sm:w-80 sm:h-80 opacity-5 pointer-events-none select-none">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg" alt="Ornamen Tut Wuri" class="w-full h-full object-contain filter invert">
            </div>

            {{-- Ornamen Lingkaran Sinar Glow --}}
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-10 w-80 h-80 bg-sumedang-amber/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 pb-6 border-b border-white/10">
                    <div class="inline-flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-forest-800/80 border border-emerald-400/40 p-2 flex items-center justify-center shadow-inner">
                            <img src="{{ $profile?->logo_url ?? asset('images/logo-official.png') }}"
                                 alt="Logo SDN Cinanggerang I"
                                 class="w-full h-full object-contain"
                                 onerror="this.onerror=null; this.src='{{ asset('images/logo-official.png') }}';">
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-sumedang-amber block">Arah & Cita-Cita Luhur</span>
                            <h3 class="text-xl sm:text-2xl font-black text-white">Visi Satuan Pendidikan</h3>
                        </div>
                    </div>

                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-emerald-300 text-xs font-semibold backdrop-blur-md border border-white/10 self-start sm:self-auto">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>Resmi KOSP 2026/2027</span>
                    </div>
                </div>

                {{-- Teks Pernyataan Visi Resmi --}}
                <div class="relative my-6 pl-6 sm:pl-8 border-l-4 border-sumedang-amber">
                    <svg class="absolute -top-3 -left-3 w-8 h-8 text-sumedang-amber/40 pointer-events-none" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    
                    <p class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-white leading-relaxed tracking-wide">
                        "{!! strip_tags($profile?->visi) ?: 'Terwujudnya murid yang beriman dan bertakwa kepada Tuhan YME, mampu berkolaborasi dan berkreativitas, memiliki kemampuan kewargaan, penalaran kritis, kemandirian dan komunikasi yang baik serta memiliki kesadaran hidup sehat.' !!}"
                    </p>
                    <p class="text-xs sm:text-sm text-gray-300 mt-3 font-medium">
                        — SD Negeri Cinanggerang I, Kec. Pamulihan, Kab. Sumedang
                    </p>
                </div>

                {{-- 5 Pilar Karakter Utama dalam Visi --}}
                <div class="pt-6 border-t border-white/10">
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-300 mb-3">5 Dimensi Inti Profil Pelajar:</p>
                    <div class="flex flex-wrap gap-2 sm:gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/15 text-xs font-semibold text-gray-100 border border-white/10 transition shadow-sm">
                            <span>🕌</span> Beriman & Bertakwa
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/15 text-xs font-semibold text-gray-100 border border-white/10 transition shadow-sm">
                            <span>🤝</span> Kolaboratif & Komunikatif
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/15 text-xs font-semibold text-gray-100 border border-white/10 transition shadow-sm">
                            <span>💡</span> Bernalar Kritis
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/15 text-xs font-semibold text-gray-100 border border-white/10 transition shadow-sm">
                            <span>🎨</span> Kreativitas & Mandiri
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/15 text-xs font-semibold text-gray-100 border border-white/10 transition shadow-sm">
                            <span>🏃‍♂️</span> Kesadaran Hidup Sehat
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. SHOWCASE 7 MISI STRATEGIS SEKOLAH --}}
        <div>
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 text-xs font-bold uppercase tracking-wider mb-2 border border-emerald-200 dark:border-emerald-800/40">
                        Langkah Strategis Nyata
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-black text-forest-950 dark:text-white">
                        7 Pilar Misi Pendidikan
                    </h3>
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 font-medium flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Terintegrasi Kurikulum Merdeka</span>
                </div>
            </div>

            {{-- Pengantar Misi (Jika Ada di Database) --}}
            @if(!empty($misiIntro))
                <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-800/40 text-xs sm:text-sm text-amber-900 dark:text-amber-200 leading-relaxed flex items-start gap-3 shadow-sm">
                    <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="pt-0.5 font-medium italic">
                        "{{ $misiIntro }}"
                    </p>
                </div>
            @endif

            {{-- Grid Kartu Misi Interaktif Berornamen --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($misiItems as $index => $item)
                    @php
                        $style = $cardStyles[$index % count($cardStyles)];
                    @endphp
                    <div class="group bg-white dark:bg-forest-900/60 rounded-3xl p-6 sm:p-7 border border-gray-100 dark:border-forest-800 shadow-sm hover:shadow-xl {{ $style['border'] }} transition-all duration-300 flex flex-col justify-between transform hover:-translate-y-1.5">
                        <div>
                            {{-- Baris Atas: Ikon + Nomor Urut --}}
                            <div class="flex items-center justify-between mb-5">
                                <div class="w-12 h-12 rounded-2xl {{ $style['bg_icon'] }} border flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    {!! $style['icon'] !!}
                                </div>
                                <span class="px-3 py-1 rounded-full {{ $style['badge'] }} text-xs font-black tracking-wider shadow-sm">
                                    0{{ $index + 1 }}
                                </span>
                            </div>

                            {{-- Kategori Pilar --}}
                            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 block mb-2">
                                Pilar Ke-{{ $index + 1 }} • {{ $style['category'] }}
                            </span>

                            {{-- Teks Isi Misi --}}
                            <p class="text-sm sm:text-base font-semibold text-gray-800 dark:text-gray-100 leading-relaxed">
                                {!! $item !!}
                            </p>
                        </div>

                        {{-- Garis Aksen Bawah --}}
                        <div class="mt-6 pt-4 border-t border-gray-100 dark:border-forest-800 flex items-center justify-between text-[11px] text-gray-400 dark:text-gray-400">
                            <span class="flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Standar Nasional Pendidikan
                            </span>
                            <span class="opacity-0 group-hover:opacity-100 text-emerald-600 dark:text-emerald-400 font-bold transition-opacity">
                                Terverifikasi ✓
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Footer Bukti Komitmen KOSP --}}
            <div class="mt-12 p-6 rounded-3xl bg-forest-50 dark:bg-forest-900/40 border border-forest-100 dark:border-forest-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-forest-900 text-sumedang-amber flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-forest-950 dark:text-white">Dokumen Resmi Satuan Pendidikan</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Kurikulum Operasional Satuan Pendidikan (KOSP) SDN Cinanggerang I Tahun Ajaran 2026/2027</p>
                    </div>
                </div>

                <a href="{{ route('guru') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-forest-900 hover:bg-forest-800 text-white text-xs font-bold shadow-md transition transform hover:-translate-y-0.5 shrink-0">
                    <span>Lihat Tenaga Pendidik & Pengajar</span>
                    <svg class="w-4 h-4 text-sumedang-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

    </section>
@endsection
