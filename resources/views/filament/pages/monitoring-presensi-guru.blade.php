<x-filament-panels::page>
    <style>
        @media print {
            /* 1. Sembunyikan elemen bawaan panel navigasi Filament */
            .fi-sidebar,
            aside.fi-sidebar,
            .fi-sidebar-close-overlay,
            .fi-topbar,
            header.fi-topbar,
            .fi-header,
            .fi-breadcrumbs,
            nav.fi-breadcrumbs,
            .fi-page-header,
            .print\:hidden,
            button {
                display: none !important;
            }

            /* 2. Format Body & Ukuran Kertas Resmi A4 Landscape */
            @page {
                size: A4 landscape;
                margin: 8mm 12mm;
            }

            html, body {
                background-color: #ffffff !important;
                color: #000000 !important;
                font-family: 'Times New Roman', Times, serif, Arial, sans-serif !important;
                font-size: 10pt !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .fi-main,
            .fi-main-ctn,
            .fi-page,
            .fi-section,
            main,
            .space-y-6 {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                box-shadow: none !important;
                border: none !important;
                background: transparent !important;
            }

            /* 3. Kop Surat Kedinasan */
            .formal-kop {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                border-bottom: 3px double #000000 !important;
                padding-bottom: 8px !important;
                margin-bottom: 12px !important;
            }

            .formal-kop img {
                height: 65px !important;
                width: auto !important;
                object-fit: contain !important;
            }

            .formal-kop .kop-text {
                text-align: center !important;
                flex: 1 !important;
                padding: 0 15px !important;
            }

            /* 4. Format Tabel Cetak Tajam & Rapi */
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 8.5pt !important;
                margin-top: 6px !important;
            }

            table th, table td {
                border: 1px solid #000000 !important;
                padding: 4px 6px !important;
                color: #000000 !important;
            }

            table thead th {
                background-color: #f3f4f6 !important;
                font-weight: bold !important;
                text-align: center !important;
            }

            tr {
                page-break-inside: avoid !important;
            }

            /* Sembunyikan progress bar di cetakan dan tampilkan teks persentase murni */
            .print-hide-bar {
                display: none !important;
            }

            .print-show-text {
                display: inline-block !important;
            }

            /* 5. Tanda Tangan */
            .print-signature-section {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 32px !important;
                page-break-inside: avoid !important;
                margin-top: 20px !important;
                padding-top: 8px !important;
            }
        }
    </style>

    <div class="space-y-6">

        {{-- Tab Navigasi & Tombol Cetak (Hanya Tampil di Layar Monitor) --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm print:hidden">
            <div class="inline-flex p-1 bg-gray-100 dark:bg-gray-900 rounded-lg">
                <button type="button" wire:click="setTab('harian')"
                        class="px-5 py-2 text-xs font-bold rounded-md transition-all {{ $activeTab === 'harian' ? 'bg-white dark:bg-gray-800 text-primary-600 dark:text-primary-400 shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">
                    📅 Monitoring Harian
                </button>
                <button type="button" wire:click="setTab('bulanan')"
                        class="px-5 py-2 text-xs font-bold rounded-md transition-all {{ $activeTab === 'bulanan' ? 'bg-white dark:bg-gray-800 text-primary-600 dark:text-primary-400 shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">
                    📊 Rekap Bulanan & Evaluasi
                </button>
            </div>

            <div class="flex items-center gap-3">
                @if($activeTab === 'harian')
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-semibold text-gray-500">Pilih Tanggal:</label>
                        <input type="date" wire:model.live="selectedDate" class="text-xs rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-900 dark:text-white">
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <select wire:model.live="selectedMonth" class="text-xs rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-900 dark:text-white">
                            @foreach([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ] as $num => $namaBulan)
                                <option value="{{ $num }}">{{ $namaBulan }}</option>
                            @endforeach
                        </select>
                        <select wire:model.live="selectedYear" class="text-xs rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-900 dark:text-white">
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                        </select>
                    </div>
                @endif

                <button type="button" onclick="window.print()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg flex items-center gap-2 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Format Cetak A4</span>
                </button>
            </div>
        </div>

        {{-- Banner Notifikasi Libur Resmi (Jika tanggal aktif adalah hari libur) --}}
        @if($this->selectedDateHoliday && $activeTab === 'harian')
            <div class="p-4 bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-700 rounded-xl text-amber-900 dark:text-amber-200 text-xs font-semibold flex items-center gap-3 print:hidden shadow-sm">
                <span class="text-2xl">🏖️</span>
                <div>
                    <div class="font-bold text-amber-800 dark:text-amber-300 uppercase tracking-wide">
                        Tanggal Terpilih Adalah Hari Libur: {{ $this->selectedDateHoliday->name }} ({{ $this->selectedDateHoliday->type }})
                    </div>
                    <p class="text-[11px] text-amber-700 dark:text-amber-400 mt-0.5">
                        Guru dan Tenaga Kependidikan tidak diwajibkan melakukan presensi kerja pada tanggal ini.
                    </p>
                </div>
            </div>
        @endif

        {{-- KOP SURAT RESMI KEDINASAN (Hanya Tampil Saat Dicetak / window.print) --}}
        <div class="hidden print:block mb-4">
            <div class="formal-kop">
                <div style="width: 80px; text-align: left;">
                    @if($this->schoolProfile?->logo_pemda_url)
                        <img src="{{ $this->schoolProfile->logo_pemda_url }}" alt="Logo Pemda" style="height: 60px; width: auto; object-fit: contain;">
                    @else
                        <img src="{{ asset('images/logo-pemda.png') }}" alt="Logo Pemda" style="height: 60px; width: auto; object-fit: contain;" onerror="this.style.display='none'">
                    @endif
                </div>

                <div class="kop-text">
                    <div style="font-size: 11pt; font-weight: bold; letter-spacing: 1px; text-transform: uppercase;">PEMERINTAH KABUPATEN SUMEDANG</div>
                    <div style="font-size: 12pt; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase;">DINAS PENDIDIKAN</div>
                    <div style="font-size: 14pt; font-weight: 900; letter-spacing: 1px; text-transform: uppercase; margin: 2px 0;">
                        {{ $this->schoolProfile?->nama_sekolah ?? 'SD NEGERI CINANGGERANG I' }}
                    </div>
                    <div style="font-size: 8pt; color: #333333;">
                        NPSN: {{ $this->schoolProfile?->npsn ?? '20208801' }} &bull; NSS: 101021008001 &bull; Akreditasi: B
                    </div>
                    <div style="font-size: 8pt; color: #333333;">
                        {{ $this->schoolProfile?->alamat ?? 'Jalan Desa Cinanggerang RT 03/04, Desa Cinanggerang, Kec. Pamulihan, Kab. Sumedang 45365' }}
                    </div>
                    <div style="font-size: 7.5pt; color: #444444; font-style: italic;">
                        Surel: {{ $this->schoolProfile?->email ?? 'sdncinanggerang1@gmail.com' }} &bull; Laman Resmi: https://sdncinanggerang1.onrender.com
                    </div>
                </div>

                <div style="width: 80px; text-align: right;">
                    @if($this->schoolProfile?->logo_tutwuri_url)
                        <img src="{{ $this->schoolProfile->logo_tutwuri_url }}" alt="Logo Tut Wuri" style="height: 60px; width: auto; object-fit: contain;">
                    @elseif($this->schoolProfile?->logo_url)
                        <img src="{{ $this->schoolProfile->logo_url }}" alt="Logo Sekolah" style="height: 60px; width: auto; object-fit: contain;">
                    @else
                        <img src="{{ asset('images/logo-sd.png') }}" alt="Logo Tut Wuri" style="height: 60px; width: auto; object-fit: contain;" onerror="this.style.display='none'">
                    @endif
                </div>
            </div>

            <div style="text-align: center; margin-top: 8px; margin-bottom: 8px;">
                <div style="font-size: 11pt; font-weight: bold; text-transform: uppercase; text-decoration: underline;">
                    @if($activeTab === 'harian')
                        LAPORAN PRESENSI HARIAN GURU & TENAGA KEPENDIDIKAN
                    @else
                        LAPORAN REKAPITULASI PRESENSI & KEHADIRAN BULANAN GTK
                    @endif
                </div>
                <div style="font-size: 8.5pt; font-weight: 600; margin-top: 2px;">
                    @if($activeTab === 'harian')
                        Hari/Tanggal: {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}
                    @else
                        Periode: Bulan {{ \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->translatedFormat('F Y') }}
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB 1: MONITORING HARIAN --}}
        @if($activeTab === 'harian')
            {{-- Statistik Ringkasan Harian (Layar Monitor Saja) --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 print:hidden">
                <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 font-semibold">Total GTK</p>
                    <p class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $this->dailyStats['total'] }}</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Personil Resmi</p>
                </div>
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 rounded-xl border border-emerald-200 dark:border-emerald-800">
                    <p class="text-xs text-emerald-700 dark:text-emerald-300 font-semibold">Tepat Waktu</p>
                    <p class="text-2xl font-black text-emerald-800 dark:text-emerald-200 mt-1">{{ $this->dailyStats['tepat_waktu'] }}</p>
                    <p class="text-[10px] text-emerald-600 mt-0.5">Hadir s/d 07:30</p>
                </div>
                <div class="p-4 bg-amber-50 dark:bg-amber-950/40 rounded-xl border border-amber-200 dark:border-amber-800">
                    <p class="text-xs text-amber-700 dark:text-amber-300 font-semibold">Terlambat</p>
                    <p class="text-2xl font-black text-amber-800 dark:text-amber-200 mt-1">{{ $this->dailyStats['terlambat'] }}</p>
                    <p class="text-[10px] text-amber-600 mt-0.5">Hadir > 07:30</p>
                </div>
                <div class="p-4 bg-blue-50 dark:bg-blue-950/40 rounded-xl border border-blue-200 dark:border-blue-800">
                    <p class="text-xs text-blue-700 dark:text-blue-300 font-semibold">Izin / Sakit / Dinas</p>
                    <p class="text-2xl font-black text-blue-800 dark:text-blue-200 mt-1">{{ $this->dailyStats['izin_sakit'] + $this->dailyStats['dinas_luar'] }}</p>
                    <p class="text-[10px] text-blue-600 mt-0.5">Dinas Luar & Berhalangan</p>
                </div>
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 rounded-xl border border-rose-200 dark:border-rose-800">
                    <p class="text-xs text-rose-700 dark:text-rose-300 font-semibold">Belum Presensi</p>
                    <p class="text-2xl font-black text-rose-800 dark:text-rose-200 mt-1">{{ $this->dailyStats['belum'] }}</p>
                    <p class="text-[10px] text-rose-600 mt-0.5">Belum Absen Masuk</p>
                </div>
            </div>

            {{-- Tabel Monitoring Harian Seluruh Guru --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center print:hidden">
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">
                            Daftar Kehadiran Seluruh GTK ({{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }})
                        </h3>
                        <p class="text-xs text-gray-500">Monitoring realtime presensi digital berbasis GPS Geofencing SDN Cinanggerang I</p>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 text-xs font-bold rounded-full">
                        Tingkat Kehadiran: {{ $this->dailyStats['persen'] }}%
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300 uppercase font-bold text-[10px] tracking-wider border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="p-2.5 text-center w-10">No</th>
                                <th class="p-2.5">Nama Lengkap & NIP</th>
                                <th class="p-2.5">Jabatan</th>
                                <th class="p-2.5 text-center">Absen Datang</th>
                                <th class="p-2.5 text-center">Absen Pulang</th>
                                <th class="p-2.5 text-center">Status Kehadiran</th>
                                <th class="p-2.5">Keterangan / Lokasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($this->dailyAttendance as $index => $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                    <td class="p-2.5 text-center font-semibold text-gray-500">{{ $index + 1 }}</td>
                                    <td class="p-2.5">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $row->teacher->nama }}, {{ $row->teacher->gelar }}</div>
                                        <div class="text-[10px] text-gray-500 font-mono">{{ $row->teacher->nip ? 'NIP: ' . $row->teacher->nip : 'NUPTK: ' . ($row->teacher->nuptk ?? '-') }}</div>
                                    </td>
                                    <td class="p-2.5 text-gray-700 dark:text-gray-300">{{ $row->teacher->jabatan }}</td>
                                    <td class="p-2.5 text-center">
                                        <div class="font-mono font-bold {{ $row->jam_masuk !== '-' ? 'text-green-600 dark:text-green-400' : 'text-gray-400' }}">
                                            {{ $row->jam_masuk }}
                                        </div>
                                        @if($row->jarak_meter !== null)
                                            <div class="text-[9px] {{ $row->is_in_radius ? 'text-emerald-600' : 'text-rose-600' }} font-semibold">
                                                {{ $row->jarak_meter }} m ({{ $row->is_in_radius ? 'Zona' : 'Luar' }})
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-2.5 text-center">
                                        <div class="font-mono font-bold {{ $row->jam_pulang !== '-' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400' }}">
                                            {{ $row->jam_pulang }}
                                        </div>
                                        @if($row->jarak_pulang_meter !== null)
                                            <div class="text-[9px] {{ $row->is_in_radius_pulang ? 'text-blue-600' : 'text-rose-600' }} font-semibold">
                                                {{ $row->jarak_pulang_meter }} m
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-2.5 text-center">
                                        @php
                                            $s = strtolower($row->status);
                                            $colorClasses = match(true) {
                                                str_contains($s, 'tepat') => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
                                                str_contains($s, 'terlambat') || str_contains($s, 'telat') => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
                                                str_contains($s, 'dinas') => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300',
                                                str_contains($s, 'sakit'), str_contains($s, 'izin'), str_contains($s, 'cuti') => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
                                                default => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300'
                                            };
                                        @endphp
                                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold {{ $colorClasses }}">
                                            {{ $row->status }}
                                        </span>
                                    </td>
                                    <td class="p-2.5 text-gray-600 dark:text-gray-300 text-[11px]">
                                        {{ $row->keterangan }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- TAB 2: REKAP BULANAN --}}
        @if($activeTab === 'bulanan')
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 print:hidden">
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">
                            Rekapitulasi Kehadiran Bulanan Guru & Tendik ({{ \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->translatedFormat('F Y') }})
                        </h3>
                        <p class="text-xs text-gray-500">
                            Formula resmi: Total Hadir = Tepat + Telat + Dinas Luar (Dinas resmi diakui hadir 100%).
                        </p>
                    </div>
                    @if($this->monthlyRecap->first())
                        <div class="flex items-center gap-2 text-xs">
                            <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 rounded-lg font-semibold">
                                📅 Hari Efektif: <strong class="text-emerald-600">{{ $this->monthlyRecap->first()->hari_efektif }}</strong> hari
                            </span>
                            @if($this->monthlyRecap->first()->hari_libur > 0)
                                <span class="px-2.5 py-1 bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 rounded-lg font-semibold">
                                    🏖️ Libur Resmi: <strong>{{ $this->monthlyRecap->first()->hari_libur }}</strong> hari
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-700 dark:text-gray-300 uppercase font-bold text-[10px] tracking-wider border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="p-2.5 text-center w-8">No</th>
                                <th class="p-2.5">Nama Guru & Tendik</th>
                                <th class="p-2.5">Jabatan</th>
                                <th class="p-2.5 text-center bg-emerald-50/50 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-300" title="Hadir Tepat Waktu">Tepat (H)</th>
                                <th class="p-2.5 text-center bg-amber-50/50 dark:bg-amber-950/20 text-amber-800 dark:text-amber-300" title="Hadir Terlambat">Telat (T)</th>
                                <th class="p-2.5 text-center bg-indigo-50/50 dark:bg-indigo-950/20 text-indigo-800 dark:text-indigo-300" title="Dinas Luar Resmi">Dinas (D)</th>
                                <th class="p-2.5 text-center bg-blue-50/50 dark:bg-blue-950/20 text-blue-800 dark:text-blue-300" title="Sakit">Sakit (S)</th>
                                <th class="p-2.5 text-center bg-purple-50/50 dark:bg-purple-950/20 text-purple-800 dark:text-purple-300" title="Izin">Izin (I)</th>
                                <th class="p-2.5 text-center bg-rose-50/50 dark:bg-rose-950/20 text-rose-800 dark:text-rose-300" title="Alpa / Tanpa Keterangan">Alpa (A)</th>
                                <th class="p-2.5 text-center font-bold bg-slate-100 dark:bg-slate-900">Total Hadir</th>
                                <th class="p-2.5 text-center font-bold">Tingkat Disiplin (%)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($this->monthlyRecap as $index => $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                    <td class="p-2.5 text-center font-semibold text-gray-500">{{ $index + 1 }}</td>
                                    <td class="p-2.5">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $row->teacher->nama }}, {{ $row->teacher->gelar }}</div>
                                        <div class="text-[10px] text-gray-500 font-mono">{{ $row->teacher->nip ? 'NIP: ' . $row->teacher->nip : 'NUPTK: ' . ($row->teacher->nuptk ?? '-') }}</div>
                                    </td>
                                    <td class="p-2.5 text-gray-600 dark:text-gray-400">{{ $row->teacher->jabatan }}</td>
                                    <td class="p-2.5 text-center font-bold text-emerald-600">{{ $row->tepat }}</td>
                                    <td class="p-2.5 text-center font-bold text-amber-600">{{ $row->telat }}</td>
                                    <td class="p-2.5 text-center font-bold text-indigo-600">{{ $row->dinas }}</td>
                                    <td class="p-2.5 text-center font-semibold text-blue-600">{{ $row->sakit }}</td>
                                    <td class="p-2.5 text-center font-semibold text-purple-600">{{ $row->izin }}</td>
                                    <td class="p-2.5 text-center font-bold text-rose-600">{{ $row->alpa }}</td>
                                    <td class="p-2.5 text-center font-black text-gray-900 dark:text-white text-sm bg-slate-50/60 dark:bg-slate-900/60">
                                        {{ $row->total_hadir }} <span class="text-[10px] font-normal text-gray-400">/ {{ $row->hari_efektif }}</span>
                                    </td>
                                    <td class="p-2.5 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2 print-hide-bar">
                                                <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $row->persentase }}%"></div>
                                            </div>
                                            <span class="font-bold text-xs {{ $row->persentase >= 90 ? 'text-emerald-600' : ($row->persentase >= 75 ? 'text-amber-600' : 'text-rose-600') }}">
                                                {{ $row->persentase }}%
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- TANDA TANGAN RESMI KEPALA SEKOLAH & PENGAWAS (Hanya Muncul Saat Dicetak) --}}
        <div class="hidden print:block">
            <div class="print-signature-section">
                <div style="font-size: 9pt; text-align: left;">
                    <div>Mengetahui,</div>
                    <div style="font-weight: bold;">Pengawas Pembina SD Kec. Pamulihan</div>
                    <div style="height: 60px;"></div>
                    <div style="font-weight: bold; text-decoration: underline;">( ..................................................... )</div>
                    <div>NIP. .....................................................</div>
                </div>

                <div style="font-size: 9pt; text-align: right;">
                    <div>Sumedang, {{ now()->translatedFormat('d F Y') }}</div>
                    <div style="font-weight: bold;">Kepala {{ $this->schoolProfile?->nama_sekolah ?? 'SD Negeri Cinanggerang I' }}</div>
                    <div style="height: 60px;"></div>
                    <div style="font-weight: bold; text-decoration: underline;">
                        {{ $this->schoolProfile?->nama_kepala_sekolah ?? 'AEP YAYAT, S.Pd.SD' }}
                    </div>
                    <div>NIP. {{ $this->schoolProfile?->nip_kepala_sekolah ?? '197206102014111001' }}</div>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>