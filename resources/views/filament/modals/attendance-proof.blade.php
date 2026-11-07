<div class="space-y-4 text-xs sm:text-sm">
    {{-- Header Info Berkas --}}
    @php
        $isSakit = ($record->status === 'Sakit (SKD)' || $record->attendance_type === 'Sakit');
        $isIzin = ($record->status === 'Izin Resmi' || $record->attendance_type === 'Izin');
        $isDinas = ($record->attendance_type === 'Dinas Luar' || str_contains($record->status, 'Dinas Luar'));
        
        $cardBg = $isSakit ? 'bg-purple-50 dark:bg-purple-950/40 border-purple-200 dark:border-purple-800 text-purple-950 dark:text-purple-200' :
                  ($isIzin ? 'bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800 text-amber-950 dark:text-amber-200' :
                  'bg-indigo-50 dark:bg-indigo-950/40 border-indigo-200 dark:border-indigo-800 text-indigo-950 dark:text-indigo-200');

        $badgeColor = $isSakit ? 'bg-purple-200 dark:bg-purple-900 text-purple-900 dark:text-purple-100' :
                      ($isIzin ? 'bg-amber-200 dark:bg-amber-900 text-amber-900 dark:text-amber-100' :
                      'bg-indigo-200 dark:bg-indigo-900 text-indigo-900 dark:text-indigo-100');
    @endphp

    <div class="p-3.5 rounded-2xl border {{ $cardBg }} space-y-1">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider">
                @if($isSakit)
                    🏥 Berkas Keterangan Sakit (SKD)
                @elseif($isIzin)
                    📝 Berkas Permohonan Izin Resmi
                @else
                    💼 Presensi Dinas Luar (DL)
                @endif
            </span>
            <span class="px-2 py-0.5 rounded-full {{ $badgeColor }} text-[10px] font-bold">
                {{ $record->duty_proof_type ?? ($isSakit ? 'Surat Dokter' : ($isIzin ? 'Surat Izin' : 'Bukti Terlampir')) }}
            </span>
        </div>
        <h4 class="font-extrabold text-base">
            {{ $record->duty_location_name ?? ($isSakit ? 'Fasilitas Kesehatan / Rumah' : ($isIzin ? 'Izin Resmi' : 'Lokasi Tugas')) }}
        </h4>
        <p class="text-xs leading-relaxed">
            <strong>Keterangan:</strong> {{ $record->duty_description ?? ($record->keterangan ?? '-') }}
        </p>
    </div>

    {{-- Detail GPS & Waktu --}}
    <div class="grid grid-cols-2 gap-2 text-xs">
        <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700">
            <span class="text-gray-400 block text-[10px] uppercase font-bold">Waktu Rekam:</span>
            <span class="font-bold text-gray-800 dark:text-white">
                {{ $record->jam_masuk ? substr($record->jam_masuk, 0, 5) . ' WIB' : '-' }}
                @if($record->jam_pulang && !$isSakit && !$isIzin)
                    <br>Pulang: {{ substr($record->jam_pulang, 0, 5) . ' WIB' }}
                @endif
            </span>
        </div>
        <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700">
            <span class="text-gray-400 block text-[10px] uppercase font-bold">Status Kehadiran:</span>
            <span class="font-bold text-gray-800 dark:text-white">
                {{ $record->status }}
            </span>
        </div>
    </div>

    {{-- Pratinjau Berkas Bukti / Foto Kegiatan / Surat Dokter --}}
    @if(!empty($record->duty_proof_file))
        @php
            $isPdf = str_ends_with(strtolower($record->duty_proof_file), '.pdf');
            $fileUrl = asset('storage/' . $record->duty_proof_file);
        @endphp

        <div class="border border-slate-200 dark:border-gray-700 rounded-2xl p-3 bg-white dark:bg-gray-900">
            <div class="flex items-center justify-between mb-2">
                <span class="font-bold text-xs text-gray-700 dark:text-gray-300">
                    {{ $isPdf ? '📄 Dokumen Berkas Resmi (PDF)' : '📸 Berkas Foto Bukti Dukung' }}
                </span>
                <a href="{{ $fileUrl }}" target="_blank"
                   class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                    <span>Buka Penuh</span>
                    <span>↗</span>
                </a>
            </div>

            @if($isPdf)
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-gray-800 text-center space-y-2">
                    <p class="text-xs text-gray-600 dark:text-gray-300">Berkas format PDF surat bukti terlampir.</p>
                    <a href="{{ $fileUrl }}" target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow transition">
                        <span>Unduh / Lihat Dokumen PDF</span>
                    </a>
                </div>
            @else
                <div class="rounded-xl overflow-hidden max-h-80 bg-black flex items-center justify-center">
                    <img src="{{ $fileUrl }}" alt="Berkas Bukti" class="w-full h-auto max-h-80 object-contain">
                </div>
            @endif
        </div>
    @else
        <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs text-center font-medium">
            Tidak ada lampiran berkas bukti terpisah yang diunggah.
        </div>
    @endif

    {{-- Swafoto Wajah Guru saat Presensi (Jika Ada) --}}
    @if(!empty($record->foto_bukti) && $record->foto_bukti !== $record->duty_proof_file)
        <div class="pt-2 border-t border-slate-100 dark:border-gray-800 flex items-center gap-3">
            <div class="w-14 h-14 rounded-xl overflow-hidden border border-slate-300 shrink-0 bg-black">
                <img src="{{ asset('storage/' . $record->foto_bukti) }}" alt="Foto Presensi" class="w-full h-full object-cover">
            </div>
            <div>
                <p class="font-bold text-xs text-gray-800 dark:text-white">Foto Verifikasi Presensi</p>
                <p class="text-[11px] text-gray-500">Terekam oleh sistem saat formulir dikirim.</p>
            </div>
        </div>
    @endif
</div>
