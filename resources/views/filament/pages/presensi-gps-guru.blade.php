<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Informasi Lokasi & Data Guru --}}
        <div class="md:col-span-1 space-y-6">
            {{-- Kartu Info Sekolah & Geofence --}}
            <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900/50 flex items-center justify-center text-primary-600 dark:text-primary-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">SDN Cinanggerang I</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Titik Patokan Presensi</p>
                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500">Koordinat</span>
                        <span class="font-mono text-xs font-semibold text-gray-700 dark:text-gray-300">
                            {{ number_format($schoolLat, 6) }}, {{ number_format($schoolLng, 6) }}
                        </span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500">Radius Maksimal</span>
                        <span class="font-bold text-primary-600 dark:text-primary-400">
                            {{ $maxRadius }} Meter
                        </span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500">Patokan Datang</span>
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">
                            S/d 07:30 WIB (Tepat Waktu)
                        </span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-gray-500">Patokan Pulang</span>
                        <span class="text-xs font-bold text-blue-600 dark:text-blue-400">
                            Mulai 12:00 WIB (Tepat Waktu)
                        </span>
                    </div>
                </div>
            </div>

            {{-- Kartu Pilih Guru & Status Hari Ini --}}
            <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <h4 class="font-bold text-gray-900 dark:text-white mb-3">Identitas Guru</h4>

                @if(!auth()->user()?->teacher_id)
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Pilih Guru / Tendik</label>
                        <select wire:model.live="selectedTeacherId" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-900 dark:text-white">
                            @foreach(\App\Models\Teacher::where('is_active', true)->orderBy('nama')->get() as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->nama }} ({{ $teacher->jabatan }})</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg mb-4">
                        <p class="text-xs text-gray-500">Akun Anda Tertaut Sebagai:</p>
                        <p class="font-bold text-gray-900 dark:text-white text-base">
                            {{ auth()->user()->teacher?->nama }}
                        </p>
                        <p class="text-xs text-primary-600">{{ auth()->user()->teacher?->jabatan }}</p>
                    </div>
                @endif

                {{-- Status Hari Ini --}}
                <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status Hari Ini ({{ now()->format('d M Y') }})</p>
                    @if($todayAttendance)
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">Absen Datang:</span>
                                <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                    {{ $todayAttendance->jam_masuk ?? '-' }}
                                    @if($todayAttendance->jarak_meter)
                                        <span class="text-[10px] text-gray-500 font-normal">({{ $todayAttendance->jarak_meter }} m)</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">Absen Pulang:</span>
                                <span class="text-sm font-bold text-blue-600 dark:text-blue-400">
                                    {{ $todayAttendance->jam_pulang ?? 'Belum Pulang' }}
                                    @if($todayAttendance->jarak_pulang_meter)
                                        <span class="text-[10px] text-gray-500 font-normal">({{ $todayAttendance->jarak_pulang_meter }} m)</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">Status Kehadiran:</span>
                                <span class="text-xs px-2 py-0.5 rounded-full font-bold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                    {{ $todayAttendance->status }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-lg text-amber-800 dark:text-amber-300 text-xs text-center font-medium">
                            Belum melakukan presensi hari ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Scanner Lokasi GPS & Tombol Presensi --}}
        <div class="md:col-span-2">
            <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700"
                 x-data="{
                    userLat: null,
                    userLng: null,
                    distance: null,
                    isChecking: false,
                    isInZone: false,
                    errorMessage: '',
                    schoolLat: {{ $schoolLat }},
                    schoolLng: {{ $schoolLng }},
                    maxRadius: {{ $maxRadius }},

                    init() {
                        this.detectLocation();
                    },

                    detectLocation() {
                        this.isChecking = true;
                        this.errorMessage = '';
                        this.userLat = null;
                        this.userLng = null;
                        this.distance = null;
                        this.isInZone = false;

                        if (!navigator.geolocation) {
                            this.errorMessage = 'Browser / HP Anda tidak mendukung fitur Geolocation GPS.';
                            this.isChecking = false;
                            return;
                        }

                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                this.userLat = position.coords.latitude;
                                this.userLng = position.coords.longitude;
                                this.distance = this.calculateDistance(this.schoolLat, this.schoolLng, this.userLat, this.userLng);
                                this.isInZone = this.distance <= this.maxRadius;
                                this.isChecking = false;
                            },
                            (error) => {
                                this.isChecking = false;
                                switch(error.code) {
                                    case error.PERMISSION_DENIED:
                                        this.errorMessage = 'Akses lokasi ditolak! Harap izinkan akses lokasi (GPS) pada pengaturan browser/HP Anda.';
                                        break;
                                    case error.POSITION_UNAVAILABLE:
                                        this.errorMessage = 'Sinyal GPS tidak terdeteksi. Silakan coba di area terbuka.';
                                        break;
                                    case error.TIMEOUT:
                                        this.errorMessage = 'Waktu pencarian lokasi habis. Silakan klik Cek Ulang Lokasi.';
                                        break;
                                    default:
                                        this.errorMessage = 'Terjadi kesalahan saat mendeteksi GPS: ' + error.message;
                                }
                            },
                            {
                                enableHighAccuracy: true,
                                timeout: 15000,
                                maximumAge: 0
                            }
                        );
                    },

                    calculateDistance(lat1, lon1, lat2, lon2) {
                        const R = 6371000;
                        const dLat = (lat2 - lat1) * Math.PI / 180;
                        const dLon = (lon2 - lon1) * Math.PI / 180;
                        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                                  Math.sin(dLon/2) * Math.sin(dLon/2);
                        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                        return Math.round(R * c);
                    },

                    submitAttendance() {
                        if (!this.isInZone) {
                            alert('Anda berada di luar zona sekolah! Jarak Anda: ' + this.distance + ' meter (Batas maksimal: ' + this.maxRadius + ' meter).');
                            return;
                        }
                        $wire.submitGpsAttendance(this.userLat, this.userLng);
                    }
                 }">

                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span>📡</span> Deteksi Geofencing GPS Realtime
                        </h2>
                        <p class="text-sm text-gray-500">Sistem otomatis memverifikasi posisi GPS perangkat Anda dengan titik SDN Cinanggerang I.</p>
                    </div>

                    <button type="button" @click="detectLocation()" :disabled="isChecking"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-semibold rounded-lg flex items-center gap-2 transition">
                        <svg class="w-4 h-4" :class="{'animate-spin': isChecking}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span x-text="isChecking ? 'Mendeteksi...' : 'Cek Ulang GPS'"></span>
                    </button>
                </div>

                {{-- Status Deteksi Loading --}}
                <div x-show="isChecking" class="p-8 text-center bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700 mb-6">
                    <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-primary-500 border-t-transparent mb-3"></div>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Menghubungkan ke satelit GPS perangkat Anda...</p>
                    <p class="text-xs text-gray-500">Pastikan GPS / Lokasi HP Anda sudah dalam kondisi ON.</p>
                </div>

                {{-- Error Message (Akses Ditolak) --}}
                <div x-show="errorMessage && !isChecking" x-cloak
                     class="p-5 bg-red-50 dark:bg-red-950/40 border border-red-300 dark:border-red-800 rounded-xl text-red-700 dark:text-red-300 mb-6 flex items-start gap-3">
                    <svg class="w-6 h-6 flex-shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <h4 class="font-bold text-sm">Gagal Mengakses Lokasi GPS</h4>
                        <p class="text-xs mt-1" x-text="errorMessage"></p>
                    </div>
                </div>

                {{-- HASIL 1: VALID DALAM ZONA (HIJAU) --}}
                <div x-show="!isChecking && userLat && isInZone" x-cloak
                     class="p-6 bg-emerald-50 dark:bg-emerald-950/40 border-2 border-emerald-500 rounded-xl mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 text-2xl shadow-lg">
                            ✓
                        </div>
                        <div class="flex-1">
                            <span class="inline-block px-2.5 py-0.5 bg-emerald-200 dark:bg-emerald-800 text-emerald-900 dark:text-emerald-100 text-xs font-bold rounded-full mb-1">
                                LOKASI TERVERIFIKASI DALAM ZONA SEKOLAH
                            </span>
                            <h3 class="text-lg font-bold text-emerald-900 dark:text-emerald-200">
                                Jarak Anda: <span class="text-2xl font-extrabold" x-text="distance"></span> Meter dari Sekolah
                            </h3>
                            <p class="text-xs text-emerald-700 dark:text-emerald-300 mt-0.5">
                                Koordinat Anda: <span class="font-mono font-semibold" x-text="userLat?.toFixed(6) + ', ' + userLng?.toFixed(6)"></span> (Maksimal: {{ $maxRadius }} meter).
                            </p>
                        </div>
                    </div>
                </div>

                {{-- HASIL 2: TIDAK VALID / LUAR ZONA (MERAH) --}}
                <div x-show="!isChecking && userLat && !isInZone" x-cloak
                     class="p-6 bg-rose-50 dark:bg-rose-950/40 border-2 border-rose-500 rounded-xl mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-rose-500 text-white flex items-center justify-center flex-shrink-0 text-2xl shadow-lg">
                            ✕
                        </div>
                        <div class="flex-1">
                            <span class="inline-block px-2.5 py-0.5 bg-rose-200 dark:bg-rose-800 text-rose-900 dark:text-rose-100 text-xs font-bold rounded-full mb-1">
                                ANDA BERADA DI LUAR ZONA SDN CINANGGERANG I
                            </span>
                            <h3 class="text-lg font-bold text-rose-900 dark:text-rose-200">
                                Jarak Anda: <span class="text-2xl font-extrabold" x-text="distance"></span> Meter
                            </h3>
                            <p class="text-xs text-rose-700 dark:text-rose-300 mt-0.5">
                                ⚠️ Anda tidak dapat melakukan presensi karena jarak Anda melebihi batas toleransi (<span class="font-bold">{{ $maxRadius }} meter</span>). Silakan lakukan presensi saat sudah tiba di lingkungan sekolah.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL TINDAKAN PRESENSI --}}
                <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                    @if(!$todayAttendance)
                        {{-- Tombol Masuk / Datang --}}
                        <div class="mb-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300 text-xs font-bold rounded-full">
                                TAHAP 1: ABSEN DATANG (PAGI)
                            </span>
                        </div>
                        <button type="button"
                                @click="submitAttendance()"
                                :disabled="!isInZone || isChecking"
                                :class="isInZone && !isChecking ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-500/30 cursor-pointer' : 'bg-gray-300 dark:bg-gray-700 text-gray-500 cursor-not-allowed'"
                                class="w-full py-4 px-6 rounded-xl font-bold text-base flex items-center justify-center gap-3 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            <span>KLIK UNTUK ABSEN DATANG SEKARANG 🏫</span>
                        </button>
                    @elseif($todayAttendance && !$todayAttendance->jam_pulang)
                        {{-- Tombol Pulang --}}
                        <div class="mb-3 text-center">
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 text-xs font-bold rounded-full">
                                TAHAP 2: ABSEN PULANG (SORE / SIANG)
                            </span>
                            <p class="text-xs text-gray-500 mt-1">Anda sudah absen datang pada pukul <span class="font-bold text-gray-800 dark:text-white">{{ $todayAttendance->jam_masuk }}</span>.</p>
                        </div>
                        <button type="button"
                                @click="submitAttendance()"
                                :disabled="!isInZone || isChecking"
                                :class="isInZone && !isChecking ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-500/30 cursor-pointer' : 'bg-gray-300 dark:bg-gray-700 text-gray-500 cursor-not-allowed'"
                                class="w-full py-4 px-6 rounded-xl font-bold text-base flex items-center justify-center gap-3 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>KLIK UNTUK ABSEN PULANG SEKARANG 🏠</span>
                        </button>
                    @else
                        {{-- Sudah Selesai Masuk & Pulang --}}
                        <div class="p-4 bg-emerald-100 dark:bg-emerald-950/50 border border-emerald-300 dark:border-emerald-800 rounded-xl text-center text-emerald-800 dark:text-emerald-200 font-bold text-sm">
                            🎉 Kehadiran Anda hari ini sudah lengkap (Absen Datang & Absen Pulang). Terima kasih atas dedikasi Anda!
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>
</x-filament-panels::page>
