<?php

namespace App\Filament\Widgets;

use App\Models\PkksEvidence;
use App\Models\PkksIndicator;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        // 1. Data Presensi Guru Hari Ini
        $totalTeachers = Teacher::count();
        $presentTeachers = TeacherAttendance::whereDate('tanggal', today())
            ->where('status', 'Hadir')
            ->count();

        // 2. Data Siswa
        $totalStudents = Student::count();
        $totalClasses = StudentClass::count();

        // 3. Data E-PKKS 2026
        $totalIndicators = PkksIndicator::count();
        $totalEvidences = PkksEvidence::count();

        return [
            Stat::make('Presensi Guru Hari Ini', $presentTeachers > 0 ? "{$presentTeachers} / {$totalTeachers} Hadir" : "{$totalTeachers} GTK Terdata")
                ->description('Status Kehadiran & Presensi Geofencing')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([8, 9, 10, 11, 11, 10, $totalTeachers])
                ->color('success'),

            Stat::make('Presensi & Populasi Siswa', "{$totalStudents} Siswa Aktif")
                ->description("{$totalClasses} Rombongan Belajar (Kelas I - VI)")
                ->descriptionIcon('heroicon-m-academic-cap')
                ->chart([24, 25, 27, 28, 29, 30, $totalStudents])
                ->color('primary'),

            Stat::make('Status Dokumen E-PKKS 2026', "{$totalIndicators} / 12 Indikator")
                ->description("{$totalEvidences}+ Bukti Fisik Digital Siap Dinilai")
                ->descriptionIcon('heroicon-m-shield-check')
                ->chart([3, 6, 9, 11, 12, 12, 12])
                ->color('amber'),
        ];
    }
}
