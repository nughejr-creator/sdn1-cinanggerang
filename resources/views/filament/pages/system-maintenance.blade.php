<x-filament-panels::page>
    <div class="space-y-6">

        {{-- BANNER PETUNJUK --}}
        <div class="p-4 rounded-2xl bg-gradient-to-r from-emerald-500/10 via-teal-500/10 to-blue-500/10 border border-emerald-500/20 dark:border-emerald-500/30 flex items-start gap-3.5">
            <div class="p-2.5 rounded-xl bg-emerald-600 text-white shrink-0 shadow-sm text-xl">
                ⚡
            </div>
            <div class="space-y-1">
                <h3 class="font-extrabold text-sm text-gray-900 dark:text-white">
                    Pusat Kontrol Deploy, Sinkronisasi Basis Data & Pembersihan Cache
                </h3>
                <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                    Halaman ini digunakan untuk memantau versi kode yang sedang aktif di server (Render/Koyeb/Hosting), memeriksa status kolom database (seperti Akreditasi), dan membersihkan cache secara langsung tanpa perlu masuk ke konsol SSH server.
                </p>
            </div>
        </div>

        {{-- 1. KARTU METRIK STATUS SISTEM & DEPLOY --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Kartu 1: Status Commit Terpasang --}}
            <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm space-y-2">
                <div class="flex items-center justify-between text-xs font-semibold text-gray-500 dark:text-gray-400">
                    <span>Versi Git Commit</span>
                    <span class="text-base">🚀</span>
                </div>
                <div class="font-mono text-lg font-black text-primary-600 dark:text-primary-400">
                    {{ $this->systemInfo['commit_hash'] }}
                </div>
                <div class="text-[11px] text-gray-600 dark:text-gray-300 truncate" title="{{ $this->systemInfo['commit_message'] }}">
                    {{ $this->systemInfo['commit_message'] }}
                </div>
                <div class="text-[10px] text-gray-400 dark:text-gray-500">
                    {{ $this->systemInfo['commit_date'] }}
                </div>
            </div>

            {{-- Kartu 2: Status Akreditasi Sekolah di Database --}}
            <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm space-y-2">
                <div class="flex items-center justify-between text-xs font-semibold text-gray-500 dark:text-gray-400">
                    <span>Akreditasi Sekolah Aktif</span>
                    <span class="text-base">🏅</span>
                </div>
                <div class="text-lg font-black text-amber-600 dark:text-amber-400">
                    {{ $this->systemInfo['current_akreditasi_label'] }}
                </div>
                <div class="text-[11px] flex items-center gap-1.5">
                    <span class="text-gray-500">Kolom Database:</span>
                    @if($this->systemInfo['has_akreditasi_column'])
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                            ✓ Siap / Termigrasi
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300">
                            ✕ Belum Ada (Perlu Migrasi)
                        </span>
                    @endif
                </div>
                <div class="text-[10px] text-gray-400">
                    Nilai di DB: <strong>{{ $this->systemInfo['current_akreditasi'] }}</strong>
                </div>
            </div>

            {{-- Kartu 3: Lingkungan Framework --}}
            <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm space-y-2">
                <div class="flex items-center justify-between text-xs font-semibold text-gray-500 dark:text-gray-400">
                    <span>Framework & PHP</span>
                    <span class="text-base">🐘</span>
                </div>
                <div class="text-lg font-black text-gray-900 dark:text-white">
                    Laravel v{{ $this->systemInfo['laravel_version'] }}
                </div>
                <div class="text-[11px] text-gray-600 dark:text-gray-300">
                    PHP v{{ $this->systemInfo['php_version'] }}
                </div>
                <div class="text-[10px] text-gray-400">
                    Env: <span class="uppercase font-bold">{{ $this->systemInfo['app_env'] }}</span> &bull; Debug: {{ $this->systemInfo['app_debug'] }}
                </div>
            </div>

            {{-- Kartu 4: Database Aktif --}}
            <div class="p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm space-y-2">
                <div class="flex items-center justify-between text-xs font-semibold text-gray-500 dark:text-gray-400">
                    <span>Koneksi Basis Data</span>
                    <span class="text-base">🗄️</span>
                </div>
                <div class="text-lg font-black text-gray-900 dark:text-white uppercase">
                    {{ $this->systemInfo['db_connection'] }}
                </div>
                <div class="text-[11px] text-gray-600 dark:text-gray-300 truncate" title="{{ $this->systemInfo['db_name'] }}">
                    DB: {{ $this->systemInfo['db_name'] }}
                </div>
                <div class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold">
                    ● Terhubung Normal
                </div>
            </div>

        </div>

        {{-- 2. KARTU AKSI PEMELIHARAAN (ONE-CLICK BUTTONS) --}}
        <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 space-y-4">
            <div>
                <h3 class="text-base font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                    <span>🛠️</span>
                    <span>Tindakan Pemeliharaan Cepat</span>
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Klik tombol di bawah ini untuk mengeksekusi perintah manajemen Laravel secara instan langsung di server web.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-2">

                {{-- Aksi 1: optimize:clear --}}
                <div class="p-3.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-400 dark:hover:border-primary-500 bg-gray-50/50 dark:bg-gray-900/30 flex flex-col justify-between gap-3">
                    <div class="space-y-1">
                        <div class="font-bold text-xs text-gray-900 dark:text-white flex items-center gap-1.5">
                            <span>🧹</span>
                            <span>Bersihkan Semua Cache</span>
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-snug">
                            Menghapus cache config, rute, template blade, events, dan icons.
                        </p>
                    </div>
                    <button type="button"
                            wire:click="clearOptimizeCache"
                            wire:loading.attr="disabled"
                            class="w-full py-2 px-3 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs shadow-sm transition flex items-center justify-center gap-1.5 disabled:opacity-50">
                        <span wire:loading.remove wire:target="clearOptimizeCache">Jalankan optimize:clear</span>
                        <span wire:loading wire:target="clearOptimizeCache">Memproses...</span>
                    </button>
                </div>

                {{-- Aksi 2: view:clear --}}
                <div class="p-3.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-emerald-400 dark:hover:border-emerald-500 bg-gray-50/50 dark:bg-gray-900/30 flex flex-col justify-between gap-3">
                    <div class="space-y-1">
                        <div class="font-bold text-xs text-gray-900 dark:text-white flex items-center gap-1.5">
                            <span>✨</span>
                            <span>Segarkan View & Template</span>
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-snug">
                            Menghapus seluruh file kompilasi view blade agar perubahan layout/cetakan langsung aktif.
                        </p>
                    </div>
                    <button type="button"
                            wire:click="clearViewCache"
                            wire:loading.attr="disabled"
                            class="w-full py-2 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition flex items-center justify-center gap-1.5 disabled:opacity-50">
                        <span wire:loading.remove wire:target="clearViewCache">Jalankan view:clear</span>
                        <span wire:loading wire:target="clearViewCache">Memproses...</span>
                    </button>
                </div>

                {{-- Aksi 3: migrate --force --}}
                <div class="p-3.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-amber-400 dark:hover:border-amber-500 bg-gray-50/50 dark:bg-gray-900/30 flex flex-col justify-between gap-3">
                    <div class="space-y-1">
                        <div class="font-bold text-xs text-gray-900 dark:text-white flex items-center gap-1.5">
                            <span>🗄️</span>
                            <span>Jalankan Migrasi Database</span>
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-snug">
                            Mengeksekusi seluruh file migrasi pending untuk menambahkan tabel atau kolom baru.
                        </p>
                    </div>
                    <button type="button"
                            wire:click="runMigration"
                            wire:loading.attr="disabled"
                            class="w-full py-2 px-3 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-sm transition flex items-center justify-center gap-1.5 disabled:opacity-50">
                        <span wire:loading.remove wire:target="runMigration">Jalankan migrate --force</span>
                        <span wire:loading wire:target="runMigration">Memproses...</span>
                    </button>
                </div>

                {{-- Aksi 4: fixAkreditasiProfile --}}
                <div class="p-3.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500 bg-gray-50/50 dark:bg-gray-900/30 flex flex-col justify-between gap-3">
                    <div class="space-y-1">
                        <div class="font-bold text-xs text-gray-900 dark:text-white flex items-center gap-1.5">
                            <span>🏅</span>
                            <span>Set Akreditasi B ke Profil</span>
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-snug">
                            Menyetel dan mengunci nilai akreditasi sekolah di basis data aktif ke nilai Akreditasi B.
                        </p>
                    </div>
                    <button type="button"
                            wire:click="fixAkreditasiProfile"
                            wire:loading.attr="disabled"
                            class="w-full py-2 px-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm transition flex items-center justify-center gap-1.5 disabled:opacity-50">
                        <span wire:loading.remove wire:target="fixAkreditasiProfile">Sinkronkan Akreditasi B</span>
                        <span wire:loading wire:target="fixAkreditasiProfile">Memproses...</span>
                    </button>
                </div>

            </div>
        </div>

        {{-- 3. KONSOL OUTPUT TERMINAL LARAVEL --}}
        @if($lastCommandOutput)
            <div class="p-5 rounded-2xl bg-gray-950 text-gray-100 border border-gray-800 shadow-xl space-y-3 font-mono">
                <div class="flex items-center justify-between border-b border-gray-800 pb-2.5">
                    <div class="flex items-center gap-2 text-xs">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-gray-400">Hasil Eksekusi:</span>
                        <span class="font-bold text-emerald-400">{{ $lastActionName }}</span>
                    </div>
                    <button type="button"
                            wire:click="$set('lastCommandOutput', null)"
                            class="text-xs text-gray-500 hover:text-gray-300">
                        Tutup Konsol [✕]
                    </button>
                </div>
                <pre class="text-xs text-gray-300 leading-relaxed overflow-x-auto whitespace-pre-wrap max-h-80">{{ $lastCommandOutput }}</pre>
            </div>
        @endif

    </div>
</x-filament-panels::page>
