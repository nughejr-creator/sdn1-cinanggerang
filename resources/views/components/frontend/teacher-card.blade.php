<section id="guru" class="py-10 md:py-12 bg-forest-950 text-white relative overflow-hidden">
    {{-- Hiasan Latar --}}
    <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:16px_16px] opacity-25 pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-8">
            <span class="inline-block px-3 py-1 rounded-full bg-forest-800 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-2">
                Pendidik & Tenaga Kependidikan
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight">
                Direktori Guru & Tendik Resmi
            </h2>
            <p class="text-xs sm:text-sm text-gray-300 mt-2">
                Dipimpin oleh 11 tenaga profesional berdedikasi tinggi membimbing putra-putri SDN Cinanggerang I
            </p>
        </div>

        {{-- GRID GURU & TENDIK (11 ORANG) --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($teachers as $teacher)
                <div class="bg-forest-900/80 rounded-2xl p-4 sm:p-5 border border-forest-800 flex flex-col items-center text-center group hover:border-emerald-500/50 hover:bg-forest-900 transition-all duration-300">

                    {{-- Foto Guru --}}
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden bg-forest-800 border-2 border-emerald-500/40 mb-3.5 relative group-hover:scale-105 transition-transform">
                        @if(!empty($teacher->foto_url))
                            <img src="{{ $teacher->foto_url }}"
                                 alt="{{ $teacher->nama }}"
                                 loading="lazy"
                                 decoding="async"
                                 class="w-full h-full object-cover object-top">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-emerald-300 font-black text-xl sm:text-2xl bg-gradient-to-tr from-forest-800 to-forest-700">
                                {{ substr($teacher->nama, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    {{-- Nama & Gelar --}}
                    <h3 class="font-bold text-xs sm:text-sm text-white group-hover:text-sumedang-amber transition leading-tight">
                        {{ $teacher->nama }}{{ $teacher->gelar ? ', ' . $teacher->gelar : '' }}
                    </h3>

                    {{-- Jabatan --}}
                    <span class="inline-block px-2 py-0.5 mt-1.5 rounded bg-forest-800 text-emerald-300 text-[10px] font-semibold">
                        {{ $teacher->jabatan }}
                    </span>

                    {{-- Mata Pelajaran / Rombel --}}
                    <p class="text-[11px] text-gray-400 mt-1 line-clamp-1">
                        {{ $teacher->mapel_diajarkan ?? 'Pendidik Satuan' }}
                    </p>

                    {{-- Status Kepegawaian (Proteksi Privasi: NIP Disembunyikan Sesuai UU PDP) --}}
                    <div class="mt-3 pt-3 border-t border-forest-800/80 w-full flex items-center justify-between text-[10px] text-gray-400">
                        <span class="font-medium">Status:</span>
                        <span class="font-bold text-gray-300">{{ $teacher->status_kepegawaian }}</span>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>
