@extends('layouts.frontend')

@section('title', $post->judul . ' - SD Negeri Cinanggerang I')
@section('meta_description', Str::limit(strip_tags($post->konten), 150))
@section('canonical_url', $post->detail_url)
@section('og_type', 'article')
@section('og_title', $post->judul)
@section('og_image', !empty($post->thumbnail_url) ? $post->thumbnail_url : ($profile?->logo_url ?? asset('images/logo-official.png')))

@push('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "NewsArticle",
  "headline": "{{ addslashes($post->judul) }}",
  "image": ["{{ !empty($post->thumbnail_url) ? $post->thumbnail_url : ($profile?->logo_url ?? asset('images/logo-official.png')) }}"],
  "datePublished": "{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->toAtomString() : $post->created_at->toAtomString() }}",
  "dateModified": "{{ ($post->updated_at ?? now())->toAtomString() }}",
  "author": {
    "@@type": "Organization",
    "name": "Humas SD Negeri Cinanggerang I",
    "url": "{{ url('/') }}"
  },
  "publisher": {
    "@@type": "ElementarySchool",
    "name": "SD Negeri Cinanggerang I",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ $profile?->logo_url ?? asset('images/logo-official.png') }}"
    }
  },
  "description": "{{ addslashes(Str::limit(strip_tags($post->konten), 160)) }}",
  "mainEntityOfPage": {
    "@@type": "WebPage",
    "@@id": "{{ $post->detail_url }}"
  }
}
</script>
@endpush

