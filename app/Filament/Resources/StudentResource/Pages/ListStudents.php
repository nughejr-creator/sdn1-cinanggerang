<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\Student;
use App\Services\DapodikStudentImporter;
use App\Services\DapodikWebServiceSync;
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
            // 1. Menu Web Service Dapodik (Port 5774 / One-Click Sync)
            Actions\Action::make('syncWebService')
                ->label('🌐 Sinkron Web Service Dapodik')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->modalHeading('Sinkronisasi Web Service Dapodik (Port 5774)')
                ->modalDescription('Tarik data Peserta Didik dan Rombongan Belajar langsung dari Web Service Dapodik di komputer sekolah.')
                ->form([
                    Forms\Components\Placeholder::make('info_mode')
                        ->label('Panduan Mode Sinkronisasi')
                        ->content(new \Illuminate\Support\HtmlString('
                            <div class="text-sm space-y-2 text-gray-600 dark:text-gray-300">
                                <p><strong>Opsi 1 (Satu Klik):</strong> Jalankan file <code class="bg-gray-100 dark:bg-gray-800 px-1 py-0.5 rounded font-mono text-primary-600 font-bold">sinkron_dapodik.bat</code> langsung di komputer sekolah yang membuka Dapodik. Data akan langsung terkirim ke server web.</p>
                                <p><strong>Opsi 2 (Tarik Sekarang):</strong> Jika website dibuka langsung di komputer yang sama dengan aplikasi Dapodik, klik tombol <em>Mulai Tarik Data</em> di bawah.</p>
                            </div>
                        ')),
                    Forms\Components\TextInput::make('ws_token')
                        ->label('Token Web Service Dapodik')
                        ->default(DapodikWebServiceSync::getDefaultToken())
                        ->required()
                        ->helperText('Token dari menu Pengaturan > Web Service di aplikasi Dapodik.'),
                    Forms\Components\TextInput::make('npsn')
                        ->label('NPSN Sekolah')
                        ->default('20208801')
                        ->required(),
                    Forms\Components\TextInput::make('ws_url')
                        ->label('URL Web Service Dapodik')
                        ->default('http://127.0.0.1:5774')
                        ->required()
                        ->helperText('Default: http://127.0.0.1:5774 (Port bawaan Dapodik).'),
                    Forms\Components\Toggle::make('prune_missing')
                        ->label('Hapus Permanen Siswa yang Tidak Ada di Dapodik (Mutasi / Keluar)')
                        ->default(true)
                        ->helperText('Jika aktif, siswa di website yang sudah tidak terdaftar di Dapodik akan otomatis dihapus permanen.'),
                ])
                ->modalSubmitActionLabel('Mulai Tarik Data')
                ->action(function (array $data) {
                    try {
                        $stats = DapodikWebServiceSync::pullFromLocal(
                            $data['ws_token'],
                            $data['npsn'],
                            $data['ws_url'],
                            $data['prune_missing'] ?? true
                        );

                        Notification::make()
                            ->title('Sinkronisasi Web Service Berhasil! 🎉')
                            ->body("Sukses memproses {$stats['total']} siswa. Baru: {$stats['created']}, Diperbarui: {$stats['updated']}, Naik/Pindah Rombel: {$stats['class_changed']}, Dihapus (Mutasi): {$stats['deleted']}.")
                            ->success()
                            ->duration(10000)
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Gagal Terhubung ke Dapodik Lokal')
                            ->body($e->getMessage() . '. Jika web ini diakses dari cloud hosting (Render), gunakan file "sinkron_dapodik.bat" di komputer sekolah.')
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),

            // 2. Tombol Download Tool Script One-Click
            Actions\Action::make('downloadTool')
                ->label('⬇️ Download Script One-Click (.bat)')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(route('admin.dapodik.download-tool'))
                ->openUrlInNewTab(),

            // 3. Menu Import Berdasarkan File Excel/CSV Unduhan Dapodik (Untuk Pemakaian Berkelanjutan)
            Actions\Action::make('importDapodik')
                ->label('📥 Impor File Excel Dapodik')
                ->icon('heroicon-o-document-arrow-up')
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
