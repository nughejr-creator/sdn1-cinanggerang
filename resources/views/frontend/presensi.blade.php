@extends('layouts.frontend')

@section('title', 'Presensi Digital Guru & Dinas Luar - SDN Cinanggerang I')
@section('meta_description', 'Layanan Presensi Mandiri GTK SDN Cinanggerang I berbasis Geofencing GPS, Validasi Swafoto (Selfie), Dinas Luar (DL), dan Pengajuan Sakit/Izin.')
@section('canonical_url', route('presensi.index'))

@section('content')
<div class="min-h-[85vh] bg-gradient-to-b from-forest-950 via-forest-900/90 to-slate-100 py-6 sm:py-10 px-4 flex flex-col justify-center items-center"
     x-data="presensiGpsApp({
        schoolLat: {{ $schoolLat }},
        schoolLng: {{ $schoolLng }},
        maxRadius: {{ $maxRadius }},
        todayRecord: @js($todayAttendance),
        submitUrl: '{{ route('presensi.store') }}',
        csrfToken: '{{ csrf_token() }}'
     })"
     x-init="initApp()">

    {{-- KARTU UTAMA PRESENSI GURU MOBILE-FIRST --}}
    <div class="w-full max-w-md bg-white dark:bg-forest-900 rounded-3xl shadow-2xl border-t-4 border-sumedang-amber overflow-hidden relative">

        {{-- Header Kartu & Profil Guru --}}
        <div class="bg-gradient-to-r from-forest-950 to-forest-900 text-white p-5 sm:p-6 border-b border-forest-800">
            <div class="flex items-center justify-between gap-3 mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-300">Presensi GTK Digital</span>
                </div>
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-forest-800 text-gray-300 border border-forest-700">
                    NPSN {{ $profile?->npsn ?? '20208801' }}
                </span>
            </div>

            {{-- Profil Guru yang Login --}}
            <div class="flex items-center gap-3.5 pt-1">
                <div class="w-12 h-12 rounded-2xl bg-forest-800 border-2 border-sumedang-amber/70 flex items-center justify-center text-sumedang-amber font-extrabold text-lg shadow-md shrink-0 overflow-hidden">
                    @if(!empty($teacher?->foto_url))
                        <img src="{{ $teacher->foto_url }}" alt="{{ $teacher->nama }}" class="w-full h-full object-cover">
                    @else
                        <span>{{ strtoupper(substr($teacher?->nama ?? $user->name, 0, 2)) }}</span>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <h2 class="text-base font-extrabold text-white truncate leading-tight">
                        {{ $teacher?->nama ?? $user->name }}
                    </h2>
                    <p class="text-xs text-emerald-300 font-medium truncate mt-0.5">
                        {{ $teacher?->jabatan ?? 'Pendidik / Tenaga Kependidikan' }}
                    </p>
                    <p class="text-[10px] text-gray-400 mt-0.5">
                        NIP: {{ $teacher?->nip ?? '-' }}
                    </p>
                </div>
                <div>
                    <form method="POST" action="{{ url('/admin/logout') }}">
                        @csrf
                        <button type="submit" title="Keluar / Ganti Akun" class="p-2 rounded-xl bg-white/10 hover:bg-white/20 text-gray-300 hover:text-white transition text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Jam Digital Real-time & Tanggal --}}
            <div class="mt-4 pt-3.5 border-t border-forest-800/80 flex items-center justify-between">
                <div>
                    <span class="text-[10px] uppercase font-bold text-gray-400 block tracking-wider">Waktu Lokal (WIB):</span>
                    <span class="text-2xl font-black text-sumedang-amber font-mono tracking-tight" x-text="liveTime">00:00:00 WIB</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-gray-400 block">Tanggal:</span>
                    <span class="text-xs font-bold text-emerald-100" x-text="liveDate">-</span>
                </div>
            </div>
        </div>

        {{-- Body Kartu Presensi --}}
        <div class="p-5 sm:p-6 space-y-4">

            {{-- Rekapitulasi Presensi Bulanan Guru (Bulan Berjalan) --}}
            @if(isset($monthlyRecap))
                <div class="rounded-2xl bg-gradient-to-r from-forest-900/10 via-forest-900/5 to-emerald-500/10 dark:from-forest-950/60 dark:to-emerald-950/30 border border-forest-800/20 dark:border-forest-700/40 p-3.5 space-y-2.5 transition">
                    <div class="flex items-center justify-between cursor-pointer" @click="showMonthlyRecap = !showMonthlyRecap">
                        <div class="flex items-center gap-2">
                            <span class="text-base">📊</span>
                            <div>
                                <h4 class="text-xs font-black text-forest-950 dark:text-white uppercase tracking-wider">
                                    Rekap Presensi {{ $monthlyRecap['month_name'] }}
                                </h4>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400">
                                    Total {{ $monthlyRecap['total_recorded'] }} hari terekam
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-black {{ $monthlyRecap['persentase'] >= 90 ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-amber-100 text-amber-800 border border-amber-300' }}">
                                {{ $monthlyRecap['persentase'] }}% Kehadiran
                            </span>
                            <span class="text-xs text-gray-400" x-text="showMonthlyRecap ? '▲' : '▼'"></span>
                        </div>
                    </div>

                    <div x-show="showMonthlyRecap" x-transition x-cloak class="pt-2 border-t border-forest-800/10 dark:border-forest-700/40 grid grid-cols-4 gap-2 text-center text-xs">
                        <div class="p-2 rounded-xl bg-white dark:bg-forest-900 border border-slate-100 dark:border-forest-800">
                            <span class="text-[10px] text-gray-400 block font-bold">Hadir</span>
                            <span class="text-xs font-black text-emerald-600">{{ $monthlyRecap['hadir_tepat'] }}</span>
                        </div>
                        <div class="p-2 rounded-xl bg-white dark:bg-forest-900 border border-slate-100 dark:border-forest-800">
                            <span class="text-[10px] text-gray-400 block font-bold">Telat</span>
                            <span class="text-xs font-black text-amber-600">{{ $monthlyRecap['hadir_terlambat'] }}</span>
                        </div>
                        <div class="p-2 rounded-xl bg-white dark:bg-forest-900 border border-slate-100 dark:border-forest-800">
                            <span class="text-[10px] text-gray-400 block font-bold">Dinas Luar</span>
                            <span class="text-xs font-black text-indigo-600">{{ $monthlyRecap['dinas_luar'] }}</span>
                        </div>
                        <div class="p-2 rounded-xl bg-white dark:bg-forest-900 border border-slate-100 dark:border-forest-800">
                            <span class="text-[10px] text-gray-400 block font-bold">Sakit/Izin</span>
                            <span class="text-xs font-black text-purple-600">{{ $monthlyRecap['sakit'] + $monthlyRecap['izin'] }}</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Notifikasi Hari Libur Resmi (Jika Hari Ini Ditetapkan Libur) --}}
            @if(isset($todayHoliday) && $todayHoliday)
                <div class="p-3.5 rounded-2xl bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-300 dark:border-amber-700/60 dark:from-amber-950/40 dark:to-orange-950/40 text-amber-900 dark:text-amber-200 text-xs font-semibold flex items-start gap-2.5 shadow-sm">
                    <span class="text-xl shrink-0">🏖️</span>
                    <div class="flex-1">
                        <div class="font-extrabold text-amber-800 dark:text-amber-300 flex items-center gap-1.5">
                            <span>HARI INI LIBUR SEKOLAH</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-200 text-amber-900 font-bold uppercase">{{ $todayHoliday->type }}</span>
                        </div>
                        <p class="text-[11px] text-amber-700 dark:text-amber-400 mt-0.5 leading-snug">
                            <span class="font-bold">{{ $todayHoliday->name }}</span>. Guru dan Tenaga Kependidikan tidak diwajibkan melakukan presensi. Presensi tetap dibuka bagi GTK yang bertugas piket atau dinas luar.
                        </p>
                    </div>
                </div>
            @endif

            {{-- Alert Notifikasi Hasil Presensi / Error --}}
            <div x-show="alertMessage" x-cloak
                 :class="alertType === 'success' ? 'bg-emerald-50 border-emerald-300 text-emerald-900' : 'bg-rose-50 border-rose-300 text-rose-900'"
                 class="p-3.5 rounded-2xl border text-xs font-semibold flex items-start gap-2.5 shadow-sm transition">
                <span class="text-base shrink-0" x-text="alertType === 'success' ? '✅' : '⚠️'"></span>
                <div class="flex-1">
                    <p class="leading-relaxed" x-text="alertMessage"></p>
                </div>
                <button type="button" @click="alertMessage = ''" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            {{-- Pilihan Tab Aksi: Datang vs Pulang (Hanya untuk Reguler / Dinas Luar) --}}
            <div x-show="!isSakitOrIzinLocked" class="p-1 rounded-2xl bg-slate-100 dark:bg-forest-950 flex gap-1">
                <button type="button"
                        @click="actionType = 'in'"
                        :class="actionType === 'in' ? 'bg-white dark:bg-forest-800 text-forest-950 dark:text-white shadow-sm font-extrabold' : 'text-gray-500 font-bold hover:text-gray-800'"
                        class="flex-1 py-2 rounded-xl text-xs flex items-center justify-center gap-1.5 transition">
                    <span>🌅</span>
                    <span>Presensi Datang</span>
                    <span x-show="todayRecord && todayRecord.jam_masuk" class="w-2 h-2 rounded-full bg-emerald-500" title="Sudah Absen Datang"></span>
                </button>
                <button type="button"
                        @click="actionType = 'out'"
                        :class="actionType === 'out' ? 'bg-white dark:bg-forest-800 text-forest-950 dark:text-white shadow-sm font-extrabold' : 'text-gray-500 font-bold hover:text-gray-800'"
                        class="flex-1 py-2 rounded-xl text-xs flex items-center justify-center gap-1.5 transition">
                    <span>🌇</span>
                    <span>Presensi Pulang</span>
                    <span x-show="todayRecord && todayRecord.jam_pulang" class="w-2 h-2 rounded-full bg-amber-500" title="Sudah Absen Pulang"></span>
                </button>
            </div>

            {{-- Status Rekap Presensi Hari Ini --}}
            <div class="p-3 rounded-2xl bg-slate-50 dark:bg-forest-950/40 border border-slate-200/80 dark:border-forest-800/80 text-xs space-y-1.5">
                <div class="flex items-center justify-between">
                    <span class="text-gray-500 font-medium">Status Kehadiran Hari Ini:</span>
                    <template x-if="todayRecord">
                        <span class="font-extrabold text-emerald-600 flex items-center gap-1">
                            <span x-text="formatJam(todayRecord.jam_masuk) + ' WIB'"></span>
                            <span class="text-[10px] px-1.5 py-0.2 rounded"
                                  :class="todayRecord.attendance_type === 'Dinas Luar' ? 'bg-indigo-100 text-indigo-800' : (todayRecord.attendance_type === 'Sakit' ? 'bg-purple-100 text-purple-800' : (todayRecord.attendance_type === 'Izin' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'))"
                                  x-text="todayRecord.attendance_type === 'Dinas Luar' ? 'Dinas Luar' : todayRecord.status"></span>
                        </span>
                    </template>
                    <template x-if="!todayRecord">
                        <span class="text-gray-400 italic">Belum Presensi</span>
                    </template>
                </div>

                <template x-if="!isSakitOrIzinLocked">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 font-medium">Status Pulang:</span>
                        <template x-if="todayRecord && todayRecord.jam_pulang">
                            <span class="font-extrabold text-sumedang-amber flex items-center gap-1">
                                <span x-text="formatJam(todayRecord.jam_pulang) + ' WIB'"></span>
                                <span class="text-[10px] px-1.5 py-0.2 rounded bg-amber-100 text-amber-800">Tercatat</span>
                            </span>
                        </template>
                        <template x-if="!todayRecord || !todayRecord.jam_pulang">
                            <span class="text-gray-400 italic">Belum Presensi</span>
                        </template>
                    </div>
                </template>
            </div>

            {{-- ======================================================== --}}
            {{-- 0. KARTU PROTEKSI KHUSUS SAKIT (SKD) ATAU IZIN RESMI --}}
            {{-- ======================================================== --}}
            <div x-show="isSakitOrIzinLocked" x-cloak
                 class="p-5 rounded-3xl bg-gradient-to-br from-purple-50 via-pink-50/30 to-white dark:from-forest-950 dark:via-purple-950/20 dark:to-forest-950 border-2 border-purple-300 dark:border-purple-700 shadow-lg text-center space-y-3.5 my-1">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-purple-100 dark:bg-purple-900/60 border border-purple-300 dark:border-purple-700 flex items-center justify-center text-3xl shadow-inner">
                    <span x-text="todayRecord && (todayRecord.status === 'Sakit (SKD)' || todayRecord.attendance_type === 'Sakit') ? '🏥' : '📝'"></span>
                </div>

                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-200/80 dark:bg-purple-800/80 text-purple-900 dark:text-purple-100 text-[10px] font-black uppercase tracking-wider">
                        ✓ Status Kehadiran Sah Hari Ini
                    </span>
                    <h3 class="text-base font-extrabold text-forest-950 dark:text-white mt-1.5"
                        x-text="todayRecord && (todayRecord.status === 'Sakit (SKD)' || todayRecord.attendance_type === 'Sakit') ? 'Laporan Sakit (SKD) Tercatat' : 'Permohonan Izin Resmi Tercatat'">
                    </h3>
                    <p class="text-xs text-purple-900 dark:text-purple-200 mt-1 leading-relaxed">
                        Pengajuan Anda telah tercatat sah seharian penuh pada sistem presensi SDN Cinanggerang I.
                    </p>
                </div>

                <div class="p-3 rounded-2xl bg-white/90 dark:bg-forest-900/90 border border-purple-200/60 dark:border-forest-800 text-left text-xs space-y-1.5">
                    <div class="flex items-center justify-between text-gray-500 dark:text-gray-400 text-[11px]">
                        <span>Status:</span>
                        <span class="font-bold text-purple-700 dark:text-purple-300" x-text="todayRecord ? todayRecord.status : ''"></span>
                    </div>
                    <div class="flex items-center justify-between text-gray-500 dark:text-gray-400 text-[11px]">
                        <span>Keterangan:</span>
                        <span class="font-semibold text-gray-800 dark:text-gray-200 truncate max-w-[200px]" x-text="todayRecord ? (todayRecord.duty_description || todayRecord.keterangan || '-') : '-'"></span>
                    </div>
                    <template x-if="todayRecord && todayRecord.duty_proof_file">
                        <div class="flex items-center justify-between text-gray-500 dark:text-gray-400 text-[11px] pt-1 border-t border-purple-100 dark:border-forest-800">
                            <span>Bukti Dokumen:</span>
                            <span class="font-bold text-emerald-600 flex items-center gap-1">
                                <span>✓ Berkas Terlampir</span>
                            </span>
                        </div>
                    </template>
                </div>

                <div class="p-3 rounded-2xl bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800 text-xs text-purple-900 dark:text-purple-200 flex items-start gap-2 text-left font-medium">
                    <span class="text-base shrink-0">ℹ️</span>
                    <p class="text-[11px] leading-snug">
                        Anda tidak perlu melakukan presensi pulang hari ini. Semoga lekas pulih dan dapat kembali beraktivitas.
                    </p>
                </div>
            </div>

            {{-- ======================================================== --}}
            {{-- 1. KARTU PROTEKSI PRESENSI DATANG (SUDAH ABSEN MASUK) --}}
            {{-- ======================================================== --}}
            <div x-show="!isSakitOrIzinLocked && actionType === 'in' && todayRecord && todayRecord.jam_masuk" x-cloak
                 class="p-5 rounded-3xl bg-gradient-to-br from-emerald-50 via-teal-50/40 to-white dark:from-forest-950 dark:via-forest-900 dark:to-forest-950 border-2 border-emerald-400 dark:border-emerald-600 shadow-lg text-center space-y-3.5 my-1">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-100 dark:bg-emerald-900/60 border border-emerald-300 dark:border-emerald-700 flex items-center justify-center text-3xl shadow-inner">
                    🛡️
                </div>

                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-200/80 dark:bg-emerald-800/80 text-emerald-900 dark:text-emerald-100 text-[10px] font-black uppercase tracking-wider">
                        ✓ Presensi Datang Sudah Tercatat
                    </span>
                    <h3 class="text-base font-extrabold text-forest-950 dark:text-white mt-1.5">
                        Anda Telah Melakukan Presensi Datang
                    </h3>
                    <p class="text-xs text-emerald-900 dark:text-emerald-200 mt-1 leading-relaxed">
                        Tercatat pada pukul <span class="text-sm font-black text-forest-900 dark:text-emerald-300 font-mono px-2 py-0.5 rounded-lg bg-emerald-100 dark:bg-forest-800 border border-emerald-300 dark:border-forest-700" x-text="formatJam(todayRecord.jam_masuk) + ' WIB'"></span>
                    </p>
                </div>

                {{-- Detail Rincian Presensi Datang --}}
                <div class="p-3 rounded-2xl bg-white/90 dark:bg-forest-900/90 border border-emerald-200/60 dark:border-forest-800 text-left text-xs space-y-1.5">
                    <div class="flex items-center justify-between text-gray-500 dark:text-gray-400 text-[11px]">
                        <span>Mode Kehadiran:</span>
                        <span class="font-bold text-forest-900 dark:text-emerald-300" x-text="todayRecord.attendance_type === 'Dinas Luar' ? '💼 Dinas Luar' : '📍 Reguler (Sekolah)'"></span>
                    </div>
                    <template x-if="todayRecord.attendance_type === 'Dinas Luar' && todayRecord.duty_location_name">
                        <div class="flex items-center justify-between text-gray-500 dark:text-gray-400 text-[11px]">
                            <span>Lokasi Tugas:</span>
                            <span class="font-bold text-indigo-700 dark:text-indigo-300 truncate max-w-[200px]" x-text="todayRecord.duty_location_name"></span>
                        </div>
                    </template>
                    <div class="flex items-center justify-between text-gray-500 dark:text-gray-400 text-[11px]">
                        <span>Status Kehadiran:</span>
                        <span class="font-bold text-emerald-700 dark:text-emerald-400" x-text="todayRecord.status"></span>
                    </div>
                </div>

                {{-- Pemberitahuan Proteksi Dobel Absen --}}
                <div class="p-3 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-700/60 text-xs text-amber-900 dark:text-amber-200 flex items-start gap-2 text-left font-medium">
                    <span class="text-base shrink-0">🔒</span>
                    <div>
                        <p class="font-bold">Proteksi Anti-Dobel Absen Aktif</p>
                        <p class="text-[11px] text-amber-800 dark:text-amber-300 mt-0.5 leading-snug">
                            Formulir presensi datang dinonaktifkan agar data kehadiran Anda tidak terduplikasi.
                        </p>
                    </div>
                </div>

                {{-- Tombol Lanjut ke Presensi Pulang --}}
                <template x-if="!todayRecord.jam_pulang">
                    <button type="button" @click="actionType = 'out'"
                            class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-forest-900 to-forest-800 hover:from-forest-800 hover:to-forest-700 text-white font-extrabold text-xs shadow-lg shadow-forest-900/20 transition flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                        <span>🌇</span>
                        <span>Lanjut ke Menu Presensi Pulang ➔</span>
                    </button>
                </template>
                <template x-if="todayRecord.jam_pulang">
                    <div class="p-2.5 rounded-2xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200 text-xs font-bold flex items-center justify-center gap-1.5">
                        <span>✓</span>
                        <span>Presensi pulang juga telah tercatat pukul <strong x-text="formatJam(todayRecord.jam_pulang) + ' WIB'"></strong></span>
                    </div>
                </template>
            </div>

            {{-- ======================================================== --}}
            {{-- 2. KARTU PROTEKSI PRESENSI PULANG (SUDAH ABSEN PULANG) --}}
            {{-- ======================================================== --}}
            <div x-show="!isSakitOrIzinLocked && actionType === 'out' && todayRecord && todayRecord.jam_pulang" x-cloak
                 class="p-5 rounded-3xl bg-gradient-to-br from-amber-50 via-emerald-50/40 to-white dark:from-forest-950 dark:via-forest-900 dark:to-forest-950 border-2 border-sumedang-amber dark:border-amber-600 shadow-lg text-center space-y-3.5 my-1">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-100 dark:bg-amber-900/60 border border-amber-300 dark:border-amber-700 flex items-center justify-center text-3xl shadow-inner">
                    🎉
                </div>

                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-200/80 dark:bg-amber-800/80 text-amber-900 dark:text-amber-100 text-[10px] font-black uppercase tracking-wider">
                        ✓ Seluruh Presensi Selesai
                    </span>
                    <h3 class="text-base font-extrabold text-forest-950 dark:text-white mt-1.5">
                        Anda Telah Melakukan Presensi Pulang
                    </h3>
                    <p class="text-xs text-amber-900 dark:text-amber-200 mt-1 leading-relaxed">
                        Tercatat pada pukul <span class="text-sm font-black text-amber-950 dark:text-amber-300 font-mono px-2 py-0.5 rounded-lg bg-amber-100 dark:bg-forest-800 border border-amber-300 dark:border-forest-700" x-text="formatJam(todayRecord.jam_pulang) + ' WIB'"></span>
                    </p>
                </div>

                {{-- Detail Rekap Hari Ini --}}
                <div class="p-3 rounded-2xl bg-white/90 dark:bg-forest-900/90 border border-amber-200/60 dark:border-forest-800 text-left text-xs space-y-1.5">
                    <div class="flex items-center justify-between text-gray-500 dark:text-gray-400 text-[11px]">
                        <span>Jam Datang:</span>
                        <span class="font-bold text-emerald-700 dark:text-emerald-300 font-mono" x-text="formatJam(todayRecord.jam_masuk) + ' WIB'"></span>
                    </div>
                    <div class="flex items-center justify-between text-gray-500 dark:text-gray-400 text-[11px]">
                        <span>Jam Pulang:</span>
                        <span class="font-bold text-sumedang-amber font-mono" x-text="formatJam(todayRecord.jam_pulang) + ' WIB'"></span>
                    </div>
                    <div class="flex items-center justify-between text-gray-500 dark:text-gray-400 text-[11px]">
                        <span>Mode Kehadiran:</span>
                        <span class="font-bold text-forest-900 dark:text-emerald-300" x-text="todayRecord.attendance_type"></span>
                    </div>
                </div>

                <div class="p-3 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-700/60 text-xs text-emerald-900 dark:text-emerald-200 flex items-start gap-2 text-left font-medium">
                    <span class="text-base shrink-0">🔒</span>
                    <div>
                        <p class="font-bold">Proteksi Anti-Dobel Absen Aktif</p>
                        <p class="text-[11px] text-emerald-800 dark:text-emerald-300 mt-0.5 leading-snug">
                            Data kehadiran hari ini telah lengkap dan terkunci dari pengulangan absen.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ======================================================== --}}
            {{-- 3. PERINGATAN WAJIB PRESENSI DATANG TERLEBIH DAHULU --}}
            {{-- ======================================================== --}}
            <div x-show="!isSakitOrIzinLocked && actionType === 'out' && (!todayRecord || !todayRecord.jam_masuk)" x-cloak
                 class="p-4 rounded-3xl bg-rose-50 dark:bg-rose-950/40 border-2 border-rose-300 dark:border-rose-800 text-center space-y-3 my-1">
                <span class="text-3xl block">⚠️</span>
                <div>
                    <h4 class="font-extrabold text-sm text-rose-950 dark:text-rose-200">
                        Belum Melakukan Presensi Datang
                    </h4>
                    <p class="text-xs text-rose-800 dark:text-rose-300 mt-1 leading-relaxed">
                        Anda belum tercatat melakukan <strong>Presensi Datang</strong> hari ini. Silakan selesaikan presensi datang terlebih dahulu sebelum merekam presensi pulang.
                    </p>
                </div>
                <button type="button" @click="actionType = 'in'"
                        class="w-full py-3 px-4 rounded-2xl bg-forest-900 hover:bg-forest-800 text-white font-extrabold text-xs shadow-md transition flex items-center justify-center gap-2">
                    <span>🌅</span>
                    <span>Buka Presensi Datang Sekarang ➔</span>
                </button>
            </div>

            {{-- ======================================================== --}}
            {{-- 4. FORMULIR PRESENSI AKTIF (HANYA MUNCUL JIKA BELUM TERKUNCI) --}}
            {{-- ======================================================== --}}
            <div x-show="!isActionLocked" x-cloak class="space-y-4">

                {{-- Banner Peringatan Waktu Pulang Belum Dibuka (< 10:00 WIB) --}}
                <div x-show="actionType === 'out' && !isPulangTimeAllowed && (tipePresensi === 'Reguler' || tipePresensi === 'Dinas Luar')" x-cloak
                     class="p-3.5 rounded-2xl bg-amber-50 dark:bg-amber-950/50 border-2 border-amber-300 dark:border-amber-700/60 text-amber-900 dark:text-amber-200 text-xs text-center space-y-1 mb-2">
                    <div class="font-extrabold flex items-center justify-center gap-1.5 text-sm text-amber-800 dark:text-amber-300">
                        <span>⏰</span>
                        <span>Presensi Pulang Dibuka Pukul 10:00 WIB</span>
                    </div>
                    <p class="text-[11px] text-amber-700 dark:text-amber-400 leading-snug">
                        Waktu saat ini masih pukul <strong x-text="liveTime"></strong>. Presensi kepulangan baru dapat dilakukan setelah pukul 10:00 WIB.
                    </p>
                </div>

                {{-- Info Bantuan jika Pagi Datang Reguler dan Siang Pulang Dinas Luar --}}
                <div x-show="actionType === 'out' && todayRecord && todayRecord.attendance_type === 'Reguler'" x-cloak
                     class="p-2.5 rounded-xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-[11px] text-blue-900 dark:text-blue-200 flex items-center gap-2 mb-2">
                    <span class="text-base shrink-0">💡</span>
                    <span><strong>Info DL:</strong> Pagi tadi Anda datang reguler di sekolah. Jika siang ini sedang tugas/rapat di luar, silakan pilih tab <strong>"Dinas Luar"</strong> untuk absen pulang dari lokasi tugas.</span>
                </div>

                {{-- A. SEGMENTED CONTROL 4 MODE PRESENSI --}}
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 block mb-1.5">
                        Pilih Lokasi / Mode Presensi:
                    </span>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-1 p-1 rounded-2xl bg-slate-100 dark:bg-forest-950 border border-slate-200 dark:border-forest-800">
                        <button type="button"
                                @click="tipePresensi = 'Reguler'"
                                :class="tipePresensi === 'Reguler' ? 'bg-forest-900 text-white shadow-md font-black' : 'text-gray-600 dark:text-gray-300 font-bold hover:text-forest-900'"
                                class="py-2.5 px-2 rounded-xl text-xs flex items-center justify-center gap-1 transition">
                            <span>📍</span>
                            <span>Hadir</span>
                        </button>
                        <button type="button"
                                @click="tipePresensi = 'Dinas Luar'"
                                :class="tipePresensi === 'Dinas Luar' ? 'bg-indigo-700 text-white shadow-md font-black' : 'text-gray-600 dark:text-gray-300 font-bold hover:text-indigo-700'"
                                class="py-2.5 px-2 rounded-xl text-xs flex items-center justify-center gap-1 transition">
                            <span>💼</span>
                            <span>Dinas Luar</span>
                        </button>
                        <button type="button"
                                @click="tipePresensi = 'Sakit'"
                                :class="tipePresensi === 'Sakit' ? 'bg-purple-700 text-white shadow-md font-black' : 'text-gray-600 dark:text-gray-300 font-bold hover:text-purple-700'"
                                class="py-2.5 px-2 rounded-xl text-xs flex items-center justify-center gap-1 transition">
                            <span>🏥</span>
                            <span>Sakit (SKD)</span>
                        </button>
                        <button type="button"
                                @click="tipePresensi = 'Izin'"
                                :class="tipePresensi === 'Izin' ? 'bg-amber-600 text-white shadow-md font-black' : 'text-gray-600 dark:text-gray-300 font-bold hover:text-amber-600'"
                                class="py-2.5 px-2 rounded-xl text-xs flex items-center justify-center gap-1 transition">
                            <span>📝</span>
                            <span>Izin Resmi</span>
                        </button>
                    </div>
                </div>

                {{-- B. LOGIKA TAMPILAN SESUAI MODE YANG DIPILIH --}}

                {{-- Mode 1: REGULER (Validasi Radius 100m Aktif) --}}
                <div x-show="tipePresensi === 'Reguler'"
                     class="p-3.5 rounded-2xl border transition"
                     :class="gpsStatus === 'ready' && isInRadius ? 'bg-emerald-50/80 border-emerald-300 text-emerald-900' : (gpsStatus === 'ready' && !isInRadius ? 'bg-rose-50/80 border-rose-300 text-rose-900' : 'bg-slate-50 border-slate-200 text-slate-700')">
                    <div class="flex items-center justify-between gap-2 mb-1.5">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full"
                                  :class="gpsStatus === 'ready' && isInRadius ? 'bg-emerald-500' : (gpsStatus === 'ready' && !isInRadius ? 'bg-rose-500' : 'bg-amber-400 animate-ping')"></span>
                            <span class="text-xs font-extrabold uppercase tracking-wide">Status Geofencing Sekolah</span>
                        </div>
                        <button type="button" @click="requestLocation()" class="text-[10px] font-bold text-emerald-700 hover:underline flex items-center gap-0.5">
                            <span>🔄</span>
                            <span>Refresh GPS</span>
                        </button>
                    </div>

                    <template x-if="gpsStatus === 'loading'">
                        <p class="text-xs text-gray-500 italic flex items-center gap-1.5">
                            <svg class="animate-spin w-3.5 h-3.5 text-sumedang-amber" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span>Mengunci titik koordinat GPS perangkat Anda...</span>
                        </p>
                    </template>

                    <template x-if="gpsStatus === 'error'">
                        <p class="text-xs text-rose-700 font-semibold" x-text="gpsError"></p>
                    </template>

                    <template x-if="gpsStatus === 'ready'">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs">Jarak dari SDN Cinanggerang I:</span>
                                <span class="text-sm font-black" x-text="Math.round(distance) + ' Meter'"></span>
                            </div>
                            <div class="mt-2 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold"
                                 :class="isInRadius ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-rose-100 text-rose-800 border border-rose-300'">
                                <span x-text="isInRadius ? '✓ Dalam Radius Sekolah (' + Math.round(distance) + ' m)' : '⚠ Di Luar Wilayah Sekolah (' + Math.round(distance) + ' m)'"></span>
                            </div>
                            <template x-if="!isInRadius">
                                <p class="text-[11px] text-rose-600 mt-1 leading-tight font-medium">
                                    Anda berjarak di atas {{ $maxRadius }} meter. Jika sedang tugas luar, silakan pilih tab <strong>"Dinas Luar"</strong> di atas.
                                </p>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- Mode 2: DINAS LUAR (Geofencing 100m di-BYPASS, GPS Koordinat Tetap Dicatat) --}}
                <div x-show="tipePresensi === 'Dinas Luar'" x-cloak class="space-y-3.5">
                    <div class="p-3.5 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-800 text-indigo-900 dark:text-indigo-200">
                        <div class="flex items-start gap-2.5">
                            <span class="text-base shrink-0">💼</span>
                            <div class="text-xs space-y-1">
                                <p class="font-extrabold text-indigo-950 dark:text-white">
                                    Mode Presensi Dinas Luar (Bypass Geofencing Aktif)
                                </p>
                                <p class="text-[11px] leading-relaxed text-indigo-800 dark:text-indigo-300">
                                    Batas radius 100 meter sekolah otomatis dilewati. Titik koordinat riil GPS perangkat Anda saat ini tetap dicatat sebagai bukti legalitas kehadiran dinas.
                                </p>
                                <template x-if="gpsStatus === 'ready'">
                                    <p class="text-[10px] font-mono text-indigo-600 dark:text-indigo-400 pt-0.5">
                                        📍 Koordinat: <span x-text="userLat?.toFixed(6)"></span>, <span x-text="userLng?.toFixed(6)"></span> (~<span x-text="Math.round(distance)"></span> m dari sekolah)
                                    </p>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Input 1: Nama Tempat / Instansi Dinas Luar --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Nama Tempat / Instansi Dinas Luar <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               x-model="dutyLocationName"
                               placeholder="Contoh: Disdik Kab. Sumedang / Gedung KKG Pamulihan"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-forest-700 dark:bg-forest-950 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>

                    {{-- Input 2: Keterangan Agenda Kegiatan --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Keterangan Agenda Kegiatan <span class="text-rose-500">*</span>
                        </label>
                        <textarea x-model="dutyDescription"
                                  rows="2"
                                  placeholder="Contoh: Mengikuti Rapat Koordinasi Kurikulum Merdeka dan Pembinaan GTK"
                                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 dark:border-forest-700 dark:bg-forest-950 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition"></textarea>
                    </div>

                    {{-- Input 3: Pilihan Jenis Bukti Validasi --}}
                    <div>
                        <span class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                            Jenis Bukti Validasi Penugasan <span class="text-rose-500">*</span>
                        </span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition text-xs font-medium"
                                    :class="dutyProofType === 'Surat Tugas / Undangan' ? 'bg-indigo-50 border-indigo-500 text-indigo-900 font-bold' : 'bg-slate-50 border-slate-200 text-gray-700'">
                                <input type="radio"
                                       x-model="dutyProofType"
                                       value="Surat Tugas / Undangan"
                                       class="text-indigo-600 focus:ring-indigo-500">
                                <span>📄 Surat Tugas / Undangan</span>
                            </label>

                            <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer transition text-xs font-medium"
                                    :class="dutyProofType === 'Foto Kegiatan di Lokasi' ? 'bg-indigo-50 border-indigo-500 text-indigo-900 font-bold' : 'bg-slate-50 border-slate-200 text-gray-700'">
                                <input type="radio"
                                       x-model="dutyProofType"
                                       value="Foto Kegiatan di Lokasi"
                                       class="text-indigo-600 focus:ring-indigo-500">
                                <span>📸 Foto di Lokasi Kegiatan</span>
                            </label>
                        </div>
                    </div>

                    {{-- Input 4: Komponen Unggah Berkas Bukti --}}
                    <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-forest-950/60 border border-dashed border-indigo-300 dark:border-indigo-700">
                        <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">
                            <span x-text="dutyProofType === 'Surat Tugas / Undangan' ? 'Unggah Berkas Surat Tugas / Undangan Resmi' : 'Unggah Foto Anda di Lokasi Kegiatan'"></span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-2">
                            Format: JPG, JPEG, PNG, atau PDF (Foto kamera HP otomatis dikompresi ringkas maks. ~100 KB).
                        </p>

                        <div class="flex items-center gap-2">
                            <label class="flex-1 py-2 px-3 rounded-xl bg-white dark:bg-forest-900 border border-slate-300 dark:border-forest-700 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-slate-50 cursor-pointer flex items-center justify-center gap-1.5 shadow-xs transition">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span>Pilih Berkas / Galeri</span>
                                <input type="file"
                                       id="duty-proof-file-input"
                                       @change="handleDutyFileChange($event)"
                                       accept="image/jpeg,image/png,image/jpg,application/pdf"
                                       class="hidden">
                            </label>

                            <template x-if="dutyProofType === 'Foto Kegiatan di Lokasi'">
                                <label class="py-2 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold cursor-pointer flex items-center gap-1 shadow-xs transition">
                                    <span>📷 Potret</span>
                                    <input type="file"
                                           @change="handleDutyFileChange($event)"
                                           accept="image/*"
                                           capture="environment"
                                           class="hidden">
                                </label>
                            </template>
                        </div>

                        <template x-if="dutyProofFile">
                            <div class="mt-2.5 p-2 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span>📎</span>
                                    <span class="font-bold truncate" x-text="dutyProofFileName"></span>
                                </div>
                                <button type="button" @click="clearDutyFile()" class="text-rose-500 hover:text-rose-700 font-bold ml-2">✕</button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Mode 3: SAKIT (Geofencing 100% di-BYPASS, Wajib Unggah SKD Dokter) --}}
                <div x-show="tipePresensi === 'Sakit'" x-cloak class="space-y-3.5">
                    <div class="p-3.5 rounded-2xl bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800 text-purple-900 dark:text-purple-200">
                        <div class="flex items-start gap-2.5">
                            <span class="text-base shrink-0">🏥</span>
                            <div class="text-xs space-y-1">
                                <p class="font-extrabold text-purple-950 dark:text-white">
                                    Mode Pengajuan Sakit Mandiri (Surat Keterangan Dokter)
                                </p>
                                <p class="text-[11px] leading-relaxed text-purple-800 dark:text-purple-300">
                                    Geofencing lokasi 100% dilewati. Lampirkan foto atau dokumen PDF Surat Keterangan Dokter (SKD) yang sah. Kehadiran otomatis diakui sah satu hari penuh.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Input: Diagnosa Singkat --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Keluhan / Diagnosa Singkat Dokter <span class="text-rose-500">*</span>
                        </label>
                        <textarea x-model="dutyDescription"
                                  rows="2"
                                  placeholder="Contoh: Mengalami demam tinggi dan flu, istirahat rawat jalan sesuai anjuran dokter Puskesmas Pamulihan"
                                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 dark:border-forest-700 dark:bg-forest-950 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition"></textarea>
                    </div>

                    {{-- Unggah SKD --}}
                    <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-forest-950/60 border border-dashed border-purple-300 dark:border-purple-700">
                        <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">
                            Unggah Surat Keterangan Dokter (SKD) <span class="text-rose-500">*</span>
                        </label>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-2">
                            Bisa berupa lembar foto surat dokter (otomatis dikompresi &lt; 100 KB) atau file dokumen PDF.
                        </p>

                        <div class="flex items-center gap-2">
                            <label class="flex-1 py-2 px-3 rounded-xl bg-white dark:bg-forest-900 border border-slate-300 dark:border-forest-700 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-slate-50 cursor-pointer flex items-center justify-center gap-1.5 shadow-xs transition">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span>Pilih Berkas / Galeri</span>
                                <input type="file"
                                       id="sakit-proof-file-input"
                                       @change="handleDutyFileChange($event)"
                                       accept="image/jpeg,image/png,image/jpg,application/pdf"
                                       class="hidden">
                            </label>

                            <label class="py-2 px-3 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold cursor-pointer flex items-center gap-1 shadow-xs transition">
                                <span>📷 Foto SKD</span>
                                <input type="file"
                                       @change="handleDutyFileChange($event)"
                                       accept="image/*"
                                       capture="environment"
                                       class="hidden">
                            </label>
                        </div>

                        <template x-if="dutyProofFile">
                            <div class="mt-2.5 p-2 rounded-xl bg-purple-50 border border-purple-300 text-purple-900 text-xs flex items-center justify-between">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span>📎</span>
                                    <span class="font-bold truncate" x-text="dutyProofFileName"></span>
                                </div>
                                <button type="button" @click="clearDutyFile()" class="text-rose-500 hover:text-rose-700 font-bold ml-2">✕</button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Mode 4: IZIN RESMI (Geofencing 100% di-BYPASS, Wajib Unggah Surat Izin) --}}
                <div x-show="tipePresensi === 'Izin'" x-cloak class="space-y-3.5">
                    <div class="p-3.5 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-200">
                        <div class="flex items-start gap-2.5">
                            <span class="text-base shrink-0">📝</span>
                            <div class="text-xs space-y-1">
                                <p class="font-extrabold text-amber-950 dark:text-white">
                                    Mode Pengajuan Izin Mandiri (Surat Permohonan Izin)
                                </p>
                                <p class="text-[11px] leading-relaxed text-amber-800 dark:text-amber-300">
                                    Geofencing lokasi 100% dilewati. Lampirkan surat permohonan izin resmi atau dokumen pendukung. Kehadiran otomatis diakui sah satu hari penuh.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Input: Alasan Izin --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Keperluan / Alasan Izin Resmi <span class="text-rose-500">*</span>
                        </label>
                        <textarea x-model="dutyDescription"
                                  rows="2"
                                  placeholder="Contoh: Mengurus administrasi keluarga mendesak / Keperluan keluarga di luar kota"
                                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 dark:border-forest-700 dark:bg-forest-950 text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition"></textarea>
                    </div>

                    {{-- Unggah Surat Izin --}}
                    <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-forest-950/60 border border-dashed border-amber-300 dark:border-amber-700">
                        <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-1">
                            Unggah Surat Izin / Berkas Pendukung <span class="text-rose-500">*</span>
                        </label>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-2">
                            Bisa berupa foto surat izin yang ditulis/ditandatangani (otomatis dikompresi &lt; 100 KB) atau file dokumen PDF.
                        </p>

                        <div class="flex items-center gap-2">
                            <label class="flex-1 py-2 px-3 rounded-xl bg-white dark:bg-forest-900 border border-slate-300 dark:border-forest-700 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-slate-50 cursor-pointer flex items-center justify-center gap-1.5 shadow-xs transition">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span>Pilih Berkas / Galeri</span>
                                <input type="file"
                                       id="izin-proof-file-input"
                                       @change="handleDutyFileChange($event)"
                                       accept="image/jpeg,image/png,image/jpg,application/pdf"
                                       class="hidden">
                            </label>

                            <label class="py-2 px-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold cursor-pointer flex items-center gap-1 shadow-xs transition">
                                <span>📷 Foto Surat</span>
                                <input type="file"
                                       @change="handleDutyFileChange($event)"
                                       accept="image/*"
                                       capture="environment"
                                       class="hidden">
                            </label>
                        </div>

                        <template x-if="dutyProofFile">
                            <div class="mt-2.5 p-2 rounded-xl bg-amber-50 border border-amber-300 text-amber-900 text-xs flex items-center justify-between">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span>📎</span>
                                    <span class="font-bold truncate" x-text="dutyProofFileName"></span>
                                </div>
                                <button type="button" @click="clearDutyFile()" class="text-rose-500 hover:text-rose-700 font-bold ml-2">✕</button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Area Kamera Swafoto Wajah (WebRTC Selfie Preview) - Muncul untuk Hadir & Dinas Luar, Opsional untuk Sakit/Izin --}}
                <div x-show="tipePresensi === 'Reguler' || tipePresensi === 'Dinas Luar' || showOptionalSelfie" class="space-y-2 pt-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center gap-1">
                            <span>📸</span>
                            <span x-text="(tipePresensi === 'Sakit' || tipePresensi === 'Izin') ? 'Swafoto (Selfie) Opsional' : 'Verifikasi Swafoto (Selfie) Wajah'"></span>
                        </span>
                        <button type="button" @click="startCamera()" x-show="!cameraActive" class="text-[10px] font-bold text-sumedang-amber hover:underline">
                            Buka Kamera
                        </button>
                        <button type="button" @click="retakeSnapshot()" x-show="snapshotData" class="text-[10px] font-bold text-rose-600 hover:underline">
                            Foto Ulang
                        </button>
                    </div>

                    {{-- Frame Kamera --}}
                    <div class="relative w-full aspect-[4/3] rounded-2xl bg-black overflow-hidden border-2 border-slate-200 dark:border-forest-800 flex items-center justify-center">
                        {{-- Video Element untuk Live Stream (Mirror Effect) --}}
                        <video id="webcam-video" autoplay playsinline muted
                               x-show="cameraActive && !snapshotData"
                               class="w-full h-full object-cover transform scale-x-[-1]"></video>

                        {{-- Canvas Snapshot Hasil Foto --}}
                        <canvas id="snapshot-canvas" class="hidden"></canvas>
                        <img :src="snapshotData" x-show="snapshotData" class="w-full h-full object-cover">

                        {{-- Placeholder Saat Kamera Belum Aktif --}}
                        <div x-show="!cameraActive && !snapshotData" class="text-center p-4 text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <p class="text-xs font-medium">Kamera selfie belum dibuka</p>
                            <button type="button" @click="startCamera()" class="mt-2.5 px-3 py-1.5 rounded-xl bg-forest-800 hover:bg-forest-700 text-white text-xs font-bold transition">
                                Aktifkan Kamera Depan
                            </button>
                        </div>

                        {{-- Watermark Info Waktu & Jarak di Snapshot --}}
                        <div x-show="cameraActive || snapshotData" class="absolute bottom-2 left-2 right-2 px-2.5 py-1 rounded-lg bg-black/60 backdrop-blur-xs text-[10px] text-white flex items-center justify-between">
                            <span x-text="liveTime"></span>
                            <span x-text="tipePresensi === 'Dinas Luar' ? 'DINAS LUAR • ' + (dutyLocationName || 'Luar Sekolah') : (tipePresensi === 'Sakit' ? 'SAKIT • SKD DOKTER' : (tipePresensi === 'Izin' ? 'IZIN RESMI' : (gpsStatus === 'ready' ? Math.round(distance) + 'm dari SDN Cinanggerang I' : 'GPS..')))"></span>
                        </div>
                    </div>
                </div>

                {{-- Toggle Opsional Swafoto untuk Sakit & Izin --}}
                <div x-show="tipePresensi === 'Sakit' || tipePresensi === 'Izin'" class="text-center pt-1">
                    <button type="button" @click="showOptionalSelfie = !showOptionalSelfie; if(showOptionalSelfie) startCamera();"
                            class="text-[11px] font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 underline">
                        <span x-text="showOptionalSelfie ? '✕ Sembunyikan Swafoto Wajah' : '+ Ambil Swafoto Wajah (Opsional)'"></span>
                    </button>
                </div>

                {{-- Tombol Aksi Utama: Ambil Foto & Rekam Presensi --}}
                <div class="pt-2">
                    <button type="button"
                            @click="submitPresensi()"
                            :disabled="!canSubmit || isSubmitting"
                            :class="canSubmit && !isSubmitting ? (tipePresensi === 'Sakit' ? 'bg-gradient-to-r from-purple-600 to-purple-800 hover:from-purple-700 hover:to-purple-900 text-white shadow-lg shadow-purple-900/20' : (tipePresensi === 'Izin' ? 'bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white shadow-lg shadow-amber-900/20' : (tipePresensi === 'Dinas Luar' ? 'bg-gradient-to-r from-indigo-600 to-indigo-800 hover:from-indigo-700 hover:to-indigo-900 text-white shadow-lg shadow-indigo-900/20' : 'bg-gradient-to-r from-emerald-600 to-forest-800 hover:from-emerald-700 hover:to-forest-900 text-white shadow-lg shadow-emerald-900/20'))) + ' transform hover:-translate-y-0.5 cursor-pointer' : 'bg-gray-300 dark:bg-forest-950 text-gray-500 cursor-not-allowed'"
                            class="w-full py-3.5 px-4 rounded-2xl font-black text-sm flex items-center justify-center gap-2 transition duration-200">
                        <template x-if="isSubmitting">
                            <svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        </template>
                        <template x-if="!isSubmitting">
                            <span x-text="tipePresensi === 'Sakit' ? '🏥' : (tipePresensi === 'Izin' ? '📝' : (tipePresensi === 'Dinas Luar' ? '💼' : '📸'))"></span>
                        </template>
                        <span x-text="isSubmitting ? 'Merekam Presensi...' : (tipePresensi === 'Sakit' ? 'Kirim Laporan Sakit & Bukti SKD' : (tipePresensi === 'Izin' ? 'Kirim Permohonan Izin Resmi' : (actionType === 'out' && !isPulangTimeAllowed ? 'Presensi Pulang Baru Dibuka Pukul 10:00 WIB' : (tipePresensi === 'Dinas Luar' ? (actionType === 'in' ? 'Rekam Presensi Datang (Dinas Luar)' : 'Rekam Presensi Pulang (Dinas Luar)') : (actionType === 'in' ? 'Ambil Foto & Rekam Presensi Datang' : 'Ambil Foto & Rekam Presensi Pulang')))))"></span>
                    </button>

                    {{-- Hint Syarat Tombol Aktif --}}
                    <div class="text-[11px] text-gray-400 text-center mt-2">
                        <template x-if="actionType === 'out' && !isPulangTimeAllowed && (tipePresensi === 'Reguler' || tipePresensi === 'Dinas Luar')">
                            <span class="text-amber-700 dark:text-amber-400 font-semibold">⏳ Presensi kepulangan baru dapat dilakukan setelah pukul 10:00 WIB</span>
                        </template>
                        <template x-if="tipePresensi === 'Reguler' && !isInRadius && (actionType !== 'out' || isPulangTimeAllowed)">
                            <span class="text-rose-600 font-medium">⚠️ Berada di luar radius sekolah (&gt; {{ $maxRadius }}m). Pilih mode "Dinas Luar" jika sedang bertugas.</span>
                        </template>
                        <template x-if="tipePresensi === 'Dinas Luar' && (!dutyLocationName || !dutyDescription || !dutyProofFile) && (actionType !== 'out' || isPulangTimeAllowed)">
                            <span class="text-indigo-600 font-medium">Lengkapi nama lokasi dinas, agenda, dan unggah berkas bukti tugas</span>
                        </template>
                        <template x-if="tipePresensi === 'Sakit' && (!dutyDescription || !dutyProofFile)">
                            <span class="text-purple-600 font-medium">Lengkapi keluhan/diagnosa dokter dan unggah berkas bukti SKD (Foto/PDF)</span>
                        </template>
                        <template x-if="tipePresensi === 'Izin' && (!dutyDescription || !dutyProofFile)">
                            <span class="text-amber-600 font-medium">Lengkapi alasan izin dan unggah berkas surat permohonan izin (Foto/PDF)</span>
                        </template>
                        <template x-if="canSubmit">
                            <span class="text-emerald-700 font-semibold">✓ Persyaratan lengkap, siap mengirim data presensi</span>
                        </template>
                    </div>
                </div>

            </div> {{-- Akhir Pembungkus Formulir Aktif (!isActionLocked) --}}

        </div>

        {{-- Footer Kartu --}}
        <div class="bg-slate-50 dark:bg-forest-950/80 p-4 border-t border-slate-100 dark:border-forest-800 text-center text-xs text-gray-500">
            <a href="{{ route('home') }}" class="font-semibold text-emerald-800 dark:text-emerald-400 hover:underline inline-flex items-center gap-1">
                <span>← Kembali ke Portal Utama</span>
            </a>
        </div>

    </div>

