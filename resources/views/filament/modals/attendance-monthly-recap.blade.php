<div class="space-y-4 text-xs sm:text-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-3.5 bg-slate-50 dark:bg-gray-800 rounded-xl border border-slate-200 dark:border-gray-700">
        <div>
            <h4 class="font-extrabold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                <span>Periode: {{ $periodLabel }}</span>
            </h4>
            <p class="text-[11px] text-gray-500">
                Rekapitulasi kehadiran digital seluruh Pendidik dan Tenaga Kependidikan SDN Cinanggerang I
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.presensi.cetak-rekap', ['bulan' => $bulan ?? now()->month, 'tahun' => $tahun ?? now()->year]) }}"
               target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Lembar Resmi (PDF)</span>
            </a>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-1.5 text-[10px] sm:text-[11px] font-bold px-1">
        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">H: Hadir Tepat</span>
        <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800">T: Terlambat</span>
        <span class="px-2 py-0.5 rounded bg-indigo-100 text-indigo-800">DL: Dinas Luar</span>
        <span class="px-2 py-0.5 rounded bg-purple-100 text-purple-800">S: Sakit</span>
        <span class="px-2 py-0.5 rounded bg-orange-100 text-orange-800">I: Izin</span>
        <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-800">A: Alpa</span>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-gray-700">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 uppercase text-[10px] tracking-wider">
                <tr>
                    <th class="py-2.5 px-3">No</th>
                    <th class="py-2.5 px-3">Nama Guru / Tendik</th>
                    <th class="py-2.5 px-2 text-center">H</th>
                    <th class="py-2.5 px-2 text-center">T</th>
                    <th class="py-2.5 px-2 text-center">DL</th>
                    <th class="py-2.5 px-2 text-center">S</th>
                    <th class="py-2.5 px-2 text-center">I</th>
                    <th class="py-2.5 px-2 text-center">A</th>
                    <th class="py-2.5 px-2 text-center">Total</th>
                    <th class="py-2.5 px-3 text-center">% Kehadiran</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-gray-800">
                @forelse($teachersRecap as $idx => $t)
                    <tr class="hover:bg-slate-50 dark:hover:bg-gray-800/50 transition">
                        <td class="py-2.5 px-3 font-semibold text-gray-400">{{ $idx + 1 }}</td>
                        <td class="py-2.5 px-3">
                            <span class="font-bold text-gray-900 dark:text-white block">{{ $t['nama'] }}</span>
                            <span class="text-[10px] text-gray-500">{{ $t['jabatan'] }}</span>
                        </td>
                        <td class="py-2.5 px-2 text-center font-bold text-emerald-600">{{ $t['hadir_tepat'] }}</td>
                        <td class="py-2.5 px-2 text-center font-bold text-amber-600">{{ $t['hadir_terlambat'] }}</td>
                        <td class="py-2.5 px-2 text-center font-bold text-indigo-600">{{ $t['dinas_luar'] }}</td>
                        <td class="py-2.5 px-2 text-center font-bold text-purple-600">{{ $t['sakit'] }}</td>
                        <td class="py-2.5 px-2 text-center font-bold text-orange-600">{{ $t['izin'] }}</td>
                        <td class="py-2.5 px-2 text-center font-bold text-rose-600">{{ $t['alpa'] }}</td>
                        <td class="py-2.5 px-2 text-center font-extrabold text-gray-700 dark:text-gray-300">{{ $t['total_hari'] }}</td>
                        <td class="py-2.5 px-3 text-center">
                            <span class="inline-block px-2 py-0.5 rounded-full text-[11px] font-black {{ $t['persentase'] >= 90 ? 'bg-emerald-100 text-emerald-800' : ($t['persentase'] >= 75 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                {{ $t['persentase'] }}%
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="py-6 text-center text-gray-400">
                            Belum ada rekaman presensi pada bulan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
