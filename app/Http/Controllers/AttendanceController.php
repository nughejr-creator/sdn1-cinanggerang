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

        if ($teacher) {
            $todayAttendance = TeacherAttendance::where('teacher_id', $teacher->id)
                ->whereDate('tanggal', $today)
                ->first();
        }

        return view('frontend.presensi', compact(
            'profile',
            'user',
            'teacher',
            'schoolLat',
            'schoolLng',
            'maxRadius',
            'todayAttendance',
            'todayHoliday'
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

        // Validasi input presensi fleksibel (mendukung Reguler & Dinas Luar)
        $request->validate([
            'attendance_type'    => 'required|in:Reguler,Dinas Luar',
            'action_type'        => 'nullable|in:in,out,masuk,pulang',
            'tipe'               => 'nullable|in:in,out,masuk,pulang',
            'latitude'           => 'required|numeric',
            'longitude'          => 'required|numeric',
            'selfie_image'       => 'required_without:foto_selfie|nullable|string',
            'foto_selfie'        => 'required_without:selfie_image|nullable|string',
            'duty_location_name' => 'required_if:attendance_type,Dinas Luar|nullable|string|max:255',
            'duty_description'   => 'required_if:attendance_type,Dinas Luar|nullable|string',
            'duty_proof_type'    => 'required_if:attendance_type,Dinas Luar|nullable|in:Surat Tugas / Undangan,Foto Kegiatan di Lokasi',
            'duty_proof_file'    => 'required_if:attendance_type,Dinas Luar|nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ], [
            'duty_proof_file.required_if' => 'Untuk presensi dinas luar, wajib melampirkan berkas surat tugas atau foto dokumentasi di lokasi kegiatan.',
            'duty_location_name.required_if' => 'Nama lokasi/instansi dinas luar wajib diisi.',
            'duty_description.required_if' => 'Keterangan agenda kegiatan dinas luar wajib diisi.',
            'duty_proof_type.required_if' => 'Pilih jenis bukti validasi dinas luar.',
            'duty_proof_file.max' => 'Ukuran berkas bukti dinas luar maksimal 2 MB.',
            'duty_proof_file.mimes' => 'Format berkas bukti harus berupa PDF, JPG, JPEG, atau PNG.',
            'selfie_image.required_without' => 'Swafoto (selfie) wajah wajib diambil sebelum merekam presensi.',
            'foto_selfie.required_without' => 'Swafoto (selfie) wajah wajib diambil sebelum merekam presensi.',
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
        $photoPath = $this->storeBase64Selfie($selfieBase64, $teacher->id, $tipe);

        // Penanganan Berkas Bukti Dinas Luar
        $dutyProofPath = null;
        if ($attendanceType === 'Dinas Luar' && $request->hasFile('duty_proof_file')) {
            $file = $request->file('duty_proof_file');
            $dutyProofPath = $file->store('presensi_dinas_luar', 'public');
        }

        $now = now()->setTimezone('Asia/Jakarta');
        $jamSekarang = $now->format('H:i:s');
        $jamHi = $now->format('H:i');
        $tanggalHariIni = $now->toDateString();

        $record = TeacherAttendance::where('teacher_id', $teacher->id)
            ->whereDate('tanggal', $tanggalHariIni)
            ->first();

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

            $pulangTepatWaktu = ($jamHi >= '12:00');
            $ketPulang = $pulangTepatWaktu ? 'Pulang Tepat Waktu (≥ 12:00)' : 'Pulang Lebih Awal (< 12:00)';

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
                'is_in_radius_pulang' => true,
                'keterangan' => ($record->keterangan ? $record->keterangan . ' | ' : '') . $ketPulang . " (Jarak: {$distance} m)",
            ];

            // Jika waktu pulang dinas luar melampirkan berkas baru
            if ($dutyProofPath) {
                $updateData['duty_proof_file'] = $dutyProofPath;
                $updateData['duty_proof_type'] = $request->duty_proof_type;
                $updateData['duty_location_name'] = $request->duty_location_name;
                $updateData['duty_description'] = $request->duty_description;
                $updateData['attendance_type'] = 'Dinas Luar';
                $updateData['status'] = $this->resolveDinasLuarStatus();
            }

            $record->update($updateData);
            $record->refresh();

            $labelSukses = ($attendanceType === 'Dinas Luar')
                ? "Presensi Pulang DINAS LUAR berhasil direkam pukul {$jamHi} WIB ({$ketPulang})!"
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
}
