<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\StudentClass;
use App\Services\DapodikStudentImporter;

class StudentDataSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $stats = DapodikStudentImporter::autoImportPreloaded();
            if (($stats['total'] ?? 0) >= 200) {
                return;
            }
        } catch (\Throwable $e) {
            // Lanjutkan ke seeder dasar jika file belum terdeteksi
        }

        $classes = StudentClass::pluck('id', 'nama')->toArray();

        $students = [
            ['nama' => 'Adara Nailal Husna', 'nipd' => '212201001', 'nisn' => '3150199776', 'jk' => 'P', 'rombel' => 'Kelas VI', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'ADEEVA INARA FATHIYATURAHMA', 'nipd' => '232401001', 'nisn' => '3166078246', 'jk' => 'P', 'rombel' => 'Kelas IV', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'ADELIA PUTRI SALSABILAH', 'nipd' => '242501001', 'nisn' => '3171664327', 'jk' => 'P', 'rombel' => 'Kelas IIIA', 'tempat_lahir' => 'SUMEDANG', 'layak_pip' => true],
            ['nama' => 'Adifa Ashalina Fauza', 'nipd' => '252601001', 'nisn' => '3197343377', 'jk' => 'P', 'rombel' => 'Kelas IIA', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'ADIRA NURAZIZAH', 'nipd' => '252601025', 'nisn' => '3180171172', 'jk' => 'P', 'rombel' => 'Kelas IIB', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'ADRENA NASHA SHAQENA', 'nipd' => '262701001', 'nisn' => '3192643178', 'jk' => 'P', 'rombel' => 'Kelas I', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'Adzriel Rafiq Syahputra', 'nipd' => '242501002', 'nisn' => '3177802775', 'jk' => 'L', 'rombel' => 'Kelas IIIA', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'AFIFA PUTRI KURNIAWAN', 'nipd' => '242501003', 'nisn' => '3177519477', 'jk' => 'P', 'rombel' => 'Kelas IIIB', 'tempat_lahir' => 'SUMEDANG', 'layak_pip' => false],
            ['nama' => 'Afiqah Dzulfa Sidqiawati', 'nipd' => '212201002', 'nisn' => '3148201622', 'jk' => 'P', 'rombel' => 'Kelas VI', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'AGUNG ALFARIDZI RAIHAN', 'nipd' => '232403047', 'nisn' => '0139059735', 'jk' => 'L', 'rombel' => 'Kelas VI', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'AGUS SAEPUL AKBAR', 'nipd' => '232401002', 'nisn' => '3161829155', 'jk' => 'L', 'rombel' => 'Kelas IV', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'AKBAR KURNIAWAN', 'nipd' => '212201003', 'nisn' => '3146374899', 'jk' => 'L', 'rombel' => 'Kelas VI', 'tempat_lahir' => 'SUMEDANG', 'layak_pip' => true],
            ['nama' => 'AL GIBRAN IRWAN DANI', 'nipd' => '242501004', 'nisn' => '3172790903', 'jk' => 'L', 'rombel' => 'Kelas IIIB', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'Alesha Oktavia El Feri', 'nipd' => '252601026', 'nisn' => '3195920757', 'jk' => 'P', 'rombel' => 'Kelas IIB', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'Alfahri khoirun Nizam', 'nipd' => '242501005', 'nisn' => '3178853706', 'jk' => 'L', 'rombel' => 'Kelas IIIA', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'Alika Meidina Putri', 'nipd' => '222301001', 'nisn' => '3167032774', 'jk' => 'P', 'rombel' => 'Kelas V', 'tempat_lahir' => 'Sumedang', 'layak_pip' => false],
            ['nama' => 'ALIKA NAZWA', 'nipd' => '252604052', 'nisn' => '0151672475', 'jk' => 'P', 'rombel' => 'Kelas V', 'tempat_lahir' => 'SUMEDANG', 'layak_pip' => true],
            ['nama' => 'Alvian Syah Muhamad Reza', 'nipd' => '232401003', 'nisn' => '3161935831', 'jk' => 'L', 'rombel' => 'Kelas IV', 'tempat_lahir' => 'Sumedang', 'layak_pip' => false],
            ['nama' => 'Anisa Febriani', 'nipd' => '212201004', 'nisn' => '3140421328', 'jk' => 'P', 'rombel' => 'Kelas VI', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'Anisa Maulida Zahra', 'nipd' => '232401004', 'nisn' => '3169625563', 'jk' => 'P', 'rombel' => 'Kelas IV', 'tempat_lahir' => 'Sumedang', 'layak_pip' => false],
            ['nama' => 'Azkayra Fitri Hamdani', 'nipd' => '222301005', 'nisn' => '3165419591', 'jk' => 'P', 'rombel' => 'Kelas V', 'tempat_lahir' => 'Sumedang', 'layak_pip' => false],
            ['nama' => 'ZACKY ASYRAFUL ANAM', 'nipd' => '212201031', 'nisn' => '3148391230', 'jk' => 'L', 'rombel' => 'Kelas VI', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'ZAFRAN ROYYAN ALMUZAKKI', 'nipd' => '222301027', 'nisn' => '3120437823', 'jk' => 'L', 'rombel' => 'Kelas V', 'tempat_lahir' => 'Sumedang', 'layak_pip' => false],
            ['nama' => 'ZAHRA NURHAFIFAH', 'nipd' => '232401035', 'nisn' => '3161023457', 'jk' => 'P', 'rombel' => 'Kelas IV', 'tempat_lahir' => 'SUMEDANG', 'layak_pip' => true],
            ['nama' => 'ZAKYA KHOIRUNISA', 'nipd' => '252601024', 'nisn' => '3190425215', 'jk' => 'P', 'rombel' => 'Kelas IIA', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'ZASKIA AULIA AZAHRA', 'nipd' => '232401036', 'nisn' => '3166177115', 'jk' => 'P', 'rombel' => 'Kelas IV', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
            ['nama' => 'ZENRA NIRWAN KUSNANDAR', 'nipd' => '222301028', 'nisn' => '3165495884', 'jk' => 'L', 'rombel' => 'Kelas V', 'tempat_lahir' => 'Sumedang', 'layak_pip' => false],
            ['nama' => 'ZEVA UMAIR RAFFASYA', 'nipd' => '252601045', 'nisn' => '3186291874', 'jk' => 'L', 'rombel' => 'Kelas IIB', 'tempat_lahir' => 'Sumedang', 'layak_pip' => false],
            ['nama' => 'ZIANNISA SALSABILA PUTRI', 'nipd' => '242501048', 'nisn' => '3185712512', 'jk' => 'P', 'rombel' => 'Kelas IIIA', 'tempat_lahir' => 'SUMEDANG', 'layak_pip' => true],
            ['nama' => 'ZIPANA SAHILA RAMADHANI', 'nipd' => '262701024', 'nisn' => '3207493215', 'jk' => 'P', 'rombel' => 'Kelas I', 'tempat_lahir' => 'Sumedang', 'layak_pip' => true],
        ];

        foreach ($students as $s) {
            Student::updateOrCreate(
                ['nisn' => $s['nisn']],
                [
                    'nama' => $s['nama'],
                    'nipd' => $s['nipd'],
                    'jenis_kelamin' => $s['jk'],
                    'student_class_id' => $classes[$s['rombel']] ?? null,
                    'tempat_lahir' => $s['tempat_lahir'],
                    'layak_pip' => $s['layak_pip'],
                    'status' => 'Aktif',
                ]
            );
        }
    }
}
