<?php

namespace App\Filament\Pages;

use App\Models\SchoolHoliday;
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

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['Admin', 'Kepala Sekolah']);
    }

    protected static string $view = 'filament.pages.monitoring-presensi-guru';

    public string $activeTab = 'harian'; // 'harian' atau 'bulanan'

    // Parameter Harian
    public string $selectedDate;

    // Parameter Bulanan
    public int $selectedMonth;
    public int $selectedYear;

    public function mount(): void
    {
        $this->selectedDate = now()->setTimezone('Asia/Jakarta')->toDateString();
        $this->selectedMonth = (int) now()->setTimezone('Asia/Jakarta')->format('m');
        $this->selectedYear = (int) now()->setTimezone('Asia/Jakarta')->format('Y');
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    /**
     * Cek apakah tanggal harian terpilih merupakan hari libur resmi
     */
    public function getSelectedDateHolidayProperty(): ?SchoolHoliday
    {
        return SchoolHoliday::isHoliday($this->selectedDate);
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
                'attendance_type' => $record?->attendance_type ?? 'Reguler',
                'jam_masuk' => $record?->jam_masuk ?? '-',
                'jam_pulang' => $record?->jam_pulang ?? '-',
                'jarak_meter' => $record?->jarak_meter,
                'is_in_radius' => $record?->is_in_radius ?? false,
                'jarak_pulang_meter' => $record?->jarak_pulang_meter,
                'is_in_radius_pulang' => $record?->is_in_radius_pulang ?? false,
                'metode' => $record?->metode ?? '-',
                'keterangan' => $record?->keterangan ?? '-',
                'foto_bukti' => $record?->foto_bukti,
                'foto_bukti_url' => $record?->foto_bukti_url,
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
        $hadir = $daily->filter(function ($item) {
            $s = strtolower($item->status);
            return str_contains($s, 'tepat') || str_contains($s, 'terlambat') || str_contains($s, 'telat') || str_contains($s, 'dinas');
        })->count();

        $tepatWaktu = $daily->filter(fn ($item) => str_contains(strtolower($item->status), 'tepat'))->count();
        $terlambat = $daily->filter(fn ($item) => str_contains(strtolower($item->status), 'terlambat') || str_contains(strtolower($item->status), 'telat'))->count();
        $dinasLuar = $daily->filter(fn ($item) => str_contains(strtolower($item->status), 'dinas'))->count();
        $izinSakit = $daily->filter(function ($item) {
            $s = strtolower($item->status);
            return str_contains($s, 'sakit') || str_contains($s, 'izin') || str_contains($s, 'cuti');
        })->count();
        $belum = $daily->where('status', 'Belum Presensi')->count();

        return [
            'total' => $total,
            'hadir' => $hadir,
            'tepat_waktu' => $tepatWaktu,
            'terlambat' => $terlambat,
            'dinas_luar' => $dinasLuar,
            'izin_sakit' => $izinSakit,
            'belum' => $belum,
            'persen' => $total > 0 ? round(($hadir / $total) * 100) : 0,
        ];
    }

    /**
     * Mengambil rekapitulasi presensi bulanan seluruh guru dengan formula akurat & pengurangan hari libur resmi
     */
    public function getMonthlyRecapProperty(): Collection
    {
        $month = $this->selectedMonth;
        $year = $this->selectedYear;
        $teachers = Teacher::where('is_active', true)->orderBy('urutan')->get();

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();

        // 1. Hitung hari efektif kerja (Hari kalender dikurangi hari Minggu)
        $nonSundayDays = 0;
        $temp = $startOfMonth->copy();
        while ($temp <= $endOfMonth) {
            if ($temp->dayOfWeek !== Carbon::SUNDAY) {
                $nonSundayDays++;
            }
            $temp->addDay();
        }

        // 2. Kurangi dengan hari libur nasional / cuti bersama / libur sekolah yang terdaftar
        $holidaysCount = SchoolHoliday::countHolidaysBetween($startOfMonth, $endOfMonth);
        $effectiveDays = max(1, $nonSundayDays - $holidaysCount);

        return $teachers->map(function ($teacher) use ($startOfMonth, $endOfMonth, $effectiveDays, $holidaysCount) {
            // Ambil seluruh rekor presensi guru pada bulan dan tahun terpilih
            $records = TeacherAttendance::where('teacher_id', $teacher->id)
                ->whereBetween('tanggal', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
                ->get();

            // Klasifikasi kehadiran fleksibel & akurat
            $tepat = $records->filter(fn ($r) => str_contains(strtolower($r->status), 'tepat'))->count();
            $telat = $records->filter(fn ($r) => str_contains(strtolower($r->status), 'terlambat') || str_contains(strtolower($r->status), 'telat'))->count();
            $dinas = $records->filter(fn ($r) => str_contains(strtolower($r->status), 'dinas') || ($r->attendance_type === 'Dinas Luar'))->count();
            $sakit = $records->filter(fn ($r) => str_contains(strtolower($r->status), 'sakit'))->count();
            $izin = $records->filter(fn ($r) => str_contains(strtolower($r->status), 'izin'))->count();
            $cuti = $records->filter(fn ($r) => str_contains(strtolower($r->status), 'cuti'))->count();
            $alpa = $records->filter(fn ($r) => str_contains(strtolower($r->status), 'alpa') || str_contains(strtolower($r->status), 'tanpa keterangan'))->count();

            // Total Hadir resmi = Hadir Tepat + Hadir Terlambat + Dinas Luar (Dinas Luar bertugas resmi)
            $totalHadir = $tepat + $telat + $dinas;

            // Persentase kehadiran terhadap target hari efektif
            $persentase = $effectiveDays > 0 ? min(100, round(($totalHadir / $effectiveDays) * 100, 1)) : 0;

            return (object) [
                'teacher' => $teacher,
                'tepat' => $tepat,
                'telat' => $telat,
                'dinas' => $dinas,
                'sakit' => $sakit,
                'izin' => $izin,
                'cuti' => $cuti,
                'alpa' => $alpa,
                'total_hadir' => $totalHadir,
                'hari_libur' => $holidaysCount,
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
