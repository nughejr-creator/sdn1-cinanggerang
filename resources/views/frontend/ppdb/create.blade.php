@extends('layouts.frontend')

@section('title', 'Formulir Pendaftaran Siswa Baru - PPDB Online SDN Cinanggerang I')
@section('meta_description', 'Formulir resmi pendaftaran peserta didik baru online SD Negeri Cinanggerang I Pamulihan Sumedang Tahun Pelajaran ' . ($setting->academic_year ?? '2026/2027'))
@section('canonical_url', route('ppdb.register'))

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-forest-950 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto"
         x-data="{
            jalur: '{{ old('jalur_pendaftaran', 'Zonasi') }}',
            asalJenjang: '{{ old('asal_jenjang', 'Rumah Tangga') }}',
            submitLoading: false,
            fileKkName: '',
            fileAktaName: '',
            fileIjazahName: '',
            fileAfirmasiName: '',
            filePindahName: '',
            handleFile(event, type) {
                const f = event.target.files[0];
                if (!f) return;
                const sizeKb = (f.size / 1024).toFixed(0);
                if (type === 'kk') this.fileKkName = f.name + ' (' + sizeKb + ' KB)';
                if (type === 'akta') this.fileAktaName = f.name + ' (' + sizeKb + ' KB)';
                if (type === 'ijazah') this.fileIjazahName = f.name + ' (' + sizeKb + ' KB)';
                if (type === 'afirmasi') this.fileAfirmasiName = f.name + ' (' + sizeKb + ' KB)';
                if (type === 'pindah') this.filePindahName = f.name + ' (' + sizeKb + ' KB)';
            }
         }">

        {{-- Header Formulir --}}
        <div class="text-center mb-8">
            <a href="{{ route('ppdb') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-forest-800 dark:text-emerald-300 hover:underline mb-3">
                <span>← Kembali ke Layanan PPDB</span>
            </a>
            <div class="inline-block px-3 py-1 rounded-full bg-forest-100 dark:bg-forest-900 text-forest-900 dark:text-emerald-300 text-xs font-black uppercase tracking-wider mb-2">
                🎒 Penerimaan Murid Baru TP {{ $setting->academic_year ?? '2026/2027' }}
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-forest-950 dark:text-white tracking-tight">
                Formulir Pendaftaran Calon Siswa Baru
            </h1>
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-1 max-w-xl mx-auto leading-relaxed">
                Silakan lengkapi formulir pendaftaran di bawah ini dengan data asli sesuai berkas resmi Kartu Keluarga dan Akta Kelahiran.
            </p>
        </div>

        {{-- Error Banner Global jika ada validasi gagal --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border-2 border-rose-300 text-rose-900 shadow-sm">
                <div class="flex items-start gap-3">
                    <span class="text-xl shrink-0">⚠️</span>
                    <div class="text-xs space-y-1">
                        <p class="font-extrabold text-sm">Terdapat kesalahan pada isian formulir:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-rose-800">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Kartu Utama Form --}}
        <div class="bg-white dark:bg-forest-900 rounded-3xl shadow-xl border border-gray-100 dark:border-forest-800 p-6 sm:p-10">
            <form action="{{ route('ppdb.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  @submit="submitLoading = true"
                  class="space-y-8">
                @csrf

                {{-- SEKSI KHUSUS: PILIHAN JALUR PENDAFTARAN RESMI --}}
                <div class="space-y-3.5 p-5 rounded-3xl bg-gradient-to-br from-forest-50 via-white to-amber-50/40 dark:from-forest-950 dark:via-forest-900 dark:to-forest-950 border-2 border-forest-600/30 dark:border-forest-700 shadow-sm">
                    <div class="flex items-center justify-between gap-2 pb-2 border-b border-forest-200/60 dark:border-forest-800">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-forest-900 text-white font-extrabold text-xs flex items-center justify-center">★</span>
                            <h2 class="font-black text-sm sm:text-base text-forest-950 dark:text-white">Pilih Jalur Pendaftaran Resmi <span class="text-rose-500">*</span></h2>
                        </div>
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-forest-100 text-forest-800 dark:bg-forest-800 dark:text-emerald-300">
                            Wajib Dipilih
                        </span>
                    </div>

                    <p class="text-xs text-gray-600 dark:text-gray-300">
                        Pilih satu jalur pendaftaran yang sesuai dengan kondisi calon peserta didik:
                    </p>

                    {{-- Radio Cards Jalur --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                        {{-- 1. Jalur Zonasi --}}
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition select-none"
                               :class="jalur === 'Zonasi' ? 'border-emerald-600 bg-emerald-50/80 dark:bg-emerald-950/40 shadow-md ring-2 ring-emerald-500/30' : 'border-gray-200 dark:border-forest-800 hover:border-gray-300 bg-white dark:bg-forest-900/60'">
                            <input type="radio" name="jalur_pendaftaran" value="Zonasi" x-model="jalur" class="sr-only">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-2xl">📍</span>
                                <span x-show="jalur === 'Zonasi'" class="w-4 h-4 rounded-full bg-emerald-600 flex items-center justify-center text-white text-[10px] font-bold">✓</span>
                            </div>
                            <span class="font-extrabold text-xs sm:text-sm text-forest-950 dark:text-white">Jalur Zonasi</span>
                            <span class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 leading-snug">
                                Prioritas berdasarkan jarak domisili alamat rumah sesuai Kartu Keluarga (KK).
                            </span>
                            <span class="mt-2 text-[10px] font-bold text-emerald-700 dark:text-emerald-300 inline-block">
                                Kuota Utama SD
                            </span>
                        </label>

                        {{-- 2. Jalur Afirmasi --}}
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition select-none"
                               :class="jalur === 'Afirmasi' ? 'border-amber-500 bg-amber-50/80 dark:bg-amber-950/40 shadow-md ring-2 ring-amber-400/30' : 'border-gray-200 dark:border-forest-800 hover:border-gray-300 bg-white dark:bg-forest-900/60'">
                            <input type="radio" name="jalur_pendaftaran" value="Afirmasi" x-model="jalur" class="sr-only">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-2xl">🤝</span>
                                <span x-show="jalur === 'Afirmasi'" class="w-4 h-4 rounded-full bg-amber-500 flex items-center justify-center text-white text-[10px] font-bold">✓</span>
                            </div>
                            <span class="font-extrabold text-xs sm:text-sm text-forest-950 dark:text-white">Jalur Afirmasi</span>
                            <span class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 leading-snug">
                                Bagi keluarga ekonomi tidak mampu penerima KIP, PKH, KKS, atau penyandang disabilitas.
                            </span>
                            <span class="mt-2 text-[10px] font-bold text-amber-700 dark:text-amber-300 inline-block">
                                Wajib Lampirkan Bukti
                            </span>
                        </label>

                        {{-- 3. Jalur Perpindahan Orang Tua --}}
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition select-none"
                               :class="jalur === 'Perpindahan Orang Tua' ? 'border-indigo-600 bg-indigo-50/80 dark:bg-indigo-950/40 shadow-md ring-2 ring-indigo-500/30' : 'border-gray-200 dark:border-forest-800 hover:border-gray-300 bg-white dark:bg-forest-900/60'">
                            <input type="radio" name="jalur_pendaftaran" value="Perpindahan Orang Tua" x-model="jalur" class="sr-only">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-2xl">🏢</span>
                                <span x-show="jalur === 'Perpindahan Orang Tua'" class="w-4 h-4 rounded-full bg-indigo-600 flex items-center justify-center text-white text-[10px] font-bold">✓</span>
                            </div>
                            <span class="font-extrabold text-xs sm:text-sm text-forest-950 dark:text-white">Perpindahan Orang Tua</span>
                            <span class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 leading-snug">
                                Mengikuti perpindahan tugas orang tua/wali instansi atau anak pendidik/tendik.
                            </span>
                            <span class="mt-2 text-[10px] font-bold text-indigo-700 dark:text-indigo-300 inline-block">
                                Wajib SK Mutasi / Tugas
                            </span>
                        </label>
                    </div>

                    {{-- Form Tambahan Khusus Berdasarkan Jalur --}}
                    {{-- A. Form Khusus Jalur Afirmasi --}}
                    <div x-show="jalur === 'Afirmasi'" x-transition x-cloak class="mt-3 p-4 rounded-2xl bg-amber-100/70 dark:bg-amber-950/60 border border-amber-300 dark:border-amber-700 space-y-2">
                        <label for="nomor_kip_pkh" class="block text-xs font-bold text-amber-950 dark:text-amber-100">
                            Nomor Kartu Bansos (KIP / PKH / KKS / SKTM) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="nomor_kip_pkh"
                               id="nomor_kip_pkh"
                               value="{{ old('nomor_kip_pkh') }}"
                               :required="jalur === 'Afirmasi'"
                               placeholder="Contoh: KIP-3211-XXXXX / Nomor Kartu PKH / KKS"
                               class="w-full px-4 py-2.5 rounded-xl border border-amber-300 dark:border-amber-700 bg-white dark:bg-forest-900 text-xs sm:text-sm font-mono text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition">
                        <p class="text-[10px] text-amber-800 dark:text-amber-300">
                            * Berkas fisik scan/foto kartu atau surat keterangan tidak mampu (SKTM) wajib diunggah pada bagian berkas di bawah.
                        </p>
                    </div>

                    {{-- B. Form Khusus Jalur Perpindahan Orang Tua --}}
                    <div x-show="jalur === 'Perpindahan Orang Tua'" x-transition x-cloak class="mt-3 p-4 rounded-2xl bg-indigo-100/70 dark:bg-indigo-950/60 border border-indigo-300 dark:border-indigo-700 space-y-2">
                        <label for="instansi_tujuan_mutasi" class="block text-xs font-bold text-indigo-950 dark:text-indigo-100">
                            Nama Instansi / Kantor Tempat Penugasan Orang Tua <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="instansi_tujuan_mutasi"
                               id="instansi_tujuan_mutasi"
                               value="{{ old('instansi_tujuan_mutasi') }}"
                               :required="jalur === 'Perpindahan Orang Tua'"
                               placeholder="Contoh: Kantor Disdik Kab. Sumedang / Koramil / Puskesmas / Perusahaan PT..."
                               class="w-full px-4 py-2.5 rounded-xl border border-indigo-300 dark:border-indigo-700 bg-white dark:bg-forest-900 text-xs sm:text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition">
                        <p class="text-[10px] text-indigo-800 dark:text-indigo-300">
                            * Surat Keputusan (SK) pindah tugas resmi instansi wajib diunggah pada bagian berkas di bawah.
                        </p>
                    </div>

                    {{-- C. Form Khusus Jalur Zonasi (Estimasi Jarak) --}}
                    <div x-show="jalur === 'Zonasi'" x-transition x-cloak class="mt-3 p-4 rounded-2xl bg-emerald-100/60 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-700/60 space-y-2">
                        <label for="jarak_ke_sekolah_meter" class="block text-xs font-bold text-emerald-950 dark:text-emerald-100">
                            Estimasi Jarak Rumah ke SDN Cinanggerang I (Meter) <span class="text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="number"
                               name="jarak_ke_sekolah_meter"
                               id="jarak_ke_sekolah_meter"
                               value="{{ old('jarak_ke_sekolah_meter') }}"
                               placeholder="Contoh: 350 (meter)"
                               class="w-full px-4 py-2.5 rounded-xl border border-emerald-300 dark:border-emerald-700 bg-white dark:bg-forest-900 text-xs sm:text-sm font-mono text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 transition">
                        <p class="text-[10px] text-emerald-800 dark:text-emerald-300">
                            Panitia akan memverifikasi kesesuaian domisili berdasarkan alamat RT/RW di Kartu Keluarga.
                        </p>
                    </div>
                </div>

                {{-- SEKSI 1: IDENTITAS CALON SISWA --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-2 border-b border-gray-100 dark:border-forest-800">
                        <span class="w-7 h-7 rounded-lg bg-forest-100 text-forest-900 font-extrabold text-xs flex items-center justify-center">1</span>
                        <h2 class="font-black text-sm sm:text-base text-forest-950 dark:text-white">Identitas Calon Peserta Didik</h2>
                    </div>

                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="nama_lengkap" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                            Nama Lengkap Calon Siswa <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="nama_lengkap"
                               id="nama_lengkap"
                               value="{{ old('nama_lengkap') }}"
                               required
                               placeholder="Nama lengkap anak sesuai Akta Kelahiran..."
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-forest-700 bg-gray-50/70 dark:bg-forest-800/80 text-xs sm:text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-forest-800 transition">
                        @error('nama_lengkap')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIK & NISN --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="nik" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                                NIK Calon Siswa (16 Digit) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   name="nik"
                                   id="nik"
                                   maxlength="16"
                                   value="{{ old('nik') }}"
                                   required
                                   placeholder="Contoh: 3211012304190001"
                                   class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-forest-700 bg-gray-50/70 dark:bg-forest-800/80 text-xs sm:text-sm font-mono text-gray-900 dark:text-white focus:ring-2 focus:ring-forest-800 transition">
                            <span class="text-[10px] text-gray-400 mt-0.5 block">Sesuai data Kartu Keluarga</span>
                            @error('nik')
                                <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nisn" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                                NISN (Nomor Induk Siswa Nasional)
                            </label>
                            <input type="text"
                                   name="nisn"
                                   id="nisn"
                                   maxlength="10"
                                   value="{{ old('nisn') }}"
                                   placeholder="10 digit (Kosongkan jika belum memiliki)"
                                   class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-forest-700 bg-gray-50/70 dark:bg-forest-800/80 text-xs sm:text-sm font-mono text-gray-900 dark:text-white focus:ring-2 focus:ring-forest-800 transition">
                            <span class="text-[10px] text-gray-400 mt-0.5 block">Opsional bagi yang pernah PAUD/TK</span>
                            @error('nisn')
                                <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Jenis Kelamin, Tempat Lahir & Tanggal Lahir --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="jenis_kelamin" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                                Jenis Kelamin <span class="text-rose-500">*</span>
                            </label>
                            <select name="jenis_kelamin"
                                    id="jenis_kelamin"
                                    required
                                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-forest-700 bg-gray-50/70 dark:bg-forest-800/80 text-xs sm:text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-forest-800 transition">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                            </select>
                        </div>

                        <div>
                            <label for="tempat_lahir" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                                Tempat Lahir <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   name="tempat_lahir"
                                   id="tempat_lahir"
                                   value="{{ old('tempat_lahir', 'Sumedang') }}"
                                   required
                                   placeholder="Kota / Kab Lahir..."
                                   class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-forest-700 bg-gray-50/70 dark:bg-forest-800/80 text-xs sm:text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-forest-800 transition">
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                                Tanggal Lahir <span class="text-rose-500">*</span>
                            </label>
                            <input type="date"
                                   name="tanggal_lahir"
                                   id="tanggal_lahir"
                                   value="{{ old('tanggal_lahir') }}"
                                   required
                                   class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-forest-700 bg-gray-50/70 dark:bg-forest-800/80 text-xs sm:text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-forest-800 transition">
                        </div>
                    </div>

                    {{-- Asal Jenjang Pendidikan & Nama Sekolah --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <div>
                            <label for="asal_jenjang" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                                Asal Jenjang Pendidikan <span class="text-rose-500">*</span>
                            </label>
                            <select name="asal_jenjang"
                                    id="asal_jenjang"
                                    x-model="asalJenjang"
                                    required
                                    class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-forest-700 bg-gray-50/70 dark:bg-forest-800/80 text-xs sm:text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-forest-800 transition font-semibold">
                                <option value="Rumah Tangga">Langsung dari Rumah Tangga (Non-Formal)</option>
                                <option value="TK">TK (Taman Kanak-kanak)</option>
                                <option value="RA">RA (Raudhatul Athfal)</option>
                                <option value="PAUD">PAUD (Pendidikan Anak Usia Dini)</option>
                            </select>
                        </div>

                        {{-- Nama Asal Sekolah (Dinamis: Muncul jika TK/RA/PAUD) --}}
                        <div x-show="asalJenjang !== 'Rumah Tangga'" x-transition x-cloak>
                            <label for="nama_asal_sekolah" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                                Nama Asal Sekolah (TK / RA / PAUD) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   name="nama_asal_sekolah"
                                   id="nama_asal_sekolah"
                                   value="{{ old('nama_asal_sekolah') }}"
                                   :required="asalJenjang !== 'Rumah Tangga'"
                                   placeholder="Contoh: TK PGRI Pamulihan / RA Al-Hidayah"
                                   class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-forest-700 bg-gray-50/70 dark:bg-forest-800/80 text-xs sm:text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-forest-800 transition">
                        </div>
                    </div>
                </div>

                {{-- SEKSI 2: DATA ORANG TUA / WALI & DOMISILI --}}
                <div class="space-y-4 pt-2">
                    <div class="flex items-center gap-2 pb-2 border-b border-gray-100 dark:border-forest-800">
                        <span class="w-7 h-7 rounded-lg bg-amber-100 text-amber-900 font-extrabold text-xs flex items-center justify-center">2</span>
                        <h2 class="font-black text-sm sm:text-base text-forest-950 dark:text-white">Data Orang Tua & Domisili</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="nama_ibu_kandung" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                                Nama Ibu Kandung <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   name="nama_ibu_kandung"
                                   id="nama_ibu_kandung"
                                   value="{{ old('nama_ibu_kandung') }}"
                                   required
                                   placeholder="Sesuai Akta Kelahiran / KK..."
                                   class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-forest-700 bg-gray-50/70 dark:bg-forest-800/80 text-xs sm:text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-forest-800 transition">
                        </div>

                        <div>
                            <label for="nama_ayah_kandung" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                                Nama Ayah Kandung
                            </label>
                            <input type="text"
                                   name="nama_ayah_kandung"
                                   id="nama_ayah_kandung"
                                   value="{{ old('nama_ayah_kandung') }}"
                                   placeholder="Nama lengkap ayah kandung..."
                                   class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-forest-700 bg-gray-50/70 dark:bg-forest-800/80 text-xs sm:text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-forest-800 transition">
                        </div>
                    </div>

                    <div>
                        <label for="nomor_whatsapp" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                            Nomor WhatsApp Aktif (Untuk Informasi Seleksi) <span class="text-rose-500">*</span>
                        </label>
                        <input type="tel"
                               name="nomor_whatsapp"
                               id="nomor_whatsapp"
                               value="{{ old('nomor_whatsapp') }}"
                               required
                               placeholder="Contoh: 081234567890"
                               class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-forest-700 bg-gray-50/70 dark:bg-forest-800/80 text-xs sm:text-sm font-mono text-gray-900 dark:text-white focus:ring-2 focus:ring-forest-800 transition">
                        <span class="text-[10px] text-gray-400 mt-0.5 block">Panitia akan mengirimkan konfirmasi penerimaan ke nomor ini</span>
                    </div>

                    <div>
                        <label for="alamat_tinggal" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                            Alamat Lengkap Tempat Tinggal <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="alamat_tinggal"
                                  id="alamat_tinggal"
                                  rows="3"
                                  required
                                  placeholder="Dusun/Blok, RT/RW, Desa Cinanggerang, Kec. Pamulihan, Kab. Sumedang..."
                                  class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-forest-700 bg-gray-50/70 dark:bg-forest-800/80 text-xs sm:text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-forest-800 transition">{{ old('alamat_tinggal') }}</textarea>
                    </div>
                </div>

                {{-- SEKSI 3: UNGGAH BERKAS PERSYARATAN DIGITAL --}}
                <div class="space-y-4 pt-2">
                    <div class="flex items-center gap-2 pb-2 border-b border-gray-100 dark:border-forest-800">
                        <span class="w-7 h-7 rounded-lg bg-teal-100 text-teal-900 font-extrabold text-xs flex items-center justify-center">3</span>
                        <h2 class="font-black text-sm sm:text-base text-forest-950 dark:text-white">Unggah Berkas Persyaratan Digital</h2>
                    </div>

                    <p class="text-xs text-gray-500">
                        Format berkas yang diterima: <strong>JPG, JPEG, PNG, atau PDF</strong> dengan ukuran maksimal <strong>2 MB</strong> per berkas.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- 1. Kartu Keluarga (Wajib) --}}
                        <div class="p-4 rounded-2xl bg-gray-50 dark:bg-forest-800/40 border border-gray-200 dark:border-forest-700 space-y-2">
                            <label class="block text-xs font-bold text-gray-800 dark:text-gray-200">
                                Scan / Foto Kartu Keluarga (KK) <span class="text-rose-500">*</span>
                            </label>
                            <input type="file"
                                   name="file_kk"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   required
                                   @change="handleFile($event, 'kk')"
                                   class="text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-forest-900 file:text-white hover:file:bg-forest-800 cursor-pointer w-full">
                            <p x-show="fileKkName" class="text-[11px] font-semibold text-emerald-600 truncate" x-text="'✓ ' + fileKkName"></p>
                        </div>

                        {{-- 2. Akta Kelahiran (Wajib) --}}
                        <div class="p-4 rounded-2xl bg-gray-50 dark:bg-forest-800/40 border border-gray-200 dark:border-forest-700 space-y-2">
                            <label class="block text-xs font-bold text-gray-800 dark:text-gray-200">
                                Scan / Foto Akta Kelahiran <span class="text-rose-500">*</span>
                            </label>
                            <input type="file"
                                   name="file_akta"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   required
                                   @change="handleFile($event, 'akta')"
                                   class="text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-forest-900 file:text-white hover:file:bg-forest-800 cursor-pointer w-full">
                            <p x-show="fileAktaName" class="text-[11px] font-semibold text-emerald-600 truncate" x-text="'✓ ' + fileAktaName"></p>
                        </div>
                    </div>

                    {{-- 3. Ijazah / SKL TK (Kondisional: Wajib jika TK/RA/PAUD) --}}
                    <div x-show="asalJenjang !== 'Rumah Tangga'" x-transition x-cloak
                         class="p-4 rounded-2xl bg-indigo-50/60 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-800 space-y-2">
                        <label class="block text-xs font-bold text-indigo-950 dark:text-indigo-200">
                            Scan / Foto Ijazah atau Surat Keterangan Lulus (SKL) TK/RA/PAUD <span class="text-rose-500">*</span>
                        </label>
                        <input type="file"
                               name="file_ijazah"
                               accept=".jpg,.jpeg,.png,.pdf"
                               :required="asalJenjang !== 'Rumah Tangga'"
                               @change="handleFile($event, 'ijazah')"
                               class="text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-900 file:text-white hover:file:bg-indigo-800 cursor-pointer w-full">
                        <p x-show="fileIjazahName" class="text-[11px] font-semibold text-indigo-700 truncate" x-text="'✓ ' + fileIjazahName"></p>
                        <p class="text-[10px] text-indigo-600 dark:text-indigo-400">
                            Wajib dilampirkan bagi calon siswa yang berasal dari jenjang formal TK, RA, atau PAUD.
                        </p>
                    </div>

                    {{-- 4. Berkas Bukti Afirmasi (Kondisional: Wajib jika Jalur Afirmasi) --}}
                    <div x-show="jalur === 'Afirmasi'" x-transition x-cloak
                         class="p-4 rounded-2xl bg-amber-50/80 dark:bg-amber-950/40 border-2 border-amber-300 dark:border-amber-700 space-y-2">
                        <label class="block text-xs font-bold text-amber-950 dark:text-amber-100">
                            Scan / Foto Kartu Bansos (KIP / PKH / KKS / SKTM Kelurahan) <span class="text-rose-500">*</span>
                        </label>
                        <input type="file"
                               name="file_bukti_afirmasi"
                               accept=".jpg,.jpeg,.png,.pdf"
                               :required="jalur === 'Afirmasi'"
                               @change="handleFile($event, 'afirmasi')"
                               class="text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-600 file:text-white hover:file:bg-amber-700 cursor-pointer w-full">
                        <p x-show="fileAfirmasiName" class="text-[11px] font-semibold text-amber-700 truncate" x-text="'✓ ' + fileAfirmasiName"></p>
                        <p class="text-[10px] text-amber-700 dark:text-amber-300">
                            Wajib melampirkan bukti kepesertaan Program Indonesia Pintar / PKH / KKS atau Surat Keterangan Tidak Mampu dari Desa/Kelurahan setempat.
                        </p>
                    </div>

                    {{-- 5. Berkas Bukti Surat Pindah Tugas (Kondisional: Wajib jika Jalur Perpindahan Orang Tua) --}}
                    <div x-show="jalur === 'Perpindahan Orang Tua'" x-transition x-cloak
                         class="p-4 rounded-2xl bg-indigo-50/80 dark:bg-indigo-950/40 border-2 border-indigo-300 dark:border-indigo-700 space-y-2">
                        <label class="block text-xs font-bold text-indigo-950 dark:text-indigo-100">
                            Scan / PDF Surat Keputusan (SK) Pindah Tugas / Surat Tugas Dinas <span class="text-rose-500">*</span>
                        </label>
                        <input type="file"
                               name="file_surat_pindah_tugas"
                               accept=".jpg,.jpeg,.png,.pdf"
                               :required="jalur === 'Perpindahan Orang Tua'"
                               @change="handleFile($event, 'pindah')"
                               class="text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-800 file:text-white hover:file:bg-indigo-900 cursor-pointer w-full">
                        <p x-show="filePindahName" class="text-[11px] font-semibold text-indigo-700 truncate" x-text="'✓ ' + filePindahName"></p>
                        <p class="text-[10px] text-indigo-700 dark:text-indigo-300">
                            Wajib melampirkan Surat Keputusan mutasi / penugasan orang tua dari instansi, lembaga, atau perusahaan yang mempekerjakan.
                        </p>
                    </div>

                </div>

                {{-- Pernyataan Kebenaran Data --}}
                <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-xs text-amber-900 dark:text-amber-200 flex items-start gap-2.5">
                    <input type="checkbox" required id="pernyataan" class="mt-0.5 rounded text-forest-800 focus:ring-forest-800">
                    <label for="pernyataan" class="leading-relaxed font-medium">
                        Saya menyatakan bahwa data yang diisikan serta berkas yang diunggah adalah <strong>benar, sah, dan dapat dipertanggungjawabkan</strong> sesuai ketentuan penerimaan peserta didik baru di SDN Cinanggerang I.
                    </label>
                </div>

                {{-- Tombol Kirim Pendaftaran --}}
                <div class="pt-2">
                    <button type="submit"
                            :disabled="submitLoading"
                            class="w-full py-4 px-6 rounded-2xl bg-forest-900 hover:bg-forest-800 text-white font-extrabold text-sm shadow-xl shadow-forest-900/25 transition flex items-center justify-center gap-2 transform hover:-translate-y-0.5 disabled:opacity-50 cursor-pointer">
                        <template x-if="submitLoading">
                            <svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        </template>
                        <span x-text="submitLoading ? 'Memproses dan Mengunggah Berkas...' : 'Kirim Pendaftaran Siswa Baru Sekarang ➔'"></span>
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
