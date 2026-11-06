@extends('layouts.frontend')

@section('title', 'Daftar Siswa & Cek NISN Mandiri - SD Negeri Cinanggerang I')
@section('meta_description', 'Layanan mandiri pengecekan NISN resmi siswa SDN Cinanggerang I, Pamulihan, Sumedang untuk keperluan administrasi PIP, PKH, dan pendataan sekolah.')
@section('canonical_url', route('siswa'))

@section('content')
    {{-- Header Banner Halaman --}}
    <div class="bg-forest-950 text-white py-12 lg:py-16 border-b border-forest-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#065F46_1px,transparent_1px)] [background-size:20px_20px] opacity-30 pointer-events-none"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-forest-800 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-3">
                <span>🔍 Layanan Mandiri Kesiswaan</span>
                <span class="w-1.5 h-1.5 rounded-full bg-sumedang-amber"></span>
                <span>NPSN: 20208801</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                Daftar Siswa & Cek NISN Mandiri
            </h1>
            <p class="text-xs sm:text-sm text-gray-300 mt-2 max-w-3xl leading-relaxed">
                Layanan mandiri pengecekan Nomor Induk Siswa Nasional (NISN) resmi SD Negeri Cinanggerang I untuk mempermudah orang tua murid dalam pengurusan administrasi bansos PKH, PIP, beasiswa, dan pendataan sekolah.
            </p>
        </div>
    </div>

    {{-- Kotak Pencarian Cepat & Filter Kelas --}}
    <section class="py-8 bg-slate-50 dark:bg-forest-950/80 border-b border-gray-200/80 dark:border-forest-900"
             x-data="{ copiedNisn: null, copyToClipboard(text) { navigator.clipboard.writeText(text); this.copiedNisn = text; setTimeout(() => { this.copiedNisn = null }, 2000); } }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-forest-900 rounded-3xl p-6 sm:p-8 shadow-xl border border-gray-100 dark:border-forest-800 relative -mt-14 z-20">
                <form action="{{ route('siswa') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 sm:gap-4 items-center">
                        {{-- Kolom Input Nama / NISN --}}
                        <div class="md:col-span-7 relative">
                            <label for="q" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                                Nama Lengkap Siswa atau NISN
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5 text-forest-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </span>
                                <input type="text"
                                       name="q"
                                       id="q"
                                       value="{{ $query }}"
                                       placeholder="Ketik Nama Siswa atau NISN (minimal 2 huruf)..."
                                       class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-forest-800/60 border border-gray-200 dark:border-forest-700 rounded-2xl text-xs sm:text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-forest-800 focus:border-forest-800 transition shadow-inner">
                            </div>
                        </div>

                        {{-- Kolom Filter Rombel --}}
                        <div class="md:col-span-3">
                            <label for="rombel_id" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                                Filter Rombel / Kelas
                            </label>
                            <select name="rombel_id"
                                    id="rombel_id"
                                    class="w-full px-3.5 py-3 bg-gray-50 dark:bg-forest-800/60 border border-gray-200 dark:border-forest-700 rounded-2xl text-xs sm:text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-forest-800 focus:border-forest-800 transition">
                                <option value="">Semua Rombel (Kelas I - VI)</option>
                                @foreach($rombels as $r)
                                    <option value="{{ $r->id }}" {{ $rombelFilter == $r->id ? 'selected' : '' }}>
                                        {{ $r->nama }} ({{ $r->students_count ?? 0 }} siswa)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol Cari & Reset --}}
                        <div class="md:col-span-2 flex items-center gap-2 pt-0 md:pt-6">
                            <button type="submit"
                                    class="w-full py-3 px-5 rounded-2xl bg-forest-900 hover:bg-forest-800 text-white font-bold text-xs sm:text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 border border-forest-800">
                                <span>Cari Data</span>
                            </button>

                            @if($query || $rombelFilter)
                                <a href="{{ route('siswa') }}"
                                   title="Reset Pencarian"
                                   class="p-3 rounded-2xl bg-gray-100 hover:bg-gray-200 dark:bg-forest-800 dark:hover:bg-forest-700 text-gray-600 dark:text-gray-300 transition flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Catatan Privasi & Keamanan Data --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-2 border-t border-gray-100 dark:border-forest-800 text-[11px] text-gray-500 dark:text-gray-400 gap-2">
                        <span class="flex items-center gap-1.5">
                            <span class="text-sumedang-amber">🔒</span>
                            <span><strong>Privasi Terjamin:</strong> Data pribadi sensitif (NIK, Tanggal Lahir, Nama Orang Tua, Alamat) dirahasiakan sesuai regulasi perlindungan data anak.</span>
                        </span>
                        <span class="font-semibold text-forest-800 dark:text-emerald-300">
                            Total Basis Data: {{ $totalSiswa }} Siswa
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Bagian Hasil Pencarian & Daftar Siswa --}}
    <section class="py-10 bg-slate-50 dark:bg-forest-950"
             x-data="{ copiedNisn: null, copyToClipboard(text) { navigator.clipboard.writeText(text); this.copiedNisn = text; setTimeout(() => { this.copiedNisn = null }, 2500); } }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Status Filter Aktif --}}
            <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        Hasil Pencarian:
                    </span>
                    <span class="px-3 py-1 rounded-full bg-forest-100 dark:bg-forest-900 text-forest-900 dark:text-emerald-300 text-xs font-black">
                        {{ $siswa->total() }} Siswa Ditemukan
                    </span>
                    @if($query)
                        <span class="text-xs text-gray-500">kata kunci: "<strong>{{ $query }}</strong>"</span>
                    @endif
                    @if($rombelFilter)
                        @php
                            $selectedRombel = $rombels->firstWhere('id', $rombelFilter);
                        @endphp
                        @if($selectedRombel)
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-forest-800 dark:text-emerald-300 text-[11px] font-bold border border-emerald-200">
                                {{ $selectedRombel->nama }}
                            </span>
                        @endif
                    @endif
                </div>

                <a href="{{ route('rombel') }}" class="text-xs font-bold text-forest-800 dark:text-emerald-300 hover:underline flex items-center gap-1">
                    <span>Lihat Rekap 8 Rombel ➔</span>
                </a>
            </div>

            @if($siswa->count() > 0)
                {{-- TAMPILAN DESKTOP: TABEL MODERN & BERSIH --}}
                <div class="hidden md:block bg-white dark:bg-forest-900 rounded-3xl shadow-sm border border-gray-200/80 dark:border-forest-800 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-forest-900 text-white text-xs font-black uppercase tracking-wider">
                                <th class="py-4 px-6">No</th>
                                <th class="py-4 px-6">Nama Lengkap Siswa</th>
                                <th class="py-4 px-6">Rombel / Kelas</th>
                                <th class="py-4 px-6">NISN Resmi</th>
                                <th class="py-4 px-6 text-center">Jenis Kelamin</th>
                                <th class="py-4 px-6 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-forest-800 text-xs">
                            @foreach($siswa as $index => $item)
                                <tr class="hover:bg-forest-50/60 dark:hover:bg-forest-800/40 transition-colors">
                                    <td class="py-4 px-6 font-semibold text-gray-400">
                                        {{ $siswa->firstItem() + $index }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full {{ $item->jenis_kelamin === 'P' ? 'bg-pink-100 text-pink-700' : 'bg-blue-100 text-blue-700' }} flex items-center justify-center font-black text-xs flex-shrink-0 shadow-sm">
                                                {{ $item->jenis_kelamin === 'P' ? 'P' : 'L' }}
                                            </div>
                                            <span class="font-extrabold text-gray-900 dark:text-white text-sm">
                                                {{ $item->nama }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-black bg-emerald-50 dark:bg-emerald-950/70 text-forest-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                            {{ $item->studentClass?->nama ?? 'Belum Ditentukan' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($item->nisn)
                                            <div class="inline-flex items-center gap-2 bg-slate-50 dark:bg-forest-800/80 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-forest-700">
                                                <code class="font-mono text-sm font-black text-forest-900 dark:text-emerald-300 tracking-wider">
                                                    {{ $item->nisn }}
                                                </code>
                                                <button type="button"
                                                        @click="copyToClipboard('{{ $item->nisn }}')"
                                                        title="Salin NISN ke Clipboard"
                                                        class="p-1 rounded-md text-gray-400 hover:text-forest-800 dark:hover:text-emerald-300 hover:bg-white dark:hover:bg-forest-700 transition">
                                                    <template x-if="copiedNisn === '{{ $item->nisn }}'">
                                                        <span class="text-[10px] text-emerald-600 font-bold">✓ Tersalin</span>
                                                    </template>
                                                    <template x-if="copiedNisn !== '{{ $item->nisn }}'">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                    </template>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Dalam Proses Dapodik</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold {{ $item->jenis_kelamin === 'P' ? 'bg-pink-50 text-pink-700 border border-pink-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                            {{ $item->jenis_kelamin === 'P' ? 'Perempuan' : 'Laki-laki' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 border border-green-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                            <span>Aktif</span>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TAMPILAN MOBILE: KARTU SISWA BERSIH & RESPONSIF --}}
                <div class="grid grid-cols-1 gap-3.5 md:hidden">
                    @foreach($siswa as $index => $item)
                        <div class="bg-white dark:bg-forest-900 rounded-2xl p-5 shadow-sm border border-gray-200/80 dark:border-forest-800 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 dark:bg-emerald-950/70 text-forest-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 mb-1.5">
                                        {{ $item->studentClass?->nama ?? 'Kelas Belum Ditentukan' }}
                                    </span>
                                    <h4 class="font-extrabold text-base text-gray-900 dark:text-white leading-tight">
                                        {{ $item->nama }}
                                    </h4>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $item->jenis_kelamin === 'P' ? 'bg-pink-100 text-pink-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $item->jenis_kelamin === 'P' ? 'Perempuan' : 'Laki-laki' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-forest-800">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">NISN Resmi</p>
                                    <code class="font-mono text-sm font-black text-forest-900 dark:text-emerald-300">
                                        {{ $item->nisn ?: '-' }}
                                    </code>
                                </div>

                                @if($item->nisn)
                                    <button type="button"
                                            @click="copyToClipboard('{{ $item->nisn }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-forest-50 dark:bg-forest-800 text-forest-900 dark:text-emerald-300 text-xs font-bold border border-emerald-200 dark:border-forest-700 active:scale-95 transition">
                                        <template x-if="copiedNisn === '{{ $item->nisn }}'">
                                            <span class="text-emerald-600">✓ Tersalin</span>
                                        </template>
                                        <template x-if="copiedNisn !== '{{ $item->nisn }}'">
                                            <span>📋 Salin NISN</span>
                                        </template>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- PAGINATION LINKS --}}
                <div class="mt-8">
                    {{ $siswa->links() }}
                </div>
            @else
                {{-- STATE KOSONG / HASIL TIDAK DITEMUKAN --}}
                <div class="bg-white dark:bg-forest-900 rounded-3xl p-10 sm:p-14 text-center border border-gray-200/80 dark:border-forest-800 shadow-sm space-y-4">
                    <div class="w-16 h-16 rounded-full bg-forest-50 dark:bg-forest-800 text-forest-800 dark:text-emerald-300 flex items-center justify-center text-3xl mx-auto shadow-inner">
                        🔍
                    </div>
                    <div class="max-w-md mx-auto space-y-2">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white">
                            Data Siswa Tidak Ditemukan
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                            Tidak ditemukan data siswa yang cocok dengan kata kunci "<strong>{{ $query }}</strong>".
                            Harap pastikan ejaan nama anak sudah sesuai dengan dokumen akta kelahiran/kartu keluarga atau coba ketikkan 1 kata saja (misal: nama depan).
                        </p>
                    </div>

                    <div class="pt-2 flex justify-center gap-3">
                        <a href="{{ route('siswa') }}"
                           class="px-5 py-2.5 rounded-xl bg-forest-900 hover:bg-forest-800 text-white font-bold text-xs shadow-md transition">
                            Tampilkan Semua Siswa
                        </a>
                        <a href="{{ route('rombel') }}"
                           class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-forest-800 text-gray-700 dark:text-gray-200 font-bold text-xs transition">
                            Buka Daftar Per Rombel
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </section>

    {{-- Panduan Administrasi Sekolah (PIP, PKH & PPDB) --}}
    <section class="py-12 bg-white dark:bg-forest-900 border-t border-gray-100 dark:border-forest-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-slate-50 dark:bg-forest-950 p-6 rounded-2xl border border-gray-100 dark:border-forest-800 space-y-2">
                    <div class="text-2xl mb-1">💳</div>
                    <h4 class="font-bold text-sm text-forest-900 dark:text-white">Pencairan Dana PIP</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        NISN diperlukan saat orang tua melakukan aktivasi rekening SimPel di Bank BRI atau pengecekan status SK Nominasi / SK Pemberian di portal resmi Kemendikbud.
                    </p>
                </div>

                <div class="bg-slate-50 dark:bg-forest-950 p-6 rounded-2xl border border-gray-100 dark:border-forest-800 space-y-2">
                    <div class="text-2xl mb-1">📋</div>
                    <h4 class="font-bold text-sm text-forest-900 dark:text-white">Pendataan Bansos PKH</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Pendamping sosial desa membutuhkan NISN yang valid dan sinkron dengan sistem Dapodik untuk verifikasi komponen pendidikan anak usia SD.
                    </p>
                </div>

                <div class="bg-slate-50 dark:bg-forest-950 p-6 rounded-2xl border border-gray-100 dark:border-forest-800 space-y-2">
                    <div class="text-2xl mb-1">🎓</div>
                    <h4 class="font-bold text-sm text-forest-900 dark:text-white">PPDB Jenjang SMP</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Siswa Kelas VI yang akan melanjutkan ke tingkat SMP/MTs wajib memiliki NISN aktif sebagai syarat utama sistem pendaftaran zonasi dan afirmasi.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
