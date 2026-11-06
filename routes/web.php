<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PkksController;
use App\Http\Controllers\PpdbController;
use App\Models\SchoolProfile;
use App\Models\Student;
use Illuminate\Support\Facades\Route;

// Bootstrap inisialisasi skema & penyesuaian awal sistem
try {
    // Bersihkan data sampah komponen PKKS yang sempat terbaca sebagai siswa
    if (\Illuminate\Support\Facades\Schema::hasTable('students')) {
        Student::whereIn('nama', ['Kepribadian', 'Profesional', 'Sosial'])->delete();
    }

    // Pastikan tahun ajaran berjalan tersetel ke 2026/2027
    if (\Illuminate\Support\Facades\Schema::hasTable('student_classes')) {
        \App\Models\StudentClass::where('tahun_ajaran', '!=', '2026/2027')->update(['tahun_ajaran' => '2026/2027']);
    }

    // Pastikan kolom status & metode di teacher_attendances bertipe VARCHAR agar mendukung semua variasi input
    if (\Illuminate\Support\Facades\Schema::hasTable('teacher_attendances')) {
        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE teacher_attendances MODIFY COLUMN status VARCHAR(60) NOT NULL DEFAULT 'Hadir Tepat Waktu'");
        } catch (\Throwable $e) {}
        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE teacher_attendances MODIFY COLUMN metode VARCHAR(60) NOT NULL DEFAULT 'Presensi Mandiri Web'");
        } catch (\Throwable $e) {}

        // Selaraskan jam presensi hari ini yang sempat tersimpan dengan zona UTC ke WIB (+7 jam)
        try {
            $todayStr = now()->setTimezone('Asia/Jakarta')->toDateString();
            $todayAtts = \App\Models\TeacherAttendance::whereDate('tanggal', $todayStr)->get();
            foreach ($todayAtts as $att) {
                if ($att->jam_masuk && substr($att->jam_masuk, 0, 2) >= '14' && substr($att->jam_masuk, 0, 2) <= '17') {
                    $converted = \Carbon\Carbon::createFromFormat('H:i:s', $att->jam_masuk, 'UTC')
                        ->setTimezone('Asia/Jakarta')
                        ->format('H:i:s');
                    $att->update(['jam_masuk' => $converted]);
                }
            }
        } catch (\Throwable $e) {}
    }

    // Pastikan tabel pengaturan PPDB & data pendaftar tersedia
    if (!\Illuminate\Support\Facades\Schema::hasTable('ppdb_settings')) {
        \Illuminate\Support\Facades\Schema::create('ppdb_settings', function ($table) {
            $table->id();
            $table->boolean('is_ppdb_open')->default(false);
            $table->string('academic_year', 20)->default('2026/2027');
            $table->text('closed_message')->nullable();
            $table->string('whatsapp_contact', 30)->default('081321021585');
            $table->timestamps();
        });
    }
    if (!\Illuminate\Support\Facades\Schema::hasTable('ppdb_registrations')) {
        \Illuminate\Support\Facades\Schema::create('ppdb_registrations', function ($table) {
            $table->id();
            $table->string('registration_number', 50)->unique();
            $table->string('nama_lengkap');
            $table->string('nik', 20)->unique();
            $table->string('nisn', 20)->nullable();
            $table->string('jalur_pendaftaran', 40)->default('Zonasi');
            $table->integer('jarak_ke_sekolah_meter')->nullable();
            $table->string('nomor_kip_pkh', 50)->nullable();
            $table->string('jenis_kelamin', 5);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('asal_jenjang', 30)->default('Rumah Tangga');
            $table->string('nama_asal_sekolah')->nullable();
            $table->string('nama_ibu_kandung');
            $table->string('nama_ayah_kandung')->nullable();
            $table->string('instansi_tujuan_mutasi')->nullable();
            $table->string('nomor_whatsapp', 30);
            $table->text('alamat_tinggal');
            $table->string('file_kk');
            $table->string('file_akta');
            $table->string('file_ijazah')->nullable();
            $table->string('file_bukti_afirmasi')->nullable();
            $table->string('file_surat_pindah_tugas')->nullable();
            $table->string('status_pendaftaran', 40)->default('Menunggu Verifikasi');
            $table->text('catatan_panitia')->nullable();
            $table->timestamps();
        });
    } else {
        // Pastikan kolom jalur pendaftaran & dokumen pendukung tersedia
        \Illuminate\Support\Facades\Schema::table('ppdb_registrations', function ($table) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('ppdb_registrations', 'jalur_pendaftaran')) {
                $table->string('jalur_pendaftaran', 40)->default('Zonasi');
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('ppdb_registrations', 'jarak_ke_sekolah_meter')) {
                $table->integer('jarak_ke_sekolah_meter')->nullable();
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('ppdb_registrations', 'nomor_kip_pkh')) {
                $table->string('nomor_kip_pkh', 50)->nullable();
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('ppdb_registrations', 'file_bukti_afirmasi')) {
                $table->string('file_bukti_afirmasi')->nullable();
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('ppdb_registrations', 'instansi_tujuan_mutasi')) {
                $table->string('instansi_tujuan_mutasi')->nullable();
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('ppdb_registrations', 'file_surat_pindah_tugas')) {
                $table->string('file_surat_pindah_tugas')->nullable();
            }
        });
    }

    // Sinkronisasi logo resmi sekolah ke favicon dan fallback images
    if (\Illuminate\Support\Facades\Schema::hasTable('school_profiles')) {
        $p = SchoolProfile::first();
        if ($p && !empty($p->logo)) {
            $logoSource = storage_path('app/public/' . $p->logo);
            if (file_exists($logoSource)) {
                @copy($logoSource, public_path('favicon.ico'));
                @copy($logoSource, public_path('images/logo-official.png'));
            }
        }
    }
} catch (\Throwable $e) {
    // Diamkan exception saat build image (database belum terhubung)
}

