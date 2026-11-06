<section id="pkks" class="py-10 md:py-12 bg-gradient-to-b from-forest-950 to-forest-900 text-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header PKKS --}}
        <div class="text-center max-w-3xl mx-auto mb-8">
            <span class="inline-block px-3.5 py-1 rounded-full bg-forest-800 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-2.5 border border-emerald-500/30">
                Transparansi Mutu & Akuntabilitas
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-white">
                Portofolio Digital E-PKKS 2026
            </h2>
            <p class="text-xs sm:text-sm text-gray-300 mt-2 leading-relaxed">
                Implementasi 12 Indikator Resmi Penilaian Kinerja Kepala Sekolah berdasarkan <strong>Permendikdasmen No. 23 Tahun 2026</strong>.
            </p>
            <div class="mt-4 flex flex-wrap justify-center gap-2 text-xs">
                <span class="px-3 py-1 bg-emerald-500/15 rounded-full border border-emerald-400/30 text-emerald-300 font-semibold">1. Kompetensi Kepribadian</span>
                <span class="px-3 py-1 bg-sky-500/15 rounded-full border border-sky-400/30 text-sky-300 font-semibold">2. Kompetensi Sosial</span>
                <span class="px-3 py-1 bg-amber-500/15 rounded-full border border-amber-400/30 text-sumedang-amber font-semibold">3. Kompetensi Profesional</span>
            </div>
        </div>

        {{-- ACCORDION 12 INDIKATOR --}}
        @php
            $indicatorsList = $pkksIndicators ?? \App\Models\PkksIndicator::with('evidences')->orderBy('indicator_number')->get();
        @endphp

        <div class="space-y-3.5 max-w-4xl mx-auto" x-data="{ activeAccordion: 1 }">
            @foreach($indicatorsList as $ind)
                <div class="bg-forest-900/90 rounded-2xl border border-forest-800 overflow-hidden transition-all shadow-sm">
                    {{-- Header Accordion --}}
                    <button type="button"
                            @click="activeAccordion = (activeAccordion === {{ $ind->indicator_number }} ? null : {{ $ind->indicator_number }})"
                            class="w-full p-4 sm:p-5 text-left flex items-start sm:items-center justify-between gap-4 hover:bg-forest-800/60 transition">
                        <div class="flex items-start gap-3.5">
                            <span class="w-8 h-8 rounded-xl bg-forest-800 text-sumedang-amber font-black text-xs flex items-center justify-center flex-shrink-0 border border-sumedang-amber/30 shadow-inner">
                                {{ $ind->indicator_number }}
                            </span>
                            <div>
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">
                                        {{ $ind->competence }}
                                    </span>
                                    <span class="text-[10px] text-gray-500">•</span>
                                    <span class="text-[10px] text-gray-300 font-medium">Status: Ada</span>
                                </div>
                                <h4 class="font-extrabold text-xs sm:text-sm text-white leading-snug">
                                    {{ $ind->title }}
                                </h4>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <span class="hidden sm:inline-block px-2.5 py-0.5 bg-forest-800 text-emerald-300 text-[10px] font-bold rounded-full border border-emerald-500/20">
                                {{ $ind->evidences->count() }} Berkas
                            </span>
                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                 :class="activeAccordion === {{ $ind->indicator_number }} ? 'rotate-180 text-sumedang-amber' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    {{-- Isi Dokumen Eviden --}}
                    <div x-show="activeAccordion === {{ $ind->indicator_number }}" x-cloak
                         class="p-4 sm:p-5 bg-forest-950/70 border-t border-forest-800 space-y-3">

                        {{-- Deskripsi Standar --}}
                        <p class="text-xs text-gray-300 leading-relaxed font-light">
                            {{ $ind->description }}
                        </p>

                        {{-- Daftar Eviden Ringkas --}}
                        <div class="space-y-2 pt-2">
                            @foreach($ind->evidences as $evidence)
                                <div class="p-3 bg-forest-900/80 rounded-xl border border-forest-800/80 flex items-center justify-between gap-3 text-xs">
                                    <div class="flex items-center gap-2.5 truncate">
                                        <span class="text-sm">
                                            @if($evidence->file_type === 'pdf') 📄
                                            @elseif($evidence->file_type === 'video') 🎥
                                            @elseif($evidence->file_type === 'image') 🖼️
                                            @else 🔗 @endif
                                        </span>
                                        <p class="font-bold text-white truncate text-xs">{{ $evidence->title }}</p>
                                    </div>
                                    <a href="{{ $evidence->external_url ?? $evidence->file_url ?? route('pkks') }}" target="_blank"
                                       class="px-2.5 py-1 bg-forest-800 hover:bg-forest-700 text-sumedang-amber font-bold text-[10px] rounded-lg border border-sumedang-amber/30 transition flex items-center gap-1 shrink-0">
                                        <span>Buka</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- CTA Banner Mandiri E-PKKS --}}
        <div class="mt-12 text-center max-w-2xl mx-auto">
            <div class="p-6 bg-gradient-to-r from-forest-900 to-forest-800 rounded-3xl border border-forest-700 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-left">
                    <h4 class="text-base font-black text-white">Showcase E-PKKS Mandiri Interaktif</h4>
                    <p class="text-xs text-gray-300 mt-0.5">Akses Executive CV Kepala Sekolah, Metrik Kinerja, dan Gating Dokumen PIN Asesor.</p>
                </div>
                <a href="{{ route('pkks') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-sumedang-amber hover:bg-sumedang-gold text-forest-950 font-black text-xs shadow-lg shadow-sumedang-amber/25 transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
                    <span>Buka Halaman E-PKKS &rarr;</span>
                </a>
            </div>
        </div>

    </div>
</section>