@section('content')
<div class="bg-slate-50 dark:bg-forest-950 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb Navigasi --}}
        <nav class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-6 font-semibold overflow-x-auto whitespace-nowrap pb-2">
            <a href="{{ route('home') }}" class="hover:text-forest-800 dark:hover:text-emerald-300">Beranda</a>
            <span>/</span>
            <a href="{{ route('warta') }}" class="hover:text-forest-800 dark:hover:text-emerald-300">Warta & Berita</a>
            <span>/</span>
            <span class="text-forest-900 dark:text-white truncate max-w-xs sm:max-w-md">{{ $post->judul }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">

            {{-- ARTIKEL UTAMA (8 KOLOM) --}}
            <main class="lg:col-span-8 bg-white dark:bg-forest-900 rounded-3xl p-6 sm:p-8 md:p-10 border border-gray-100 dark:border-forest-800 shadow-sm">
                
                {{-- Metadata Artikel --}}
                <div class="flex flex-wrap items-center gap-2.5 mb-4">
                    <span class="px-3 py-1 bg-forest-100 dark:bg-forest-800 text-forest-800 dark:text-emerald-300 text-xs font-bold rounded-full uppercase tracking-wider">
                        {{ $post->kategori }}
                    </span>
                    <span class="text-xs text-gray-400">
                        📅 {{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->translatedFormat('l, d F Y') : now()->translatedFormat('l, d F Y') }}
                    </span>
                    <span class="text-xs text-gray-400">•</span>
                    <span class="text-xs text-gray-400">Oleh: <strong>Humas SDN Cinanggerang I</strong></span>
                </div>

                {{-- Judul Utama --}}
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-forest-950 dark:text-white leading-tight mb-6">
                    {{ $post->judul }}
                </h1>

                {{-- Foto Utama / Thumbnail --}}
                @if(!empty($post->thumbnail_url))
                    <div class="rounded-2xl overflow-hidden mb-8 border border-gray-100 dark:border-forest-800 shadow-md">
                        <img src="{{ $post->thumbnail_url }}"
                             alt="{{ $post->judul }}"
                             class="w-full max-h-[460px] object-cover">
                    </div>
                @endif

                {{-- Isi Konten Berita --}}
                <div class="article-content prose prose-emerald dark:prose-invert max-w-none text-slate-700 dark:text-gray-200 text-sm sm:text-base leading-relaxed space-y-4">
                    {!! $post->konten !!}
                </div>

                {{-- Tombol Bagikan & Kembali --}}
                <div class="mt-10 pt-6 border-t border-gray-100 dark:border-forest-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center gap-2 text-xs font-bold text-gray-500 dark:text-gray-400">
                        <span>Bagikan Warta:</span>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($post->judul . ' ' . $post->detail_url) }}" target="_blank"
                           class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 hover:bg-emerald-100 transition flex items-center gap-1">
                            <span>💬 WhatsApp</span>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($post->detail_url) }}" target="_blank"
                           class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300 hover:bg-blue-100 transition">
                            Facebook
                        </a>
                    </div>

                    <a href="{{ route('warta') }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gray-100 dark:bg-forest-800 text-gray-700 dark:text-gray-200 text-xs font-bold hover:bg-gray-200 dark:hover:bg-forest-700 transition">
                        <span>&larr;</span>
                        <span>Kembali ke Warta</span>
                    </a>
                </div>

            </main>

            {{-- SIDEBAR KANAN (4 KOLOM) --}}
            <aside class="lg:col-span-4 space-y-6">

                {{-- Kotak Pengumuman Kedinasan --}}
                @if(isset($announcements) && $announcements->count() > 0)
                    <div class="bg-white dark:bg-forest-900 rounded-3xl p-6 border border-gray-100 dark:border-forest-800 shadow-sm">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-xl">📢</span>
                            <h3 class="font-extrabold text-base text-forest-950 dark:text-white">Pengumuman Kedinasan</h3>
                        </div>

                        <div class="space-y-3">
                            @foreach($announcements as $announcement)
                                <a href="{{ $announcement->detail_url }}" class="block p-3 rounded-2xl bg-amber-50/60 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-900/60 hover:border-amber-400 transition group">
                                    <span class="text-[10px] text-amber-700 dark:text-amber-300 font-semibold block">
                                        📅 {{ $announcement->tanggal_mulai ? $announcement->tanggal_mulai->translatedFormat('d M Y') : '-' }}
                                    </span>
                                    <h4 class="font-bold text-xs text-forest-950 dark:text-white group-hover:text-amber-800 dark:group-hover:text-amber-300 mt-1 line-clamp-2">
                                        {{ $announcement->judul }}
                                    </h4>
                                </a>
                            @endforeach
                        </div>

                        <a href="{{ route('pengumuman') }}" class="mt-4 block text-center text-xs font-bold text-forest-800 dark:text-emerald-300 hover:underline">
                            Lihat Semua Pengumuman &rarr;
                        </a>
                    </div>
                @endif

                {{-- Warta Terbaru Lainnya --}}
                @if(isset($relatedPosts) && $relatedPosts->count() > 0)
                    <div class="bg-white dark:bg-forest-900 rounded-3xl p-6 border border-gray-100 dark:border-forest-800 shadow-sm">
                        <h3 class="font-extrabold text-base text-forest-950 dark:text-white mb-4">
                            Warta Terbaru Lainnya
                        </h3>

                        <div class="space-y-4">
                            @foreach($relatedPosts as $related)
                                <a href="{{ $related->detail_url }}" class="flex gap-3 group items-center">
                                    @if(!empty($related->thumbnail_url))
                                        <img src="{{ $related->thumbnail_url }}" alt="{{ $related->judul }}"
                                             class="w-16 h-16 rounded-xl object-cover shrink-0 group-hover:scale-105 transition-transform">
                                    @else
                                        <div class="w-16 h-16 rounded-xl bg-forest-100 dark:bg-forest-800 flex items-center justify-center shrink-0 text-xl">
                                            📰
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <span class="text-[10px] text-gray-400 font-semibold block">
                                            {{ $related->published_at ? \Carbon\Carbon::parse($related->published_at)->translatedFormat('d M Y') : '-' }}
                                        </span>
                                        <h4 class="font-bold text-xs text-forest-950 dark:text-white group-hover:text-forest-700 dark:group-hover:text-emerald-300 transition line-clamp-2 mt-0.5">
                                            {{ $related->judul }}
                                        </h4>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </aside>

        </div>

    </div>
</div>
@endsection