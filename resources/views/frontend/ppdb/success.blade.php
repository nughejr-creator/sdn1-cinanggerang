@extends('layouts.frontend')

@section('title', 'Pendaftaran Berhasil - PPDB SDN Cinanggerang I')
@section('canonical_url', route('ppdb.success', $registration->registration_number))

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-forest-950 py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="max-w-xl w-full bg-white dark:bg-forest-900 rounded-3xl shadow-2xl border border-gray-100 dark:border-forest-800 overflow-hidden text-center"
         x-data="{ copied: false, copyReg() { navigator.clipboard.writeText('{{ $registration->registration_number }}'); this.copied = true; setTimeout(() => this.copied = false, 2500); } }">

        {{-- Header Banner Berhasil --}}
        <div class="bg-gradient-to-r from-forest-950 via-forest-900 to-forest-950 text-white p-8">
            <div class="w-16 h-16 rounded-3xl bg-emerald-500/20 border-2 border-emerald-400 text-emerald-400 flex items-center justify-center text-3xl mx-auto mb-3 shadow-inner">
                ✓
            </div>
            <span class="inline-block px-3 py-1 rounded-full bg-emerald-900/60 border border-emerald-500/40 text-emerald-300 text-xs font-black uppercase tracking-wider mb-2">
                Pendaftaran Berhasil Diterima
            </span>
            <h1 class="text-xl sm:text-2xl font-black text-white">
                Tanda Bukti Registrasi Calon Siswa
            </h1>
            <p class="text-xs text-gray-300 mt-1 max-w-md mx-auto">
                SD Negeri Cinanggerang I — Tahun Pelajaran {{ $setting->academic_year ?? '2026/2027' }}
            </p>
        </div>

        {{-- Konten Bukti Registrasi --}}
        <div class="p-6 sm:p-8 space-y-6">

            {{-- Kartu Nomor Registrasi --}}
            <div class="p-5 rounded-2xl bg-forest-50 dark:bg-forest-800/60 border-2 border-dashed border-forest-600 dark:border-emerald-500 text-center space-y-1">
                <span class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Nomor Registrasi Resmi:
                </span>
                <div class="text-2xl sm:text-3xl font-black text-forest-900 dark:text-emerald-300 font-mono tracking-wider">
                    {{ $registration->registration_number }}
                </div>
                <button type="button" @click="copyReg()" class="inline-flex items-center gap-1.5 text-xs font-bold text-forest-800 dark:text-emerald-400 hover:underline pt-1">
                    <span x-text="copied ? '✓ Berhasil Disalin!' : '📋 Salin Nomor Registrasi'"></span>
                </button>
            </div>

            {{-- Ringkasan Data Calon Siswa --}}
            <div class="bg-slate-50 dark:bg-forest-950/60 p-4 rounded-2xl border border-gray-100 dark:border-forest-800 text-left text-xs space-y-2">
                <div class="flex justify-between py-1 border-b border-gray-200/60 dark:border-forest-800">
                    <span class="text-gray-500 dark:text-gray-400">Nama Lengkap:</span>
                    <span class="font-extrabold text-gray-900 dark:text-white">{{ $registration->nama_lengkap }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-200/60 dark:border-forest-800">
                    <span class="text-gray-500 dark:text-gray-400">NIK:</span>
                    <span class="font-mono font-bold text-gray-900 dark:text-white">{{ $registration->nik }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-200/60 dark:border-forest-800">
                    <span class="text-gray-500 dark:text-gray-400">Jenis Kelamin:</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $registration->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-200/60 dark:border-forest-800">
                    <span class="text-gray-500 dark:text-gray-400">Asal Jenjang:</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $registration->asal_jenjang }} {{ $registration->nama_asal_sekolah ? '(' . $registration->nama_asal_sekolah . ')' : '' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-gray-200/60 dark:border-forest-800">
                    <span class="text-gray-500 dark:text-gray-400">Status Saat Ini:</span>
                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-black uppercase">
                        {{ $registration->status_pendaftaran }}
                    </span>
                </div>
                <div class="flex justify-between pt-1">
                    <span class="text-gray-500 dark:text-gray-400">Waktu Mendaftar:</span>
                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $registration->created_at->format('d M Y, H:i') }} WIB</span>
                </div>
            </div>

            {{-- Petunjuk Langkah Berikutnya --}}
            <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-xs text-left space-y-2 text-amber-900 dark:text-amber-200">
                <p class="font-extrabold flex items-center gap-1.5">
                    <span>📌</span>
                    <span>Informasi Penting Bagi Orang Tua:</span>
                </p>
                <ol class="list-decimal list-inside space-y-1 text-[11px] leading-relaxed text-amber-800 dark:text-amber-300">
                    <li>Simpan atau tangkap layar (screenshot) <strong>Nomor Registrasi</strong> di atas sebagai tanda bukti sah.</li>
                    <li>Panitia akan memverifikasi berkas digital Anda dalam 1–2 hari kerja.</li>
                    <li>Gunakan tombol <strong>Cek Status Pendaftaran</strong> pada portal PPDB untuk memantau kelulusan dan mengunduh Surat Keterangan Kelulusan resmi.</li>
                </ol>
            </div>

            {{-- Tombol Aksi --}}
            <div class="pt-2 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('ppdb') }}"
                   class="flex-1 py-3 px-4 rounded-xl bg-forest-900 hover:bg-forest-800 text-white font-extrabold text-xs shadow-md transition flex items-center justify-center gap-1.5">
                    <span>🏠</span>
                    <span>Kembali ke Halaman PPDB</span>
                </a>
                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^\d]/', '', $setting->whatsapp_contact ?? '081321021585')) }}?text=Halo%20Panitia%20PPDB%20SDN%20Cinanggerang%20I,%20saya%20telah%20mendaftarkan%20calon%20siswa%20dengan%20nomor%20registrasi%20{{ $registration->registration_number }}."
                   target="_blank"
                   class="flex-1 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md transition flex items-center justify-center gap-1.5">
                    <span>💬</span>
                    <span>Konfirmasi via WhatsApp</span>
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
