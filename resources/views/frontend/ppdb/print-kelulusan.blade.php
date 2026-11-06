<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Kelulusan PPDB - {{ $calon->nama_lengkap }} ({{ $calon->registration_number }})</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Times+New+Roman&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @page {
            size: A4;
            margin: 15mm 20mm 20mm 20mm;
        }
        @media print {
            body {
                background: white !important;
                color: black !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .page-sheet {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #111827;
        }
        .font-sans {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
        }
    </style>
</head>
<body class="bg-slate-100 py-6">

    {{-- Tombol Tindakan Cetak (Hanya tampil di layar browser, tersembunyi saat diprint) --}}
    <div class="no-print max-w-4xl mx-auto mb-6 px-4 flex items-center justify-between">
        <a href="{{ route('ppdb') }}" class="font-sans inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-gray-300 text-xs font-bold text-gray-700 hover:bg-gray-50 shadow-sm transition">
            ← Kembali ke Halaman PPDB
        </a>
        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()" class="font-sans inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-black shadow-md transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Unduh Dokumen Resmi (PDF)</span>
            </button>
        </div>
    </div>

    {{-- LEMBAR SURAT RESMI A4 --}}
    <div class="page-sheet max-w-4xl mx-auto bg-white p-8 sm:p-12 shadow-xl border border-gray-200">

        {{-- 1. KOP SURAT RESMI PEMERINTAH KABUPATEN SUMEDANG --}}
        <div class="flex items-center justify-between border-b-4 border-double border-gray-900 pb-3 mb-6">
            {{-- Logo Pemkab Sumedang / Tut Wuri Handayani --}}
            <div class="w-20 h-20 flex items-center justify-center shrink-0">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg"
                     alt="Kemendikdasmen"
                     class="w-16 h-16 object-contain">
            </div>

            {{-- Teks Lembaga & Satuan Pendidikan --}}
            <div class="text-center flex-1 px-4">
                <h3 class="text-sm font-bold uppercase tracking-wider leading-tight">
                    PEMERINTAH DAERAH KABUPATEN SUMEDANG
                </h3>
                <h3 class="text-xs font-bold uppercase tracking-wide leading-tight">
                    DINAS PENDIDIKAN
                </h3>
                <h2 class="text-lg sm:text-xl font-black uppercase tracking-tight text-gray-950 mt-0.5 leading-snug">
                    SD NEGERI CINANGGERANG I
                </h2>
                <p class="text-[10px] leading-tight text-gray-700 mt-1">
                    NPSN: {{ $profile?->npsn ?? '20208801' }} | NSS: 101021004001 | Status Akreditasi: A (Unggul)
                </p>
                <p class="text-[10px] leading-tight text-gray-600 italic">
                    {{ $profile?->alamat ?? 'Dusun Ciidras RT 02 RW 01 Desa Cinanggerang, Kec. Pamulihan, Kab. Sumedang 45365' }}
                </p>
                <p class="text-[9.5px] leading-tight text-gray-600">
                    Website: https://sdncinanggerang1.sch.id | Email: {{ $profile?->email ?? 'sdncinanggerang1@gmail.com' }} | Telp: {{ $profile?->telepon ?? '0813-2102-1585' }}
                </p>
            </div>

            {{-- Logo Resmi Sekolah --}}
            <div class="w-20 h-20 flex items-center justify-center shrink-0">
                <img src="{{ $profile?->logo_url ?? asset('images/logo-official.png') }}"
                     alt="Logo SDN Cinanggerang I"
                     class="w-18 h-18 object-contain">
            </div>
        </div>

        {{-- 2. JUDUL SURAT RESMI --}}
        <div class="text-center mb-6">
            <h1 class="text-base sm:text-lg font-black uppercase underline tracking-wider decoration-1">
                SURAT KETERANGAN KELULUSAN SELEKSI PESERTA DIDIK BARU
            </h1>
            <p class="text-xs font-semibold mt-1">
                Nomor: 421.2/PPDB-{{ date('Y') }}/{{ str_replace('REG-', '', $calon->registration_number) }}
            </p>
            <p class="text-[11px] text-gray-700 italic">
                Tahun Pelajaran {{ $setting->academic_year ?? '2026/2027' }}
            </p>
        </div>

        {{-- 3. PERNYATAAN KEPALA SEKOLAH --}}
        <div class="text-xs leading-relaxed space-y-3 mb-5">
            <p class="text-justify">
                Berdasarkan hasil verifikasi administrasi dan keputusan rapat Panitia Penerimaan Peserta Didik Baru (PPDB) Sekolah Dasar Negeri Cinanggerang I Kecamatan Pamulihan Kabupaten Sumedang, Kepala Sekolah SD Negeri Cinanggerang I dengan ini menerangkan bahwa:
            </p>

            {{-- Tabel Biodata Calon Siswa --}}
            <table class="w-full text-xs my-3 border-collapse">
                <tbody>
                    <tr class="leading-relaxed">
                        <td class="w-48 py-1 font-semibold text-gray-700">1. Nomor Registrasi PPDB</td>
                        <td class="w-4 py-1 text-center font-bold">:</td>
                        <td class="py-1 font-mono font-bold text-gray-950">{{ $calon->registration_number }}</td>
                    </tr>
                    <tr class="leading-relaxed">
                        <td class="py-1 font-semibold text-gray-700">2. Nama Lengkap Calon Siswa</td>
                        <td class="py-1 text-center font-bold">:</td>
                        <td class="py-1 font-black text-gray-950 uppercase">{{ $calon->nama_lengkap }}</td>
                    </tr>
                    <tr class="leading-relaxed">
                        <td class="py-1 font-semibold text-gray-700">3. NIK (Nomor Induk Kependudukan)</td>
                        <td class="py-1 text-center font-bold">:</td>
                        <td class="py-1 font-mono text-gray-900">{{ $calon->nik }}</td>
                    </tr>
                    @if($calon->nisn)
                    <tr class="leading-relaxed">
                        <td class="py-1 font-semibold text-gray-700">4. NISN</td>
                        <td class="py-1 text-center font-bold">:</td>
                        <td class="py-1 font-mono text-gray-900">{{ $calon->nisn }}</td>
                    </tr>
                    @endif
                    <tr class="leading-relaxed">
                        <td class="py-1 font-semibold text-gray-700">5. Jenis Kelamin</td>
                        <td class="py-1 text-center font-bold">:</td>
                        <td class="py-1 text-gray-900">{{ $calon->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr class="leading-relaxed">
                        <td class="py-1 font-semibold text-gray-700">6. Tempat, Tanggal Lahir</td>
                        <td class="py-1 text-center font-bold">:</td>
                        <td class="py-1 text-gray-900">{{ $calon->tempat_lahir }}, {{ $calon->tanggal_lahir->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr class="leading-relaxed">
                        <td class="py-1 font-semibold text-gray-700">7. Asal Jenjang Pendidikan</td>
                        <td class="py-1 text-center font-bold">:</td>
                        <td class="py-1 text-gray-900">{{ $calon->asal_jenjang }} {{ $calon->nama_asal_sekolah ? '(' . $calon->nama_asal_sekolah . ')' : '' }}</td>
                    </tr>
                    <tr class="leading-relaxed">
                        <td class="py-1 font-semibold text-gray-700">8. Nama Orang Tua (Ibu / Ayah)</td>
                        <td class="py-1 text-center font-bold">:</td>
                        <td class="py-1 text-gray-900">{{ $calon->nama_ibu_kandung }} {{ $calon->nama_ayah_kandung ? '/ ' . $calon->nama_ayah_kandung : '' }}</td>
                    </tr>
                    <tr class="leading-relaxed">
                        <td class="py-1 font-semibold text-gray-700">9. Alamat Tempat Tinggal</td>
                        <td class="py-1 text-center font-bold">:</td>
                        <td class="py-1 text-gray-900">{{ $calon->alamat_tinggal }}</td>
                    </tr>
                    <tr class="leading-relaxed">
                        <td class="py-1 font-semibold text-gray-700">10. Jalur Seleksi Pendaftaran</td>
                        <td class="py-1 text-center font-bold">:</td>
                        <td class="py-1 font-bold text-gray-950">
                            Jalur {{ $calon->jalur_pendaftaran ?? 'Zonasi' }}
                            @if($calon->jalur_pendaftaran === 'Afirmasi' && $calon->nomor_kip_pkh)
                                <span class="font-normal text-gray-600">(KIP/PKH: {{ $calon->nomor_kip_pkh }})</span>
                            @elseif($calon->jalur_pendaftaran === 'Perpindahan Orang Tua' && $calon->instansi_tujuan_mutasi)
                                <span class="font-normal text-gray-600">(Instansi: {{ $calon->instansi_tujuan_mutasi }})</span>
                            @elseif($calon->jalur_pendaftaran === 'Zonasi' && $calon->jarak_ke_sekolah_meter)
                                <span class="font-normal text-gray-600">(Estimasi Jarak: {{ $calon->jarak_ke_sekolah_meter }} m)</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- Kotak Pernyataan DITERIMA --}}
            <div class="p-3.5 my-3 bg-emerald-50 border-2 border-emerald-600 rounded-lg text-center">
                <span class="text-[11px] uppercase tracking-widest font-bold text-emerald-800 block">K E P U T U S A N :</span>
                <p class="text-base font-black uppercase text-emerald-900 tracking-wider mt-0.5">
                    D I T E R I M A
                </p>
                <p class="text-xs font-semibold text-emerald-800 mt-0.5">
                    Sebagai Peserta Didik Baru Kelas I SD Negeri Cinanggerang I Tahun Pelajaran {{ $setting->academic_year ?? '2026/2027' }}
                </p>
            </div>

            {{-- 4. KETENTUAN DAFTAR ULANG --}}
            <div class="space-y-1 pt-1">
                <p class="font-bold underline text-gray-900">Petunjuk Daftar Ulang Fisik & Dokumen:</p>
                <ol class="list-decimal list-inside space-y-0.5 text-[11px] text-gray-800 leading-normal pl-1">
                    <li>Membawa cetakan <strong>Surat Keterangan Kelulusan</strong> ini ke sekretariat PPDB SDN Cinanggerang I.</li>
                    <li>Melampirkan fotokopi <strong>Kartu Keluarga (KK)</strong> dan <strong>Akta Kelahiran</strong> masing-masing 2 rangkap.</li>
                    <li>Melampirkan fotokopi KTP kedua orang tua masing-masing 1 lembar.</li>
                    <li>Melampirkan fotokopi Ijazah/SKL TK/RA/PAUD asli bagi yang memiliki.</li>
                    <li>Seluruh berkas dimasukkan ke dalam map snelhecter (Laki-laki: Map Merah | Perempuan: Map Hijau).</li>
                </ol>
            </div>
        </div>

        {{-- 5. TITIMANGSA & TANDA TANGAN KEPALA SEKOLAH --}}
        <div class="mt-8 pt-4 flex justify-between items-end text-xs">
            {{-- Catatan Panitia --}}
            <div class="max-w-xs text-[10px] text-gray-500 leading-tight">
                <p class="font-bold text-gray-700">Catatan:</p>
                <p>Surat keterangan ini adalah dokumen sah hasil seleksi PPDB Digital SDN Cinanggerang I dan berlaku sebagai tanda bukti resmi daftar ulang.</p>
                <p class="mt-2 font-mono text-[9px]">Dicetak secara digital: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>

            {{-- Kolom Tanda Tangan Kepala Sekolah --}}
            <div class="text-center w-64 space-y-1">
                <p>Pamulihan, {{ now()->translatedFormat('d F Y') }}</p>
                <p class="font-bold">Kepala SD Negeri Cinanggerang I,</p>

                {{-- Area Tanda Tangan & Stempel Resmi --}}
                <div class="h-20 flex items-center justify-center relative select-none">
                    {{-- Stempel Bulat Sekolah Samar --}}
                    <div class="w-16 h-16 rounded-full border-2 border-indigo-900/40 text-indigo-900/40 flex items-center justify-center text-[8px] font-bold text-center rotate-[-12deg] tracking-tighter">
                        SDN CINANGGERANG I
                    </div>
                </div>

                <p class="font-black text-gray-950 underline text-sm tracking-wide">
                    {{ $profile?->kepala_sekolah ?? 'AEP YAYAT, S.Pd' }}
                </p>
                <p class="text-[11px] text-gray-700">
                    NIP. {{ $profile?->nip_kepala_sekolah ?? '196805121991031008' }}
                </p>
            </div>
        </div>

    </div>

</body>
</html>