</div>

{{-- Alpine.js State & Geolocation Logic Script --}}
<script>
function presensiGpsApp(config) {
    return {
        schoolLat: config.schoolLat,
        schoolLng: config.schoolLng,
        maxRadius: config.maxRadius,
        todayRecord: config.todayRecord,
        submitUrl: config.submitUrl,
        csrfToken: config.csrfToken,

        // State Presensi
        tipePresensi: 'Reguler', // 'Reguler', 'Dinas Luar', 'Sakit', 'Izin'
        actionType: 'in', // 'in' (Datang) atau 'out' (Pulang)
        showMonthlyRecap: false,
        showOptionalSelfie: false,

        // State Dinas Luar, Sakit & Izin
        dutyLocationName: '',
        dutyDescription: '',
        dutyProofType: 'Surat Tugas / Undangan', // 'Surat Tugas / Undangan' atau 'Foto Kegiatan di Lokasi'
        dutyProofFile: null,
        dutyProofFileName: '',

        // Jam & Tanggal
        liveTime: '',
        liveDate: '',

        // Lokasi GPS
        userLat: null,
        userLng: null,
        distance: 9999,
        isInRadius: false,
        gpsStatus: 'loading',
        gpsError: '',

        // Kamera & Selfie
        cameraActive: false,
        videoStream: null,
        snapshotData: null,
        isSubmitting: false,
        alertMessage: '',
        alertType: 'success',

        get isSakitOrIzinLocked() {
            if (!this.todayRecord) return false;
            const st = this.todayRecord.status || '';
            const at = this.todayRecord.attendance_type || '';
            return st === 'Sakit (SKD)' || st === 'Izin Resmi' || at === 'Sakit' || at === 'Izin';
        },

        get isPulangTimeAllowed() {
            if (!this.liveTime) return false;
            // Format liveTime: 'HH:MM:SS WIB'
            const currentHm = this.liveTime.substring(0, 5);
            return currentHm >= '10:00';
        },

        get canSubmit() {
            if (this.isActionLocked) return false;

            // Jika mode Sakit atau Izin: Cukup isi keterangan dan berkas bukti dukung (SKD/surat permohonan)
            if (this.tipePresensi === 'Sakit' || this.tipePresensi === 'Izin') {
                return this.dutyDescription.trim().length > 0 && this.dutyProofFile !== null;
            }

            // Jika presensi pulang reguler/dinas luar, wajib setelah pukul 10:00 WIB
            if (this.actionType === 'out' && !this.isPulangTimeAllowed) {
                return false;
            }

            // Validasi kamera/selfie untuk Reguler & Dinas Luar
            const hasSelfie = this.cameraActive || this.snapshotData;
            const hasGps = this.gpsStatus === 'ready';

            if (!hasSelfie || !hasGps) return false;

            if (this.tipePresensi === 'Reguler') {
                return this.isInRadius;
            } else if (this.tipePresensi === 'Dinas Luar') {
                // Dinas Luar: butuh lokasi, deskripsi, dan berkas bukti
                return this.dutyLocationName.trim().length > 0 &&
                       this.dutyDescription.trim().length > 0 &&
                       this.dutyProofFile !== null;
            }

            return false;
        },

        get isActionLocked() {
            if (this.isSakitOrIzinLocked) return true;

            if (this.actionType === 'in') {
                return !!(this.todayRecord && this.todayRecord.jam_masuk);
            }
            if (this.actionType === 'out') {
                return !!(this.todayRecord && this.todayRecord.jam_pulang) || !(this.todayRecord && this.todayRecord.jam_masuk);
            }
            return false;
        },

        formatJam(val) {
            if (!val) return '-';
            return val.substring(0, 5);
        },

        initApp() {
            // Auto pilih tipe aksi Datang / Pulang berdasarkan record hari ini
            if (this.todayRecord && this.todayRecord.jam_masuk && !this.todayRecord.jam_pulang) {
                this.actionType = 'out';
            }

            // Jika record hari ini bertipe Dinas Luar, defaultkan ke Dinas Luar
            if (this.todayRecord && this.todayRecord.attendance_type === 'Dinas Luar') {
                this.tipePresensi = 'Dinas Luar';
                this.dutyLocationName = this.todayRecord.duty_location_name || '';
                this.dutyDescription = this.todayRecord.duty_description || '';
                this.dutyProofType = this.todayRecord.duty_proof_type || 'Surat Tugas / Undangan';
            }

            // Jalankan jam real-time
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);

            // Minta izin dan kunci lokasi GPS
            this.requestLocation();

            // Nyalakan kamera otomatis jika bukan Sakit / Izin
            if (!this.isSakitOrIzinLocked) {
                this.startCamera();
            }
        },

        updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            this.liveTime = `${hours}:${minutes}:${seconds} WIB`;

            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            this.liveDate = now.toLocaleDateString('id-ID', options);
        },

        requestLocation() {
            this.gpsStatus = 'loading';
            this.gpsError = '';

            if (!navigator.geolocation) {
                this.gpsStatus = 'error';
                this.gpsError = 'Perangkat Anda tidak mendukung fitur Geolocation GPS.';
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (pos) => this.handleGpsSuccess(pos),
                (err) => this.handleGpsError(err),
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );

            // Watch position real-time
            navigator.geolocation.watchPosition(
                (pos) => this.handleGpsSuccess(pos),
                (err) => {},
                { enableHighAccuracy: true, maximumAge: 5000 }
            );
        },

        handleGpsSuccess(pos) {
            this.userLat = pos.coords.latitude;
            this.userLng = pos.coords.longitude;
            this.distance = this.calculateHaversine(this.userLat, this.userLng, this.schoolLat, this.schoolLng);
            this.isInRadius = this.distance <= this.maxRadius;
            this.gpsStatus = 'ready';
        },

        handleGpsError(err) {
            this.gpsStatus = 'error';
            if (err.code === 1) {
                this.gpsError = 'Izin akses lokasi GPS ditolak. Silakan izinkan browser mengakses lokasi Anda.';
            } else if (err.code === 2) {
                this.gpsError = 'Sinyal GPS tidak terdeteksi. Pastikan GPS HP/Laptop Anda telah aktif.';
            } else {
                this.gpsError = 'Gagal mengambil koordinat lokasi (' + err.message + ').';
            }
        },

        calculateHaversine(lat1, lon1, lat2, lon2) {
            const R = 6371000; // Radius bumi dalam meter
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        },

        compressImageFile(file, maxWidth = 1024, maxHeight = 1024, quality = 0.72) {
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = (e) => {
                    const img = new Image();
                    img.src = e.target.result;
                    img.onload = () => {
                        let width = img.width;
                        let height = img.height;

                        if (width > height) {
                            if (width > maxWidth) {
                                height = Math.round((height * maxWidth) / width);
                                width = maxWidth;
                            }
                        } else {
                            if (height > maxHeight) {
                                width = Math.round((width * maxHeight) / height);
                                height = maxHeight;
                            }
                        }

                        const canvas = document.createElement('canvas');
                        canvas.width = width;
                        canvas.height = height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, width, height);

                        canvas.toBlob((blob) => {
                            if (!blob) {
                                resolve(file);
                                return;
                            }
                            // Jika masih di atas 110 KB, perkecil dengan kualitas 0.55
                            if (blob.size > 112640 && quality > 0.55) {
                                canvas.toBlob((blob2) => {
                                    const finalBlob = blob2 || blob;
                                    const compressed = new File([finalBlob], file.name.replace(/\.[^/.]+$/, "") + ".jpg", {
                                        type: 'image/jpeg',
                                        lastModified: Date.now()
                                    });
                                    resolve(compressed);
                                }, 'image/jpeg', 0.55);
                            } else {
                                const compressed = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".jpg", {
                                    type: 'image/jpeg',
                                    lastModified: Date.now()
                                });
                                resolve(compressed);
                            }
                        }, 'image/jpeg', quality);
                    };
                    img.onerror = () => resolve(file);
                };
                reader.onerror = () => resolve(file);
            });
        },

        async handleDutyFileChange(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Jika berkas berupa foto/gambar (JPG, PNG, WEBP, dari Kamera HP atau Galeri)
            if (file.type.startsWith('image/')) {
                this.alertMessage = 'Sedang mereduksi ukuran foto otomatis (< 100 KB)...';
                this.alertType = 'success';

                try {
                    const compressed = await this.compressImageFile(file);
                    this.dutyProofFile = compressed;
                    const sizeKb = Math.round(compressed.size / 1024);
                    const originalKb = Math.round(file.size / 1024);
                    this.dutyProofFileName = `${file.name} (${sizeKb} KB • Terkompres Otomatis dari ${originalKb} KB)`;
                    this.alertMessage = `Foto berhasil dioptimasi menjadi ${sizeKb} KB (Hemat penyimpanan & kuota).`;
                    this.alertType = 'success';
                } catch (err) {
                    this.dutyProofFile = file;
                    this.dutyProofFileName = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
                    this.alertMessage = '';
                }
            } else if (file.type === 'application/pdf') {
                // Berkas PDF Dokumen Resmi (Batas aman 5 MB)
                if (file.size > 5242880) {
                    this.alertMessage = 'Ukuran berkas PDF melebihi batas maksimal 5 MB!';
                    this.alertType = 'error';
                    event.target.value = '';
                    return;
                }
                this.dutyProofFile = file;
                this.dutyProofFileName = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
                this.alertMessage = '';
            } else {
                this.alertMessage = 'Format berkas tidak didukung. Mohon gunakan foto (JPG, PNG) atau dokumen PDF.';
                this.alertType = 'error';
                event.target.value = '';
            }
        },

        clearDutyFile() {
            this.dutyProofFile = null;
            this.dutyProofFileName = '';
            const input1 = document.getElementById('duty-proof-file-input');
            if (input1) input1.value = '';
            const input2 = document.getElementById('sakit-proof-file-input');
            if (input2) input2.value = '';
            const input3 = document.getElementById('izin-proof-file-input');
            if (input3) input3.value = '';
        },

        async startCamera() {
            const video = document.getElementById('webcam-video');
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                this.alertMessage = 'Browser Anda tidak mendukung akses kamera selfie.';
                this.alertType = 'error';
                return;
            }

            try {
                this.videoStream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
                    audio: false
                });
                if (video) {
                    video.srcObject = this.videoStream;
                    this.cameraActive = true;
                }
            } catch (err) {
                console.error('Camera error:', err);
                this.cameraActive = false;
                this.alertMessage = 'Gagal membuka kamera: Mohon berikan izin kamera di browser perangkat Anda.';
                this.alertType = 'error';
            }
        },

        takeSnapshot() {
            const video = document.getElementById('webcam-video');
            const canvas = document.getElementById('snapshot-canvas');
            if (!video || !canvas) return null;

            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
            const ctx = canvas.getContext('2d');

            // Mirror image sesuai preview
            ctx.translate(canvas.width, 0);
            ctx.scale(-1, 1);
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Kembalikan transformasi untuk teks watermark
            ctx.setTransform(1, 0, 0, 1, 0, 0);

            // Watermark tanggal, jam dan keterangan
            ctx.fillStyle = 'rgba(0, 0, 0, 0.65)';
            ctx.fillRect(10, canvas.height - 42, canvas.width - 20, 32);
            ctx.font = '13px Arial';
            ctx.fillStyle = '#ffffff';

            let tagLokasi = `SDN Cinanggerang I • ${Math.round(this.distance)}m`;
            if (this.tipePresensi === 'Dinas Luar') {
                tagLokasi = `DINAS LUAR: ${this.dutyLocationName || 'Lokasi Tugas'}`;
            } else if (this.tipePresensi === 'Sakit') {
                tagLokasi = 'SAKIT (SKD DOKTER)';
            } else if (this.tipePresensi === 'Izin') {
                tagLokasi = 'IZIN RESMI';
            }

            ctx.fillText(`${tagLokasi} • ${this.liveTime}`, 20, canvas.height - 21);

            this.snapshotData = canvas.toDataURL('image/jpeg', 0.72);
            return this.snapshotData;
        },

        retakeSnapshot() {
            this.snapshotData = null;
            this.startCamera();
        },

        async submitPresensi() {
            if (!this.canSubmit || this.isSubmitting) return;

            let selfieBase64 = null;
            const isRegulerOrDinas = (this.tipePresensi === 'Reguler' || this.tipePresensi === 'Dinas Luar');

            if (isRegulerOrDinas || this.showOptionalSelfie) {
                if (this.cameraActive || this.snapshotData) {
                    selfieBase64 = this.takeSnapshot();
                }
            }

            if (isRegulerOrDinas && !selfieBase64) {
                this.alertMessage = 'Gagal merekam swafoto selfie. Silakan pastikan kamera menyala dan coba lagi.';
                this.alertType = 'error';
                return;
            }

            this.isSubmitting = true;
            this.alertMessage = '';

            try {
                // Gunakan FormData untuk mengirim berkas dan data teks
                const formData = new FormData();
                formData.append('attendance_type', this.tipePresensi);
                formData.append('action_type', this.actionType);
                formData.append('tipe', this.actionType === 'in' ? 'masuk' : 'pulang');
                formData.append('latitude', this.userLat || 0);
                formData.append('longitude', this.userLng || 0);

                if (selfieBase64) {
                    formData.append('selfie_image', selfieBase64);
                    formData.append('foto_selfie', selfieBase64);
                }

                if (this.tipePresensi === 'Dinas Luar') {
                    formData.append('duty_location_name', this.dutyLocationName);
                    formData.append('duty_description', this.dutyDescription);
                    formData.append('duty_proof_type', this.dutyProofType);
                    if (this.dutyProofFile) {
                        formData.append('duty_proof_file', this.dutyProofFile);
                    }
                } else if (this.tipePresensi === 'Sakit' || this.tipePresensi === 'Izin') {
                    formData.append('duty_description', this.dutyDescription);
                    if (this.dutyProofFile) {
                        formData.append('duty_proof_file', this.dutyProofFile);
                    }
                }

                const response = await fetch(this.submitUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.alertType = 'success';
                    this.alertMessage = data.message;
                    if (data.record) {
                        this.todayRecord = data.record;
                        if (this.actionType === 'in') {
                            this.actionType = 'out';
                        }
                    }
                } else {
                    this.alertType = 'error';
                    this.alertMessage = data.message || 'Presensi gagal disimpan.';
                }
            } catch (err) {
                console.error(err);
                this.alertType = 'error';
                this.alertMessage = 'Terjadi gangguan jaringan saat mengirim data presensi.';
            } finally {
                this.isSubmitting = false;
            }
        }
    }
}
</script>
@endsection
