<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\PkksEvidence;
use App\Models\PkksKomponen;
use App\Models\Post;
use App\Models\SchoolProfile;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Teacher;
use App\Models\VideoGallery;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontendController extends Controller
{
    /**
     * Tampilkan Halaman Utama Resmi SDN Cinanggerang I
     */
    public function index(): View
    {
        // Bersihkan cache view terkompilasi agar selalu fresh di lokal
        if (app()->environment('local')) {
            array_map('unlink', glob(storage_path('framework/views/*.php')));
        }

        $profile = SchoolProfile::first();

        // 11 Personil Guru & Tenaga Kependidikan Resmi
        $teachers = Teacher::where('is_active', true)
            ->orderBy('urutan')
            ->get();

        // Data Kepala Sekolah Resmi (Bpk. Aep Yayat, S.Pd.SD)
        $kepalaSekolah = Teacher::where('is_active', true)
            ->where(function ($q) {
                $q->where('jabatan', 'like', '%Kepala%')
                  ->orWhere('nama', 'like', '%Aep Yayat%');
            })
            ->first();

        // Statistik Realtime
        $stats = [
            'total_siswa' => max(Student::count(), 219),
            'total_guru' => $teachers->count() ?: 11,
            'total_rombel' => StudentClass::count() ?: 8,
            'npsn' => $profile?->npsn ?? '20208801',
        ];

        // 1. Artikel Berita untuk Beranda: Ambil maksimal 2 artikel yang disematkan (tampil_di_beranda = true)
        // Fallback otomatis ke 2 artikel terbaru jika belum ada yang disematkan
        $pinnedPosts = Post::where('status', 'Publish')
            ->where('tampil_di_beranda', true)
            ->latest('published_at')
            ->take(2)
            ->get();

        if ($pinnedPosts->count() < 2) {
            $needed = 2 - $pinnedPosts->count();
            $fallbackPosts = Post::where('status', 'Publish')
                ->whereNotIn('id', $pinnedPosts->pluck('id'))
                ->latest('published_at')
                ->take($needed)
                ->get();
            $posts = $pinnedPosts->concat($fallbackPosts);
        } else {
            $posts = $pinnedPosts;
        }

        // 2. Pengumuman untuk Beranda: Ambil maksimal 2 pengumuman yang disematkan (tampil_di_beranda = true)
        // Fallback otomatis ke 2 pengumuman aktif terbaru
        $pinnedAnnouncements = Announcement::where('is_active', true)
            ->where('tampil_di_beranda', true)
            ->latest('tanggal_mulai')
            ->take(2)
            ->get();

        if ($pinnedAnnouncements->count() < 2) {
            $needed = 2 - $pinnedAnnouncements->count();
            $fallbackAnnouncements = Announcement::where('is_active', true)
                ->whereNotIn('id', $pinnedAnnouncements->pluck('id'))
                ->latest('tanggal_mulai')
                ->take($needed)
                ->get();
            $announcements = $pinnedAnnouncements->concat($fallbackAnnouncements);
        } else {
            $announcements = $pinnedAnnouncements;
        }

        // Auto-seed / sync video resmi jika belum lengkap
        if (VideoGallery::count() < 7) {
            (new \Database\Seeders\VideoGallerySeeder())->run();
        }

        // Galeri Video Interaktif (YouTube, TikTok, Google Drive)
        $videos = VideoGallery::latest('id')
            ->take(8)
            ->get();

        // Auto-seed data E-PKKS 12 Indikator Permendikdasmen No. 23/2026 jika belum ada
        if (!\Illuminate\Support\Facades\Schema::hasTable('headmaster_profiles') || 
            !\Illuminate\Support\Facades\Schema::hasTable('pkks_indicators') || 
            !\Illuminate\Support\Facades\Schema::hasTable('pkks_evidences') ||
            !\Illuminate\Support\Facades\Schema::hasColumn('pkks_evidences', 'pkks_indicator_id')) {
            try {
                $migration = require database_path('migrations/2026_11_05_180000_create_pkks_permendikdasmen_tables.php');
                $migration->up();
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            }
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('pkks_indicators')) {
            if (\App\Models\PkksIndicator::count() < 12 || \App\Models\HeadmasterProfile::count() === 0) {
                (new \Database\Seeders\PkksDatabaseSeeder())->run();
            }
        }

        // Showcase Portofolio E-PKKS (12 Indikator Permendikdasmen No. 23/2026) Tahun Aktif
        $pkksIndicators = \App\Models\PkksIndicator::whereHas('period', function ($q) {
            $q->where('status', 'active');
        })->with('evidences')->orderBy('indicator_number')->get();

        if ($pkksIndicators->isEmpty()) {
            $pkksIndicators = \App\Models\PkksIndicator::with('evidences')
                ->orderBy('indicator_number')
                ->get();
        }

        return view('frontend.index', compact(
            'profile',
            'kepalaSekolah',
            'teachers',
            'stats',
            'posts',
            'announcements',
            'videos',
            'pkksIndicators'
        ));
    }

    /**
     * Halaman Profil & Visi Misi Sekolah
     */
    public function profil(): View
    {
        if (app()->environment('local')) {
            array_map('unlink', glob(storage_path('framework/views/*.php')));
        }
        $profile = SchoolProfile::first();
        return view('frontend.profil', compact('profile'));
    }

    /**
     * Halaman Direktori Guru & Tenaga Kependidikan
     */
    public function guru(): View
    {
        if (app()->environment('local')) {
            array_map('unlink', glob(storage_path('framework/views/*.php')));
        }
        $profile = SchoolProfile::first();
        $teachers = Teacher::where('is_active', true)
            ->orderBy('urutan')
            ->get();

        return view('frontend.guru', compact('profile', 'teachers'));
    }

    /**
     * Halaman Warta, Berita & Pengumuman Sekolah
     */
    public function warta(): View
    {
        $profile = SchoolProfile::first();
        $posts = Post::where('status', 'Publish')
            ->latest('published_at')
            ->paginate(9);

        $announcements = Announcement::where('is_active', true)
            ->latest('tanggal_mulai')
            ->get();

        return view('frontend.warta', compact('profile', 'posts', 'announcements'));
    }

    /**
     * Halaman Detail Lengkap Berita & Artikel Sekolah
     */
    public function wartaDetail(string $slug): View
    {
        if (app()->environment('local')) {
            array_map('unlink', glob(storage_path('framework/views/*.php')));
        }
        $profile = SchoolProfile::first();

        $post = Post::where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        // Rekomendasi berita terkait lainnya
        $relatedPosts = Post::where('status', 'Publish')
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        $announcements = Announcement::where('is_active', true)
            ->latest('tanggal_mulai')
            ->take(4)
            ->get();

        return view('frontend.warta-detail', compact('profile', 'post', 'relatedPosts', 'announcements'));
    }

    /**
     * Halaman Seluruh Pengumuman Kedinasan Resmi
     */
    public function pengumuman(): View
    {
        $profile = SchoolProfile::first();
        $announcements = Announcement::where('is_active', true)
            ->latest('tanggal_mulai')
            ->paginate(10);

        $recentPosts = Post::where('status', 'Publish')
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('frontend.pengumuman-index', compact('profile', 'announcements', 'recentPosts'));
    }

    /**
     * Halaman Detail Surat / Pengumuman Kedinasan
     */
    public function pengumumanDetail($id): View
    {
        if (app()->environment('local')) {
            array_map('unlink', glob(storage_path('framework/views/*.php')));
        }
        $profile = SchoolProfile::first();

        $announcement = Announcement::findOrFail($id);

        $otherAnnouncements = Announcement::where('is_active', true)
            ->where('id', '!=', $announcement->id)
            ->latest('tanggal_mulai')
            ->take(5)
            ->get();

        $recentPosts = Post::where('status', 'Publish')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('frontend.pengumuman-detail', compact('profile', 'announcement', 'otherAnnouncements', 'recentPosts'));
    }

    /**
     * Halaman Dokumentasi Kegiatan (Galeri Foto Kegiatan Siswa, Video TikTok & Arsip Google Drive)
     */
    public function dokumentasi(): View
    {
        $profile = SchoolProfile::first();
        
        if (VideoGallery::count() < 7) {
            (new \Database\Seeders\VideoGallerySeeder())->run();
        }

        // Ambil seluruh dokumentasi album foto kegiatan siswa
        $galleries = \App\Models\Gallery::latest('tanggal_kegiatan')
            ->latest('id')
            ->get();

        $videos = VideoGallery::latest('id')->get();

        return view('frontend.dokumentasi', compact('profile', 'videos', 'galleries'));
    }

    /**
     * Halaman Portofolio Mutu Digital E-PKKS (12 Indikator Permendikdasmen No. 23/2026)
     */
    public function pkks(Request $request): View
    {
        return (new PkksController())->index($request);
    }

    /**
     * Halaman Rombongan Belajar (Rombel Kelas I - VI)
     */
    public function rombel(): View
    {
        // Bersihkan data sampah non-siswa jika ada
        Student::whereIn('nama', ['Kepribadian', 'Profesional', 'Sosial'])->delete();
        Student::whereNull('student_class_id')->whereNull('nisn')->delete();

        $profile = SchoolProfile::first();
        $rombels = StudentClass::withCount(['students' => function ($q) {
                $q->where('status', 'Aktif');
            }])
            ->with('teacher')
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        $totalSiswa = Student::where('status', 'Aktif')->count();

        return view('frontend.rombel', compact('profile', 'rombels', 'totalSiswa'));
    }

    /**
     * Halaman Daftar Siswa & Pencarian / Cek NISN Mandiri
     */
    public function siswa(Request $request): View
    {
        // Bersihkan data sampah non-siswa (misal komponen PKKS yang sempat terbaca)
        Student::whereIn('nama', ['Kepribadian', 'Profesional', 'Sosial'])->delete();
        Student::whereNull('student_class_id')->whereNull('nisn')->delete();

        $profile = SchoolProfile::first();
        $query = trim((string)$request->get('q', ''));
        $rombelFilter = $request->get('rombel_id');

        $siswaQuery = Student::where('status', 'Aktif')
            ->select('id', 'nama', 'nisn', 'student_class_id', 'jenis_kelamin')
            ->with('studentClass:id,nama');

        // Filter pencarian nama atau NISN (minimal 2 karakter)
        if ($query && strlen($query) >= 2) {
            $siswaQuery->where(function ($q) use ($query) {
                $q->where('nama', 'like', "%{$query}%")
                  ->orWhere('nisn', 'like', "%{$query}%");
            });
        }

        // Filter berdasarkan Rombel/Kelas (opsional)
        if (!empty($rombelFilter)) {
            $siswaQuery->where('student_class_id', $rombelFilter);
        }

        $siswa = $siswaQuery->orderBy('student_class_id')
            ->orderBy('nama')
            ->paginate(20)
            ->withQueryString();

        $rombels = StudentClass::select('id', 'nama', 'tingkat')
            ->withCount(['students' => function ($q) {
                $q->where('status', 'Aktif');
            }])
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        $totalSiswa = Student::where('status', 'Aktif')->count();

        return view('frontend.siswa', compact('profile', 'siswa', 'rombels', 'query', 'rombelFilter', 'totalSiswa'));
    }

    /**
     * Halaman Layanan & Informasi PPDB Online
     */
    public function ppdb(): View
    {
        $profile = SchoolProfile::first();
        $setting = \App\Models\PpdbSetting::getSetting();
        return view('frontend.ppdb', compact('profile', 'setting'));
    }
}