// Route khusus favicon.ico agar selalu mengembalikan logo resmi SDN Cinanggerang 1
Route::get('/favicon.ico', function () {
    $path = public_path('images/logo-official.png');
    if (file_exists($path)) {
        return response()->file($path, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-cache, must-revalidate',
        ]);
    }
    return response()->file(public_path('favicon.ico'));
});

// Fallback akses berkas Cloudflare R2 / S3 jika tidak ada di storage lokal kontainer
Route::get('/storage/{path}', function ($path) {
    $localFile = public_path('storage/' . $path);
    if (file_exists($localFile) && !is_dir($localFile)) {
        return response()->file($localFile);
    }
    if (env('AWS_URL')) {
        return redirect(rtrim(env('AWS_URL'), '/') . '/' . $path);
    }
    abort(404);
})->where('path', '.*');

// Pastikan cache view terkompilasi selalu bersih di environment lokal
if (app()->environment('local')) {
    array_map('unlink', glob(storage_path('framework/views/*.php')));
}

/*
|--------------------------------------------------------------------------
| Web Routes - SDN Cinanggerang I (Clean URLs & SEO Friendly)
|--------------------------------------------------------------------------
*/

// Auth Redirect Helper (/login -> Filament Admin Login)
Route::get('/login', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        return redirect()->intended(route('presensi.index'));
    }
    return redirect()->to('/admin/login');
})->name('login');

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/profil', [FrontendController::class, 'profil'])->name('profil');
Route::get('/guru', [FrontendController::class, 'guru'])->name('guru');
Route::get('/rombel', [FrontendController::class, 'rombel'])->name('rombel');
Route::get('/siswa', [FrontendController::class, 'siswa'])->name('siswa');
Route::get('/warta', [FrontendController::class, 'warta'])->name('warta');
Route::get('/dokumentasi', [FrontendController::class, 'dokumentasi'])->name('dokumentasi');
Route::get('/e-pkks', [PkksController::class, 'index'])->name('pkks');
Route::post('/e-pkks/verify-pin', [PkksController::class, 'verifyPin'])->name('pkks.verify-pin');
Route::post('/e-pkks/lock-pin', [PkksController::class, 'lockPin'])->name('pkks.lock-pin');
Route::get('/ppdb', [FrontendController::class, 'ppdb'])->name('ppdb');

// Rute Lengkap Layanan PPDB Online
Route::get('/ppdb/daftar', [PpdbController::class, 'create'])->name('ppdb.register');
Route::post('/ppdb/daftar', [PpdbController::class, 'store'])->name('ppdb.store');
Route::get('/ppdb/berhasil/{registration_number}', [PpdbController::class, 'success'])->name('ppdb.success');
Route::post('/ppdb/cek-status', [PpdbController::class, 'checkStatus'])->name('ppdb.check-status');
Route::get('/ppdb/cetak-kelulusan/{registration_number}', [PpdbController::class, 'printKelulusan'])->name('ppdb.print-kelulusan');

// Rute Presensi GPS Guru Digital
Route::get('/presensi', [AttendanceController::class, 'index'])->name('presensi.index');
Route::post('/presensi', [AttendanceController::class, 'store'])->name('presensi.store');
