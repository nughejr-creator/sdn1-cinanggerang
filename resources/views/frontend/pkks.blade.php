@extends('layouts.frontend')

@section('title', 'E-PKKS Digital 2026 | Penilaian Kinerja Kepala Sekolah - SDN Cinanggerang I')
@section('meta_description', 'Showcase portofolio bukti fisik digital Penilaian Kinerja Kepala Sekolah (E-PKKS) SDN Cinanggerang I tahun 2026 sesuai Permendikdasmen No. 23 Tahun 2026.')
@section('canonical_url', route('pkks'))

@section('content')
<div class="bg-slate-50 min-h-screen text-slate-800"
     x-data="{
        activeTab: 'all',
        pdfModalOpen: false,
        pdfUrl: '',
        pdfTitle: '',
        videoModalOpen: false,
        videoUrl: '',
        videoTitle: '',
        pinModalOpen: false,
        pinInput: '',
        pinError: '',
        pinLoading: false,

        openPdf(url, title) {
            this.pdfUrl = url;
            this.pdfTitle = title;
            this.pdfModalOpen = true;
        },
        openVideo(url, title) {
            this.videoUrl = url;
            this.videoTitle = title;
            this.videoModalOpen = true;
        },
        async submitPin() {
            if (!this.pinInput) {
                this.pinError = 'Silakan masukkan PIN Asesor.';
                return;
            }
            this.pinLoading = true;
            this.pinError = '';

            try {
                const response = await fetch('{{ route('pkks.verify-pin') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ pin: this.pinInput })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    window.location.reload();
                } else {
                    this.pinError = data.message || 'PIN Asesor tidak valid. Silakan coba kembali.';
                }
            } catch (err) {
                this.pinError = 'Terjadi kesalahan sistem. Silakan muat ulang halaman.';
            } finally {
                this.pinLoading = false;
            }
        }
     }">

    {{-- HERO SECTION & BENTO GRID: EXECUTIVE CV KEPALA SEKOLAH --}}
    <section class="bg-gradient-to-b from-forest-950 via-forest-900 to-forest-950 text-white pt-10 pb-16 lg:pb-20 relative overflow-hidden border-b border-forest-800/80">
        {{-- Pola Background Halus --}}
        <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:24px_24px] opacity-25 pointer-events-none"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Top Header Banner --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 pb-6 border-b border-forest-800/60">
                <div>
                    <div class="flex flex-wrap items-center gap-2.5 mb-3">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-forest-800/80 border border-emerald-400/30 text-emerald-300 text-xs font-bold shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-sumedang-amber animate-pulse"></span>
                            <span>Instrumen Resmi Permendikdasmen No. 23 Tahun 2026</span>
                        </div>

                        {{-- Pemilih Arsip Multi-Tahun (Year Selector Dropdown) --}}
                        <div class="relative inline-block text-left" x-data="{ openYearDropdown: false }" @click.away="openYearDropdown = false">
                            <button type="button"
                                    @click="openYearDropdown = !openYearDropdown"
                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-forest-800 hover:bg-forest-700 text-sumedang-amber border border-sumedang-amber/40 text-xs font-bold transition shadow-sm">
                                <span>📅 Periode: Tahun {{ $activePeriod?->academic_year ?? '2026' }}</span>
                                @if($isArchivedView)
                                    <span class="px-1.5 py-0.2 rounded bg-amber-400/20 text-sumedang-amber text-[9px] uppercase font-black">Arsip Terkunci</span>
                                @else
                                    <span class="px-1.5 py-0.2 rounded bg-emerald-500/20 text-emerald-300 text-[9px] uppercase font-black">Aktif</span>
                                @endif
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="openYearDropdown" x-cloak
                                 x-transition
                                 class="absolute left-0 mt-2 w-64 rounded-2xl bg-forest-900 border border-forest-700 shadow-2xl py-2 z-50">
                                <div class="px-3.5 py-1.5 border-b border-forest-800 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                    Pilih Arsip Penilaian (Akreditasi)
                                </div>
                                @if(!empty($periodsList))
                                    @foreach($periodsList as $p)
                                        <a href="{{ route('pkks', ['tahun' => $p->academic_year]) }}"
                                           class="flex items-center justify-between px-3.5 py-2.5 text-xs font-semibold hover:bg-forest-800 transition {{ ($activePeriod?->id === $p->id) ? 'bg-forest-800 text-sumedang-amber font-bold' : 'text-gray-200' }}">
                                            <span class="flex items-center gap-2">
                                                <span>Tahun {{ $p->academic_year }}</span>
                                            </span>
                                            @if($p->isArchived())
                                                <span class="text-[9px] px-2 py-0.5 rounded-full bg-gray-800 text-gray-300 border border-gray-700">🔒 Terkunci</span>
                                            @else
                                                <span class="text-[9px] px-2 py-0.5 rounded-full bg-emerald-950 text-emerald-300 border border-emerald-700">🟢 Berjalan</span>
                                            @endif
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white leading-tight">
                        E-PKKS Digital <span class="text-sumedang-amber">{{ $activePeriod?->academic_year ?? '2026' }}</span>
                    </h1>
                    <p class="text-xs sm:text-sm text-emerald-200/90 mt-1.5 max-w-2xl font-light">
                        Penilaian Kinerja Kepala Sekolah Berbasis Bukti Fisik Digital, Tata Kelola Terbuka & Akuntabel.
                    </p>
                </div>

                {{-- Status Asesor & Auth PIN Toggle & Admin Edit Shortcut --}}
                <div class="flex flex-wrap items-center gap-3">
                    @auth
                        <a href="{{ url('/admin/headmaster-profiles') }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-forest-800 hover:bg-forest-700 text-sumedang-amber text-xs font-bold border border-sumedang-amber/40 shadow transition">
                            <span>✏️ Mode Edit Admin</span>
                        </a>
                    @else
                        <a href="{{ url('/admin') }}"
                           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-forest-800/80 hover:bg-forest-700 text-gray-300 hover:text-white text-xs font-semibold border border-forest-700 transition">
                            <svg class="w-3.5 h-3.5 text-sumedang-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            <span>Login Editor</span>
                        </a>
                    @endauth

                    @if($isAssessorUnlocked)
                        <div class="flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-500/20 border border-emerald-400/40 text-emerald-200 text-xs font-bold">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Akses Asesor Aktif (Terbuka Penuh)</span>
                        </div>
                        <form action="{{ route('pkks.lock-pin') }}" method="POST">
                            @csrf
                            <button type="submit" class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-gray-300 hover:text-white text-xs font-semibold border border-white/20 transition" title="Kunci Kembali Dokumen">
                                🔒 Kunci
                            </button>
                        </form>
                    @else
                        <button type="button"
                                @click="pinModalOpen = true"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sumedang-amber hover:bg-sumedang-gold text-forest-950 font-extrabold text-xs shadow-lg shadow-sumedang-amber/20 transition-all transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4 text-forest-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <span>Masuk PIN Asesor</span>
                        </button>
                    @endif
                </div>
            </div>

            {{-- STEMPEL BANNER KHUSUS ARSIP AKREDITASI BAN-S/M --}}
            @if($isArchivedView)
                <div class="mb-8 p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-amber-950/90 via-forest-950 to-forest-900 border-2 border-sumedang-amber/60 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xl">
                    <div class="flex items-start gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-amber-500/20 text-sumedang-amber flex items-center justify-center text-2xl shrink-0 border border-sumedang-amber/40 shadow-inner">
                            🔒
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-black text-sumedang-amber text-sm sm:text-base">
                                    Arsip Resmi Dokumen E-PKKS Tahun {{ $activePeriod?->academic_year }}
                                </h3>
                                <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 text-[10px] font-bold border border-emerald-500/30">
                                    Akreditasi Ready
                                </span>
                            </div>
                            <p class="text-gray-300 text-xs mt-1 leading-relaxed">
                                Seluruh bukti fisik pada periode ini telah dinilai dan berstatus <strong>Terkunci (Historical Archive)</strong>. Data tidak dapat ditimpa dan dapat dibuka kapan saja untuk audit kedinasan dan akreditasi sekolah.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('pkks') }}"
                       class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-sumedang-amber hover:bg-sumedang-gold text-forest-950 font-black text-xs shrink-0 transition shadow-md whitespace-nowrap">
                        <span>← Kembali ke Tahun Berjalan (Aktif)</span>
                    </a>
                </div>
            @endif

            {{-- BENTO GRID EXECUTIVE CV KEPALA SEKOLAH --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- Kolom 1: Foto Profil & Identitas Resmi (4 Kolom) --}}
                <div class="lg:col-span-4 bg-forest-900/70 backdrop-blur-md rounded-3xl p-6 border border-forest-700/60 shadow-xl flex flex-col justify-between">
                    <div>
                        {{-- Avatar / Foto Kepala Sekolah --}}
                        <div class="relative w-36 h-36 sm:w-44 sm:h-44 mx-auto mb-5 rounded-2xl overflow-hidden border-4 border-sumedang-amber/70 shadow-2xl bg-forest-950 flex items-center justify-center">
                            @if(!empty($headmaster?->photo_url))
                                <img src="{{ $headmaster->photo_url }}"
                                     alt="{{ $headmaster->name }}"
                                     class="w-full h-full object-cover object-top"
                                     onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                <div class="hidden w-full h-full bg-gradient-to-br from-forest-800 to-forest-950 flex flex-col items-center justify-center text-center p-4">
                                    <span class="text-4xl sm:text-5xl font-black text-sumedang-amber">AY</span>
                                    <span class="text-[10px] text-emerald-300 font-bold uppercase mt-1 tracking-wider">Kepala Sekolah</span>
                                </div>
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-forest-800 to-forest-950 flex flex-col items-center justify-center text-center p-4">
                                    <span class="text-4xl sm:text-5xl font-black text-sumedang-amber">AY</span>
                                    <span class="text-[10px] text-emerald-300 font-bold uppercase mt-1 tracking-wider">Kepala Sekolah</span>
                                </div>
                            @endif

                            {{-- Status Badge Aktif --}}
                            <div class="absolute bottom-2 right-2 px-2 py-0.5 rounded-full bg-emerald-500 text-forest-950 font-black text-[9px] uppercase tracking-wider flex items-center gap-1 shadow">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                                <span>Aktif</span>
                            </div>
                        </div>

                        {{-- Identitas Formal --}}
                        <div class="text-center">
                            <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                                {{ $headmaster?->name ?? 'Bpk. Aep Yayat, S.Pd' }}
                            </h2>
                            <p class="text-xs font-semibold text-sumedang-amber mt-0.5">
                                Kepala SD Negeri Cinanggerang I
                            </p>
                            <p class="text-[11px] text-emerald-300/90 font-mono mt-1">
                                NIP: {{ $headmaster?->nip ?? '197206102014111001' }}
                            </p>
                            <p class="text-[11px] text-gray-300">
                                Pangkat/Gol: <span class="font-bold text-white">{{ $headmaster?->rank_group ?? 'Penata Tk. I / IV/b' }}</span>
                            </p>
                            @if(!empty($headmaster?->nuptk))
                                <p class="text-[10px] text-gray-400 font-mono mt-0.5">
                                    NUPTK: {{ $headmaster->nuptk }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Tombol Aksi Dokumen Eksekutif --}}
                    <div class="mt-6 pt-5 border-t border-forest-800 flex flex-col sm:flex-row gap-2.5">
                        <a href="https://drive.google.com/drive/folders/14OBTBm-pTOgP-YJ7Wffzz6VCqxqwyxoG?usp=sharing"
                           target="_blank"
                           class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-forest-800 hover:bg-forest-700 text-white text-xs font-bold border border-emerald-500/30 transition shadow">
                            <svg class="w-4 h-4 text-sumedang-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>Unduh CV (PDF)</span>
                        </a>
                        <a href="https://drive.google.com/drive/folders/14OBTBm-pTOgP-YJ7Wffzz6VCqxqwyxoG?usp=sharing"
                           target="_blank"
                           class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-sumedang-amber/15 hover:bg-sumedang-amber/25 text-sumedang-amber hover:text-white text-xs font-bold border border-sumedang-amber/30 transition">
                            <svg class="w-4 h-4 text-sumedang-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span>Pakta Integritas</span>
                        </a>
                    </div>

                    @auth
                        <div class="mt-3 text-center">
                            <a href="{{ url('/admin/headmaster-profiles/' . ($headmaster?->id ?? 1) . '/edit') }}"
                               target="_blank"
                               class="text-[11px] text-sumedang-amber hover:text-white font-bold inline-flex items-center gap-1 transition">
                                <span>✏️ Edit Profil & Riwayat Pimpinan di Panel Admin</span>
                            </a>
                        </div>
                    @endauth
                </div>

                {{-- Kolom 2: Filosofi Kepemimpinan & Track Record (8 Kolom) --}}
                <div class="lg:col-span-8 flex flex-col gap-6">

                    {{-- Bento Card Atas: Filosofi & Kutipan Kepemimpinan --}}
                    <div class="bg-forest-900/60 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-forest-700/60 shadow-xl relative overflow-hidden">
                        <div class="absolute -right-6 -bottom-6 text-emerald-800/20 pointer-events-none select-none">
                            <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                        </div>

                        <div class="flex items-center gap-2 text-xs font-bold text-sumedang-amber uppercase tracking-wider mb-3">
                            <span>💡 Filosofi Kepemimpinan Pembelajaran</span>
                        </div>

                        <blockquote class="text-base sm:text-lg lg:text-xl font-light text-white leading-relaxed italic mb-4">
                            "{{ $headmaster?->leadership_quote ?? 'Memimpin dengan keteladanan, melayani dengan keikhlasan, dan mengabdi untuk kemajuan generasi penerus bangsa yang berakhlak mulia, cerdas, mandiri, dan berwawasan lingkungan.' }}"
                        </blockquote>

                        <p class="text-xs text-gray-300 leading-relaxed font-light">
                            {{ $headmaster?->bio_summary ?? 'Pendidik dan pemimpin pembelajaran berdedikasi tinggi di bawah naungan Dinas Pendidikan Pemerintah Kabupaten Sumedang. Berkomitmen menghadirkan ekosistem sekolah ramah anak serta adaptif terhadap transformasi Kurikulum Merdeka dan teknologi digital.' }}
                        </p>
                    </div>

                    {{-- Bento Card Bawah: Riwayat Diklat, Prestasi & Keaktifan Organisasi --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        {{-- Diklat & Pelatihan --}}
                        <div class="bg-forest-900/50 backdrop-blur-md rounded-2xl p-5 border border-forest-800/80 shadow">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="p-2 rounded-xl bg-emerald-500/20 text-emerald-300 text-sm">🎓</span>
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-emerald-300">Pengembangan Diri</h3>
                            </div>
                            <ul class="text-xs text-gray-300 space-y-2">
                                <li class="flex items-start gap-1.5">
                                    <span class="text-sumedang-amber text-xs font-bold mt-0.5">•</span>
                                    <span>Bimtek Implementasi Kurikulum Merdeka (2024)</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-sumedang-amber text-xs font-bold mt-0.5">•</span>
                                    <span>Pelatihan Perencanaan Berbasis Data (PBD) (2025)</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-sumedang-amber text-xs font-bold mt-0.5">•</span>
                                    <span>Program PKB Kepala Sekolah - BBGP Jabar</span>
                                </li>
                            </ul>
                        </div>

                        {{-- Prestasi & Apresiasi --}}
                        <div class="bg-forest-900/50 backdrop-blur-md rounded-2xl p-5 border border-forest-800/80 shadow">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="p-2 rounded-xl bg-amber-500/20 text-sumedang-amber text-sm">🏆</span>
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-sumedang-amber">Penghargaan</h3>
                            </div>
                            <ul class="text-xs text-gray-300 space-y-2">
                                <li class="flex items-start gap-1.5">
                                    <span class="text-sumedang-amber text-xs font-bold mt-0.5">•</span>
                                    <span>Kepala Sekolah Penggerak Mutu Literasi & Numerasi</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-sumedang-amber text-xs font-bold mt-0.5">•</span>
                                    <span>Apresiasi Inovasi Pengelolaan Sekolah Adiwiyata</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-sumedang-amber text-xs font-bold mt-0.5">•</span>
                                    <span>Keteladanan Manajerial Satuan Pendidikan</span>
                                </li>
                            </ul>
                        </div>

                        {{-- Keaktifan Organisasi --}}
                        <div class="bg-forest-900/50 backdrop-blur-md rounded-2xl p-5 border border-forest-800/80 shadow">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="p-2 rounded-xl bg-sky-500/20 text-sky-300 text-sm">🤝</span>
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-sky-300">Organisasi Profesi</h3>
                            </div>
                            <ul class="text-xs text-gray-300 space-y-2">
                                <li class="flex items-start gap-1.5">
                                    <span class="text-sumedang-amber text-xs font-bold mt-0.5">•</span>
                                    <span>Pengurus KKKS Kec. Pamulihan (2023 - Sekarang)</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-sumedang-amber text-xs font-bold mt-0.5">•</span>
                                    <span>Anggota Aktif PGRI Cabang Pamulihan</span>
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-sumedang-amber text-xs font-bold mt-0.5">•</span>
                                    <span>Pembina Gugus Komunitas Belajar Sekolah</span>
                                </li>
                            </ul>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>

    {{-- 2. DASHBOARD METRIK KINERJA (4 CARD STATISTIK) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Card 1: 12 Indikator Mutu --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-lg shadow-slate-200/50 flex items-center gap-4 hover:border-emerald-500 transition group">
                <div class="w-13 h-13 rounded-2xl bg-forest-900 text-sumedang-amber flex items-center justify-center font-black text-xl shadow-md group-hover:scale-105 transition-transform">
                    12
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Indikator Mutu</p>
                    <h3 class="text-xl font-black text-forest-950">12 / 12 Terpenuhi</h3>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-0.5">Permendikdasmen 23/2026</p>
                </div>
            </div>

            {{-- Card 2: 100% Keterpenuhan Bukti Fisik --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-lg shadow-slate-200/50 flex items-center gap-4 hover:border-emerald-500 transition group">
                <div class="w-13 h-13 rounded-2xl bg-emerald-100 text-forest-900 flex items-center justify-center font-black text-xl shadow-md group-hover:scale-105 transition-transform">
                    {{ $stats['percentage'] }}%
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Keterpenuhan Eviden</p>
                    <h3 class="text-xl font-black text-emerald-700">Status "Ada" Penuh</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Tuntas Diverifikasi Internal</p>
                </div>
            </div>

            {{-- Card 3: Rekap Berkas Digital --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-lg shadow-slate-200/50 flex items-center gap-4 hover:border-emerald-500 transition group">
                <div class="w-13 h-13 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center font-black text-xl shadow-md group-hover:scale-105 transition-transform">
                    {{ $stats['total_evidences'] }}+
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Berkas Portofolio</p>
                    <h3 class="text-base font-black text-forest-950">
                        {{ $stats['total_pdfs'] }} PDF • {{ $stats['total_videos'] }} Video
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Cloud Storage Google Drive</p>
                </div>
            </div>

            {{-- Card 4: Legalitas Regulasi --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-lg shadow-slate-200/50 flex items-center gap-4 hover:border-emerald-500 transition group">
                <div class="w-13 h-13 rounded-2xl bg-sky-100 text-sky-800 flex items-center justify-center font-black text-lg shadow-md group-hover:scale-105 transition-transform">
                    ⚖️
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Dasar Hukum Penilaian</p>
                    <h3 class="text-xs font-extrabold text-forest-950 leading-snug">Permendikdasmen No. 23/2026</h3>
                    <p class="text-[11px] text-emerald-600 font-bold mt-0.5">Kepala Sekolah Berkinerja</p>
                </div>
            </div>

        </div>
    </section>

    {{-- 3. INTERACTIVE SHOWCASE 12 INDIKATOR PKKS --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">

        {{-- Section Title & Filter Tabs --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-forest-900 text-xs font-extrabold uppercase tracking-wider mb-2">
                    Ceklis & Portofolio Eviden Digital
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-forest-950 tracking-tight">
                    Showcase 12 Indikator Kinerja Kepala Sekolah
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-1">
                    Klik nomor indikator atau filter pilar kompetensi untuk meninjau dokumen eviden secara langsung.
                </p>
            </div>

            {{-- Filter Tabs Pilar --}}
            <div class="inline-flex p-1.5 rounded-2xl bg-white border border-slate-200 shadow-sm text-xs font-bold overflow-x-auto">
                <button type="button"
                        @click="activeTab = 'all'"
                        :class="activeTab === 'all' ? 'bg-forest-900 text-white shadow' : 'text-slate-600 hover:text-forest-900'"
                        class="px-4 py-2 rounded-xl transition whitespace-nowrap">
                    Semua Pilar (12)
                </button>
                <button type="button"
                        @click="activeTab = 'Kepribadian'"
                        :class="activeTab === 'Kepribadian' ? 'bg-forest-900 text-white shadow' : 'text-slate-600 hover:text-forest-900'"
                        class="px-4 py-2 rounded-xl transition whitespace-nowrap">
                    1. Kepribadian (1-3)
                </button>
                <button type="button"
                        @click="activeTab = 'Sosial'"
                        :class="activeTab === 'Sosial' ? 'bg-forest-900 text-white shadow' : 'text-slate-600 hover:text-forest-900'"
                        class="px-4 py-2 rounded-xl transition whitespace-nowrap">
                    2. Sosial (4-6)
                </button>
                <button type="button"
                        @click="activeTab = 'Profesional'"
                        :class="activeTab === 'Profesional' ? 'bg-forest-900 text-white shadow' : 'text-slate-600 hover:text-forest-900'"
                        class="px-4 py-2 rounded-xl transition whitespace-nowrap">
                    3. Profesional (7-12)
                </button>
            </div>
        </div>

        {{-- GRID LAYOUT: SIDEBAR QUICK JUMP & LIST INDIKATOR --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- STICKY SIDEBAR NAVIGATION (3 Kolom di Desktop) --}}
            <aside class="hidden lg:block lg:col-span-3 sticky top-24 space-y-4">
                <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm">
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-500 mb-3 px-2">
                        Daftar 12 Indikator
                    </h4>
                    <nav class="space-y-1">
                        @foreach($indicators as $ind)
                            <a href="#indikator-{{ $ind->indicator_number }}"
                               x-show="activeTab === 'all' || activeTab === '{{ $ind->competence }}'"
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium text-slate-600 hover:text-forest-900 hover:bg-emerald-50/70 transition group">
                                <span class="w-6 h-6 rounded-lg bg-slate-100 group-hover:bg-forest-900 group-hover:text-white flex items-center justify-center font-bold text-[11px] transition">
                                    {{ $ind->indicator_number }}
                                </span>
                                <span class="truncate font-semibold group-hover:font-bold">
                                    {{ $ind->title }}
                                </span>
                            </a>
                        @endforeach
                    </nav>

                    {{-- Informasi Singkat Asesor --}}
                    <div class="mt-6 pt-5 border-t border-slate-100 text-xs">
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/80">
                            <p class="font-bold text-forest-950 text-[11px]">Tim Penguji / Asesor:</p>
                            <p class="text-[11px] text-slate-600 mt-0.5">{{ $stats['assessor_name'] }}</p>
                            <p class="text-[10px] text-emerald-700 font-semibold mt-1">Disdik Kab. Sumedang</p>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- LIST CARD 12 INDIKATOR (9 Kolom di Desktop) --}}
            <div class="lg:col-span-9 space-y-6">
                @foreach($indicators as $ind)
                    <article id="indikator-{{ $ind->indicator_number }}"
                             x-show="activeTab === 'all' || activeTab === '{{ $ind->competence }}'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm hover:shadow-md transition">

                        {{-- Header Card Indikator --}}
                        <div class="flex flex-wrap items-start justify-between gap-4 pb-4 border-b border-slate-100">
                            <div class="flex items-start gap-3 sm:gap-4">
                                {{-- Nomor Indikator --}}
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-forest-900 text-sumedang-amber font-black text-lg sm:text-xl flex items-center justify-center shrink-0 shadow-sm">
                                    {{ $ind->indicator_number }}
                                </div>

                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        {{-- Badge Kompetensi --}}
                                        @if($ind->competence === 'Kepribadian')
                                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase">
                                                Kompetensi Kepribadian
                                            </span>
                                        @elseif($ind->competence === 'Sosial')
                                            <span class="px-2.5 py-0.5 rounded-full bg-sky-100 text-sky-800 text-[10px] font-extrabold uppercase">
                                                Kompetensi Sosial
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800 text-[10px] font-extrabold uppercase">
                                                Kompetensi Profesional
                                            </span>
                                        @endif

                                        <span class="text-xs text-slate-400">• Indikator {{ $ind->indicator_number }}</span>
                                    </div>

                                    <h3 class="text-lg sm:text-xl font-black text-forest-950 tracking-tight">
                                        {{ $ind->title }}
                                    </h3>
                                </div>
                            </div>

                            {{-- Status Keterpenuhan Badge & Edit Shortcut --}}
                            <div class="flex items-center gap-2">
                                @auth
                                    <a href="{{ url('/admin/pkks-indicators/' . $ind->id . '/edit') }}"
                                       target="_blank"
                                       class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-forest-900 text-slate-700 hover:text-white text-[11px] font-bold border border-slate-300 transition flex items-center gap-1 shadow-xs">
                                        <span>✏️ Edit</span>
                                    </a>
                                @endauth

                                @if($isArchivedView)
                                    <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 border border-amber-300 text-amber-800 text-xs font-black shadow-xs">
                                        <span>🔒 TERARSIPKAN (TERVERIFIKASI)</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-300 text-emerald-700 text-xs font-black shadow-xs">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span>STATUS: ADA</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Deskripsi Standar Instrumen --}}
                        <div class="py-4">
                            <p class="text-xs sm:text-sm text-slate-700 leading-relaxed">
                                {{ $ind->description }}
                            </p>

                            {{-- Best Practice Notes (Praktik Baik Sekolah) --}}
                            @if(!empty($ind->best_practice_notes))
                                <div class="mt-3.5 p-3.5 rounded-2xl bg-forest-50/70 border border-emerald-200/80 text-xs text-forest-950">
                                    <div class="flex items-center gap-1.5 font-extrabold text-forest-900 mb-1">
                                        <span>🌟 Narasi Praktik Baik SDN Cinanggerang I:</span>
                                    </div>
                                    <p class="text-emerald-950 font-normal leading-relaxed">
                                        {{ $ind->best_practice_notes }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- DAFTAR BUKTI FISIK / EVIDEN DIGITAL --}}
                        <div class="mt-2 pt-4 border-t border-slate-100">
                            <h4 class="text-xs font-black uppercase tracking-wider text-slate-500 mb-3 flex items-center justify-between">
                                <span>Ceklis Bukti Fisik Terlampir ({{ $ind->evidences->count() }} Dokumen)</span>
                                <span class="text-[11px] text-emerald-700 font-bold lowercase">semua berkas terverifikasi</span>
                            </h4>

                            <div class="space-y-3">
                                @forelse($ind->evidences as $ev)
                                    <div class="p-3.5 sm:p-4 rounded-2xl border transition flex flex-col sm:flex-row sm:items-center justify-between gap-3 {{ !$ev->is_public && !$isAssessorUnlocked ? 'bg-amber-50/40 border-amber-200/80' : 'bg-slate-50 hover:bg-emerald-50/40 border-slate-200/80 hover:border-emerald-300' }}">

                                        {{-- Info Dokumen --}}
                                        <div class="flex items-start gap-3">
                                            {{-- Ikon Tipe File --}}
                                            <div class="p-2.5 rounded-xl shrink-0 font-bold text-xs {{ $ev->file_type === 'pdf' ? 'bg-red-100 text-red-700' : ($ev->file_type === 'video' ? 'bg-purple-100 text-purple-700' : ($ev->file_type === 'image' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700')) }}">
                                                @if($ev->file_type === 'pdf')
                                                    📄 PDF
                                                @elseif($ev->file_type === 'video')
                                                    🎥 Video
                                                @elseif($ev->file_type === 'image')
                                                    🖼️ Foto
                                                @else
                                                    🔗 Portal
                                                @endif
                                            </div>

                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h5 class="text-xs sm:text-sm font-bold text-forest-950 leading-snug">
                                                        {{ $ev->title }}
                                                    </h5>

                                                    {{-- Gating Badge (Jika Privat) --}}
                                                    @if(!$ev->is_public)
                                                        @if($isAssessorUnlocked)
                                                            <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                                                                🔓 Terbuka
                                                            </span>
                                                        @else
                                                            <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-bold flex items-center gap-1">
                                                                🔒 Khusus Asesor
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>

                                                @if(!empty($ev->description))
                                                    <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                                                        {{ $ev->description }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Tombol Aksi Eviden --}}
                                        <div class="shrink-0 flex items-center gap-2 sm:self-center">
                                            {{-- Case 1: Dokumen Terkunci & Belum Input PIN --}}
                                            @if(!$ev->is_public && !$isAssessorUnlocked)
                                                <button type="button"
                                                        @click="pinModalOpen = true"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs shadow-xs transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                    <span>Buka PIN Asesor</span>
                                                </button>

                                            {{-- Case 2: Dokumen Publik atau Akses Sudah Terbuka --}}
                                            @else
                                                {{-- Jika Video --}}
                                                @if($ev->file_type === 'video')
                                                    <a href="{{ $ev->external_url }}"
                                                       target="_blank"
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition">
                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                        <span>Tonton Video</span>
                                                    </a>

                                                {{-- Jika Dokumen PDF --}}
                                                @elseif($ev->file_type === 'pdf')
                                                    <a href="{{ $ev->external_url ?? $ev->file_url ?? 'https://drive.google.com/drive/folders/14OBTBm-pTOgP-YJ7Wffzz6VCqxqwyxoG?usp=sharing' }}"
                                                       target="_blank"
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-forest-900 hover:bg-forest-800 text-white font-bold text-xs shadow-xs transition">
                                                        <svg class="w-3.5 h-3.5 text-sumedang-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        <span>Buka Dokumen</span>
                                                    </a>

                                                {{-- Jika Foto / Link --}}
                                                @else
                                                    <a href="{{ $ev->external_url ?? $ev->file_url ?? 'https://drive.google.com/drive/folders/14OBTBm-pTOgP-YJ7Wffzz6VCqxqwyxoG?usp=sharing' }}"
                                                       target="_blank"
                                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                        <span>Lihat Galeri</span>
                                                    </a>
                                                @endif
                                            @endif
                                        </div>

                                    </div>
                                @empty
                                    <p class="text-xs text-slate-500 italic">Belum ada eviden khusus yang diunggah.</p>
                                @endforelse
                            </div>
                        </div>

                    </article>
                @endforeach
            </div>

        </div>

    </section>

    {{-- MODAL INPUT PIN ASESOR --}}
    <div x-show="pinModalOpen" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog" aria-modal="true">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            {{-- Backdrop --}}
            <div x-show="pinModalOpen"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm"
                 @click="pinModalOpen = false"></div>

            {{-- Modal Box --}}
            <div x-show="pinModalOpen"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative inline-block w-full max-w-md p-6 sm:p-8 my-8 text-left bg-white rounded-3xl shadow-2xl z-10 border border-slate-200">

                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-700 font-black text-2xl flex items-center justify-center mb-4">
                    🔐
                </div>

                <h3 class="text-xl font-black text-forest-950">
                    Otorisasi Akses Dokumen Asesor
                </h3>
                <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                    Masukkan PIN/Token Cepat Asesor untuk membuka berkas kedinasan, arsip keuangan BOS, dan dokumen manajerial internal sekolah.
                </p>

                <form @submit.prevent="submitPin()" class="mt-5 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                            Kode PIN Asesor
                        </label>
                        <input type="password"
                               x-model="pinInput"
                               placeholder="Masukkan PIN (Default: 1972)"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-mono text-center text-lg tracking-widest text-forest-950 font-bold"
                               maxlength="10"
                               autofocus>
                        <p x-show="pinError" x-text="pinError" class="text-xs text-red-600 font-semibold mt-1.5"></p>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="button"
                                @click="pinModalOpen = false"
                                class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                                :disabled="pinLoading"
                                class="flex-1 py-2.5 rounded-xl bg-forest-900 hover:bg-forest-800 text-white font-black text-xs transition shadow-md flex items-center justify-center gap-2">
                            <span x-show="!pinLoading">Buka Berkas</span>
                            <span x-show="pinLoading">Memverifikasi...</span>
                        </button>
                    </div>
                </form>

                <div class="mt-5 pt-4 border-t border-slate-100 text-center">
                    <p class="text-[11px] text-slate-400">
                        *PIN resmi diterbitkan khusus untuk Tim Asesor Pengawas Pembina.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
