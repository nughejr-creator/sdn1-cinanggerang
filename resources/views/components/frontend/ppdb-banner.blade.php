@php
    $ppdbSetting = $setting ?? \App\Models\PpdbSetting::getSetting();
    $waClean = preg_replace('/^0/', '62', preg_replace('/[^\d]/', '', $ppdbSetting->whatsapp_contact ?? '081321021585'));
@endphp

<section id="ppdb"
         x-data="{
            openCheckModal: false,
            checkNik: '',
            checkNama: '',
            isChecking: false,
            checkResult: null,
            checkError: '',

            async submitCheck() {
                if (!this.checkNik || !this.checkNama) {
                    this.checkError = 'Mohon masukkan NIK (16 digit) dan Nama Lengkap calon siswa.';
                    return;
                }
                this.isChecking = true;
                this.checkError = '';
                this.checkResult = null;

                try {
                    const response = await fetch('{{ route('ppdb.check-status') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            nik: this.checkNik,
                            nama_lengkap: this.checkNama
                        })
                    });

                    const res = await response.json();

                    if (response.ok && res.success) {
                        this.checkResult = res.data;
                    } else {
                        this.checkError = res.message || 'Data pendaftaran tidak ditemukan. Periksa kembali NIK dan Nama Lengkap.';
                    }
                } catch (err) {
                    console.error(err);
                    this.checkError = 'Terjadi gangguan jaringan saat memverifikasi status pendaftaran.';
                } finally {
                    this.isChecking = false;
                }
            },

            resetCheck() {
                this.checkNik = '';
                this.checkNama = '';
                this.checkResult = null;
                this.checkError = '';
            }
         }"
         class="py-10 md:py-12 bg-gradient-to-br from-forest-900 via-forest-800 to-sumedang-teal text-white relative overflow-hidden">
    {{-- Hiasan Latar Belakang --}}
    <div class="absolute -right-24 -top-24 w-96 h-96 rounded-full bg-sumedang-amber/10 blur-3xl"></div>
    <div class="absolute -left-24 -bottom-24 w-96 h-96 rounded-full bg-emerald-400/10 blur-3xl"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">

            {{-- Kolom Kiri: Informasi & Jalur --}}
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-sumedang-amber/20 border border-sumedang-amber/40 text-sumedang-amber rounded-full text-xs font-extrabold uppercase tracking-wider">
                    <span>🎒</span> Penerimaan Peserta Didik Baru (PPDB) {{ $ppdbSetting->academic_year ?? '2026/2027' }}
                </div>

                <h2 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">
                    Mari Bergabung Bersama Keluarga Besar SDN Cinanggerang I
                </h2>

                <p class="text-emerald-100 text-sm sm:text-base leading-relaxed">
                    Layanan pendaftaran murid baru kini lebih mudah, transparan, dan tanpa antre. Orang tua dapat mendaftarkan putra-putri tercinta secara langsung melalui sistem PPDB Digital kami.
                </p>

                {{-- Jalur Pendaftaran --}}
                <div class="grid grid-cols-3 gap-3 pt-2">
                    <div class="p-3.5 bg-white/10 rounded-xl backdrop-blur-sm border border-white/10">
                        <span class="text-xs font-bold text-sumedang-amber">Jalur 1</span>
                        <h4 class="font-bold text-sm text-white mt-0.5">Zonasi Desa</h4>
                        <p class="text-[11px] text-gray-300 mt-1">Domisili Cinanggerang & sekitarnya</p>
                    </div>
                    <div class="p-3.5 bg-white/10 rounded-xl backdrop-blur-sm border border-white/10">
                        <span class="text-xs font-bold text-emerald-300">Jalur 2</span>
                        <h4 class="font-bold text-sm text-white mt-0.5">Afirmasi (KIP)</h4>
                        <p class="text-[11px] text-gray-300 mt-1">Pemegang KIP, PKH & KKS</p>
                    </div>
                    <div class="p-3.5 bg-white/10 rounded-xl backdrop-blur-sm border border-white/10">
                        <span class="text-xs font-bold text-teal-300">Jalur 3</span>
                        <h4 class="font-bold text-sm text-white mt-0.5">Mutasi / Pindah</h4>
                        <p class="text-[11px] text-gray-300 mt-1">Perpindahan tugas orang tua</p>
                    </div>
                </div>

                {{-- Persyaratan Singkat --}}
                <div class="p-4 bg-forest-950/40 rounded-xl border border-white/10 text-xs text-gray-200">
                    <span class="font-bold text-sumedang-amber">Dokumen yang Diperlukan:</span>
                    Akta Kelahiran Anak, Kartu Keluarga (KK), KTP Orang Tua, dan Ijazah/SKL (bagi lulusan TK/RA/PAUD).
                </div>
            </div>

            {{-- Kolom Kanan: 3 Langkah & Tombol Aksi --}}
            <div class="lg:col-span-5">
                <div class="bg-white text-gray-900 rounded-3xl p-6 sm:p-8 shadow-2xl">
                    <h3 class="text-lg font-bold text-forest-950 mb-1">Alur Pendaftaran Mudah</h3>
                    <p class="text-xs text-gray-500 mb-6">3 tahapan praktis dari pendaftaran hingga diterima</p>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-8 h-8 rounded-full bg-forest-100 text-forest-900 font-extrabold text-xs flex items-center justify-center flex-shrink-0">1</div>
                            <div>
                                <h4 class="font-bold text-xs text-gray-900">Isi Formulir Online</h4>
                                <p class="text-[11px] text-gray-500">Lengkapi data anak, orang tua, dan unggah berkas persyaratan resmi.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-8 h-8 rounded-full bg-forest-100 text-forest-900 font-extrabold text-xs flex items-center justify-center flex-shrink-0">2</div>
                            <div>
                                <h4 class="font-bold text-xs text-gray-900">Verifikasi Panitia</h4>
                                <p class="text-[11px] text-gray-500">Panitia sekolah memverifikasi kesesuaian berkas administratif.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-8 h-8 rounded-full bg-sumedang-amber text-forest-950 font-extrabold text-xs flex items-center justify-center flex-shrink-0">3</div>
                            <div>
                                <h4 class="font-bold text-xs text-gray-900">Pengumuman & Daftar Ulang</h4>
                                <p class="text-[11px] text-gray-500">Cek status penerimaan dan cetak surat keterangan kelulusan resmi.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Kontainer Tombol Aksi Sesuai Sakelar PPDB --}}
                    <div class="mt-8 pt-6 border-t border-gray-100 space-y-3">
                        @if($ppdbSetting->is_ppdb_open)
                            {{-- 1. Tombol Utama Aktif --}}
                            <a href="{{ route('ppdb.register') }}"
                               class="w-full py-3.5 px-4 rounded-xl bg-forest-900 hover:bg-forest-800 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow-lg shadow-forest-900/20 transition-all transform hover:-translate-y-0.5">
                                <span class="text-base font-bold">+</span>
                                <span>Daftar Calon Siswa Baru Sekarang</span>
                            </a>

                            {{-- 2. Tombol Cek Status Pendaftaran Tepat di Bawahnya --}}
                            <button type="button"
                                    @click="openCheckModal = true; resetCheck();"
                                    class="border-2 border-emerald-700 text-emerald-800 hover:bg-emerald-50 rounded-xl font-semibold py-2.5 px-4 w-full flex items-center justify-center gap-2 mt-3 transition shadow-sm">
                                <span>🔍</span>
                                <span>Cek Status Pendaftaran Calon Siswa</span>
                            </button>
                        @else
                            {{-- Pendaftaran Ditutup --}}
                            <button type="button" disabled
                                    class="w-full py-3.5 px-4 rounded-xl bg-slate-300 text-slate-500 cursor-not-allowed font-extrabold text-xs flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>Pendaftaran Belum Dibuka</span>
                            </button>

                            {{-- Kartu Pemberitahuan Ditutup --}}
                            <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs text-left leading-relaxed mt-2">
                                <div class="flex items-start gap-2">
                                    <span class="text-base shrink-0">📢</span>
                                    <span>{{ $ppdbSetting->closed_message ?: 'Mohon maaf, pendaftaran peserta didik baru belum dibuka atau periode pendaftaran telah berakhir.' }}</span>
                                </div>
                            </div>
                        @endif

                        {{-- 3. Tombol Kontak WhatsApp Panitia --}}
                        <a href="https://wa.me/{{ $waClean }}?text=Halo%20Panitia%20PPDB%20SDN%20Cinanggerang%20I,%20saya%20ingin%20bertanya%20informasi%20pendaftaran%20peserta%20didik%20baru."
                           target="_blank"
                           class="w-full py-2.5 px-4 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs flex items-center justify-center gap-2 transition">
                            <span>💬 Hubungi Panitia PPDB (WhatsApp)</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL CEK STATUS PENDAFTARAN CALON SISWA --}}
    {{-- ======================================================== --}}
    <div x-show="openCheckModal" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity"
         @keydown.escape.window="openCheckModal = false">

        <div class="bg-white dark:bg-forest-900 rounded-3xl max-w-lg w-full shadow-2xl border border-gray-100 dark:border-forest-800 overflow-hidden transform transition-all text-gray-900 dark:text-gray-100"
             @click.away="openCheckModal = false">

            {{-- Header Modal --}}
            <div class="bg-gradient-to-r from-forest-950 to-forest-900 text-white p-5 flex items-center justify-between border-b border-forest-800">
                <div class="flex items-center gap-2.5">
                    <span class="text-xl">🔍</span>
                    <div>
                        <h3 class="font-extrabold text-sm sm:text-base leading-tight">Cek Status Pendaftaran PPDB</h3>
                        <p class="text-[11px] text-emerald-300">Layanan Mandiri SDN Cinanggerang I</p>
                    </div>
                </div>
                <button type="button" @click="openCheckModal = false" class="text-gray-400 hover:text-white transition text-lg p-1">
                    ✕
                </button>
            </div>

            <div class="p-6 space-y-4">

                {{-- Formulir Input Pencarian --}}
                <div class="space-y-3">
                    <div>
                        <label for="check-nik" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Nomor Induk Kependudukan (NIK) Calon Siswa:
                        </label>
                        <input type="text"
                               id="check-nik"
                               x-model="checkNik"
                               maxlength="16"
                               placeholder="Masukkan 16 digit NIK sesuai Kartu Keluarga..."
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 dark:border-forest-700 bg-gray-50 dark:bg-forest-800 text-xs sm:text-sm font-mono focus:ring-2 focus:ring-forest-800 focus:border-forest-800 transition">
                    </div>

                    <div>
                        <label for="check-nama" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Nama Lengkap Calon Siswa:
                        </label>
                        <input type="text"
                               id="check-nama"
                               x-model="checkNama"
                               @keydown.enter="submitCheck()"
                               placeholder="Nama lengkap anak sesuai Akta Kelahiran..."
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 dark:border-forest-700 bg-gray-50 dark:bg-forest-800 text-xs sm:text-sm focus:ring-2 focus:ring-forest-800 focus:border-forest-800 transition">
                    </div>

                    <button type="button"
                            @click="submitCheck()"
                            :disabled="isChecking"
                            class="w-full py-3 px-4 rounded-xl bg-forest-900 hover:bg-forest-800 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow-md transition disabled:opacity-50">
                        <template x-if="isChecking">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        </template>
                        <span x-text="isChecking ? 'Memeriksa Basis Data...' : '🔍 Periksa Status Sekarang'"></span>
                    </button>
                </div>

                {{-- Alert Error jika data tidak ditemukan --}}
                <div x-show="checkError" x-cloak class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs flex items-start gap-2">
                    <span class="text-base shrink-0">⚠️</span>
                    <span class="leading-relaxed" x-text="checkError"></span>
                </div>

                {{-- Card Hasil Pencarian --}}
                <div x-show="checkResult" x-cloak class="mt-4 p-4 rounded-2xl border transition space-y-3"
                     :class="{
                        'bg-amber-50 border-amber-300 text-amber-950': checkResult?.status_pendaftaran === 'Menunggu Verifikasi',
                        'bg-emerald-50 border-emerald-300 text-emerald-950': checkResult?.status_pendaftaran === 'Diterima',
                        'bg-rose-50 border-rose-300 text-rose-950': checkResult?.status_pendaftaran === 'Ditolak'
                     }">

                    <div class="flex items-center justify-between pb-2 border-b border-gray-200/60">
                        <div>
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block">No. Registrasi:</span>
                            <span class="text-xs font-black font-mono" x-text="checkResult?.registration_number"></span>
                        </div>
                        <div>
                            {{-- Badge Status --}}
                            <template x-if="checkResult?.status_pendaftaran === 'Menunggu Verifikasi'">
                                <span class="px-2.5 py-1 rounded-full bg-amber-200 text-amber-900 text-[10px] font-black uppercase">
                                    ⏳ Menunggu Verifikasi
                                </span>
                            </template>
                            <template x-if="checkResult?.status_pendaftaran === 'Diterima'">
                                <span class="px-2.5 py-1 rounded-full bg-emerald-200 text-emerald-900 text-[10px] font-black uppercase">
                                    ✓ Lolos & Diterima
                                </span>
                            </template>
                            <template x-if="checkResult?.status_pendaftaran === 'Ditolak'">
                                <span class="px-2.5 py-1 rounded-full bg-rose-200 text-rose-900 text-[10px] font-black uppercase">
                                    ✕ Berkas Ditolak
                                </span>
                            </template>
                        </div>
                    </div>

                    <div class="space-y-1 text-xs">
                        <p class="text-sm font-black text-gray-900" x-text="checkResult?.nama_lengkap"></p>
                        <p class="text-gray-600">NIK: <span class="font-mono" x-text="checkResult?.nik"></span></p>
                        <p class="text-gray-600" x-show="checkResult?.nisn">NISN: <span class="font-mono" x-text="checkResult?.nisn"></span></p>
                        <p class="text-gray-700 font-semibold flex items-center gap-1.5 pt-0.5">
                            <span>Jalur:</span>
                            <span class="font-extrabold px-2 py-0.5 rounded text-[10px]"
                                  :class="{
                                      'bg-emerald-100 text-emerald-800 border border-emerald-300': checkResult?.jalur_pendaftaran === 'Zonasi',
                                      'bg-amber-100 text-amber-800 border border-amber-300': checkResult?.jalur_pendaftaran === 'Afirmasi',
                                      'bg-indigo-100 text-indigo-800 border border-indigo-300': checkResult?.jalur_pendaftaran === 'Perpindahan Orang Tua'
                                  }"
                                  x-text="'Jalur ' + (checkResult?.jalur_pendaftaran || 'Zonasi')"></span>
                        </p>
                        <p class="text-gray-500 text-[11px]">Waktu Pendaftaran: <span x-text="checkResult?.tanggal_daftar"></span></p>
                    </div>

                    {{-- Pesan Status Spesifik --}}
                    <div class="p-3 rounded-xl bg-white/90 border border-gray-200 text-xs">
                        <template x-if="checkResult?.status_pendaftaran === 'Menunggu Verifikasi'">
                            <div>
                                <p class="font-bold text-amber-800">Sedang Dalam Proses Verifikasi Berkas</p>
                                <p class="text-gray-600 text-[11px] mt-0.5" x-text="checkResult?.catatan_panitia || 'Panitia sedang memeriksa kelengkapan berkas fisik dan digital Anda. Silakan cek berkala.'"></p>
                            </div>
                        </template>

                        <template x-if="checkResult?.status_pendaftaran === 'Ditolak'">
                            <div>
                                <p class="font-bold text-rose-800">Pendaftaran Ditolak / Berkas Tidak Lengkap</p>
                                <p class="text-rose-700 text-[11px] mt-0.5" x-text="'Catatan Panitia: ' + (checkResult?.catatan_panitia || 'Berkas tidak memenuhi persyaratan seleksi.')"></p>
                            </div>
                        </template>

                        <template x-if="checkResult?.status_pendaftaran === 'Diterima'">
                            <div>
                                <p class="font-black text-emerald-800 text-sm">SELAMAT! Calon Peserta Didik DITERIMA di SDN Cinanggerang I</p>
                                <p class="text-emerald-700 text-[11px] mt-0.5" x-text="checkResult?.catatan_panitia || 'Silakan cetak Surat Keterangan Kelulusan resmi di bawah ini dan ikuti alur daftar ulang.'"></p>

                                {{-- Tombol Cetak Surat Kelulusan --}}
                                <a :href="checkResult?.print_url" target="_blank"
                                   class="w-full mt-3 py-2.5 px-4 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-xs flex items-center justify-center gap-2 shadow-md transition transform hover:-translate-y-0.5">
                                    <span>🖨️</span>
                                    <span>Cetak Surat Keterangan Kelulusan & Tanda Terima</span>
                                </a>
                            </div>
                        </template>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
