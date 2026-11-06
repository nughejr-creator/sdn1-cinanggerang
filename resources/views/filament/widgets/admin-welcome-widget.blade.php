@php
    $user = filament()->auth()->user();
    $headmaster = \App\Models\HeadmasterProfile::where('is_active', true)->first() ?? \App\Models\HeadmasterProfile::first();
    $school = \App\Models\SchoolProfile::first();
    $photoUrl = (!empty($headmaster?->photo_path) && file_exists(public_path('storage/' . $headmaster->photo_path)))
        ? asset('storage/' . $headmaster->photo_path)
        : null;
@endphp

<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section class="border-t-4 border-amber-500 shadow-sm">
        <div class="flex items-center gap-x-4">
            
            {{-- Foto Profil Ringkas (Fixed 64x64px dengan Border Emas) --}}
            <div class="profile-avatar-img-wrp shrink-0">
                @if($photoUrl)
                    <img src="{{ $photoUrl }}"
                         alt="{{ $user?->name ?? 'Admin' }}"
                         class="profile-avatar-img w-16 h-16 rounded-full object-cover border-2 border-amber-500 shadow-md ring-2 ring-amber-400/20"
                         style="width: 4rem !important; height: 4rem !important; border-radius: 9999px !important; object-fit: cover !important; border: 2px solid #F59E0B !important;">
                @else
                    <div class="w-16 h-16 rounded-full bg-emerald-800 text-amber-300 font-bold flex items-center justify-center text-xl border-2 border-amber-500 shadow-md"
                         style="width: 4rem !important; height: 4rem !important; border-radius: 9999px !important; border: 2px solid #F59E0B !important;">
                        {{ strtoupper(substr($user?->name ?? 'AI', 0, 2)) }}
                    </div>
                @endif
            </div>

            {{-- Identitas Pengguna & Satuan Pendidikan --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-bold text-gray-950 dark:text-white truncate">
                        Welcome, {{ $user?->name ?? 'Admin SDN Cinanggerang I' }}
                    </h2>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                        NPSN: {{ $school?->npsn ?? '20208801' }}
                    </span>
                </div>

                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                    Panel Manajemen GTK, Presensi GPS & E-PKKS Digital 2026 • Pamulihan
                </p>
            </div>

            {{-- Tombol Logout --}}
            <form action="{{ filament()->getLogoutUrl() }}" method="post" class="my-auto shrink-0">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-slate-700 hover:text-red-600 text-xs font-semibold border border-slate-300 shadow-sm transition">
                    <svg class="w-4 h-4 text-slate-500 hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Sign out</span>
                </button>
            </form>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
