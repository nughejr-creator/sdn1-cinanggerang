<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DapodikWebServiceSync
{
    /**
     * Default web service token (dapat dioverride via env atau parameter).
     */
    public static function getDefaultToken(): string
    {
        return env('DAPODIK_WS_TOKEN', 'Cu7bJNdcgqTI42V');
    }

    /**
     * Ambil data langsung dari Web Service Dapodik lokal (port 5774).
     */
    public static function pullFromLocal(
        ?string $token = null,
        string $npsn = '20208801',
        string $baseUrl = 'http://127.0.0.1:5774',
        bool $pruneMissing = true
    ): array {
        $token = $token ?: self::getDefaultToken();
        $baseUrl = rtrim($baseUrl, '/');
        // Ganti localhost dengan 127.0.0.1 untuk menghindari latency IPv6 timeout pada Windows cURL
        $baseUrl = str_replace('//localhost:', '//127.0.0.1:', $baseUrl);

        // 1. Ambil Data Rombel
        $rombelResp = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->timeout(15)->get("{$baseUrl}/WebService/getRombonganBelajar?npsn={$npsn}");

        if (!$rombelResp->successful()) {
            throw new \Exception("Gagal mengambil Rombongan Belajar dari Dapodik: " . $rombelResp->body());
        }

        $rombelData = $rombelResp->json();
        $rombels = $rombelData['rows'] ?? (is_array($rombelData) ? $rombelData : []);

        // 2. Ambil Data Peserta Didik
        $pdResp = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->timeout(30)->get("{$baseUrl}/WebService/getPesertaDidik?npsn={$npsn}");

        if (!$pdResp->successful()) {
            throw new \Exception("Gagal mengambil Peserta Didik dari Dapodik: " . $pdResp->body());
        }

        $pdData = $pdResp->json();
        $students = $pdData['rows'] ?? (is_array($pdData) ? $pdData : []);

        // 3. Proses Sinkronisasi ke Database (dengan pembersihan siswa mutasi/keluar jika diaktifkan)
        return self::processSyncData($students, $rombels, $pruneMissing);
    }

    /**
     * Proses data array siswa dan rombel (baik dari pull lokal maupun dari kiriman payload API).
     *
     * @param array $students Daftar peserta didik dari Dapodik
     * @param array $rombels Daftar rombel dari Dapodik
     * @param bool $pruneMissing Hapus permanen siswa di web yang sudah tidak ada di Dapodik (mutasi/keluar)
     */
    public static function processSyncData(array $students, array $rombels = [], bool $pruneMissing = true): array
    {
        if (empty($students)) {
            throw new \Exception("Daftar data siswa Dapodik kosong. Sinkronisasi dibatalkan demi keamanan data.");
        }

        return DB::transaction(function () use ($students, $rombels, $pruneMissing) {
            // 1. Sinkronisasi dan petakan seluruh kelas rombel
            $classMap = []; // [nama_rombel => class_id, rombel_id => class_id]
            $existingClasses = StudentClass::pluck('id', 'nama')->toArray();

            foreach ($rombels as $r) {
                $namaRombel = trim($r['nama'] ?? '');
                if (!$namaRombel) continue;

                $tingkat = (int)($r['tingkat_pendidikan_id'] ?? self::extractTingkat($namaRombel));
                $kurikulum = $r['kurikulum_id_str'] ?? 'Kurikulum Merdeka';

                $class = StudentClass::firstOrCreate(
                    ['nama' => $namaRombel],
                    [
                        'tingkat' => $tingkat ?: 1,
                        'wali_kelas' => 'Guru ' . $namaRombel,
                        'kurikulum' => $kurikulum,
                        'tahun_ajaran' => '2026/2027',
                    ]
                );

                $classMap[$namaRombel] = $class->id;
                if (!empty($r['rombongan_belajar_id'])) {
                    $classMap[$r['rombongan_belajar_id']] = $class->id;
                }
            }

            // Cache kelas yang sudah ada di database
            foreach ($existingClasses as $name => $id) {
                if (!isset($classMap[$name])) {
                    $classMap[$name] = $id;
                }
            }

            // Pre-load siswa yang ada di memori untuk pencocokan cepat tanpa ribuan query
            $existingByNisn = Student::whereNotNull('nisn')->get()->keyBy('nisn');
            $existingByNik = Student::whereNotNull('nik')->get()->keyBy('nik');
            $existingByNipd = Student::whereNotNull('nipd')->get()->keyBy('nipd');

            $stats = [
                'total' => 0,
                'created' => 0,
                'updated' => 0,
                'class_changed' => 0, // Siswa yang naik kelas / pindah rombel
                'deleted' => 0,       // Siswa yang dihapus permanen karena tidak ada di Dapodik
                'skipped' => 0,
                'classes_synced' => count($classMap),
            ];

            $syncedStudentIds = [];

            // 2. Loop dan Upsert Peserta Didik
            foreach ($students as $row) {
                $nama = trim($row['nama'] ?? '');
                if (empty($nama)) {
                    $stats['skipped']++;
                    continue;
                }

                $namaRombel = trim($row['nama_rombel'] ?? '');
                $rombelId = $row['rombongan_belajar_id'] ?? null;
                $classId = null;

                if ($rombelId && isset($classMap[$rombelId])) {
                    $classId = $classMap[$rombelId];
                } elseif ($namaRombel && isset($classMap[$namaRombel])) {
                    $classId = $classMap[$namaRombel];
                } elseif ($namaRombel) {
                    $tingkat = (int)($row['tingkat_pendidikan_id'] ?? self::extractTingkat($namaRombel));
                    $newClass = StudentClass::firstOrCreate(
                        ['nama' => $namaRombel],
                        [
                            'tingkat' => $tingkat ?: 1,
                            'wali_kelas' => 'Guru ' . $namaRombel,
                            'kurikulum' => $row['kurikulum_id_str'] ?? 'Kurikulum Merdeka',
                            'tahun_ajaran' => '2026/2027',
                        ]
                    );
                    $classId = $newClass->id;
                    $classMap[$namaRombel] = $classId;
                }

                $jkRaw = $row['jenis_kelamin'] ?? 'L';
                $jk = (strtoupper(substr((string)$jkRaw, 0, 1)) === 'P') ? 'P' : 'L';

                $nisn = !empty($row['nisn']) ? trim((string)$row['nisn']) : null;
                $nipd = !empty($row['nipd']) ? trim((string)$row['nipd']) : null;
                $nik = !empty($row['nik']) ? trim((string)$row['nik']) : null;

                $data = [
                    'nama' => $nama,
                    'nipd' => $nipd,
                    'nik' => $nik,
                    'jenis_kelamin' => $jk,
                    'tempat_lahir' => $row['tempat_lahir'] ?? null,
                    'tanggal_lahir' => !empty($row['tanggal_lahir']) ? substr((string)$row['tanggal_lahir'], 0, 10) : null,
                    'agama' => $row['agama_id_str'] ?? 'Islam',
                    'student_class_id' => $classId,
                    'alamat' => $row['alamat_jalan'] ?? null,
                    'nama_ayah' => $row['nama_ayah'] ?? null,
                    'pekerjaan_ayah' => $row['pekerjaan_ayah_id_str'] ?? null,
                    'nama_ibu' => $row['nama_ibu'] ?? null,
                    'pekerjaan_ibu' => $row['pekerjaan_ibu_id_str'] ?? null,
                    'nama_wali' => $row['nama_wali'] ?? null,
                    'no_hp' => $row['nomor_telepon_seluler'] ?? ($row['nomor_telepon_rumah'] ?? null),
                    'status' => 'Aktif',
                ];

                // 3. Cari data siswa di database (Multi-layer matching: NISN -> NIK -> NIPD -> Nama & Rombel)
                $existing = null;
                if (!empty($nisn) && isset($existingByNisn[$nisn])) {
                    $existing = $existingByNisn[$nisn];
                } elseif (!empty($nik) && isset($existingByNik[$nik])) {
                    $existing = $existingByNik[$nik];
                } elseif (!empty($nipd) && isset($existingByNipd[$nipd])) {
                    $existing = $existingByNipd[$nipd];
                } elseif ($classId) {
                    $existing = Student::where('nama', $nama)
                        ->where('student_class_id', $classId)
                        ->first();
                }

                if ($existing) {
                    // Deteksi apakah siswa naik kelas / pindah rombel
                    if ($classId && $existing->student_class_id != $classId) {
                        $stats['class_changed']++;
                    }

                    // Replace / Update in place data siswa yang ada
                    $existing->update($data);
                    $syncedStudentIds[] = $existing->id;
                    $stats['updated']++;
                } else {
                    $data['nisn'] = $nisn;
                    $created = Student::create($data);
                    $syncedStudentIds[] = $created->id;

                    if ($nisn) $existingByNisn[$nisn] = $created;
                    if ($nik) $existingByNik[$nik] = $created;
                    if ($nipd) $existingByNipd[$nipd] = $created;
                    $stats['created']++;
                }

                $stats['total']++;
            }

            // 4. HAPUS PERMANEN SISWA YANG TIDAK ADA DI DAPODIK (Mutasi Keluar / Pindah Sekolah / Lulus)
            if ($pruneMissing && !empty($syncedStudentIds)) {
                $missingQuery = Student::whereNotIn('id', $syncedStudentIds);
                $missingCount = $missingQuery->count();

                if ($missingCount > 0) {
                    $missingQuery->delete();
                    $stats['deleted'] = $missingCount;
                }
            }

            return $stats;
        });
    }

    /**
     * Ekstrak angka tingkat kelas dari nama rombel (e.g. "Kelas IIIB" -> 3).
     */
    private static function extractTingkat(string $namaRombel): int
    {
        if (preg_match('/VI/i', $namaRombel)) return 6;
        if (preg_match('/IV/i', $namaRombel)) return 4;
        if (preg_match('/V/i', $namaRombel)) return 5;
        if (preg_match('/III/i', $namaRombel)) return 3;
        if (preg_match('/II/i', $namaRombel)) return 2;
        if (preg_match('/I/i', $namaRombel)) return 1;
        if (preg_match('/6/', $namaRombel)) return 6;
        if (preg_match('/5/', $namaRombel)) return 5;
        if (preg_match('/4/', $namaRombel)) return 4;
        if (preg_match('/3/', $namaRombel)) return 3;
        if (preg_match('/2/', $namaRombel)) return 2;
        if (preg_match('/1/', $namaRombel)) return 1;
        return 1;
    }
}
