@php
    $startDate = now()->translatedFormat('d F Y');
    if (!empty($data['tanggal_mulai'])) {
        try {
            $startDate = \Carbon\Carbon::parse($data['tanggal_mulai'])->translatedFormat('d F Y');
        } catch (\Throwable $e) {}
    }
    
    $endDate = null;
    if (!empty($data['tanggal_selesai'])) {
        try {
            $endDate = \Carbon\Carbon::parse($data['tanggal_selesai'])->translatedFormat('d F Y');
        } catch (\Throwable $e) {}
    }

    $isActive = true;
    if (!empty($data['tanggal_selesai'])) {
        try {
            if (\Carbon\Carbon::parse($data['tanggal_selesai'])->isPast()) {
                $isActive = false;
            }
        } catch (\Throwable $e) {}
    }

    // Tangani Konten HTML (TipTap Array / String)
    $contentHtml = '';
    $rawContent = $data['isi'] ?? null;
    if (!empty($rawContent)) {
        if (is_array($rawContent)) {
            try {
                $contentHtml = tiptap_converter()->asHTML($rawContent);
            } catch (\Throwable $e) {
                $contentHtml = '<p>' . e(json_encode($rawContent)) . '</p>';
            }
        } else {
            $contentHtml = (string) $rawContent;
        }
    }
@endphp

<div class="space-y-6 text-left">
    {{-- Notice Banner --}}
    <div class="p-4 rounded-xl border {{ $isActive ? 'bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-950/40 dark:border-amber-800 dark:text-amber-300' : 'bg-gray-100 border-gray-300 text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300' }} flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
        <div class="flex items-center gap-2">
            <span class="text-base">📢</span>
            <div>
                <strong>Simulasi Pratinjau Pengumuman Kedinasan</strong> (Status: <span class="font-bold uppercase">{{ $isActive ? 'Aktif' : 'Arsip/Berakhir' }}</span>)
                <p class="text-[11px] opacity-80">Format pratinjau ini sesuai dengan apa yang akan dilihat oleh masyarakat di halaman pengumuman portal.</p>
            </div>
        </div>
        @if(isset($record) && $record?->exists)
            <a href="{{ $record->detail_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition shadow-sm whitespace-nowrap">
                <span>🌐 Buka di Tab Baru</span>
                <span>↗</span>
            </a>
        @endif
    </div>

    {{-- Main Preview Container matching frontend.pengumuman-detail --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 sm:p-8 border border-gray-200 dark:border-gray-800 shadow-sm">
        {{-- Metadata Pengumuman --}}
        <div class="flex flex-wrap items-center gap-2.5 mb-4 text-xs">
            <span class="px-3.5 py-1 bg-amber-100 text-amber-900 dark:bg-amber-950/70 dark:text-amber-300 font-bold rounded-full uppercase tracking-wider flex items-center gap-1.5 border border-amber-300/40">
                <span class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-amber-500 animate-pulse' : 'bg-gray-400' }}"></span>
                {{ $isActive ? 'Pengumuman Aktif' : 'Arsip Pengumuman' }}
            </span>
            <span class="text-gray-400">
                📅 Rilis: {{ $startDate }}
                @if($endDate)
                    — s.d. {{ $endDate }}
                @endif
            </span>
            <span class="text-gray-300 dark:text-gray-700">•</span>
            <span class="text-gray-500 dark:text-gray-400">Penerbit: <strong>Sekretariat SDN Cinanggerang I</strong></span>
        </div>

        {{-- Judul Utama --}}
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white leading-tight mb-6">
            {{ !empty($data['judul']) ? $data['judul'] : 'Judul Pengumuman Belum Diisi' }}
        </h1>

        {{-- Isi Konten Pengumuman --}}
        <div class="article-content prose prose-emerald dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 text-sm sm:text-base leading-relaxed space-y-4 pt-2 border-t border-gray-100 dark:border-gray-800">
            @if(!empty($contentHtml))
                {!! $contentHtml !!}
            @else
                <p class="text-gray-400 italic">Isi teks pengumuman masih kosong.</p>
            @endif
        </div>
    </div>
</div>
