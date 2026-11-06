<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Tab Navigasi & Tombol Cetak --}}
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

                <button type="button" onclick="window.print()" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold rounded-lg flex items-center gap-2 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Laporan</span>
                </button>
            </div>
        </div>

        {{-- KOP SURAT (Hanya Tampil Saat Dicetak) --}}
        <div class="hidden print:block text-center border-b-2 border-black pb-4 mb-6">
            <h2 class="text-lg font-bold uppercase tracking-wider">PEMERINTAH KABUPATEN SUMEDANG</h2>
            <h3 class="text-md font-bold uppercase tracking-wider">DINAS PENDIDIKAN</h3>
            <h1 class="text-xl font-extrabold uppercase">SD NEGERI CINANGGERANG I</h1>
            <p class="text-xs">NPSN: 20208801 | Alamat: Jln. Desa Cinanggerang RT 03 / RW 04, Kec. Pamulihan, Kab. Sumedang</p>
            <div class="mt-2 text-sm font-bold uppercase underline">
                LAPORAN REKAPITULASI PRESENSI GURU & TENAGA KEPENDIDIKAN
            </div>
            <p class="text-xs mt-1">
                Periode: {{ $activeTab === 'harian' ? \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') : \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->translatedFormat('F Y') }}
            </p>
        </div>

        {{-- TAB 1: MONITORING HARIAN --}}
        @if($activeTab === 'harian')
            {{-- Statistik Ringkasan Harian --}}
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
                    <p class="text-2xl font-black text-blue-800 dark:text-blue-200 mt-1">{{ $this->dailyStats['izin_sakit'] }}</p>
                    <p class="text-[10px] text-blue-600 mt-0.5">Dengan Keterangan</p>
                </div>
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 rounded-xl border border-rose-200 dark:border-rose-800">
                    <p class="text-xs text-rose-700 dark:text-rose-300 font-semibold">Belum Presensi</p>
                    <p class="text-2xl font-black text-rose-800 dark:text-rose-200 mt-1">{{ $this->dailyStats['belum'] }}</p>
                    <p class="text-[10px] text-rose-600 mt-0.5">Belum Absen Masuk</p>
                </div>
            </div>

            {{-- Tabel Monitoring Harian Seluruh Guru --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">
                            Daftar Kehadiran Seluruh GTK ({{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }})
                        </h3>
                        <p class="text-xs text-gray-500">Monitoring realtime presensi digital berbasis GPS Geofencing SDN Cinanggerang I</p>
                    </div>
                    <span class="px-3 py-1 bg-primary-100 text-primary-800 dark:bg-primary-900/40 dark:text-primary-300 text-xs font-bold rounded-full">
                        Tingkat Kehadiran: {{ $this->dailyStats['persen'] }}%
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-600 dark:text-gray-300 uppercase font-bold text-[10px] tracking-wider border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="p-3 text-center w-12">No</th>
                                <th class="p-3">Nama Lengkap & NIP</th>
                                <th class="p-3">Jabatan</th>
                                <th class="p-3">Absen Datang (Jam & GPS)</th>
                                <th class="p-3">Absen Pulang (Jam & GPS)</th>
                                <th class="p-3 text-center">Status Kehadiran</th>
                                <th class="p-3">Keterangan / Lokasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($this->dailyAttendance as $index => $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                    <td class="p-3 text-center font-semibold text-gray-500">{{ $index + 1 }}</td>
                                    <td class="p-3">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $row->teacher->nama }}, {{ $row->teacher->gelar }}</div>
                                        <div class="text-[10px] text-gray-500 font-mono">{{ $row->teacher->nip ? 'NIP: ' . $row->teacher->nip : 'NUPTK: ' . $row->teacher->nuptk }}</div>
                                    </td>
                                    <td class="p-3 text-gray-700 dark:text-gray-300">{{ $row->teacher->jabatan }}</td>
                                    <td class="p-3">
                                        <div class="font-mono font-bold {{ $row->jam_masuk !== '-' ? 'text-green-600 dark:text-green-400' : 'text-gray-400' }}">
                                            {{ $row->jam_masuk }}
                                        </div>
                                        @if($row->jarak_meter !== null)
                                            <div class="text-[10px] {{ $row->is_in_radius ? 'text-emerald-600' : 'text-rose-600' }} font-semibold">
                                                {{ $row->is_in_radius ? '📍' : '⚠️' }} {{ $row->jarak_meter }} m ({{ $row->is_in_radius ? 'Dalam Zona' : 'Luar Zona' }})
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-3">
                                        <div class="font-mono font-bold {{ $row->jam_pulang !== '-' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400' }}">
                                            {{ $row->jam_pulang }}
                                        </div>
                                        @if($row->jarak_pulang_meter !== null)
                                            <div class="text-[10px] {{ $row->is_in_radius_pulang ? 'text-blue-600' : 'text-rose-600' }} font-semibold">
                                                📍 {{ $row->jarak_pulang_meter }} m (Dalam Zona)
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center">
                                        @php
                                            $colorClasses = match($row->status) {
                                                'Hadir Tepat Waktu' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
                                                'Hadir Terlambat' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
                                                'Sakit (SKD)', 'Izin Resmi', 'Dinas Luar / Pelatihan' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
                                                default => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300'
                                            };
                                        @endphp
                                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold {{ $colorClasses }}">
                                            {{ $row->status }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-gray-500 max-w-xs truncate">
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
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">
                            Rekapitulasi Kehadiran Bulanan Guru & Tendik ({{ \Carbon\Carbon::create($selectedYear, $selectedMonth, 1)->translatedFormat('F Y') }})
                        </h3>
                        <p class="text-xs text-gray-500">Laporan akumulasi kehadiran, keterlambatan, izin, dan persentase kedisiplinan kerja</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-600 dark:text-gray-300 uppercase font-bold text-[10px] tracking-wider border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="p-3 text-center w-10">No</th>
                                <th class="p-3">Nama Guru & Tendik</th>
                                <th class="p-3">Jabatan</th>
                                <th class="p-3 text-center bg-emerald-50/50 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-300" title="Hadir Tepat Waktu">Tepat (H)</th>
                                <th class="p-3 text-center bg-amber-50/50 dark:bg-amber-950/20 text-amber-800 dark:text-amber-300" title="Terlambat">Telat (T)</th>
                                <th class="p-3 text-center bg-blue-50/50 dark:bg-blue-950/20 text-blue-800 dark:text-blue-300" title="Sakit">Sakit (S)</th>
                                <th class="p-3 text-center bg-indigo-50/50 dark:bg-indigo-950/20 text-indigo-800 dark:text-indigo-300" title="Izin">Izin (I)</th>
                                <th class="p-3 text-center bg-purple-50/50 dark:bg-purple-950/20 text-purple-800 dark:text-purple-300" title="Dinas Luar">Dinas (D)</th>
                                <th class="p-3 text-center bg-rose-50/50 dark:bg-rose-950/20 text-rose-800 dark:text-rose-300" title="Alpa">Alpa (A)</th>
                                <th class="p-3 text-center font-bold">Total Hadir</th>
                                <th class="p-3 text-center">Persentase (%)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($this->monthlyRecap as $index => $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                    <td class="p-3 text-center font-semibold text-gray-500">{{ $index + 1 }}</td>
                                    <td class="p-3">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $row->teacher->nama }}, {{ $row->teacher->gelar }}</div>
                                        <div class="text-[10px] text-gray-500 font-mono">{{ $row->teacher->nip ? 'NIP: ' . $row->teacher->nip : 'NUPTK: ' . $row->teacher->nuptk }}</div>
                                    </td>
                                    <td class="p-3 text-gray-600 dark:text-gray-400">{{ $row->teacher->jabatan }}</td>
                                    <td class="p-3 text-center font-bold text-emerald-600">{{ $row->tepat }}</td>
                                    <td class="p-3 text-center font-bold text-amber-600">{{ $row->telat }}</td>
                                    <td class="p-3 text-center font-semibold text-blue-600">{{ $row->sakit }}</td>
                                    <td class="p-3 text-center font-semibold text-indigo-600">{{ $row->izin }}</td>
                                    <td class="p-3 text-center font-semibold text-purple-600">{{ $row->dinas }}</td>
                                    <td class="p-3 text-center font-bold text-rose-600">{{ $row->alpa }}</td>
                                    <td class="p-3 text-center font-black text-gray-900 dark:text-white text-sm">
                                        {{ $row->total_hadir }} <span class="text-[10px] font-normal text-gray-400">/ {{ $row->hari_efektif }}</span>
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $row->persentase }}%"></div>
                                            </div>
                                            <span class="font-bold text-xs {{ $row->persentase >= 90 ? 'text-green-600' : ($row->persentase >= 75 ? 'text-amber-600' : 'text-rose-600') }}">
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

        {{-- TANDA TANGAN KEPALA SEKOLAH (Hanya Muncul Saat Dicetak) --}}
        <div class="hidden print:grid grid-cols-2 gap-8 pt-8 mt-8 border-t border-gray-300">
            <div class="text-xs">
                <p>Mengetahui,</p>
                <p class="font-bold">Pengawas Pembina SD</p>
                <div class="h-20"></div>
                <p class="font-bold underline">( ..................................................... )</p>
                <p>NIP. .....................................................</p>
            </div>
            <div class="text-xs text-right">
                <p>Sumedang, {{ now()->translatedFormat('d F Y') }}</p>
                <p class="font-bold">Kepala SD Negeri Cinanggerang I</p>
                <div class="h-20"></div>
                <p class="font-bold underline">AEP YAYAT, S.Pd</p>
                <p>NIP. 197206102014111001</p>
            </div>
        </div>

    </div>
</x-filament-panels::page>
