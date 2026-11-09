<?php

namespace App\Http\Controllers;

use App\Models\SchoolHoliday;
use App\Models\SchoolProfile;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    /**
     * Pastikan schema kolom pendukung presensi GPS & dinas luar tersedia
     */
    protected function ensureSchema(): void
    {
        if (Schema::hasTable('teacher_attendances')) {
            // Ubah tipe kolom status & metode dari ENUM ke VARCHAR(60) agar fleksibel tanpa error truncation
            try {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE teacher_attendances MODIFY COLUMN status VARCHAR(60) NOT NULL DEFAULT 'Hadir Tepat Waktu'");
            } catch (\Throwable $e) {
                try {
                    \Illuminate\Support\Facades\DB::statement("ALTER TABLE teacher_attendances MODIFY COLUMN status ENUM('Hadir Tepat Waktu', 'Hadir Terlambat', 'Sakit (SKD)', 'Izin Resmi', 'Dinas Luar', 'Dinas Luar / Pelatihan', 'Cuti', 'Tanpa Keterangan (Alpa)') NOT NULL DEFAULT 'Hadir Tepat Waktu'");
                } catch (\Throwable $e2) {}
            }

            try {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE teacher_attendances MODIFY COLUMN metode VARCHAR(60) NOT NULL DEFAULT 'Presensi Mandiri Web'");
            } catch (\Throwable $e) {}

            $needed = [
                'foto_pulang' => 'string',
                'latitude_pulang' => 'decimal',
                'longitude_pulang' => 'decimal',
                'jarak_pulang_meter' => 'integer',
                'is_in_radius_pulang' => 'boolean',
                'attendance_type' => 'string_default_reguler',
                'duty_proof_type' => 'string',
                'duty_proof_file' => 'string',
                'duty_location_name' => 'string',
                'duty_description' => 'text',
            ];

            foreach ($needed as $column => $type) {
                if (!Schema::hasColumn('teacher_attendances', $column)) {
                    Schema::table('teacher_attendances', function ($table) use ($column, $type) {
                        if ($type === 'string') $table->string($column)->nullable();
                        elseif ($type === 'string_default_reguler') $table->string($column, 30)->default('Reguler');
                        elseif ($type === 'text') $table->text($column)->nullable();
                        elseif ($type === 'decimal') $table->decimal($column, 11, 8)->nullable();
                        elseif ($type === 'integer') $table->integer($column)->nullable();
                        elseif ($type === 'boolean') $table->boolean($column)->default(true);
                    });
                }
            }
        }
    }

    /**
     * Dapatkan nilai status dinas luar yang kompatibel dengan kolom status di basis data
     */
    protected function resolveDinasLuarStatus(): string
    {
        try {
            $col = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM teacher_attendances WHERE Field = 'status'");
            if (!empty($col[0]->Type)) {
                $typeStr = (string)$col[0]->Type;
                if (str_starts_with($typeStr, 'enum(')) {
                    if (!str_contains($typeStr, "'Dinas Luar'")) {
                        if (str_contains($typeStr, 'Dinas Luar / Pelatihan')) {
                            return 'Dinas Luar / Pelatihan';
                        }
                    }
                }
            }
        } catch (\Throwable $e) {}

        return 'Dinas Luar';
    }

    /**
     * Halaman Presensi GPS Guru Mobile-First
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            session()->put('url.intended', route('presensi.index'));
            return redirect()->route('login')->with('status', 'Silakan masuk dengan akun GTK Anda untuk melakukan presensi digital.');
        }

        $this->ensureSchema();

        $user = Auth::user();
        $teacher = $user->teacher;

        // Jika akun user belum terhubung ke teacher_id, coba cari lewat email atau nama
        if (!$teacher) {
            $teacher = Teacher::where('email', $user->email)
                ->orWhere('nama', 'like', '%' . $user->name . '%')
                ->first() ?: Teacher::where('is_active', true)->first();
        }

        $profile = SchoolProfile::first();
        $schoolLat = (float) ($profile?->latitude ?? -6.89437395);
        $schoolLng = (float) ($profile?->longitude ?? 107.87197894);
        $maxRadius = (int) ($profile?->radius_absen_meter ?? 100);

        $todayAttendance = null;
        $today = now()->setTimezone('Asia/Jakarta')->toDateString();
        $todayHoliday = SchoolHoliday::isHoliday($today);

        // Rekapitulasi Presensi Bulanan Guru yang Sedang Login
        $now = now()->setTimezone('Asia/Jakarta');
        $currentMonth = $now->month;
        $currentYear = $now->year;
        $monthName = $now->translatedFormat('F Y');

        $monthlyAttendances = collect();
        if ($teacher) {
            $todayAttendance = TeacherAttendance::where('teacher_id', $teacher->id)
                ->whereDate('tanggal', $today)
                ->first();

            $monthlyAttendances = TeacherAttendance::where('teacher_id', $teacher->id)
                ->whereYear('tanggal', $currentYear)
                ->whereMonth('tanggal', $currentMonth)
                ->get();
        }

        $monthlyRecap = [
            'month_name' => $monthName,
            'total_recorded' => $monthlyAttendances->count(),
            'hadir_tepat' => $monthlyAttendances->where('status', 'Hadir Tepat Waktu')->count(),
            'hadir_terlambat' => $monthlyAttendances->where('status', 'Hadir Terlambat')->count(),
            'total_hadir' => $monthlyAttendances->whereIn('status', ['Hadir Tepat Waktu', 'Hadir Terlambat'])->count(),
            'dinas_luar' => $monthlyAttendances->whereIn('status', ['Dinas Luar', 'Dinas Luar / Pelatihan'])->count(),
            'sakit' => $monthlyAttendances->where('status', 'Sakit (SKD)')->count(),
            'izin' => $monthlyAttendances->where('status', 'Izin Resmi')->count(),
            'alpa' => $monthlyAttendances->where('status', 'Tanpa Keterangan (Alpa)')->count(),
        ];
        $kehadiranEfektif = $monthlyRecap['total_hadir'] + $monthlyRecap['dinas_luar'];
        $monthlyRecap['persentase'] = $monthlyRecap['total_recorded'] > 0 
            ? round(($kehadiranEfektif / $monthlyRecap['total_recorded']) * 100) 
            : 100;

        return view('frontend.presensi', compact(
            'profile',
            'user',
            'teacher',
            'schoolLat',
            'schoolLng',
            'maxRadius',
            'todayAttendance',
            'todayHoliday',
            'monthlyRecap'
        ));
    }

    /**
     * Simpan Presensi Datang / Pulang Berbasis GPS Geofencing, Foto Selfie & Dinas Luar
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Sesi login telah berakhir. Silakan login kembali.'], 401);
            }
            return redirect()->route('login');
        }

        $this->ensureSchema();

        $isIzinOrSakit = in_array($request->attendance_type, ['Sakit', 'Izin']);

        // Validasi input presensi fleksibel (mendukung Reguler, Dinas Luar, Sakit, & Izin)
        $request->validate([
            'attendance_type'    => 'required|in:Reguler,Dinas Luar,Sakit,Izin',
            'action_type'        => 'nullable|in:in,out,masuk,pulang',
            'tipe'               => 'nullable|in:in,out,masuk,pulang',
            'latitude'           => $isIzinOrSakit ? 'nullable|numeric' : 'required|numeric|not_in:0',
            'longitude'          => $isIzinOrSakit ? 'nullable|numeric' : 'required|numeric|not_in:0',
            'selfie_image'       => $isIzinOrSakit ? 'nullable|string' : 'required_without:foto_selfie|string',
            'foto_selfie'        => $isIzinOrSakit ? 'nullable|string' : 'required_without:selfie_image|string',
            'duty_location_name' => 'required_if:attendance_type,Dinas Luar|nullable|string|max:255',
            'duty_description'   => $isIzinOrSakit ? 'required|string' : 'required_if:attendance_type,Dinas Luar|nullable|string',
            'duty_proof_type'    => 'nullable|string',
            'duty_proof_file'    => ($isIzinOrSakit ? 'required|' : 'required_if:attendance_type,Dinas Luar|') . 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:10240',
        ], [
            'latitude.required' => 'Akses lokasi GPS wajib diaktifkan dan diizinkan.',
            'latitude.not_in' => 'Koordinat GPS tidak valid. Pastikan GPS HP Anda aktif.',
            'longitude.required' => 'Akses lokasi GPS wajib diaktifkan dan diizinkan.',
            'longitude.not_in' => 'Koordinat GPS tidak valid. Pastikan GPS HP Anda aktif.',
            'selfie_image.required_without' => 'Swafoto (selfie) wajah wajib diambil melalui kamera sebelum merekam presensi.',
            'foto_selfie.required_without' => 'Swafoto (selfie) wajah wajib diambil melalui kamera sebelum merekam presensi.',
            'duty_proof_file.required' => 'Wajib melampirkan berkas bukti dokumen (Surat Dokter untuk Sakit, atau Surat Permohonan Izin).',
            'duty_proof_file.required_if' => 'Untuk presensi dinas luar, wajib melampirkan berkas surat tugas atau foto dokumentasi di lokasi kegiatan.',
            'duty_location_name.required_if' => 'Nama lokasi/instansi dinas luar wajib diisi.',
            'duty_description.required' => 'Keterangan alasan sakit atau keperluan izin wajib diisi.',
            'duty_description.required_if' => 'Keterangan agenda kegiatan dinas luar wajib diisi.',
            'duty_proof_type.required_if' => 'Pilih jenis bukti validasi dinas luar.',
            'duty_proof_file.max' => 'Ukuran berkas bukti maksimal 10 MB.',
            'duty_proof_file.mimes' => 'Format berkas bukti harus berupa PDF, JPG, JPEG, WEBP, atau PNG.',
        ]);

        $user = Auth::user();
        $teacher = $user->teacher;
        if (!$teacher) {
            $teacher = Teacher::where('email', $user->email)
                ->orWhere('nama', 'like', '%' . $user->name . '%')
                ->first() ?: Teacher::where('is_active', true)->first();
        }

        if (!$teacher) {
            $msg = 'Data profil GTK/Guru tidak ditemukan untuk akun ini.';
            return $request->wantsJson() 
                ? response()->json(['success' => false, 'message' => $msg], 422) 
                : back()->with('error', $msg);
        }

        $profile = SchoolProfile::first();
        $schoolLat = (float) ($profile?->latitude ?? -6.89437395);
        $schoolLng = (float) ($profile?->longitude ?? 107.87197894);
        $maxRadius = (int) ($profile?->radius_absen_meter ?? 100);

        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;
        $attendanceType = $request->attendance_type;

        // Normalisasi tipe aksi (in/out atau masuk/pulang)
        $actionRaw = $request->input('action_type') ?: $request->input('tipe');
        $tipe = in_array($actionRaw, ['out', 'pulang']) ? 'pulang' : 'masuk';

        // Hitung jarak riil dari SDN Cinanggerang I
        $distance = $this->calculateHaversineDistance($schoolLat, $schoolLng, $lat, $lng);

        // Validasi Geofencing jika Presensi Reguler
        if ($attendanceType === 'Reguler') {
            if ($distance > $maxRadius) {
                $msg = "Presensi Reguler Ditolak! Posisi GPS Anda berjarak {$distance} meter dari SDN Cinanggerang I. Batas maksimal yang diizinkan adalah {$maxRadius} meter. Jika sedang bertugas di luar sekolah, pilih mode 'Presensi Dinas Luar'.";
                return $request->wantsJson() 
                    ? response()->json(['success' => false, 'message' => $msg, 'distance' => $distance], 422) 
                    : back()->with('error', $msg);
            }
            $isInRadius = true;
        } else {
            // Mode Dinas Luar: Geofencing radius 100 meter di-BYPASS
            $isInRadius = true;
        }

        // Ambil Data Selfie Base64
        $selfieBase64 = $request->input('selfie_image') ?: $request->input('foto_selfie');

        // Validasi mutlak foto selfie untuk Reguler dan Dinas Luar (Tidak boleh kosong atau dilewati)
        if (!$isIzinOrSakit) {
            if (empty($selfieBase64) || strlen($selfieBase64) < 200 || !str_contains($selfieBase64, 'data:image')) {
                $msg = 'Presensi Ditolak! Swafoto (selfie) wajah melalui kamera wajib diambil dan tidak boleh kosong.';
                return $request->wantsJson() 
                    ? response()->json(['success' => false, 'message' => $msg], 422) 
                    : back()->with('error', $msg);
            }
        }

        $photoPath = $this->storeBase64Selfie($selfieBase64, $teacher->id, $tipe);

        // Penanganan Berkas Bukti (Dinas Luar, Sakit SKD, atau Izin Resmi)
        $dutyProofPath = null;
        if (in_array($attendanceType, ['Dinas Luar', 'Sakit', 'Izin']) && $request->hasFile('duty_proof_file')) {
            $file = $request->file('duty_proof_file');
            $mime = $file->getMimeType() ?: '';

            if (str_starts_with($mime, 'image/') && function_exists('imagecreatefromstring')) {
                try {
                    $imageContent = file_get_contents($file->getRealPath());
                    $sourceImage = @imagecreatefromstring($imageContent);
                    if ($sourceImage !== false) {
                        $w = imagesx($sourceImage);
                        $h = imagesy($sourceImage);
                        $maxDim = 1024;
                        if ($w > $maxDim || $h > $maxDim) {
                            if ($w > $h) {
                                $newW = $maxDim;
                                $newH = (int) round(($h * $maxDim) / $w);
                            } else {
                                $newH = $maxDim;
                                $newW = (int) round(($w * $maxDim) / $h);
                            }
                            $resized = imagecreatetruecolor($newW, $newH);
                            imagecopyresampled($resized, $sourceImage, 0, 0, 0, 0, $newW, $newH, $w, $h);
                            imagedestroy($sourceImage);
                            $sourceImage = $resized;
                        }

                        $prefix = strtolower($attendanceType);
                        $fileName = $prefix . '_proof_' . $teacher->id . '_' . date('Ymd_His') . '.jpg';
                        $dutyProofPath = 'attendances_proofs/' . $fileName;

                        ob_start();
                        imagejpeg($sourceImage, null, 72);
                        $compressedData = ob_get_clean();
                        imagedestroy($sourceImage);

                        Storage::disk('public')->put($dutyProofPath, $compressedData);
                    } else {
                        $dutyProofPath = $file->store('attendances_proofs', 'public');
                    }
                } catch (\Throwable $e) {
                    $dutyProofPath = $file->store('attendances_proofs', 'public');
                }
            } else {
                $dutyProofPath = $file->store('attendances_proofs', 'public');
            }
        }

        $now = now()->setTimezone('Asia/Jakarta');
        $jamSekarang = $now->format('H:i:s');
        $jamHi = $now->format('H:i');
        $tanggalHariIni = $now->toDateString();

        $record = TeacherAttendance::where('teacher_id', $teacher->id)
            ->whereDate('tanggal', $tanggalHariIni)
            ->first();

        // JIKA PENGAJUAN SAKIT (SKD) ATAU IZIN RESMI
        if ($isIzinOrSakit) {
            $statusLabel = ($attendanceType === 'Sakit') ? 'Sakit (SKD)' : 'Izin Resmi';
            $proofTypeLabel = ($attendanceType === 'Sakit') ? 'Surat Keterangan Dokter (SKD)' : 'Surat Permohonan Izin';

            $data = [
                'teacher_id' => $teacher->id,
                'tanggal' => $tanggalHariIni,
                'jam_masuk' => $jamSekarang,
                'jam_pulang' => '12:00:00', // Status sah satu hari penuh
                'status' => $statusLabel,
                'attendance_type' => $attendanceType,
                'duty_proof_type' => $proofTypeLabel,
                'duty_proof_file' => $dutyProofPath,
                'duty_description' => $request->duty_description,
                'duty_location_name' => ($attendanceType === 'Sakit') ? 'Rumah / Fasilitas Kesehatan' : ($request->duty_location_name ?: 'Tempat Izin'),
                'metode' => 'Presensi Mandiri Web',
                'foto_bukti' => $dutyProofPath ?: $photoPath,
                'latitude' => $lat ?: $schoolLat,
                'longitude' => $lng ?: $schoolLng,
                'jarak_meter' => $distance,
                'is_in_radius' => true,
                'is_in_radius_pulang' => true,
                'keterangan' => "Pengajuan Mandiri {$statusLabel}: {$request->duty_description}",
            ];

            if ($record) {
                $record->update($data);
            } else {
                $record = TeacherAttendance::create($data);
            }

            $labelSukses = "Laporan {$statusLabel} Anda berhasil dikirim ke sekolah beserta berkas bukti dukung!";
            return $request->wantsJson()
                ? response()->json(['success' => true, 'message' => $labelSukses, 'distance' => $distance, 'record' => $record])
                : redirect()->route('presensi.index')->with('success', $labelSukses);
        }

        if ($tipe === 'masuk') {
            if ($record && $record->jam_masuk) {
                $msg = 'Anda sudah melakukan presensi datang hari ini pada pukul ' . substr($record->jam_masuk, 0, 5) . ' WIB.';
                return $request->wantsJson() 
                    ? response()->json(['success' => false, 'message' => $msg], 422) 
                    : back()->with('error', $msg);
            }

            // Tentukan status kehadiran
            if ($attendanceType === 'Dinas Luar') {
                $status = $this->resolveDinasLuarStatus();
                $keterangan = "Dinas Luar: {$request->duty_location_name} - {$request->duty_description} (GPS: {$lat}, {$lng})";
            } else {
                // Batas datang tepat waktu 07:30 WIB
                $status = ($jamHi > '07:30') ? 'Hadir Terlambat' : 'Hadir Tepat Waktu';
                $keterangan = $request->keterangan ?: "Presensi Masuk Mandiri ({$distance} m dari sekolah)";
            }

            $data = [
                'teacher_id' => $teacher->id,
                'tanggal' => $tanggalHariIni,
                'jam_masuk' => $jamSekarang,
                'status' => $status,
                'attendance_type' => $attendanceType,
                'duty_proof_type' => $request->duty_proof_type,
                'duty_proof_file' => $dutyProofPath,
                'duty_location_name' => $request->duty_location_name,
                'duty_description' => $request->duty_description,
                'metode' => 'Presensi Mandiri Web',
                'foto_bukti' => $photoPath,
                'latitude' => $lat,
                'longitude' => $lng,
                'jarak_meter' => $distance,
                'is_in_radius' => $isInRadius,
                'keterangan' => $keterangan,
            ];

            if ($record) {
                $record->update($data);
            } else {
                $record = TeacherAttendance::create($data);
            }

            $savedRecord = TeacherAttendance::where('teacher_id', $teacher->id)->whereDate('tanggal', $tanggalHariIni)->first();
            $labelSukses = ($attendanceType === 'Dinas Luar') 
                ? "Presensi Datang DINAS LUAR di '{$request->duty_location_name}' berhasil direkam pukul {$jamHi} WIB!"
                : "Presensi Datang berhasil direkam ({$status}) pukul {$jamHi} WIB pada jarak {$distance} meter!";

            return $request->wantsJson()
                ? response()->json(['success' => true, 'message' => $labelSukses, 'distance' => $distance, 'record' => $savedRecord])
                : redirect()->route('presensi.index')->with('success', $labelSukses);

        } else { // Pulang
            if ($record && $record->jam_pulang) {
                $msg = 'Anda sudah melakukan presensi pulang hari ini pada pukul ' . substr($record->jam_pulang, 0, 5) . ' WIB.';
                return $request->wantsJson() 
                    ? response()->json(['success' => false, 'message' => $msg], 422) 
                    : back()->with('error', $msg);
            }

            // Batas minimal presensi pulang adalah setelah pukul 10:00 WIB
            if ($jamHi < '10:00') {
                $msg = 'Presensi Pulang belum dibuka! Presensi pulang baru dapat dilakukan setelah pukul 10:00 WIB (Waktu server saat ini: ' . $jamHi . ' WIB).';
                return $request->wantsJson() 
                    ? response()->json(['success' => false, 'message' => $msg], 422) 
                    : back()->with('error', $msg);
            }

            $isFriday = \Carbon\Carbon::parse($tanggalHariIni)->isFriday();
            $jamTargetPulang = $isFriday ? '11:00' : '12:00';
            $pulangTepatWaktu = ($jamHi >= $jamTargetPulang);
            $ketPulang = $pulangTepatWaktu ? "Pulang Tepat Waktu (≥ {$jamTargetPulang})" : "Pulang Lebih Awal (< {$jamTargetPulang})";

            if (!$record) {
                // Jika belum absen masuk tapi langsung pulang
                $statusAwal = ($attendanceType === 'Dinas Luar') ? $this->resolveDinasLuarStatus() : 'Hadir Tepat Waktu';
                $record = TeacherAttendance::create([
                    'teacher_id' => $teacher->id,
                    'tanggal' => $tanggalHariIni,
                    'jam_masuk' => null,
                    'status' => $statusAwal,
                    'attendance_type' => $attendanceType,
                    'metode' => 'Presensi Mandiri Web',
                    'is_in_radius' => true,
                ]);
            }

            $updateData = [
                'jam_pulang' => $jamSekarang,
                'foto_pulang' => $photoPath,
                'latitude_pulang' => $lat,
                'longitude_pulang' => $lng,
                'jarak_pulang_meter' => $distance,
                'is_in_radius_pulang' => ($attendanceType === 'Dinas Luar' ? true : $isInRadius),
            ];

            // Jika presensi pulang dengan status Dinas Luar (baik dari pagi DL atau siang ditugaskan DL)
            if ($attendanceType === 'Dinas Luar') {
                $updateData['attendance_type'] = 'Dinas Luar';
                $updateData['status'] = $this->resolveDinasLuarStatus();
                $updateData['duty_location_name'] = $request->duty_location_name ?: $record->duty_location_name;
                $updateData['duty_description'] = $request->duty_description ?: $record->duty_description;
                $updateData['duty_proof_type'] = $request->duty_proof_type ?: $record->duty_proof_type;
                if ($dutyProofPath) {
                    $updateData['duty_proof_file'] = $dutyProofPath;
                }
                $ketDl = "Pulang Dinas Luar di: {$request->duty_location_name} ({$ketPulang})";
                $updateData['keterangan'] = $record->keterangan ? ($record->keterangan . ' | ' . $ketDl) : $ketDl;
            } else {
                $updateData['keterangan'] = ($record->keterangan ? $record->keterangan . ' | ' : '') . $ketPulang . " (Jarak: {$distance} m)";
            }

            $record->update($updateData);
            $record->refresh();

            $labelSukses = ($attendanceType === 'Dinas Luar')
                ? "Presensi Pulang DINAS LUAR di '{$request->duty_location_name}' berhasil direkam pukul {$jamHi} WIB ({$ketPulang})!"
                : "Presensi Pulang berhasil direkam pukul {$jamHi} WIB ({$ketPulang}) pada jarak {$distance} meter!";

            return $request->wantsJson()
                ? response()->json(['success' => true, 'message' => $labelSukses, 'distance' => $distance, 'record' => $record])
                : redirect()->route('presensi.index')->with('success', $labelSukses);
        }
    }

    /**
     * Hitung Jarak Menggunakan Haversine Formula (Meter)
     */
    protected function calculateHaversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): int
    {
        $earthRadius = 6371000; // Radius bumi dalam meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return (int) round($earthRadius * $c);
    }

    /**
     * Simpan Foto Selfie Base64 ke Storage Public
     */
    protected function storeBase64Selfie(?string $base64Data, int $teacherId, string $tipe): ?string
    {
        if (empty($base64Data)) {
            return null;
        }

        try {
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                $type = strtolower($type[1]);
                if (!in_array($type, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $type = 'jpg';
                }
            } else {
                $type = 'jpg';
            }

            $imageData = base64_decode($base64Data);
            if ($imageData === false) {
                return null;
            }

            $fileName = "selfie_{$teacherId}_{$tipe}_" . date('Ymd_His') . "_{$type}." . ($type === 'jpeg' ? 'jpg' : $type);
            $filePath = "attendances/{$fileName}";

            Storage::disk('public')->put($filePath, $imageData);
            return $filePath;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Gagal simpan foto selfie presensi: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cetak Lembar Rekapitulasi Presensi Bulanan GTK (Format Dinas Cetak Fisik / PDF)
     */
    public function printRecap(Request $request)
    {
        $bulan = (int) ($request->query('bulan') ?? now()->setTimezone('Asia/Jakarta')->month);
        $tahun = (int) ($request->query('tahun') ?? now()->setTimezone('Asia/Jakarta')->year);

        $now = now()->setTimezone('Asia/Jakarta');
        $isCurrentMonth = ($bulan === (int)$now->format('m') && $tahun === (int)$now->format('Y'));

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ][$bulan] ?? 'Bulan ' . $bulan;
        $periodLabel = "{$namaBulan} {$tahun}";

        $startOfMonth = \Carbon\Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::create($tahun, $bulan, 1)->endOfMonth();

        // Tentukan batas tanggal perhitungan Hari Efektif (HE):
        // Jika mengecek bulan berjalan, batasnya adalah hari ini (tanggal berjalan)
        // Jika mengecek bulan lampau/penuh, batasnya adalah akhir bulan tersebut
        $cutoffDate = $isCurrentMonth ? min($now->copy()->endOfDay(), $endOfMonth) : $endOfMonth;

        // Hitung hari kerja kalender (Senin - Sabtu, tanpa hari Minggu)
        $nonSundayDays = 0;
        $temp = $startOfMonth->copy();
        while ($temp <= $cutoffDate) {
            if ($temp->dayOfWeek !== \Carbon\Carbon::SUNDAY) {
                $nonSundayDays++;
            }
            $temp->addDay();
        }

        // Kurangi dengan hari libur resmi sekolah yang terdaftar
        $holidaysCount = SchoolHoliday::countHolidaysBetween($startOfMonth, $cutoffDate);
        $hariEfektif = max(1, $nonSundayDays - $holidaysCount);

        $heInfoLabel = $isCurrentMonth 
            ? "{$hariEfektif} Hari (Dihitung s.d. {$now->translatedFormat('d F Y')} - Bulan Berjalan)"
            : "{$hariEfektif} Hari (Total 1 Bulan Penuh)";

        $profile = SchoolProfile::first();
        $teachers = Teacher::where('is_active', true)->orderBy('nama')->get();
        $attendances = TeacherAttendance::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get();

        $teachersRecap = $teachers->map(function ($t) use ($attendances, $hariEfektif) {
            $userAtts = $attendances->where('teacher_id', $t->id);
            $hadirTepat = $userAtts->filter(fn ($r) => str_contains(strtolower($r->status), 'tepat'))->count();
            $hadirTerlambat = $userAtts->filter(fn ($r) => str_contains(strtolower($r->status), 'terlambat') || str_contains(strtolower($r->status), 'telat'))->count();
            $dinasLuar = $userAtts->filter(fn ($r) => str_contains(strtolower($r->status), 'dinas') || ($r->attendance_type === 'Dinas Luar'))->count();
            $sakit = $userAtts->filter(fn ($r) => str_contains(strtolower($r->status), 'sakit'))->count();
            $izin = $userAtts->filter(fn ($r) => str_contains(strtolower($r->status), 'izin'))->count();
            $alpa = $userAtts->filter(fn ($r) => str_contains(strtolower($r->status), 'alpa') || str_contains(strtolower($r->status), 'tanpa keterangan'))->count();

            // Total Hadir resmi = Tepat + Terlambat + Dinas Luar
            $totalHadir = $hadirTepat + $hadirTerlambat + $dinasLuar;
            
            // Persentase kehadiran terhadap Hari Efektif acuan
            $persentase = $hariEfektif > 0 ? min(100, round(($totalHadir / $hariEfektif) * 100)) : 0;

            // 5 Kriteria Predikat Kedinasan
            $predikat = match (true) {
                $persentase >= 90 => 'Sangat Baik',
                $persentase >= 80 => 'Baik',
                $persentase >= 70 => 'Cukup',
                $persentase >= 50 => 'Buruk',
                default           => 'Sangat Buruk',
            };

            return [
                'id' => $t->id,
                'nama' => $t->nama,
                'nip' => $t->nip ?? '-',
                'jabatan' => $t->jabatan ?? 'Guru',
                'hadir_tepat' => $hadirTepat,
                'hadir_terlambat' => $hadirTerlambat,
                'dinas_luar' => $dinasLuar,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpa' => $alpa,
                'total_hadir' => $totalHadir,
                'hari_efektif' => $hariEfektif,
                'persentase' => $persentase,
                'predikat' => $predikat,
            ];
        });

        return view('print.attendance-recap', compact('profile', 'teachersRecap', 'bulan', 'tahun', 'periodLabel', 'hariEfektif', 'isCurrentMonth', 'heInfoLabel'));
    }
}
