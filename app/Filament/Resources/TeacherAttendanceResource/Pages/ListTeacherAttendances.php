<?php

namespace App\Filament\Resources\TeacherAttendanceResource\Pages;

use App\Filament\Resources\TeacherAttendanceResource;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;

class ListTeacherAttendances extends ListRecords
{
    protected static string $resource = TeacherAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cetakRekap')
                ->label('Cetak Rekap Presensi')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->modalHeading('Cetak Laporan Rekapitulasi Presensi GTK')
                ->modalDescription('Pilih periode bulan dan tahun untuk mencetak dokumen format resmi kedinasan SDN Cinanggerang I siap cetak / simpan PDF.')
                ->modalSubmitActionLabel('Buka Lembar Cetak')
                ->form([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('bulan')
                            ->label('Pilih Bulan')
                            ->options([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                            ])
                            ->default(now()->month)
                            ->required(),
                        Forms\Components\Select::make('tahun')
                            ->label('Pilih Tahun')
                            ->options([
                                now()->subYear()->year => now()->subYear()->year,
                                now()->year => now()->year,
                                now()->addYear()->year => now()->addYear()->year,
                            ])
                            ->default(now()->year)
                            ->required(),
                    ]),
                ])
                ->action(function (array $data) {
                    return redirect()->to(route('admin.presensi.cetak-rekap', [
                        'bulan' => $data['bulan'],
                        'tahun' => $data['tahun'],
                    ]));
                }),

            Actions\Action::make('lihatRekap')
                ->label('Tabel Rekap Bulanan')
                ->icon('heroicon-o-chart-bar')
                ->color('info')
                ->form([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('bulan')
                            ->label('Pilih Bulan')
                            ->options([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                            ])
                            ->default(now()->month)
                            ->required(),
                        Forms\Components\Select::make('tahun')
                            ->label('Pilih Tahun')
                            ->options([
                                now()->subYear()->year => now()->subYear()->year,
                                now()->year => now()->year,
                                now()->addYear()->year => now()->addYear()->year,
                            ])
                            ->default(now()->year)
                            ->required(),
                    ]),
                ])
                ->modalHeading('Rekapitulasi Kehadiran Bulanan Seluruh GTK')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup')
                ->modalContent(function (array $data = []): \Illuminate\Contracts\View\View {
                    $bulan = (int) ($data['bulan'] ?? now()->month);
                    $tahun = (int) ($data['tahun'] ?? now()->year);
                    $namaBulan = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                    ][$bulan] ?? 'Bulan ' . $bulan;
                    $periodLabel = "{$namaBulan} {$tahun}";

                    $teachers = Teacher::where('is_active', true)->orderBy('nama')->get();
                    $attendances = TeacherAttendance::whereYear('tanggal', $tahun)
                        ->whereMonth('tanggal', $bulan)
                        ->get();

                    $teachersRecap = $teachers->map(function ($t) use ($attendances) {
                        $userAtts = $attendances->where('teacher_id', $t->id);
                        $hadirTepat = $userAtts->where('status', 'Hadir Tepat Waktu')->count();
                        $hadirTerlambat = $userAtts->where('status', 'Hadir Terlambat')->count();
                        $dinasLuar = $userAtts->whereIn('status', ['Dinas Luar', 'Dinas Luar / Pelatihan'])->count();
                        $sakit = $userAtts->where('status', 'Sakit (SKD)')->count();
                        $izin = $userAtts->where('status', 'Izin Resmi')->count();
                        $alpa = $userAtts->where('status', 'Tanpa Keterangan (Alpa)')->count();
                        $totalHari = $userAtts->count();

                        $efektif = $hadirTepat + $hadirTerlambat + $dinasLuar;
                        $persentase = $totalHari > 0 ? round(($efektif / $totalHari) * 100) : 100;

                        return [
                            'nama' => $t->nama,
                            'jabatan' => $t->jabatan,
                            'hadir_tepat' => $hadirTepat,
                            'hadir_terlambat' => $hadirTerlambat,
                            'dinas_luar' => $dinasLuar,
                            'sakit' => $sakit,
                            'izin' => $izin,
                            'alpa' => $alpa,
                            'total_hari' => $totalHari,
                            'persentase' => $persentase,
                        ];
                    });

                    return view('filament.modals.attendance-monthly-recap', [
                        'periodLabel' => $periodLabel,
                        'teachersRecap' => $teachersRecap,
                        'bulan' => $bulan,
                        'tahun' => $tahun,
                    ]);
                }),

            Actions\CreateAction::make()->label('Catat Presensi Guru'),
        ];
    }
}
