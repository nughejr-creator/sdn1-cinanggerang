<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DapodikStudentImporter
{
    /**
     * Cari file unduhan Dapodik yang pernah diunggah di sistem.
     */
    public static function getAvailableSourceFiles(): array
    {
        $patterns = [
            'C:/Users/USER/.gemini/antigravity/brain/a831f183-e308-4484-9ea0-0236671bad9a/.user_uploaded/*.xlsx',
            database_path('seeders/data/*.csv'),
            database_path('seeders/data/*.xlsx'),
            storage_path('app/temp-imports/*.xlsx'),
            storage_path('app/temp-imports/*.csv'),
        ];

        $found = [];
        foreach ($patterns as $pattern) {
            foreach (glob($pattern) as $file) {
                if (is_file($file)) {
                    $found[] = str_replace('\\', '/', $file);
                }
            }
        }
        return array_unique($found);
    }

    /**
     * Import dari file yang terdeteksi secara otomatis (mencari yang berisi data siswa terbanyak / 219 siswa).
     */
    public static function autoImportPreloaded(): array
    {
        $files = self::getAvailableSourceFiles();
        $bestFile = null;
        $maxStudents = 0;
        $bestStats = null;

        foreach ($files as $file) {
            try {
                $inspection = self::inspect($file);
                if ($inspection['is_student_data'] && $inspection['total_students'] > $maxStudents) {
                    $maxStudents = $inspection['total_students'];
                    $bestFile = $file;
                }
            } catch (\Throwable $e) {
                // Abaikan file yang bukan Excel/data siswa
            }
        }

        if (!$bestFile) {
            throw new \Exception("File Dapodik siswa tidak ditemukan di folder unggahan.");
        }

        $stats = self::import($bestFile);
        $stats['source_file'] = basename($bestFile);
        return $stats;
    }

    /**
     * Parse and import student data from XLSX or CSV file.
     */
    public static function import(string $filePath): array
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($extension === 'xlsx') {
            $rows = self::parseXlsx($filePath);
        } elseif (in_array($extension, ['csv', 'txt'])) {
            $rows = self::parseCsv($filePath);
        } else {
            throw new \Exception("Format file tidak didukung. Harap unggah file .xlsx atau .csv");
        }

        return self::processRows($rows, $filePath);
    }

    /**
     * Inspect file without saving, returning row counts and header mapping.
     */
    public static function inspect(string $filePath): array
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if ($extension === 'xlsx') {
            $rows = self::parseXlsx($filePath);
        } else {
            $rows = self::parseCsv($filePath);
        }

        $headerInfo = self::findHeaderRow($rows);
        $isStudentData = false;
        $totalStudents = 0;

        if ($headerInfo) {
            $isStudentData = true;
            $dataRows = array_slice($rows, $headerInfo['row_index'] + 1);
            foreach ($dataRows as $r) {
                if (!empty($r[$headerInfo['mapping']['nama'] ?? 0])) {
                    $totalStudents++;
                }
            }
        } else {
            // Check if raw format (e.g. part1.csv)
            foreach ($rows as $r) {
                if (count($r) >= 5 && is_numeric($r[0] ?? null) && !empty($r[1] ?? null)) {
                    $totalStudents++;
                }
            }
            if ($totalStudents > 0) {
                $isStudentData = true;
            }
        }

        return [
            'file' => basename($filePath),
            'total_rows' => count($rows),
            'is_student_data' => $isStudentData,
            'total_students' => $totalStudents,
            'has_header' => (bool)$headerInfo,
        ];
    }

    /**
     * Native XLSX parser using ZipArchive and SimpleXML without external packages.
     */
    public static function parseXlsx(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File tidak ditemukan: {$filePath}");
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \Exception("Tidak dapat membuka arsip file Excel (.xlsx).");
        }

        // 1. Read shared strings
        $sharedStrings = [];
        if (($idx = $zip->locateName('xl/sharedStrings.xml')) !== false) {
            $xmlContent = $zip->getFromIndex($idx);
            // Hapus namespace XML agar pemanggilan XPath / SimpleXML 100% konsisten
            $cleanXml = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $xmlContent);
            $xml = @simplexml_load_string($cleanXml);
            if ($xml) {
                foreach ($xml->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string)$si->t;
                    } elseif (isset($si->r)) {
                        $str = '';
                        foreach ($si->r as $r) {
                            $str .= (string)$r->t;
                        }
                        $sharedStrings[] = $str;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        // 2. Locate first worksheet
        $sheetFile = 'xl/worksheets/sheet1.xml';
        if ($zip->locateName($sheetFile) === false) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (str_starts_with($name, 'xl/worksheets/sheet') && str_ends_with($name, '.xml')) {
                    $sheetFile = $name;
                    break;
                }
            }
        }

        $sheetContent = $zip->getFromName($sheetFile);
        $zip->close();

        if (!$sheetContent) {
            throw new \Exception("Lembar kerja Excel (worksheet) tidak ditemukan.");
        }

        $cleanSheet = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $sheetContent);
        $xml = @simplexml_load_string($cleanSheet);
        if (!$xml || !isset($xml->sheetData)) {
            return [];
        }

        $rows = [];
        foreach ($xml->sheetData->row as $row) {
            $rowData = [];
            foreach ($row->c as $c) {
                $cellRef = (string)$c['r'];
                preg_match('/^([A-Z]+)/i', $cellRef, $matches);
                $colLetters = $matches[1] ?? 'A';
                $colIndex = self::colLetterToIndex($colLetters);

                $type = (string)$c['t'];
                $val = '';
                if ($type === 's') {
                    $sIdx = (int)$c->v;
                    $val = $sharedStrings[$sIdx] ?? '';
                } elseif ($type === 'inlineStr') {
                    $val = (string)($c->is->t ?? '');
                } else {
                    $val = (string)($c->v ?? '');
                }

                $rowData[$colIndex] = trim($val);
            }

            if (!empty($rowData)) {
                $maxKey = max(array_keys($rowData));
                $cleanRow = [];
                for ($k = 0; $k <= $maxKey; $k++) {
                    $cleanRow[$k] = $rowData[$k] ?? '';
                }
                $rows[] = $cleanRow;
            }
        }

        return $rows;
    }

    /**
     * Parse CSV files with automatic delimiter detection.
     */
    public static function parseCsv(string $filePath): array
    {
        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new \Exception("Gagal membuka file CSV.");
        }

        $rows = [];
        $firstLine = fgets($handle);
        rewind($handle);

        $delimiter = str_contains((string)$firstLine, ';') ? ';' : ',';

        while (($data = fgetcsv($handle, 8192, $delimiter)) !== false) {
            $rows[] = array_map('trim', $data);
        }

        fclose($handle);
        return $rows;
    }

    /**
     * Convert Excel column letters to 0-based index.
     */
    private static function colLetterToIndex(string $letters): int
    {
        $letters = strtoupper($letters);
        $len = strlen($letters);
        $index = 0;
        for ($i = 0; $i < $len; $i++) {
            $index = $index * 26 + (ord($letters[$i]) - ord('A') + 1);
        }
        return $index - 1;
    }

    /**
     * Find header row index and map standard Dapodik column names.
     */
    private static function findHeaderRow(array $rows): ?array
    {
        foreach ($rows as $rowIndex => $cols) {
            $colsLower = array_map(fn($v) => strtolower(trim((string)$v)), $cols);

            $hasNama = false;
            $hasNisn = false;
            $hasJk = false;
            $hasRombel = false;

            foreach ($colsLower as $col) {
                if ($col === 'nama' || str_contains($col, 'nama peserta didik') || str_contains($col, 'nama siswa')) {
                    $hasNama = true;
                }
                if ($col === 'nisn' || str_contains($col, 'nisn')) {
                    $hasNisn = true;
                }
                if ($col === 'jk' || str_contains($col, 'jenis kelamin')) {
                    $hasJk = true;
                }
                if (str_contains($col, 'rombel') || str_contains($col, 'kelas')) {
                    $hasRombel = true;
                }
            }

            // File Dapodik Peserta Didik WAJIB memiliki kolom Nama DAN NISN
            if ($hasNama && $hasNisn) {
                $mapping = [];
                foreach ($colsLower as $colIdx => $colName) {
                    $mapping[$colName] = $colIdx;
                }
                return [
                    'row_index' => $rowIndex,
                    'mapping' => $mapping,
                    'raw' => $cols,
                ];
            }
        }
        return null;
    }

    /**
     * Process extracted rows and update/create database records.
     */
    private static function processRows(array $rows, string $sourcePath = ''): array
    {
        $headerInfo = self::findHeaderRow($rows);
        $classes = StudentClass::pluck('id', 'nama')->toArray();

        $stats = [
            'total' => 0,
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'classes' => [],
            'errors' => [],
        ];

        $exportedCsvRows = [];

        if ($headerInfo) {
            $map = $headerInfo['mapping'];
            $dataRows = array_slice($rows, $headerInfo['row_index'] + 1);

            // Flexible column finder
            $getVal = function(array $row, array $keywords, array $exclude = []) use ($map) {
                foreach ($map as $colName => $colIdx) {
                    $matched = false;
                    foreach ($keywords as $kw) {
                        if ($colName === $kw || str_contains($colName, $kw)) {
                            $matched = true;
                            break;
                        }
                    }
                    if ($matched) {
                        $isExcluded = false;
                        foreach ($exclude as $ex) {
                            if (str_contains($colName, $ex)) {
                                $isExcluded = true;
                                break;
                            }
                        }
                        if (!$isExcluded && isset($row[$colIdx])) {
                            return trim((string)$row[$colIdx]);
                        }
                    }
                }
                return null;
            };

            foreach ($dataRows as $r) {
                $nama = $getVal($r, ['nama'], ['ayah', 'ibu', 'wali', 'sekolah', 'kip', 'kks']);
                if (empty($nama) || strcasecmp($nama, 'nama') === 0 || str_starts_with(strtolower($nama), 'total') || str_starts_with(strtolower($nama), 'jumlah')) {
                    continue;
                }

                // Abaikan jika baris merupakan nama komponen PKKS / bukan nama siswa
                if (in_array(strtolower($nama), ['kepribadian', 'profesional', 'sosial', 'komponen', 'indikator', 'standar'])) {
                    continue;
                }

                $nisn = $getVal($r, ['nisn']);
                $nipd = $getVal($r, ['nipd', 'no induk']);
                $nik = $getVal($r, ['nik'], ['ayah', 'ibu', 'wali']);
                $jkRaw = $getVal($r, ['jk', 'jenis kelamin']);
                $jk = (strtoupper(substr((string)$jkRaw, 0, 1)) === 'P') ? 'P' : 'L';
                $tempatLahir = $getVal($r, ['tempat lahir']);
                $tanggalLahirRaw = $getVal($r, ['tanggal lahir', 'tgl lahir']);
                $tanggalLahir = self::parseDate($tanggalLahirRaw);

                $rombelStr = $getVal($r, ['rombel saat ini', 'rombel', 'kelas']);
                $studentClassId = self::resolveClassId($rombelStr, $classes);

                $layakPipRaw = $getVal($r, ['layak pip']);
                $layakPip = in_array(strtolower((string)$layakPipRaw), ['ya', '1', 'true', 'layak', 'penerima']);

                $kipRaw = $getVal($r, ['penerima kip']);
                $penerimaKip = in_array(strtolower((string)$kipRaw), ['ya', '1', 'true']);

                $pkhRaw = $getVal($r, ['penerima kps', 'penerima pkh', 'kps/kip']);
                $penerimaPkh = in_array(strtolower((string)$pkhRaw), ['ya', '1', 'true']);

                $data = [
                    'nama' => $nama,
                    'nipd' => $nipd ?: null,
                    'nik' => $nik ?: null,
                    'jenis_kelamin' => $jk,
                    'tempat_lahir' => $tempatLahir ?: null,
                    'tanggal_lahir' => $tanggalLahir,
                    'student_class_id' => $studentClassId,
                    'layak_pip' => $layakPip,
                    'penerima_kip' => $penerimaKip,
                    'penerima_pkh' => $penerimaPkh,
                    'alasan_layak_pip' => $getVal($r, ['alasan layak pip']) ?: null,
                    'alamat' => $getVal($r, ['alamat', 'dusun', 'jalan']) ?: null,
                    'rt' => $getVal($r, ['rt']) ?: null,
                    'rw' => $getVal($r, ['rw']) ?: null,
                    'dusun' => $getVal($r, ['dusun']) ?: null,
                    'desa' => $getVal($r, ['kelurahan', 'desa']) ?: null,
                    'kecamatan' => $getVal($r, ['kecamatan']) ?: null,
                    'no_hp' => $getVal($r, ['hp', 'telepon']) ?: null,
                    'nama_ayah' => $getVal($r, ['nama ayah']) ?: null,
                    'pekerjaan_ayah' => $getVal($r, ['pekerjaan ayah']) ?: null,
                    'nama_ibu' => $getVal($r, ['nama ibu']) ?: null,
                    'pekerjaan_ibu' => $getVal($r, ['pekerjaan ibu']) ?: null,
                    'nama_wali' => $getVal($r, ['nama wali']) ?: null,
                    'status' => 'Aktif',
                ];

                $search = [];
                if (!empty($nisn)) {
                    $search = ['nisn' => $nisn];
                } elseif (!empty($nipd)) {
                    $search = ['nipd' => $nipd];
                } else {
                    $search = ['nama' => $nama, 'student_class_id' => $studentClassId];
                }

                $existing = Student::where($search)->first();
                if ($existing) {
                    $existing->update($data);
                    $stats['updated']++;
                } else {
                    $data['nisn'] = $nisn ?: null;
                    Student::create($data);
                    $stats['created']++;
                }

                $stats['total']++;
                if ($rombelStr && !in_array($rombelStr, $stats['classes'])) {
                    $stats['classes'][] = $rombelStr;
                }

                $exportedCsvRows[] = array_merge([$stats['total']], $data, ['rombel_nama' => $rombelStr]);
            }
        } else {
            // Raw 68-column fallback (matches Dapodik standard export)
            foreach ($rows as $r) {
                if (count($r) < 5 || !is_numeric($r[0])) {
                    continue;
                }
                $nama = $r[1] ?? '';
                if (empty($nama)) continue;

                $nisn = $r[4] ?? null;
                $rombelStr = $r[42] ?? ($r[37] ?? '');
                $studentClassId = self::resolveClassId($rombelStr, $classes);
                $layakPip = strtolower($r[53] ?? '') === 'ya';

                $data = [
                    'nama' => $nama,
                    'nipd' => $r[2] ?? null,
                    'jenis_kelamin' => (strtoupper(substr((string)($r[3] ?? 'L'), 0, 1)) === 'P') ? 'P' : 'L',
                    'tempat_lahir' => $r[5] ?? null,
                    'tanggal_lahir' => self::parseDate($r[6] ?? null),
                    'nik' => $r[7] ?? null,
                    'alamat' => $r[9] ?? null,
                    'rt' => $r[10] ?? null,
                    'rw' => $r[11] ?? null,
                    'dusun' => $r[12] ?? null,
                    'desa' => $r[13] ?? null,
                    'kecamatan' => $r[14] ?? null,
                    'no_hp' => $r[19] ?? null,
                    'nama_ayah' => $r[24] ?? null,
                    'pekerjaan_ayah' => $r[27] ?? null,
                    'nama_ibu' => $r[30] ?? null,
                    'pekerjaan_ibu' => $r[33] ?? null,
                    'nama_wali' => $r[36] ?? null,
                    'student_class_id' => $studentClassId,
                    'layak_pip' => $layakPip,
                    'alasan_layak_pip' => $r[54] ?? null,
                    'status' => 'Aktif',
                ];

                $criteria = $nisn ? ['nisn' => $nisn] : ['nama' => $nama];
                $existing = Student::where($criteria)->first();
                if ($existing) {
                    $existing->update($data);
                    $stats['updated']++;
                } else {
                    $data['nisn'] = $nisn;
                    Student::create($data);
                    $stats['created']++;
                }
                $stats['total']++;
                if ($rombelStr && !in_array($rombelStr, $stats['classes'])) {
                    $stats['classes'][] = $rombelStr;
                }
            }
        }

        return $stats;
    }

    /**
     * Resolve class ID from rombel string.
     */
    private static function resolveClassId(?string $rombelStr, array &$classes): ?int
    {
        if (empty($rombelStr)) {
            return null;
        }

        $clean = trim($rombelStr);

        // Normalize format
        $normalized = preg_replace('/\s+/', ' ', $clean);
        $normalized = str_ireplace(['kelas 1', 'kelas 01', 'tingkat 1'], 'Kelas I', $normalized);
        $normalized = str_ireplace(['kelas 2 a', 'kelas 2a', 'kelas ii a', '2a'], 'Kelas IIA', $normalized);
        $normalized = str_ireplace(['kelas 2 b', 'kelas 2b', 'kelas ii b', '2b'], 'Kelas IIB', $normalized);
        $normalized = str_ireplace(['kelas 3 a', 'kelas 3a', 'kelas iii a', '3a'], 'Kelas IIIA', $normalized);
        $normalized = str_ireplace(['kelas 3 b', 'kelas 3b', 'kelas iii b', '3b'], 'Kelas IIIB', $normalized);
        $normalized = str_ireplace(['kelas 4', 'kelas 04', 'tingkat 4'], 'Kelas IV', $normalized);
        $normalized = str_ireplace(['kelas 5', 'kelas 05', 'tingkat 5'], 'Kelas V', $normalized);
        $normalized = str_ireplace(['kelas 6', 'kelas 06', 'tingkat 6'], 'Kelas VI', $normalized);

        if (isset($classes[$clean])) return $classes[$clean];
        if (isset($classes[$normalized])) return $classes[$normalized];

        foreach ($classes as $name => $id) {
            if (strcasecmp($name, $clean) === 0 || strcasecmp($name, $normalized) === 0) {
                return $id;
            }
        }

        $tingkat = 1;
        if (preg_match('/VI/i', $normalized)) $tingkat = 6;
        elseif (preg_match('/IV/i', $normalized)) $tingkat = 4;
        elseif (preg_match('/V/i', $normalized)) $tingkat = 5;
        elseif (preg_match('/III/i', $normalized)) $tingkat = 3;
        elseif (preg_match('/II/i', $normalized)) $tingkat = 2;
        elseif (preg_match('/I/i', $normalized)) $tingkat = 1;

        $newClass = StudentClass::firstOrCreate(
            ['nama' => $normalized],
            [
                'tingkat' => $tingkat,
                'wali_kelas' => 'Guru Kelas ' . $normalized,
                'kurikulum' => 'Kurikulum Merdeka',
                'tahun_ajaran' => '2026/2027',
            ]
        );

        $classes[$normalized] = $newClass->id;
        return $newClass->id;
    }

    /**
     * Parse date string or Excel serial number.
     */
    private static function parseDate($dateVal): ?string
    {
        if (empty($dateVal)) {
            return null;
        }

        if (is_numeric($dateVal) && (int)$dateVal > 10000 && (int)$dateVal < 60000) {
            $unixTime = ((int)$dateVal - 25569) * 86400;
            return gmdate('Y-m-d', $unixTime);
        }

        try {
            return Carbon::parse($dateVal)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
