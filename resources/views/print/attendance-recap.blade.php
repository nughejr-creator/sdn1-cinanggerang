<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kehadiran GTK - {{ $periodLabel }} - SDN Cinanggerang I</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Times+New+Roman&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 15mm 15mm;
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
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            thead {
                display: table-header-group;
            }
            tfoot {
                display: table-footer-group;
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

    {{-- Tombol Navigasi & Cetak (Hanya tampil di browser, tersembunyi saat diprint) --}}
    <div class="no-print max-w-5xl mx-auto mb-6 px-4">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-200 flex flex-col md:flex-row items-center justify-between gap-4 font-sans">
            <div class="flex items-center gap-3">
                <a href="/admin/teacher-attendances" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-xs font-bold text-gray-700 transition">
                    ← Kembali ke Panel Admin
                </a>
                <span class="text-xs text-gray-400 font-medium hidden sm:inline">|</span>
                <span class="text-xs font-bold text-gray-800">
                    Laporan Cetak Presensi GTK
                </span>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                {{-- Form Filter Ganti Bulan & Tahun --}}
                <form method="GET" action="{{ route('admin.presensi.cetak-rekap') }}" class="flex items-center gap-1.5">
                    <select name="bulan" class="text-xs font-semibold px-2.5 py-1.5 rounded-lg border border-gray-300 bg-white focus:ring-2 focus:ring-emerald-500">
                        @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $mNum => $mName)
                            <option value="{{ $mNum }}" {{ $bulan == $mNum ? 'selected' : '' }}>{{ $mName }}</option>
                        @endforeach
                    </select>

                    <select name="tahun" class="text-xs font-semibold px-2.5 py-1.5 rounded-lg border border-gray-300 bg-white focus:ring-2 focus:ring-emerald-500">
                        @foreach([now()->subYear()->year, now()->year, now()->addYear()->year] as $yVal)
                            <option value="{{ $yVal }}" {{ $tahun == $yVal ? 'selected' : '' }}>{{ $yVal }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="px-3 py-1.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-xs font-bold transition">
                        Tampilkan
                    </button>
                </form>

                {{-- Tombol Cetak / PDF --}}
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-black shadow-md transition transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>🖨️ Cetak / Simpan PDF</span>
                </button>
            </div>
        </div>
    </div>

    {{-- LEMBAR CETAK RESMI A4 --}}
    <div class="page-sheet max-w-5xl mx-auto bg-white p-8 sm:p-12 shadow-xl border border-gray-200">

        {{-- 1. KOP SURAT RESMI PEMKAB SUMEDANG & SDN CINANGGERANG I --}}
        <div class="flex items-center justify-between border-b-4 border-double border-gray-900 pb-3 mb-5">
            {{-- Logo Pemkab Sumedang / Tut Wuri Handayani --}}
            <div class="w-20 h-20 flex items-center justify-center shrink-0">
                <img src="https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg"
                     alt="Tut Wuri Handayani"
                     class="w-16 h-16 object-contain">
            </div>

            {{-- Teks Instansi Sekolah --}}
            <div class="text-center flex-1 px-4">
                <h3 class="text-xs sm:text-sm font-bold uppercase tracking-wider leading-tight">
                    PEMERINTAH DAERAH KABUPATEN SUMEDANG
                </h3>
                <h3 class="text-xs sm:text-sm font-bold uppercase tracking-wide leading-tight">
                    DINAS PENDIDIKAN
                </h3>
                <h2 class="text-base sm:text-xl font-black uppercase tracking-tight text-gray-950 mt-0.5 leading-snug">
                    SD NEGERI CINANGGERANG I
                </h2>
                <p class="text-[10px] leading-tight text-gray-700 mt-1">
                    NPSN: {{ $profile?->npsn ?? '20208801' }} | NSS: 101021004001 | Status Akreditasi: A (Unggul)
                </p>
                <p class="text-[9.5px] leading-tight text-gray-600 italic">
                    {{ $profile?->alamat ?? 'Dusun Ciidras RT 02 RW 01 Desa Cinanggerang, Kec. Pamulihan, Kab. Sumedang 45365' }}
                </p>
                <p class="text-[9px] leading-tight text-gray-600">
                    Website: https://sdncinanggerang1.sch.id | Email: {{ $profile?->email ?? 'sdncinanggerang1@gmail.com' }}
                </p>
            </div>

            {{-- Logo Resmi Sekolah --}}
            <div class="w-20 h-20 flex items-center justify-center shrink-0">
                <img src="{{ $profile?->logo_url ?? asset('images/logo-official.png') }}"
                     alt="Logo SDN Cinanggerang I"
                     class="w-16 h-16 object-contain">
            </div>
        </div>

        {{-- 2. JUDUL DOKUMEN LAPORAN --}}
        <div class="text-center mb-6">
            <h1 class="text-sm sm:text-base font-black uppercase underline tracking-wider decoration-1">
                LAPORAN REKAPITULASI PRESENSI & KEHADIRAN GTK DIGITAL
            </h1>
            <p class="text-xs font-bold uppercase text-gray-800 mt-1">
                PERIODE BULAN: {{ strtoupper($periodLabel) }}
            </p>
            <p class="text-[10px] text-gray-600 italic">
                Data terverifikasi otomatis melalui Sistem Presensi GPS & Bukti Dukung Digital Mandiri
            </p>
        </div>

        {{-- 3. TABEL REKAPITULASI PRESENSI GTK --}}
        <div class="mb-5 overflow-x-auto">
            <table class="w-full border-collapse border border-gray-900 text-[11px] leading-tight">
                <thead>
                    <tr class="bg-gray-100 text-gray-950 uppercase text-[10px] font-bold">
                        <th class="border border-gray-800 py-2 px-1 text-center w-7">No</th>
                        <th class="border border-gray-800 py-2 px-2 text-left">Nama Pendidik / Tenaga Kependidikan</th>
                        <th class="border border-gray-800 py-2 px-2 text-center w-28">NIP / NUPTK</th>
                        <th class="border border-gray-800 py-2 px-2 text-left w-24">Jabatan</th>
                        <th class="border border-gray-800 py-2 px-1 text-center w-9" title="Hadir Tepat Waktu">H</th>
                        <th class="border border-gray-800 py-2 px-1 text-center w-9" title="Hadir Terlambat">T</th>
                        <th class="border border-gray-800 py-2 px-1 text-center w-9" title="Dinas Luar">DL</th>
                        <th class="border border-gray-800 py-2 px-1 text-center w-9" title="Sakit">S</th>
                        <th class="border border-gray-800 py-2 px-1 text-center w-9" title="Izin">I</th>
                        <th class="border border-gray-800 py-2 px-1 text-center w-9" title="Alpa / Tanpa Keterangan">A</th>
                        <th class="border border-gray-800 py-2 px-1 text-center w-11">Total</th>
                        <th class="border border-gray-800 py-2 px-1 text-center w-14">% Hadir</th>
                        <th class="border border-gray-800 py-2 px-2 text-center w-20">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totH = 0; $totT = 0; $totDL = 0; $totS = 0; $totI = 0; $totA = 0; $totSemua = 0; $sumPersen = 0;
                    @endphp
                    @forelse($teachersRecap as $idx => $t)
                        @php
                            $totH += $t['hadir_tepat'];
                            $totT += $t['hadir_terlambat'];
                            $totDL += $t['dinas_luar'];
                            $totS += $t['sakit'];
                            $totI += $t['izin'];
                            $totA += $t['alpa'];
                            $totSemua += $t['total_hari'];
                            $sumPersen += $t['persentase'];
                        @endphp
                        <tr class="{{ $loop->iteration % 2 == 0 ? 'bg-gray-50/50' : 'bg-white' }}">
                            <td class="border border-gray-700 py-1.5 px-1 text-center font-semibold text-gray-600">{{ $idx + 1 }}</td>
                            <td class="border border-gray-700 py-1.5 px-2 font-bold text-gray-900">
                                {{ $t['nama'] }}
                            </td>
                            <td class="border border-gray-700 py-1.5 px-2 text-center font-mono text-[10px]">
                                {{ $t['nip'] !== '-' ? $t['nip'] : '-' }}
                            </td>
                            <td class="border border-gray-700 py-1.5 px-2 text-gray-800 text-[10.5px]">
                                {{ $t['jabatan'] }}
                            </td>
                            <td class="border border-gray-700 py-1.5 px-1 text-center font-semibold text-gray-900">{{ $t['hadir_tepat'] }}</td>
                            <td class="border border-gray-700 py-1.5 px-1 text-center font-semibold text-amber-900">{{ $t['hadir_terlambat'] }}</td>
                            <td class="border border-gray-700 py-1.5 px-1 text-center font-semibold text-indigo-900">{{ $t['dinas_luar'] }}</td>
                            <td class="border border-gray-700 py-1.5 px-1 text-center font-semibold text-purple-900">{{ $t['sakit'] }}</td>
                            <td class="border border-gray-700 py-1.5 px-1 text-center font-semibold text-orange-900">{{ $t['izin'] }}</td>
                            <td class="border border-gray-700 py-1.5 px-1 text-center font-semibold text-red-900">{{ $t['alpa'] }}</td>
                            <td class="border border-gray-700 py-1.5 px-1 text-center font-bold text-gray-950">{{ $t['total_hari'] }}</td>
                            <td class="border border-gray-700 py-1.5 px-1 text-center font-bold text-gray-900">
                                {{ $t['persentase'] }}%
                            </td>
                            <td class="border border-gray-700 py-1.5 px-1 text-center text-[10px] text-gray-600">
                                @if($t['persentase'] >= 90)
                                    <span class="text-emerald-700 font-semibold">Sangat Baik</span>
                                @elseif($t['persentase'] >= 75)
                                    <span class="text-amber-700 font-semibold">Baik</span>
                                @else
                                    <span class="text-red-700 font-semibold">Kurang</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="border border-gray-700 py-6 text-center text-gray-500 italic">
                                Belum ada catatan presensi pada periode bulan {{ $periodLabel }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($teachersRecap) > 0)
                    <tfoot>
                        <tr class="bg-gray-100 font-bold text-gray-950">
                            <td colspan="4" class="border border-gray-800 py-2 px-3 text-center uppercase tracking-wide">
                                Jumlah Akumulasi & Rata-rata
                            </td>
                            <td class="border border-gray-800 py-2 px-1 text-center">{{ $totH }}</td>
                            <td class="border border-gray-800 py-2 px-1 text-center">{{ $totT }}</td>
                            <td class="border border-gray-800 py-2 px-1 text-center">{{ $totDL }}</td>
                            <td class="border border-gray-800 py-2 px-1 text-center">{{ $totS }}</td>
                            <td class="border border-gray-800 py-2 px-1 text-center">{{ $totI }}</td>
                            <td class="border border-gray-800 py-2 px-1 text-center">{{ $totA }}</td>
                            <td class="border border-gray-800 py-2 px-1 text-center">{{ $totSemua }}</td>
                            <td class="border border-gray-800 py-2 px-1 text-center">
                                {{ round($sumPersen / max(1, count($teachersRecap))) }}%
                            </td>
                            <td class="border border-gray-800 py-2 px-1 text-center">-</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        {{-- 4. KETERANGAN KODE SINGKATAN --}}
        <div class="mb-8 text-[10px] text-gray-700 leading-relaxed font-sans">
            <span class="font-bold underline block mb-1">Keterangan Kolom:</span>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-1">
                <div>• <strong>H</strong> : Hadir Tepat Waktu (Presensi GPS di sekolah)</div>
                <div>• <strong>T</strong> : Hadir Terlambat (Datang setelah 07:30 WIB)</div>
                <div>• <strong>DL</strong> : Dinas Luar (Surat Tugas / Berkas Dukung)</div>
                <div>• <strong>S</strong> : Sakit (Surat Keterangan Dokter / Bukti)</div>
                <div>• <strong>I</strong> : Izin Resmi (Bukti Undangan / Keterangan)</div>
                <div>• <strong>A</strong> : Tanpa Keterangan / Alpa</div>
            </div>
        </div>

        {{-- 5. TITIMANGSA & TANDA TANGAN KEPALA SEKOLAH --}}
        <div class="pt-2 flex justify-between items-end text-xs">
            {{-- Catatan & Verifikasi Sistem --}}
            <div class="max-w-xs text-[10px] text-gray-500 leading-tight">
                <p class="font-bold text-gray-800">Catatan Sekolah:</p>
                <p>Dokumen rekapitulasi kehadiran ini sah dicetak dari Sistem Informasi Manajemen Kepegawaian GTK SDN Cinanggerang I sebagai arsip kedinasan.</p>
                <p class="mt-2 font-mono text-[9px] text-gray-400">Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>

            {{-- Kolom Tanda Tangan Kepala Sekolah --}}
            <div class="text-center w-64 space-y-1">
                <p>Pamulihan, {{ now()->translatedFormat('d F Y') }}</p>
                <p class="font-bold">Kepala SD Negeri Cinanggerang I,</p>

                {{-- Area Tanda Tangan & Stempel Resmi --}}
                <div class="h-20 flex items-center justify-center relative select-none">
                    <div class="w-16 h-16 rounded-full border-2 border-indigo-900/30 text-indigo-900/40 flex items-center justify-center text-[7.5px] font-bold text-center rotate-[-12deg] tracking-tighter">
                        SDN CINANGGERANG I
                    </div>
                </div>

                <p class="font-black text-gray-950 underline text-sm tracking-wide">
                    {{ $profile?->kepala_sekolah ?? 'AEP YAYAT, S.Pd.SD' }}
                </p>
                <p class="text-[11px] text-gray-700">
                    NIP. {{ $profile?->nip_kepala_sekolah ?? '197206102014111001' }}
                </p>
            </div>
        </div>

    </div>

</body>
</html>