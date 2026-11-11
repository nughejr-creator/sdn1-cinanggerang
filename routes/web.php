<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PkksController;
use App\Http\Controllers\PpdbController;
use App\Http\Controllers\SitemapController;
use App\Models\SchoolProfile;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SDN Cinanggerang I (Clean URLs & SEO Friendly)
|--------------------------------------------------------------------------
*/

// Rute SEO & Pengindeksan Google (Sitemap XML & Robots.txt)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');
Route::get('/google7c21792a147ed13a.html', fn () => response('google-site-verification: google7c21792a147ed13a.html', 200)->header('Content-Type', 'text/html'));

// Route khusus favicon.ico agar selalu mengembalikan logo resmi SDN Cinanggerang 1
Route::get('/favicon.ico', function () {
    try {
        $profile = SchoolProfile::first();
        if ($profile && !empty($profile->favicon)) {
            return redirect($profile->favicon_url);
        }
    } catch (\Throwable $e) {}

    $path = public_path('images/logo-official.png');
    if (file_exists($path)) {
        return response()->file($path, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-cache, must-revalidate',
        ]);
    }
    return response()->file(public_path('favicon.ico'));
});

// Fallback akses berkas Cloudflare R2 / S3 jika dipanggil melalui path storage lokal
Route::get('/storage/{path}', function ($path) {
    // 1. Cek langsung di storage/app/public/ (tempat upload lokal Laravel/Filament)
    $storageFile = storage_path('app/public/' . $path);
    if (file_exists($storageFile) && !is_dir($storageFile)) {
        return response()->file($storageFile);
    }

    // 2. Cek di public/storage/ (jika symlink atau folder disalin ke public)
    $localFile = public_path('storage/' . $path);
    if (file_exists($localFile) && !is_dir($localFile)) {
        return response()->file($localFile);
    }

    // 3. Cek di storage/app/
    $rawStorage = storage_path('app/' . $path);
    if (file_exists($rawStorage) && !is_dir($rawStorage)) {
        return response()->file($rawStorage);
    }

    if (env('AWS_URL')) {
        return redirect(rtrim(env('AWS_URL'), '/') . '/' . ltrim($path, '/'));
    }
    abort(404);
})->where('path', '.*');

// Pastikan cache view terkompilasi selalu bersih di environment lokal
if (app()->environment('local')) {
    array_map('unlink', glob(storage_path('framework/views/*.php')));
}

// Auth Redirect Helper (/login -> Filament Admin Login)
Route::get('/login', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        return redirect()->intended(route('presensi.index'));
    }
    return redirect()->to('/admin/login');
})->name('login');

// Halaman Publik Utama
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/profil', [FrontendController::class, 'profil'])->name('profil');
Route::get('/guru', [FrontendController::class, 'guru'])->name('guru');
Route::get('/rombel', [FrontendController::class, 'rombel'])->name('rombel');
Route::get('/siswa', [FrontendController::class, 'siswa'])->name('siswa');
Route::get('/warta', [FrontendController::class, 'warta'])->name('warta');
Route::get('/warta/{slug}', [FrontendController::class, 'wartaDetail'])->name('warta.detail')->where('slug', '.*');
Route::get('/berita/{slug}', fn ($slug) => redirect()->route('warta.detail', $slug))->where('slug', '.*');
Route::get('/pengumuman', [FrontendController::class, 'pengumuman'])->name('pengumuman');
Route::get('/pengumuman/{id}', [FrontendController::class, 'pengumumanDetail'])->name('pengumuman.detail');
Route::get('/dokumentasi', [FrontendController::class, 'dokumentasi'])->name('dokumentasi');
Route::get('/e-pkks', [PkksController::class, 'index'])->name('pkks');
Route::post('/e-pkks/verify-pin', [PkksController::class, 'verifyPin'])->name('pkks.verify-pin');
Route::post('/e-pkks/lock-pin', [PkksController::class, 'lockPin'])->name('pkks.lock-pin');
Route::post('/e-pkks/review', [PkksController::class, 'storeReview'])->name('pkks.store-review');
Route::get('/ppdb', [FrontendController::class, 'ppdb'])->name('ppdb');
Route::get('/pengembang', [FrontendController::class, 'pengembang'])->name('pengembang');

// Rute E-Learning & Bank Perangkat Ajar Kurikulum Merdeka (Akses Publik)
Route::get('/perangkat-ajar', [FrontendController::class, 'perangkatAjar'])->name('perangkat-ajar');
Route::get('/e-learning/perangkat-ajar', fn () => redirect()->route('perangkat-ajar'));

// Rute Lengkap Layanan PPDB Online
Route::get('/ppdb/daftar', [PpdbController::class, 'create'])->name('ppdb.register');
Route::post('/ppdb/daftar', [PpdbController::class, 'store'])->name('ppdb.store');
Route::get('/ppdb/berhasil/{registration_number}', [PpdbController::class, 'success'])->name('ppdb.success');
Route::post('/ppdb/cek-status', [PpdbController::class, 'checkStatus'])->name('ppdb.check-status');
Route::get('/ppdb/cetak-kelulusan/{registration_number}', [PpdbController::class, 'printKelulusan'])->name('ppdb.print-kelulusan');

// Rute Presensi GPS Guru Digital & Laporan Cetak Kedinasan
Route::get('/presensi', [AttendanceController::class, 'index'])->name('presensi.index');
Route::post('/presensi', [AttendanceController::class, 'store'])->name('presensi.store');
Route::get('/admin/presensi/cetak-rekap', [AttendanceController::class, 'printRecap'])->name('admin.presensi.cetak-rekap');

// API Endpoint Sinkronisasi Web Service Dapodik (One-Click Sync dari Komputer Lokal)
Route::post('/api/dapodik/sync-students', [App\Http\Controllers\Api\DapodikSyncController::class, 'sync'])->name('api.dapodik.sync');
Route::get('/admin/dapodik/download-sync-tool', function () {
    $path = base_path('sinkron_dapodik.bat');
    if (file_exists($path)) {
        return response()->download($path, 'sinkron_dapodik.bat');
    }
    abort(404);
})->name('admin.dapodik.download-tool');
