<?php

namespace App\Filament\Pages;

use App\Models\PpdbSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManagePpdbSetting extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationGroup = 'Layanan PPDB Online';
    protected static ?string $navigationLabel = 'Kontrol Gelombang PPDB';
    protected static ?string $title = 'Sakelar Kontrol & Pengaturan PPDB';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.manage-ppdb-setting';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = PpdbSetting::getSetting();
        $this->form->fill($setting->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Sakelar Buka / Tutup Pendaftaran PPDB')
                    ->description('Kontrol utama status penerimaan peserta didik baru di portal web publik SDN Cinanggerang I')
                    ->schema([
                        Forms\Components\Toggle::make('is_ppdb_open')
                            ->label('Buka Gelombang Pendaftaran PPDB')
                            ->helperText('Jika non-aktif, tombol pendaftaran terkunci dan tombol cek status disembunyikan di web publik.')
                            ->onColor('success')
                            ->offColor('danger'),

                        Forms\Components\TextInput::make('academic_year')
                            ->label('Tahun Pelajaran')
                            ->default('2026/2027')
                            ->required(),

                        Forms\Components\Textarea::make('closed_message')
                            ->label('Pesan Saat Pendaftaran Ditutup')
                            ->default('Mohon maaf, pendaftaran peserta didik baru belum dibuka atau periode pendaftaran telah berakhir.')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('whatsapp_contact')
                            ->label('Nomor WhatsApp Panitia')
                            ->default('081321021585')
                            ->required(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $setting = PpdbSetting::getSetting();
        $setting->update($state);

        Notification::make()
            ->title('Pengaturan PPDB Berhasil Disimpan')
            ->body('Status pendaftaran di website publik telah diperbarui.')
            ->success()
            ->send();
    }
}
