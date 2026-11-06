<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbRegistration extends Model
{
    protected $table = 'ppdb_registrations';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Generate nomor registrasi otomatis format: REG-{TAHUN}-{NOMOR_URUT}
     * Contoh: REG-2026-001
     */
    /**
     * Generate nomor registrasi otomatis format: REG-{TAHUN}-{NOMOR_URUT}
     * Contoh: REG-2026-001
     */
    public static function generateRegistrationNumber(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        $formattedCount = str_pad((string)$count, 3, '0', STR_PAD_LEFT);
        $number = "REG-{$year}-{$formattedCount}";

        // Pastikan unik
        while (self::where('registration_number', $number)->exists()) {
            $count++;
            $formattedCount = str_pad((string)$count, 3, '0', STR_PAD_LEFT);
            $number = "REG-{$year}-{$formattedCount}";
        }

        return $number;
    }

    public function getFileKkUrlAttribute(): ?string
    {
        return !empty($this->file_kk) ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->file_kk) : null;
    }

    public function getFileAktaUrlAttribute(): ?string
    {
        return !empty($this->file_akta) ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->file_akta) : null;
    }

    public function getFileIjazahUrlAttribute(): ?string
    {
        return !empty($this->file_ijazah) ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->file_ijazah) : null;
    }

    public function getFileBuktiAfirmasiUrlAttribute(): ?string
    {
        return !empty($this->file_bukti_afirmasi) ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->file_bukti_afirmasi) : null;
    }

    public function getFileSuratPindahTugasUrlAttribute(): ?string
    {
        return !empty($this->file_surat_pindah_tugas) ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->file_surat_pindah_tugas) : null;
    }
}
