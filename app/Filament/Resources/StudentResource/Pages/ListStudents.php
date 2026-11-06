<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\Student;
use App\Services\DapodikStudentImporter;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // 1. Tombol Sinkronisasi Cepat 219 Siswa dari File Dapodik Bawaan
            Actions\Action::make('syncPreloaded')
                ->label('⚡ Sinkronkan 219 Siswa Dapodik')
                ->icon('heroicon-o-arrow-path')
                ->color('amber')
                ->requiresConfirmation()
                ->modalHeading('Sinkronisasi Data Siswa dari File Dapodik')
                ->modalDescription('Sistem akan membaca file daftar Peserta Didik Dapodik (219 siswa) yang tersimpan di server dan memasukkannya ke database beserta rombel kelas masing-masing.')
                ->modalSubmitActionLabel('Ya, Sinkronkan Sekarang')
                ->action(function () {
                    try {
                        $stats = DapodikStudentImporter::autoImportPreloaded();
                        $source = $stats['source_file'] ?? 'File Dapodik';

                        Notification::make()
                            ->title('Sinkronisasi Dapodik Berhasil! 🎉')
                            ->body("Berhasil memproses {$stats['total']} siswa dari [{$source}]. Baru: {$stats['created']}, Diperbarui: {$stats['updated']}.")
                            ->success()
                            ->duration(10000)
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Gagal Melakukan Sinkronisasi')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            // 2. Menu Import Berdasarkan File Excel/CSV Unduhan Dapodik (Untuk Pemakaian Berkelanjutan)
            Actions\Action::make('importDapodik')
                ->label('📥 Impor File Excel Dapodik')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->modalHeading('Impor Data Siswa (Format Baku Dapodik Kemendikbud)')
                ->modalDescription('Unggah file unduhan baku Peserta Didik dari aplikasi Dapodik (.xlsx atau .csv). Sistem akan secara cerdas memetakan NISN, NIK, NIPD, nama ortu, status PIP/KIP, dan memasukkan siswa ke rombel kelas yang sesuai secara otomatis.')
                ->modalSubmitActionLabel('Mulai Impor Data')
                ->form([
                    Forms\Components\FileUpload::make('file_dapodik')
                        ->label('Pilih Berkas Excel / CSV Dapodik')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            'text/csv',
                            'text/plain',
                        ])
                        ->disk('local')
                        ->directory('temp-dapodik-imports')
                        ->required()
                        ->preserveFilenames()
                        ->helperText('Format yang didukung: .xlsx atau .csv langsung hasil unduhan "Daftar Peserta Didik" dari Manajemen Dapodik.'),
                ])
                ->action(function (array $data) {
                    $relative = $data['file_dapodik'];
                    $fullPath = Storage::disk('local')->path($relative);

                    try {
                        $stats = DapodikStudentImporter::import($fullPath);

                        Notification::make()
                            ->title('Impor Data Siswa Selesai! 🎉')
                            ->body("Sukses memproses {$stats['total']} siswa. Data Baru: {$stats['created']}, Data Diperbarui: {$stats['updated']}.")
                            ->success()
                            ->duration(10000)
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Gagal Mengimpor File Excel')
                            ->body('Terjadi kendala saat membaca file: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    } finally {
                        @unlink($fullPath);
                    }
                }),

            Actions\CreateAction::make()->label('Tambah Siswa Manual'),
        ];
    }
}
