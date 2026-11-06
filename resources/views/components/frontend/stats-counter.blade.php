<section class="relative z-20 -mt-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
         x-data="{
            hasAnimated: false,
            siswaCount: 0,
            guruCount: 0,
            rombelCount: 0,
            targetSiswa: {{ $stats['total_siswa'] ?? 219 }},
            targetGuru: {{ $stats['total_guru'] ?? 11 }},
            targetRombel: {{ $stats['total_rombel'] ?? 8 }},

            animate() {
                if (this.hasAnimated) return;
                this.hasAnimated = true;

                let s = 0, g = 0, r = 0;
                let stepS = Math.ceil(this.targetSiswa / 40);
                let timer = setInterval(() => {
                    s = Math.min(this.targetSiswa, s + stepS);
                    g = Math.min(this.targetGuru, g + 1);
                    r = Math.min(this.targetRombel, r + 1);

                    this.siswaCount = s;
                    this.guruCount = g;
                    this.rombelCount = r;

                    if (s >= this.targetSiswa && g >= this.targetGuru && r >= this.targetRombel) {
                        clearInterval(timer);
                    }
                }, 30);
            }
         }"
         x-intersect.once="animate()">

    <div class="bg-white dark:bg-forest-900 rounded-2xl shadow-xl shadow-forest-950/10 border border-gray-100 dark:border-forest-800 p-6 sm:p-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8 divide-y sm:divide-y-0 sm:divide-x divide-gray-100 dark:divide-forest-800">

            {{-- Stat 1: Siswa --}}
            <div class="flex items-center gap-4 pt-4 sm:pt-0">
                <div class="w-14 h-14 rounded-2xl bg-forest-50 dark:bg-forest-800 text-forest-800 dark:text-emerald-300 flex items-center justify-center flex-shrink-0 shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <div class="text-2xl sm:text-4xl font-black text-forest-900 dark:text-white" x-text="siswaCount">0</div>
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 dark:text-gray-400">Peserta Didik Aktif</p>
                    <span class="text-[10px] text-emerald-600 font-bold">Terdaftar Dapodik</span>
                </div>
            </div>

            {{-- Stat 2: Guru & Tendik --}}
            <div class="flex items-center gap-4 sm:pl-6 pt-4 sm:pt-0">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-sumedang-amber flex items-center justify-center flex-shrink-0 shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                </div>
                <div>
                    <div class="text-2xl sm:text-4xl font-black text-forest-900 dark:text-white" x-text="guruCount">0</div>
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 dark:text-gray-400">Pendidik & Tendik</p>
                    <span class="text-[10px] text-sumedang-amber font-bold">100% Tersertifikasi / Sarjana</span>
                </div>
            </div>

            {{-- Stat 3: Rombel --}}
            <div class="flex items-center gap-4 sm:pl-6 pt-4 sm:pt-0">
                <div class="w-14 h-14 rounded-2xl bg-teal-50 dark:bg-teal-950/40 text-sumedang-teal dark:text-teal-300 flex items-center justify-center flex-shrink-0 shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <div class="text-2xl sm:text-4xl font-black text-forest-900 dark:text-white" x-text="rombelCount">0</div>
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 dark:text-gray-400">Rombongan Belajar</p>
                    <span class="text-[10px] text-teal-600 font-bold">Kelas I s.d. Kelas VI</span>
                </div>
            </div>

            {{-- Stat 4: Portofolio Mutu --}}
            <div class="flex items-center gap-4 sm:pl-6 pt-4 sm:pt-0">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 flex items-center justify-center flex-shrink-0 shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <div class="text-2xl sm:text-4xl font-black text-forest-900 dark:text-white">12</div>
                    <p class="text-xs sm:text-sm font-semibold text-gray-500 dark:text-gray-400">Indikator E-PKKS</p>
                    <span class="text-[10px] text-emerald-600 font-bold">Permendikdasmen 23/2026</span>
                </div>
            </div>

        </div>
    </div>
</section>
