<?php

namespace App\Http\Controllers;

use App\Models\PpdbRegistration;
use App\Models\PpdbSetting;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PpdbController extends Controller
{
    /**
     * Pastikan schema tabel ppdb_settings dan ppdb_registrations tersedia di database
     */
    protected function ensureSchema(): void
    {
        if (!Schema::hasTable('ppdb_settings')) {
            Schema::create('ppdb_settings', function ($table) {
                $table->id();
                $table->boolean('is_ppdb_open')->default(false);
                $table->string('academic_year', 20)->default('2026/2027');
                $table->text('closed_message')->nullable();
                $table->string('whatsapp_contact', 30)->default('081321021585');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ppdb_registrations')) {
            Schema::create('ppdb_registrations', function ($table) {
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
            Schema::table('ppdb_registrations', function ($table) {
                if (!Schema::hasColumn('ppdb_registrations', 'jalur_pendaftaran')) {
                    $table->string('jalur_pendaftaran', 40)->default('Zonasi');
                }
                if (!Schema::hasColumn('ppdb_registrations', 'jarak_ke_sekolah_meter')) {
                    $table->integer('jarak_ke_sekolah_meter')->nullable();
                }
                if (!Schema::hasColumn('ppdb_registrations', 'nomor_kip_pkh')) {
                    $table->string('nomor_kip_pkh', 50)->nullable();
                }
                if (!Schema::hasColumn('ppdb_registrations', 'file_bukti_afirmasi')) {
                    $table->string('file_bukti_afirmasi')->nullable();
                }
                if (!Schema::hasColumn('ppdb_registrations', 'instansi_tujuan_mutasi')) {
                    $table->string('instansi_tujuan_mutasi')->nullable();
                }
                if (!Schema::hasColumn('ppdb_registrations', 'file_surat_pindah_tugas')) {
                    $table->string('file_surat_pindah_tugas')->nullable();
                }
            });
        }
    }

    /**
     * Tampilan Formulir Pendaftaran Siswa Baru (PPDB)
     */
    public function create()
    {
        $this->ensureSchema();
        $setting = PpdbSetting::getSetting();

        // 1. Validasi Pintu Pendaftaran
        if (!$setting->is_ppdb_open) {
            return redirect()->route('ppdb')->with('error_closed', $setting->closed_message);
        }

        $profile = SchoolProfile::first();
        return view('frontend.ppdb.create', compact('setting', 'profile'));
    }

    /**
     * Simpan Data Pendaftaran Calon Siswa Baru dengan Validasi Ketat & Anti-Duplikasi
     */
    public function store(Request $request)
    {
        $this->ensureSchema();
        $setting = PpdbSetting::getSetting();

        // 1. Validasi Pintu Pendaftaran
        if (!$setting->is_ppdb_open) {
            $msg = $setting->closed_message ?: 'Pendaftaran PPDB saat ini sedang ditutup.';
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : redirect()->route('ppdb')->with('error_closed', $msg);
        }

        // 2. Validasi Anti-Duplikasi Data Spesifik
        $nikInput = trim((string)$request->nik);
        $nisnInput = trim((string)$request->nisn);
        $namaInput = trim((string)$request->nama_lengkap);
        $tglLahirInput = $request->tanggal_lahir;

        // Cek NIK unik
        if (PpdbRegistration::where('nik', $nikInput)->exists()) {
            $errorMsg = "Gagal Mendaftar: NIK [{$nikInput}] sudah pernah didaftarkan sebelumnya. Calon siswa ini telah memiliki nomor registrasi.";
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $errorMsg, 'errors' => ['nik' => [$errorMsg]]], 422)
                : back()->withInput()->withErrors(['nik' => $errorMsg]);
        }

        // Cek NISN unik (jika diisi)
        if (!empty($nisnInput) && PpdbRegistration::where('nisn', $nisnInput)->exists()) {
            $errorMsg = "Gagal Mendaftar: NISN [{$nisnInput}] sudah pernah terdaftar pada sistem PPDB.";
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $errorMsg, 'errors' => ['nisn' => [$errorMsg]]], 422)
                : back()->withInput()->withErrors(['nisn' => $errorMsg]);
        }

        // Cek Kombinasi Nama Lengkap & Tanggal Lahir yang sama persis
        if (PpdbRegistration::where('nama_lengkap', 'like', $namaInput)->whereDate('tanggal_lahir', $tglLahirInput)->exists()) {
            $errorMsg = "Gagal Mendaftar: Calon siswa dengan nama [{$namaInput}] dan tanggal lahir tersebut sudah pernah didaftarkan.";
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $errorMsg, 'errors' => ['nama_lengkap' => [$errorMsg]]], 422)
                : back()->withInput()->withErrors(['nama_lengkap' => $errorMsg]);
        }

        // 3. Validasi Form & Aturan Berkas Persyaratan (Upload Rules)
        $request->validate([
            'jalur_pendaftaran' => 'required|in:Zonasi,Afirmasi,Perpindahan Orang Tua',
            'jarak_ke_sekolah_meter' => 'nullable|integer',
            'nomor_kip_pkh'     => [
                Rule::requiredIf(fn() => $request->jalur_pendaftaran === 'Afirmasi'),
                'nullable',
                'string',
                'max:50',
            ],
            'file_bukti_afirmasi' => [
                Rule::requiredIf(fn() => $request->jalur_pendaftaran === 'Afirmasi'),
                'nullable',
                'file',
                'mimes:jpeg,png,jpg,pdf',
                'max:2048',
            ],
            'instansi_tujuan_mutasi' => [
                Rule::requiredIf(fn() => $request->jalur_pendaftaran === 'Perpindahan Orang Tua'),
                'nullable',
                'string',
                'max:255',
            ],
            'file_surat_pindah_tugas' => [
                Rule::requiredIf(fn() => $request->jalur_pendaftaran === 'Perpindahan Orang Tua'),
                'nullable',
                'file',
                'mimes:jpeg,png,jpg,pdf',
                'max:2048',
            ],
            'nama_lengkap'      => 'required|string|max:255',
            'nik'               => 'required|digits:16',
            'nisn'              => 'nullable|digits:10',
            'jenis_kelamin'     => 'required|in:L,P',
            'tempat_lahir'      => 'required|string|max:100',
            'tanggal_lahir'     => 'required|date',
            'asal_jenjang'      => 'required|in:TK,RA,PAUD,Rumah Tangga',
            'nama_asal_sekolah' => 'nullable|string|max:255',
            'nama_ibu_kandung'  => 'required|string|max:255',
            'nama_ayah_kandung' => 'nullable|string|max:255',
            'nomor_whatsapp'    => 'required|string|max:30',
            'alamat_tinggal'    => 'required|string',
            'file_kk'           => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'file_akta'         => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'file_ijazah'       => [
                Rule::requiredIf(fn() => in_array($request->asal_jenjang, ['TK', 'RA', 'PAUD'])),
                'nullable',
                'file',
                'mimes:jpeg,png,jpg,pdf',
                'max:2048',
            ],
        ], [
            'jalur_pendaftaran.required' => 'Pilih salah satu Jalur Pendaftaran resmi.',
            'nomor_kip_pkh.required'     => 'Untuk Jalur Afirmasi, nomor kartu KIP / PKH / KKS wajib diisi.',
            'file_bukti_afirmasi.required' => 'Untuk Jalur Afirmasi, berkas scan/foto KIP/PKH/KKS/SKTM resmi wajib dilampirkan.',
            'file_bukti_afirmasi.mimes'  => 'Berkas bukti afirmasi harus berformat JPG, JPEG, PNG, atau PDF.',
            'file_bukti_afirmasi.max'    => 'Ukuran berkas bukti afirmasi maksimal 2 MB.',
            'instansi_tujuan_mutasi.required' => 'Untuk Jalur Perpindahan Tugas Orang Tua, nama instansi/lembaga tempat bertugas wajib diisi.',
            'file_surat_pindah_tugas.required' => 'Untuk Jalur Perpindahan Tugas, berkas SK / Surat Tugas Resmi penugasan orang tua wajib dilampirkan.',
            'file_surat_pindah_tugas.mimes' => 'Berkas surat pindah tugas harus berformat JPG, JPEG, PNG, atau PDF.',
            'file_surat_pindah_tugas.max' => 'Ukuran berkas surat pindah tugas maksimal 2 MB.',
            'nama_lengkap.required' => 'Nama lengkap calon siswa wajib diisi.',
            'nik.required'          => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
            'nik.digits'            => 'NIK harus terdiri dari tepat 16 digit angka sesuai Kartu Keluarga.',
            'nisn.digits'           => 'NISN harus terdiri dari 10 digit angka jika diisi.',
            'jenis_kelamin.required'=> 'Pilih jenis kelamin calon siswa.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required'=> 'Tanggal lahir wajib diisi.',
            'asal_jenjang.required' => 'Pilih asal jenjang pendidikan anak.',
            'nama_ibu_kandung.required' => 'Nama ibu kandung wajib diisi.',
            'nomor_whatsapp.required' => 'Nomor WhatsApp aktif wajib diisi untuk informasi panitia.',
            'alamat_tinggal.required' => 'Alamat tempat tinggal lengkap wajib diisi.',
            'file_kk.required'      => 'Berkas scan/foto Kartu Keluarga (KK) wajib diunggah.',
            'file_kk.mimes'         => 'Berkas KK harus berformat JPG, JPEG, PNG, atau PDF.',
            'file_kk.max'           => 'Ukuran berkas KK maksimal 2 MB.',
            'file_akta.required'    => 'Berkas scan/foto Akta Kelahiran wajib diunggah.',
            'file_akta.mimes'       => 'Berkas Akta harus berformat JPG, JPEG, PNG, atau PDF.',
            'file_akta.max'         => 'Ukuran berkas Akta maksimal 2 MB.',
            'file_ijazah.required'  => 'Untuk lulusan TK, RA, atau PAUD, berkas Ijazah / SKL wajib dilampirkan.',
            'file_ijazah.mimes'     => 'Berkas Ijazah harus berformat JPG, JPEG, PNG, atau PDF.',
            'file_ijazah.max'       => 'Ukuran berkas Ijazah maksimal 2 MB.',
        ]);

        // Simpan Berkas Dokumen Wajib
        $fileKkPath = $request->file('file_kk')->store('ppdb_docs', 'public');
        $fileAktaPath = $request->file('file_akta')->store('ppdb_docs', 'public');
        
        $fileIjazahPath = null;
        if ($request->hasFile('file_ijazah')) {
            $fileIjazahPath = $request->file('file_ijazah')->store('ppdb_docs', 'public');
        }

        // Simpan Berkas Dokumen Bersyarat Sesuai Jalur
        $fileBuktiAfirmasi = null;
        if ($request->hasFile('file_bukti_afirmasi')) {
            $fileBuktiAfirmasi = $request->file('file_bukti_afirmasi')->store('ppdb_docs', 'public');
        }

        $fileSuratPindah = null;
        if ($request->hasFile('file_surat_pindah_tugas')) {
            $fileSuratPindah = $request->file('file_surat_pindah_tugas')->store('ppdb_docs', 'public');
        }

        // Generate Nomor Registrasi Unik
        $registrationNumber = PpdbRegistration::generateRegistrationNumber();

        // Simpan ke Basis Data
        $registration = PpdbRegistration::create([
            'registration_number' => $registrationNumber,
            'jalur_pendaftaran'   => $request->jalur_pendaftaran,
            'jarak_ke_sekolah_meter' => $request->filled('jarak_ke_sekolah_meter') ? (int)$request->jarak_ke_sekolah_meter : null,
            'nomor_kip_pkh'       => $request->jalur_pendaftaran === 'Afirmasi' ? trim($request->nomor_kip_pkh) : null,
            'instansi_tujuan_mutasi' => $request->jalur_pendaftaran === 'Perpindahan Orang Tua' ? trim($request->instansi_tujuan_mutasi) : null,
            'nama_lengkap'        => $namaInput,
            'nik'                 => $nikInput,
            'nisn'                => $nisnInput ?: null,
            'jenis_kelamin'       => $request->jenis_kelamin,
            'tempat_lahir'        => trim($request->tempat_lahir),
            'tanggal_lahir'       => $tglLahirInput,
            'asal_jenjang'        => $request->asal_jenjang,
            'nama_asal_sekolah'   => in_array($request->asal_jenjang, ['TK', 'RA', 'PAUD']) ? trim($request->nama_asal_sekolah) : null,
            'nama_ibu_kandung'    => trim($request->nama_ibu_kandung),
            'nama_ayah_kandung'   => $request->filled('nama_ayah_kandung') ? trim($request->nama_ayah_kandung) : null,
            'nomor_whatsapp'      => trim($request->nomor_whatsapp),
            'alamat_tinggal'      => trim($request->alamat_tinggal),
            'file_kk'             => $fileKkPath,
            'file_akta'           => $fileAktaPath,
            'file_ijazah'         => $fileIjazahPath,
            'file_bukti_afirmasi' => $fileBuktiAfirmasi,
            'file_surat_pindah_tugas' => $fileSuratPindah,
            'status_pendaftaran'  => 'Menunggu Verifikasi',
            'catatan_panitia'     => 'Berkas telah diterima dan masuk antrean verifikasi administratif panitia PPDB.',
        ]);

        return redirect()->route('ppdb.success', $registration->registration_number);
    }

    /**
     * Halaman Bukti Pendaftaran Berhasil
     */
    public function success(string $registration_number)
    {
        $this->ensureSchema();
        $registration = PpdbRegistration::where('registration_number', $registration_number)->firstOrFail();
        $setting = PpdbSetting::getSetting();
        $profile = SchoolProfile::first();

        return view('frontend.ppdb.success', compact('registration', 'setting', 'profile'));
    }

    /**
     * Pengecekan Status Pendaftaran Mandiri (Cek Status)
     */
    public function checkStatus(Request $request)
    {
        $this->ensureSchema();

        $request->validate([
            'nik'          => 'required|string',
            'nama_lengkap' => 'required|string',
        ], [
            'nik.required'          => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
            'nama_lengkap.required' => 'Nama lengkap calon siswa wajib diisi.',
        ]);

        $nik = trim($request->nik);
        $nama = trim($request->nama_lengkap);

        $calon = PpdbRegistration::where('nik', $nik)
            ->where('nama_lengkap', 'like', '%' . $nama . '%')
            ->first();

        if (!$calon) {
            $msg = "Data pendaftar dengan NIK [{$nik}] dan nama [{$nama}] tidak ditemukan pada sistem PPDB. Mohon pastikan data yang dimasukkan sesuai dengan Kartu Keluarga.";
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $msg], 404)
                : back()->with('check_error', $msg)->withInput();
        }

        $printUrl = ($calon->status_pendaftaran === 'Diterima') 
            ? route('ppdb.print-kelulusan', $calon->registration_number) 
            : null;

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'registration_number' => $calon->registration_number,
                    'nama_lengkap'        => $calon->nama_lengkap,
                    'nik'                 => $calon->nik,
                    'nisn'                => $calon->nisn,
                    'jalur_pendaftaran'   => $calon->jalur_pendaftaran ?? 'Zonasi',
                    'status_pendaftaran'  => $calon->status_pendaftaran,
                    'catatan_panitia'     => $calon->catatan_panitia,
                    'tanggal_daftar'      => $calon->created_at->format('d M Y H:i'),
                    'print_url'           => $printUrl,
                ]
            ]);
        }

        return back()->with('check_result', $calon)->withInput();
    }

    /**
     * Cetak Surat Keterangan Kelulusan & Tanda Terima Seleksi PPDB
     */
    public function printKelulusan(string $registration_number)
    {
        $this->ensureSchema();
        $calon = PpdbRegistration::where('registration_number', $registration_number)->firstOrFail();

        // Proteksi: Surat hanya dapat dicetak jika status pendaftaran adalah 'Diterima'
        if ($calon->status_pendaftaran !== 'Diterima') {
            abort(403, 'Surat Keterangan Kelulusan hanya dapat dicetak untuk calon siswa yang berstatus Diterima.');
        }

        $setting = PpdbSetting::getSetting();
        $profile = SchoolProfile::first();

        return view('frontend.ppdb.print-kelulusan', compact('calon', 'setting', 'profile'));
    }
}
