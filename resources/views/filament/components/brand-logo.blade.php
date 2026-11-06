@php
    $profile = \App\Models\SchoolProfile::first();
    $schoolLogo = !empty($profile?->logo) 
        ? asset('storage/' . $profile->logo) 
        : asset('images/logo-sd.svg');
@endphp

<div class="flex items-center gap-2.5 py-1">
    <div class="w-9 h-9 rounded-xl bg-white p-1 border border-amber-400 shadow flex items-center justify-center shrink-0 overflow-hidden">
        <img src="{{ $schoolLogo }}" alt="Logo {{ $profile?->name ?? 'SDN Cinanggerang I' }}" class="w-full h-full object-contain">
    </div>
    <div class="flex flex-col text-left">
        <span class="text-xs font-semibold text-white tracking-wide leading-tight" style="color: #ffffff !important;">
            {{ $profile?->name ?? 'SD Negeri Cinanggerang I' }}
        </span>
        <span class="text-[11px] font-semibold text-white/90 tracking-normal mt-0.5" style="color: #ffffff !important;">
            Panel GTK & Administrasi
        </span>
    </div>
</div>
