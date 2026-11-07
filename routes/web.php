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

    // Pastikan tabel pengaturan hari libur sekolah tersedia
    if (!\Illuminate\Support\Facades\Schema::hasTable('school_holidays')) {
        \Illuminate\Support\Facades\Schema::create('school_holidays', function ($table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('type', 60)->default('Hari Libur Nasional');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    // Pastikan tabel galleries untuk dokumentasi foto kegiatan tersedia dan kolom foto bertipe LONGTEXT
    if (!\Illuminate\Support\Facades\Schema::hasTable('galleries')) {
        \Illuminate\Support\Facades\Schema::create('galleries', function ($table) {
            $table->id();
            $table->string('judul_kegiatan');
            $table->date('tanggal_kegiatan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->longText('foto')->nullable();
            $table->timestamps();
        });
    } else {
        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE galleries MODIFY COLUMN foto LONGTEXT NULL");
        } catch (\Throwable $e) {}
    }

    // Pastikan kolom ticker & pengaturan beranda di school_profiles tersedia
    if (\Illuminate\Support\Facades\Schema::hasTable('school_profiles')) {
        \Illuminate\Support\Facades\Schema::table('school_profiles', function ($table) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('school_profiles', 'ticker_text')) {
                $table->text('ticker_text')->nullable();
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('school_profiles', 'is_ticker_active')) {
                $table->boolean('is_ticker_active')->default(true);
            }
        });
    }

    // Pastikan kolom tampil_di_beranda di posts & announcements tersedia
    if (\Illuminate\Support\Facades\Schema::hasTable('posts')) {
        \Illuminate\Support\Facades\Schema::table('posts', function ($table) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('posts', 'tampil_di_beranda')) {
                $table->boolean('tampil_di_beranda')->default(false);
            }
        });
    }
    if (\Illuminate\Support\Facades\Schema::hasTable('announcements')) {
        \Illuminate\Support\Facades\Schema::table('announcements', function ($table) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('announcements', 'tampil_di_beranda')) {
                $table->boolean('tampil_di_beranda')->default(true);
            }
        });
    }

    // Pastikan pengumuman agenda SAS 1 T.A. 2026/2027 tersedia
    if (\Illuminate\Support\Facades\Schema::hasTable('announcements')) {
        \App\Models\Announcement::firstOrCreate(
            ['judul' => 'PENGUMUMAN: Pelaksanaan Sumatif Akhir Semester (SAS) 1 T.A. 2026/2027 SD Negeri Cinanggerang I'],
            [
                'tanggal_mulai' => '2026-11-30',
                'tanggal_selesai' => '2026-12-12',
                'isi' => '<p>Diberitahukan kepada seluruh Bapak/Ibu Pendidik, Tenaga Kependidikan, Orang Tua/Wali Murid, serta seluruh Peserta Didik SD Negeri Cinanggerang I bahwa <strong>Pelaksanaan Sumatif Akhir Semester (SAS) 1 Tahun Ajaran 2026/2027</strong> akan diselenggarakan mulai tanggal <strong>30 November s.d. 12 Desember 2026</strong>. Mohon seluruh peserta didik mempersiapkan diri dengan optimal.</p>',
                'is_active' => true,
            ]
        );
    }

    // Bersihkan file dan tabel duplikat LearningMaterial agar menu E-Learning tetap tunggal & rapi
    $dupResource = app_path('Filament/Resources/LearningMaterialResource.php');
    if (file_exists($dupResource)) {
        @unlink(app_path('Filament/Resources/LearningMaterialResource/Pages/ListLearningMaterials.php'));
        @unlink(app_path('Filament/Resources/LearningMaterialResource/Pages/CreateLearningMaterial.php'));
        @unlink(app_path('Filament/Resources/LearningMaterialResource/Pages/EditLearningMaterial.php'));
        @rmdir(app_path('Filament/Resources/LearningMaterialResource/Pages'));
        @unlink(app_path('Filament/Resources/LearningMaterialResource.php'));
        @rmdir(app_path('Filament/Resources/LearningMaterialResource'));
        @unlink(app_path('Models/LearningMaterial.php'));
    }
    if (\Illuminate\Support\Facades\Schema::hasTable('learning_materials')) {
        try {
            \Illuminate\Support\Facades\Schema::dropIfExists('learning_materials');
        } catch (\Throwable $e) {}
    }

    // Pastikan skema tabel pengguna (users) & akun kepala sekolah tersedia
    if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
        \Illuminate\Support\Facades\Schema::table('users', function ($table) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'role')) {
                $table->string('role', 40)->default('Guru');
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'teacher_id')) {
                $table->foreignId('teacher_id')->nullable();
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });

        // Normalisasi role pengguna agar guru non-admin berstatus 'Guru'
        \App\Models\User::whereNotIn('role', ['Admin', 'Kepala Sekolah'])->update(['role' => 'Guru']);

        // Pastikan akun Kepala Sekolah tersedia jika belum ada untuk pengujian peran
        $ksTeacher = \App\Models\Teacher::where('nama', 'like', '%Aep Yayat%')->first();
        if ($ksTeacher && !\App\Models\User::where('role', 'Kepala Sekolah')->exists()) {
            \App\Models\User::firstOrCreate(
                ['email' => 'kepsek@sdncinanggerang1.sch.id'],
                [
                    'name' => 'Aep Yayat, S.Pd (Kepala Sekolah)',
                    'password' => \Illuminate\Support\Facades\Hash::make('kepsek12345'),
                    'role' => 'Kepala Sekolah',
                    'teacher_id' => $ksTeacher->id,
                    'is_active' => true,
                ]
            );
        }
    }

    // Pastikan skema tabel program kegiatan sekolah (Ekstrakurikuler, Kokurikuler, Pembiasaan) tersedia
    if (!\Illuminate\Support\Facades\Schema::hasTable('school_programs')) {
        \Illuminate\Support\Facades\Schema::create('school_programs', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('category', 40); // ekstrakurikuler, kokurikuler, pembiasaan
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('category');
        });
    }

    // Pastikan kolom program_id & program_category di tabel posts tersedia
    if (\Illuminate\Support\Facades\Schema::hasTable('posts')) {
        \Illuminate\Support\Facades\Schema::table('posts', function ($table) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('posts', 'program_id')) {
                $table->foreignId('program_id')->nullable();
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('posts', 'program_category')) {
                $table->string('program_category', 40)->nullable();
            }
        });
    }

    // Seed Data Awal 10 Program Kegiatan Sekolah Resmi SDN Cinanggerang I
    if (\Illuminate\Support\Facades\Schema::hasTable('school_programs')) {
        $initialPrograms = [
            // 1. Ekstrakurikuler
            ['name' => 'Pramuka', 'category' => 'ekstrakurikuler', 'description' => 'Praja Muda Karana - Pembinaan kepanduan, kemandirian, kedisiplinan dan kepemimpinan siswa.'],
            ['name' => 'Olahraga', 'category' => 'ekstrakurikuler', 'description' => 'Pengembangan bakat dan prestasi atletik, sepak bola, bola voli, dan bulutangkis.'],
            ['name' => 'Seni', 'category' => 'ekstrakurikuler', 'description' => 'Pengembangan kreativitas seni rupa, seni musik, dan tari tradisional khas Sunda.'],
            ['name' => 'Kewirausahaan', 'category' => 'ekstrakurikuler', 'description' => 'Pendidikan kewirausahaan sejak dini, market day, dan karya kreativitas siswa.'],

            // 2. Kokurikuler
            ['name' => 'G7KAIH', 'category' => 'kokurikuler', 'description' => 'Gerakan 7 Kebaikan Anak Indonesia Hebat - Pembiasaan budi pekerti luhur dan nilai Pancasila.'],
            ['name' => 'Sekolah Ramah Lingkungan', 'category' => 'kokurikuler', 'description' => 'Program Adiwiyata, penghijauan pekarangan sekolah, dan taman kelas asri.'],
            ['name' => 'Zero Waste', 'category' => 'kokurikuler', 'description' => 'Gerakan bebas sampah plastik, pemilahan sampah organik/anorganik, dan daur ulang.'],

            // 3. Pembiasaan
            ['name' => 'Sholat Dhuha', 'category' => 'pembiasaan', 'description' => 'Pembiasaan sholat dhuha bersama untuk menumbuhkan nilai spiritual dan religiusitas siswa.'],
            ['name' => 'Senam', 'category' => 'pembiasaan', 'description' => 'Senam kesegaran jasmani ceria rutin setiap pagi untuk kesehatan dan kebugaran jasmani.'],
            ['name' => 'Literasi', 'category' => 'pembiasaan', 'description' => 'Gerakan literasi membaca buku 15 menit sebelum kegiatan belajar mengajar dimulai.'],
        ];

        foreach ($initialPrograms as $prog) {
            \App\Models\SchoolProgram::firstOrCreate(
                ['name' => $prog['name'], 'category' => $prog['category']],
                [
                    'description' => $prog['description'],
                    'is_active' => true,
                ]
            );
        }
    }

    // Pastikan minimal 2 berita tayang agar grid 2 kolom di beranda seimbang
    if (\Illuminate\Support\Facades\Schema::hasTable('posts')) {
        \App\Models\Post::firstOrCreate(
            ['slug' => 'gelar-karya-p5-kearifan-lokal'],
            [
                'judul' => 'Gelar Karya P5 SDN Cinanggerang I: Menumbuhkan Karakter Pancasila Melalui Kearifan Lokal',
                'kategori' => 'Kegiatan Kurikulum Merdeka',
                'published_at' => '2026-11-05',
                'konten' => '<p>SD Negeri Cinanggerang I menyelenggarakan kegiatan Gelar Karya P5 (Proyek Penguatan Profil Pelajar Pancasila) dengan tema Kearifan Lokal. Seluruh peserta didik menampilkan kreativitas bernilai budaya dan kewirausahaan.</p>',
                'status' => 'Publish',
            ]
        );
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
    try {
        $profile = \App\Models\SchoolProfile::first();
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
Route::get('/warta/{slug}', [FrontendController::class, 'wartaDetail'])->name('warta.detail')->where('slug', '.*');
Route::get('/berita/{slug}', fn ($slug) => redirect()->route('warta.detail', $slug))->where('slug', '.*');
Route::get('/pengumuman', [FrontendController::class, 'pengumuman'])->name('pengumuman');
Route::get('/pengumuman/{id}', [FrontendController::class, 'pengumumanDetail'])->name('pengumuman.detail');
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

// Rute Presensi GPS Guru Digital & Laporan Cetak Kedinasan
Route::get('/presensi', [AttendanceController::class, 'index'])->name('presensi.index');
Route::post('/presensi', [AttendanceController::class, 'store'])->name('presensi.store');
Route::get('/admin/presensi/cetak-rekap', [AttendanceController::class, 'printRecap'])->name('admin.presensi.cetak-rekap');
