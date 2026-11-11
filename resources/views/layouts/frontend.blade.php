<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Verifikasi Kepemilikan Resmi Google Search Console --}}
    <meta name="google-site-verification" content="gXTboZ3WB5IUzjRY1-aYbAB2pNG9zR1kOfAcmXcljGo">

    {{-- SEO & Metadata Sekolah Resmi --}}
    <title>@yield('title', 'SD Negeri Cinanggerang I | Portal Resmi & E-PKKS Digital')</title>
    <meta name="description" content="@yield('meta_description', 'Portal Resmi SD Negeri Cinanggerang I, NPSN: 20208801, Kec. Pamulihan, Kab. Sumedang. Pusat informasi profil, layanan PPDB Online, dan Portofolio Digital E-PKKS.')">
    <meta name="keywords" content="SDN Cinanggerang 1, SD Negeri Cinanggerang I, Pamulihan, Sumedang, PPDB Sumedang, E-PKKS, Sekolah Dasar Negeri, Akreditasi B">
    <meta name="author" content="SD Negeri Cinanggerang I">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    {{-- Petunjuk Pengindeksan Robot Mesin Pencari (Googlebot, Bing, dsb) --}}
    <meta name="robots" content="@yield('meta_robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')">
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    {{-- Open Graph / WhatsApp Preview Meta --}}
    <meta property="og:site_name" content="SD Negeri Cinanggerang I">
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', 'SD Negeri Cinanggerang I (NPSN: 20208801) - Sumedang')">
    <meta property="og:description" content="@yield('meta_description', 'Portal Resmi SD Negeri Cinanggerang I, NPSN: 20208801, Kec. Pamulihan, Kab. Sumedang. Pusat informasi profil, layanan PPDB Online, dan Portofolio Digital E-PKKS.')">
    <meta property="og:image" content="@yield('og_image', $profile?->logo_url ?? asset('images/logo-official.png'))">

    {{-- Twitter Card Preview Meta --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'SD Negeri Cinanggerang I | Portal Resmi & E-PKKS Digital')">
    <meta name="twitter:description" content="@yield('meta_description', 'Portal Resmi SD Negeri Cinanggerang I, NPSN: 20208801, Kec. Pamulihan, Kab. Sumedang.')">
    <meta name="twitter:image" content="@yield('og_image', $profile?->logo_url ?? asset('images/logo-official.png'))">

    {{-- JSON-LD Schema.org Structured Data untuk Google Search Console & Rich Snippet --}}
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@graph": [
        {
          "@@type": "ElementarySchool",
          "@@id": "{{ url('/#school') }}",
          "name": "SD Negeri Cinanggerang I",
          "alternateName": ["SDN Cinanggerang 1", "SDN Cinanggerang I Pamulihan"],
          "url": "{{ url('/') }}",
          "logo": "{{ $profile?->logo_url ?? asset('images/logo-official.png') }}",
          "image": "{{ $profile?->foto_bangunan ? asset('storage/' . $profile->foto_bangunan) : ($profile?->logo_url ?? asset('images/logo-official.png')) }}",
          "description": "Satuan Pendidikan Dasar Negeri di Kecamatan Pamulihan, Kabupaten Sumedang, Jawa Barat. Terakreditasi B.",
          "address": {
            "@@type": "PostalAddress",
            "streetAddress": "{{ $profile?->alamat ?? 'Jln. Desa Cinanggerang RT 03/04' }}",
            "addressLocality": "Pamulihan",
            "addressRegion": "Jawa Barat",
            "postalCode": "45362",
            "addressCountry": "ID"
          },
          "geo": {
            "@@type": "GeoCoordinates",
            "latitude": {{ $profile?->latitude ?? -6.89437395 }},
            "longitude": {{ $profile?->longitude ?? 107.87197894 }}
          },
          "telephone": "{{ $profile?->telepon ?? '0813-2102-1585' }}",
          "email": "{{ $profile?->email ?? 'sdncinanggerang1@gmail.com' }}"
        },
        {
          "@@type": "WebSite",
          "@@id": "{{ url('/#website') }}",
          "url": "{{ url('/') }}",
          "name": "Portal Resmi SD Negeri Cinanggerang I",
          "publisher": {
            "@@id": "{{ url('/#school') }}"
          },
          "inLanguage": "id-ID"
        }
      ]
    }
    </script>
    @stack('schema')

    {{-- Favicon Browser Tab (Logo Resmi SDN Cinanggerang I) --}}
    @php
        $officialFavicon = $profile?->favicon_url ?? asset('images/logo-official.png');
    @endphp
    <link rel="icon" type="image/png" href="{{ $officialFavicon }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $officialFavicon }}">
    <link rel="apple-touch-icon" href="{{ $officialFavicon }}">

    {{-- Google Font: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CSS & Plugins (Forms, Typography, Aspect Ratio) via CDN --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        forest: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#065F46', // Deep Emerald
                            900: '#064E3B', // Brand Core Green
                            950: '#022c22', // Darkest Forest
                        },
                        sumedang: {
                            teal: '#0F4C5C',
                            amber: '#F59E0B',
                            gold: '#D97706',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- Alpine.js (Core & Plugins) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .bg-canvas {
            background-color: #fbfdfc;
            background-image: radial-gradient(#dcfce7 0.75px, transparent 0.75px);
            background-size: 24px 24px;
        }

        /* Dukungan Perataan Halaman Teks (Align Left, Center, Right, Justify) */
        align-left, [align-left], .article-content [style*="text-align: left"], .prose [style*="text-align: left"] {
            display: block;
            text-align: left !important;
        }
        align-center, [align-center], .article-content [style*="text-align: center"], .prose [style*="text-align: center"] {
            display: block;
            text-align: center !important;
        }
        align-right, [align-right], .article-content [style*="text-align: right"], .prose [style*="text-align: right"] {
            display: block;
            text-align: right !important;
        }
        align-justify, [align-justify], .article-content [style*="text-align: justify"], .prose [style*="text-align: justify"], p[style*="text-align: justify"] {
            display: block;
            text-align: justify !important;
            text-justify: inter-word !important;
            hyphens: auto;
        }

        /* Penataan Tipografi Konten Artikel & Editor (Sinkronisasi WYSIWYG) */
        .article-content blockquote,
        .prose blockquote {
            border-left: 4px solid #059669 !important; /* Emerald 600 */
            background-color: rgba(236, 253, 245, 0.6) !important; /* Emerald 50 */
            color: #064e3b !important; /* Emerald 900 */
            padding: 0.875rem 1.25rem !important;
            margin: 1.5rem 0 !important;
            border-radius: 0 0.75rem 0.75rem 0 !important;
            font-style: italic !important;
            font-size: 1rem !important;
            line-height: 1.625 !important;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }
        .dark .article-content blockquote,
        .dark .prose blockquote {
            border-left-color: #34d399 !important; /* Emerald 400 */
            background-color: rgba(6, 78, 59, 0.25) !important;
            color: #d1fae5 !important;
        }

        /* Penomoran & Poin Butir Artikel */
        .article-content ul, .prose ul {
            list-style-type: disc !important;
            padding-left: 1.5rem !important;
            margin: 1rem 0 !important;
        }
        .article-content ol, .prose ol {
            list-style-type: decimal !important;
            padding-left: 1.5rem !important;
            margin: 1rem 0 !important;
        }
        .article-content li, .prose li {
            margin-bottom: 0.375rem !important;
        }

        /* Media Gambar di Dalam Artikel */
        .article-content img, .prose img {
            border-radius: 0.75rem !important;
            max-width: 100% !important;
            height: auto !important;
            display: inline-block !important;
            margin: 1rem 0 !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.07);
        }

        /* Tabel di Dalam Artikel */
        .article-content table, .prose table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 1.5rem 0 !important;
            font-size: 0.875rem !important;
        }
        .article-content th, .article-content td, .prose th, .prose td {
            border: 1px solid #cbd5e1 !important;
            padding: 0.5rem 0.75rem !important;
        }
        .article-content th, .prose th {
            background-color: #f1f5f9 !important;
            font-weight: 700 !important;
            text-align: left;
        }
        .dark .article-content th, .dark .article-content td, .dark .prose th, .dark .prose td {
            border-color: #334155 !important;
        }
        .dark .article-content th, .dark .prose th {
            background-color: #1e293b !important;
        }
    </style>

    <script>
        // Custom Elements untuk tag perataan teks Trix
        ['align-left', 'align-center', 'align-right', 'align-justify'].forEach(tag => {
            if (window.customElements && !customElements.get(tag)) {
                try { customElements.define(tag, class extends HTMLElement {}); } catch(e) {}
            }
        });
    </script>
</head>
<body class="font-sans antialiased text-gray-800 bg-canvas selection:bg-sumedang-amber selection:text-white">

    {{-- Navbar Komponen --}}
    @include('components.frontend.navbar')

    {{-- Konten Utama Halaman --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer Komponen --}}
    @include('components.frontend.footer')

    @stack('scripts')
</body>
</html>
