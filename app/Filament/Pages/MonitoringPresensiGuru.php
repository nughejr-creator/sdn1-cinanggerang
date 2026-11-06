<?php

namespace App\Filament\Pages;

use App\Models\SchoolProfile;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class MonitoringPresensiGuru extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationGroup = 'Presensi & Kehadiran Digital';
    protected static ?string $navigationLabel = 'Monitoring & Rekap Presensi';
    protected static ?string $title = 'Monitoring & Rekap Presensi Guru';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.monitoring-presensi-guru';

    public string $activeTab = 'harian'; // 'harian' atau 'bulanan'

    // Parameter Harian
    public string $selectedDate;

    // Parameter Bulanan
    public int $selectedMonth;
    public int $selectedYear;

    public function mount(): void
    {
        $this->selectedDate = now()->toDateString();
        $this->selectedMonth = (int) now()->format('m');
        $this->selectedYear = (int) now()->format('Y');
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    /**
     * Mengambil data kehadiran harian seluruh 11 guru pada tanggal terpilih
     */
    public function getDailyAttendanceProperty(): Collection
    {
        $date = $this->selectedDate;
        $teachers = Teacher::where('is_active', true)->orderBy('urutan')->get();
        $attendances = TeacherAttendance::whereDate('tanggal', $date)->get()->keyBy('teacher_id');

        return $teachers->map(function ($teacher) use ($attendances) {
            $record = $attendances->get($teacher->id);
            return (object) [
                'teacher' => $teacher,
                'record' => $record,
                'status' => $record?->status ?? 'Belum Presensi',
                'jam_masuk' => $record?->jam_masuk ?? '-',
                'jam_pulang' => $record?->jam_pulang ?? '-',
                'jarak_meter' => $record?->jarak_meter,
                'is_in_radius' => $record?->is_in_radius ?? false,
                'jarak_pulang_meter' => $record?->jarak_pulang_meter,
                'is_in_radius_pulang' => $record?->is_in_radius_pulang ?? false,
                'metode' => $record?->metode ?? '-',
                'keterangan' => $record?->keterangan ?? '-',
                'foto_bukti' => $record?->foto_bukti,
            ];
        });
    }

    /**
     * Menghitung ringkasan statistik harian
     */
    public function getDailyStatsProperty(): array
    {
        $daily = $this->dailyAttendance;
        $total = $daily->count();
        $hadir = $daily->whereIn('status', ['Hadir Tepat Waktu', 'Hadir Terlambat'])->count();
        $tepatWaktu = $daily->where('status', 'Hadir Tepat Waktu')->count();
        $terlambat = $daily->where('status', 'Hadir Terlambat')->count();
        $izinSakit = $daily->whereIn('status', ['Sakit (SKD)', 'Izin Resmi', 'Dinas Luar / Pelatihan', 'Cuti'])->count();
        $belum = $daily->where('status', 'Belum Presensi')->count();

        return [
            'total' => $total,
            'hadir' => $hadir,
            'tepat_waktu' => $tepatWaktu,
            'terlambat' => $terlambat,
            'izin_sakit' => $izinSakit,
            'belum' => $belum,
            'persen' => $total > 0 ? round(($hadir / $total) * 100) : 0,
        ];
    }

    /**
     * Mengambil rekapitulasi presensi bulanan seluruh guru
     */
    public function getMonthlyRecapProperty(): Collection
    {
        $month = $this->selectedMonth;
        $year = $this->selectedYear;
        $teachers = Teacher::where('is_active', true)->orderBy('urutan')->get();

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();

        // Hitung estimasi hari efektif kerja (Senin - Jumat/Sabtu)
        $effectiveDays = 0;
        $temp = $startOfMonth->copy();
        while ($temp <= $endOfMonth) {
            if (! in_array($temp->dayOfWeek, [Carbon::SUNDAY])) {
                $effectiveDays++;
            }
            $temp->addDay();
        }

        return $teachers->map(function ($teacher) use ($month, $year, $effectiveDays) {
            $records = TeacherAttendance::where('teacher_id', $teacher->id)
                ->whereMonth('tanggal', $month)
                ->whereYear('tanggal', $year)
                ->get();

            $tepat = $records->where('status', 'Hadir Tepat Waktu')->count();
            $telat = $records->where('status', 'Hadir Terlambat')->count();
            $sakit = $records->where('status', 'Sakit (SKD)')->count();
            $izin = $records->where('status', 'Izin Resmi')->count();
            $dinas = $records->where('status', 'Dinas Luar / Pelatihan')->count();
            $alpa = $records->where('status', 'Tanpa Keterangan (Alpa)')->count();
            $totalHadir = $tepat + $telat;

            $persentase = $effectiveDays > 0 ? min(100, round(($totalHadir / $effectiveDays) * 100, 1)) : 0;

            return (object) [
                'teacher' => $teacher,
                'tepat' => $tepat,
                'telat' => $telat,
                'sakit' => $sakit,
                'izin' => $izin,
                'dinas' => $dinas,
                'alpa' => $alpa,
                'total_hadir' => $totalHadir,
                'hari_efektif' => $effectiveDays,
                'persentase' => $persentase,
            ];
        });
    }

    public function getSchoolProfileProperty(): ?SchoolProfile
    {
        return SchoolProfile::first();
    }
}
