<div class="space-y-4 text-xs sm:text-sm">
    {{-- Header Info Dinas --}}
    <div class="p-3.5 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-800 text-indigo-950 dark:text-indigo-200 space-y-1">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Presensi Dinas Luar (DL)</span>
            <span class="px-2 py-0.5 rounded-full bg-indigo-200 dark:bg-indigo-900 text-indigo-900 dark:text-indigo-100 text-[10px] font-bold">
                {{ $record->duty_proof_type ?? 'Bukti Terlampir' }}
            </span>
        </div>
        <h4 class="font-extrabold text-base text-indigo-950 dark:text-white">
            {{ $record->duty_location_name ?? 'Lokasi Dinas Belum Diisi' }}
        </h4>
        <p class="text-xs text-indigo-800 dark:text-indigo-300 leading-relaxed">
            <strong>Agenda:</strong> {{ $record->duty_description ?? '-' }}
        </p>
    </div>

    {{-- Detail GPS & Waktu --}}
    <div class="grid grid-cols-2 gap-2 text-xs">
        <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700">
            <span class="text-gray-400 block text-[10px] uppercase font-bold">Jam Presensi:</span>
            <span class="font-bold text-gray-800 dark:text-white">
                Datang: {{ $record->jam_masuk ? substr($record->jam_masuk, 0, 5) . ' WIB' : '-' }}
                @if($record->jam_pulang)
                    <br>Pulang: {{ substr($record->jam_pulang, 0, 5) . ' WIB' }}
                @endif
            </span>
        </div>
        <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-gray-800 border border-slate-200 dark:border-gray-700">
            <span class="text-gray-400 block text-[10px] uppercase font-bold">Jarak GPS:</span>
            <span class="font-bold text-gray-800 dark:text-white">
                {{ $record->jarak_meter ? $record->jarak_meter . ' m dari sekolah' : 'Tercatat' }}
            </span>
        </div>
    </div>

    {{-- Pratinjau Berkas Bukti / Foto Kegiatan --}}
    @if(!empty($record->duty_proof_file))
        @php
            $isPdf = str_ends_with(strtolower($record->duty_proof_file), '.pdf');
            $fileUrl = asset('storage/' . $record->duty_proof_file);
        @endphp

        <div class="border border-slate-200 dark:border-gray-700 rounded-2xl p-3 bg-white dark:bg-gray-900">
            <div class="flex items-center justify-between mb-2">
                <span class="font-bold text-xs text-gray-700 dark:text-gray-300">
                    {{ $isPdf ? '📄 Dokumen Surat Tugas / Undangan Resmi (PDF)' : '📸 Foto Dokumentasi di Lokasi Kegiatan' }}
                </span>
                <a href="{{ $fileUrl }}" target="_blank"
                   class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                    <span>Buka Penuh</span>
                    <span>↗</span>
                </a>
            </div>

            @if($isPdf)
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-gray-800 text-center space-y-2">
                    <p class="text-xs text-gray-600 dark:text-gray-300">Berkas format PDF surat tugas terlampir.</p>
                    <a href="{{ $fileUrl }}" target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow transition">
                        <span>Unduh / Lihat Dokumen PDF</span>
                    </a>
                </div>
            @else
                <div class="rounded-xl overflow-hidden max-h-80 bg-black flex items-center justify-center">
                    <img src="{{ $fileUrl }}" alt="Bukti Dinas Luar" class="w-full h-auto max-h-80 object-contain">
                </div>
            @endif
        </div>
    @else
        <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs text-center font-medium">
            Tidak ada lampiran berkas dinas luar tambahan yang diunggah.
        </div>
    @endif

    {{-- Swafoto Wajah Guru saat Presensi --}}
    @if(!empty($record->foto_bukti))
        <div class="pt-2 border-t border-slate-100 dark:border-gray-800 flex items-center gap-3">
            <div class="w-14 h-14 rounded-xl overflow-hidden border border-slate-300 shrink-0 bg-black">
                <img src="{{ asset('storage/' . $record->foto_bukti) }}" alt="Selfie Wajah" class="w-full h-full object-cover">
            </div>
            <div>
                <p class="font-bold text-xs text-gray-800 dark:text-white">Verifikasi Swafoto (Selfie) Wajah</p>
                <p class="text-[11px] text-gray-500">Terekam otomatis oleh kamera perangkat saat menekan tombol presensi.</p>
            </div>
        </div>
    @endif
</div>
