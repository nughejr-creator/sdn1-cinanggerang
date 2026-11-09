<?php

namespace App\Filament\Pages;

use App\Models\SchoolProfile;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PresensiGpsGuru extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'Presensi & Kehadiran Digital';
    protected static ?string $navigationLabel = 'Presensi GPS Mandiri Guru';
    protected static ?string $title = 'Presensi GPS Mandiri Guru (Geofencing)';
    protected static ?int $navigationSort = 0;

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['Admin', 'Kepala Sekolah']);
    }

    protected static string $view = 'filament.pages.presensi-gps-guru';

    public ?int $selectedTeacherId = null;
    public ?float $schoolLat = null;
    public ?float $schoolLng = null;
    public int $maxRadius = 100;
    public ?TeacherAttendance $todayAttendance = null;

    public function mount(): void
    {
        // Alihkan presensi mandiri ke portal presensi digital resmi yang dilengkapi verifikasi kamera selfie
        redirect()->route('presensi.index');
    }

    public function loadTodayAttendance(): void
    {
        if ($this->selectedTeacherId) {
            $this->todayAttendance = TeacherAttendance::where('teacher_id', $this->selectedTeacherId)
                ->whereDate('tanggal', today())
                ->first();
        }
    }

    public function updatedSelectedTeacherId(): void
    {
        $this->loadTodayAttendance();
    }

    public function submitGpsAttendance(float $lat, float $lng, ?string $keterangan = null): void
    {
        if (! $this->selectedTeacherId) {
            Notification::make()->title('Silakan pilih nama guru terlebih dahulu.')->warning()->send();
            return;
        }

        $distance = $this->calculateHaversineDistance($this->schoolLat, $this->schoolLng, $lat, $lng);

        // KETENTUAN UTAMA: Jika di luar radius, tolak presensi!
        if ($distance > $this->maxRadius) {
            Notification::make()
                ->title('Presensi Ditolak! Di Luar Zona Sekolah')
                ->body("Posisi GPS Anda terdeteksi berjarak {$distance} meter dari SDN Cinanggerang I. Batas maksimal yang diizinkan adalah {$this->maxRadius} meter.")
                ->danger()
                ->persistent()
                ->send();
            return;
        }

        $existing = TeacherAttendance::where('teacher_id', $this->selectedTeacherId)
            ->whereDate('tanggal', today())
            ->first();

        // 1. Jika sudah ada presensi masuk tapi belum presensi pulang -> Catat Jam Pulang dengan Validasi GPS
        if ($existing) {
            if (! $existing->jam_pulang) {
                $jamPulang = now()->format('H:i:s');
                $pulangTepatWaktu = now()->format('H:i') >= '12:00';
                $keteranganPulang = $pulangTepatWaktu ? 'Pulang Tepat Waktu (>= 12:00)' : 'Pulang Lebih Awal (< 12:00)';

                $existing->update([
                    'jam_pulang' => $jamPulang,
                    'latitude_pulang' => $lat,
                    'longitude_pulang' => $lng,
                    'jarak_pulang_meter' => $distance,
                    'is_in_radius_pulang' => true,
                    'keterangan' => ($existing->keterangan ? $existing->keterangan . ' | ' : '') . $keteranganPulang,
                ]);

                $this->loadTodayAttendance();

                Notification::make()
                    ->title($pulangTepatWaktu ? 'Presensi Pulang Tepat Waktu! 🏠' : 'Presensi Pulang Tercatat ⚠️')
                    ->body("Jam pulang Anda tercatat pukul {$jamPulang} ({$keteranganPulang}). Terverifikasi di sekolah (Jarak: {$distance} meter).")
                    ->color($pulangTepatWaktu ? 'success' : 'warning')
                    ->send();
                return;
            }

            Notification::make()
                ->title('Presensi Hari Ini Sudah Lengkap')
                ->body('Anda sudah mencatat kehadiran Datang & Pulang hari ini.')
                ->info()
                ->send();
            return;
        }

        // 2. Presensi Datang / Masuk Baru
        $jamMasuk = now()->format('H:i:s');
        // Patokan datang tepat waktu: sampai dengan pukul 07:30
        $status = (now()->format('H:i') > '07:30') ? 'Hadir Terlambat' : 'Hadir Tepat Waktu';

        TeacherAttendance::create([
            'teacher_id' => $this->selectedTeacherId,
            'tanggal' => today(),
            'jam_masuk' => $jamMasuk,
            'status' => $status,
            'metode' => 'Presensi Mandiri Web',
            'latitude' => $lat,
            'longitude' => $lng,
            'jarak_meter' => $distance,
            'is_in_radius' => true,
            'keterangan' => $keterangan ?? "Presensi mandiri GPS (Jarak: {$distance} m)",
        ]);

        $this->loadTodayAttendance();

        Notification::make()
            ->title('Presensi Masuk Berhasil! 🎉')
            ->body("Status: {$status} pada pukul {$jamMasuk}. Terverifikasi di area sekolah (Jarak: {$distance} meter).")
            ->success()
            ->send();
    }

    private function calculateHaversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): int
    {
        $earthRadius = 6371000; // Radius bumi dalam satuan meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return (int) round($earthRadius * $c);
    }
}
