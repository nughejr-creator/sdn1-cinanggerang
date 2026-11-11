@php
    // 1. Tangani Thumbnail Preview
    $thumbnailUrl = null;
    $thumbnail = $data['thumbnail'] ?? null;
    if (is_array($thumbnail) && !empty($thumbnail)) {
        $first = reset($thumbnail);
        if (is_string($first)) {
            $thumbnailUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($first);
        } elseif (is_object($first) && method_exists($first, 'temporaryUrl')) {
            try {
                $thumbnailUrl = $first->temporaryUrl();
            } catch (\Throwable $e) {}
        }
    } elseif (is_string($thumbnail) && !empty($thumbnail)) {
        $thumbnailUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($thumbnail);
    }

    // 2. Tangani Kategori
    $categoryName = 'Umum / Warta Sekolah';
    if (!empty($data['category_id']) && is_numeric($data['category_id'])) {
        try {
            $cat = \App\Models\Category::find($data['category_id']);
            if ($cat) {
                $categoryName = $cat->nama;
            }
        } catch (\Throwable $e) {}
    }

    // 3. Tangani Tanggal Rilis
    $publishedDate = now()->translatedFormat('l, d F Y');
    if (!empty($data['published_at'])) {
        try {
            $publishedDate = \Carbon\Carbon::parse($data['published_at'])->translatedFormat('l, d F Y');
        } catch (\Throwable $e) {}
    }

    // 4. Tangani Konten HTML (Dukungan TipTap JSON/Array maupun String HTML biasa)
    $contentHtml = '';
    $rawContent = $data['konten'] ?? null;
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

    $status = is_string($data['status'] ?? null) ? $data['status'] : 'Draft';
    $isPublished = $status === 'Publish';
@endphp

<div class="space-y-6 text-left">
    {{-- Notice Banner --}}
    <div class="p-4 rounded-xl border {{ $isPublished ? 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-300' : 'bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-950/40 dark:border-amber-800 dark:text-amber-300' }} flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
        <div class="flex items-center gap-2">
            <span class="text-base">👁️</span>
            <div>
                <strong>Simulasi Pratinjau Tampilan Warta</strong> (Status: <span class="font-bold uppercase">{{ $status }}</span>)
                <p class="text-[11px] opacity-80">Tampilan berikut mencerminkan persis bagaimana artikel akan tampil bagi pembaca di portal publik sekolah.</p>
            </div>
        </div>
        @if(isset($record) && $record?->exists)
            <a href="{{ $record->detail_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition shadow-sm whitespace-nowrap">
                <span>🌐 Buka di Tab Baru</span>
                <span>↗</span>
            </a>
        @endif
    </div>

    {{-- Main Preview Container matching frontend.warta-detail --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 sm:p-8 border border-gray-200 dark:border-gray-800 shadow-sm">
        {{-- Metadata Artikel --}}
        <div class="flex flex-wrap items-center gap-2.5 mb-4 text-xs">
            <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-200 font-bold rounded-full uppercase tracking-wider">
                {{ $categoryName }}
            </span>
            <span class="text-gray-400">
                📅 {{ $publishedDate }}
            </span>
            <span class="text-gray-300 dark:text-gray-700">•</span>
            <span class="text-gray-500 dark:text-gray-400">Oleh: <strong>Humas SDN Cinanggerang I</strong></span>
        </div>

        {{-- Judul Utama --}}
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white leading-tight mb-6">
            {{ !empty($data['judul']) ? $data['judul'] : 'Judul Artikel Belum Diisi' }}
        </h1>

        {{-- Foto Utama / Thumbnail --}}
        @if($thumbnailUrl)
            <div class="rounded-xl overflow-hidden mb-6 border border-gray-100 dark:border-gray-800 shadow-sm max-h-[420px] bg-gray-50">
                <img src="{{ $thumbnailUrl }}"
                     alt="{{ $data['judul'] ?? 'Pratinjau Foto' }}"
                     class="w-full h-auto object-cover max-h-[420px]">
            </div>
        @endif

        {{-- Isi Konten Berita --}}
        <div class="prose prose-emerald dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 text-sm sm:text-base leading-relaxed space-y-4 pt-2 border-t border-gray-100 dark:border-gray-800">
            @if(!empty($contentHtml))
                {!! $contentHtml !!}
            @else
                <p class="text-gray-400 italic">Konten artikel masih kosong. Tuliskan teks warta pada editor formulir untuk melihat hasil pratinjau.</p>
            @endif
        </div>
    </div>
</div>
