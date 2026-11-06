@php
    $profile = \App\Models\SchoolProfile::first();
    $schoolLogo = !empty($profile?->logo) 
        ? asset('storage/' . $profile->logo) 
        : asset('images/logo-sd.svg');
@endphp

{{-- Single Root Element for Livewire 3 --}}
<div class="login-wrapper min-h-screen max-h-screen overflow-hidden flex items-center justify-center bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-950 p-4">

    {{-- Tailwind CSS CDN & Konfigurasi Khusus Login --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            200: '#a7f3d0',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        },
                        teal: {
                            950: '#042f2e',
                        }
                    }
                }
            }
        }
    </script>

    {{-- Kartu Login Utama --}}
    <div class="login-card w-full max-w-md bg-white rounded-2xl shadow-2xl border-t-4 border-amber-500 p-6 md:p-8 flex flex-col justify-center">

        {{-- Header Kartu & Logo --}}
        <div class="flex flex-col items-center justify-center text-center mb-4">
            {{-- Logo SDN Cinanggerang I Proporsional (Tinggi h-16 / 64px) --}}
            <div class="h-16 w-auto max-h-16 mb-2.5 flex items-center justify-center">
                <img src="{{ $schoolLogo }}"
                     alt="Logo {{ $profile?->name ?? 'SDN Cinanggerang I' }}"
                     class="h-16 w-auto max-h-16 object-contain drop-shadow">
            </div>

            <h1 class="text-lg font-bold text-slate-800 tracking-wide leading-tight">
                {{ strtoupper($profile?->name ?? 'SD NEGERI CINANGGERANG I') }}
            </h1>
            <p class="text-xs font-semibold text-emerald-800 mt-1">
                Portal GTK, Presensi GPS & E-PKKS Digital
            </p>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 mt-2">
                <span>NPSN: {{ $profile?->npsn ?? '20208801' }}</span>
                <span>•</span>
                <span>Pamulihan</span>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-3.5 p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-semibold flex items-center gap-2.5 shadow-sm">
                <span class="text-base shrink-0">🔔</span>
                <span class="leading-relaxed">{{ session('status') }}</span>
            </div>
        @endif

        {{-- Form Filament Livewire --}}
        <x-filament-panels::form id="form" wire:submit="authenticate" class="space-y-3.5">
            {{ $this->form }}

            <x-filament-panels::form.actions
                :actions="$this->getCachedFormActions()"
                :full-width="$this->hasFullWidthFormActions()"
            />
        </x-filament-panels::form>

        {{-- Footer Kartu --}}
        <div class="mt-4 pt-3 border-t border-slate-100 text-center">
            <a href="{{ url('/') }}"
               class="text-xs text-slate-500 hover:text-emerald-700 font-medium transition inline-flex items-center gap-1">
                <span>← Kembali ke Website Utama</span>
            </a>
            <p class="text-[11px] text-slate-400 mt-2">
                © {{ date('Y') }} SD Negeri Cinanggerang I • Sumedang
            </p>
        </div>

    </div>

    {{-- Scoped Styles Presisi Sesuai Spesifikasi (Bebas Scrollbar, Pas 100vh) --}}
    <style>
        /* Kunci Layar Penuh 100vh Bebas Scrollbar */
        html, body {
            overflow: hidden !important;
            height: 100vh !important;
            max-height: 100vh !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .login-wrapper {
            min-height: 100vh !important;
            max-height: 100vh !important;
            height: 100vh !important;
            overflow: hidden !important;
        }

        /* Form Container Spacing */
        .login-card form,
        .login-card .fi-fo-component-ctn {
            gap: 0.875rem !important; /* space-y-3.5 */
        }

        .login-card .fi-fo-field-wrp {
            margin-bottom: 0.75rem !important;
        }

        .login-card .fi-fo-field-wrp-label {
            margin-bottom: 0.25rem !important;
        }

        /* Label Input: text-xs font-semibold text-slate-700 (Title Case) */
        .login-card label,
        .login-card .fi-fo-field-wrp-label span {
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            color: #334155 !important;
            text-transform: none !important;
            letter-spacing: normal !important;
        }

        /* Input Field: rounded-xl border-slate-300 py-2.5 text-sm */
        .login-card .fi-input-wrp {
            border-radius: 0.75rem !important;
            border: 1px solid #cbd5e1 !important;
            background-color: #f8fafc !important;
            box-shadow: none !important;
            transition: all 0.2s ease-in-out !important;
        }

        .login-card .fi-input-wrp:focus-within {
            background-color: #ffffff !important;
            border-color: #059669 !important;
            box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.25) !important;
        }

        .login-card input[type="email"],
        .login-card input[type="password"] {
            padding-top: 0.625rem !important; /* py-2.5 */
            padding-bottom: 0.625rem !important;
            font-size: 0.875rem !important;
            box-shadow: none !important;
            border: none !important;
            background-color: transparent !important;
        }

        /* Tombol Sign In: w-full py-2.5 bg-emerald-700 hover:bg-emerald-800 */
        .login-card button[type="submit"],
        .login-card .fi-btn-primary {
            width: 100% !important;
            padding-top: 0.625rem !important;
            padding-bottom: 0.625rem !important;
            background-color: #047857 !important;
            color: #ffffff !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            margin-top: 0.5rem !important;
            transition: all 0.2s ease-in-out !important;
            border: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .login-card button[type="submit"]:hover,
        .login-card .fi-btn-primary:hover {
            background-color: #065f46 !important;
            box-shadow: 0 10px 15px -3px rgba(4, 120, 87, 0.25) !important;
        }

        /* Sembunyikan elemen bawaan yang tidak diperlukan */
        .login-card .fi-simple-header,
        .login-card .fi-logo {
            display: none !important;
        }
    </style>
</div>
