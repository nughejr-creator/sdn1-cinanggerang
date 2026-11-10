<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class PkksPeriod extends Model
{
    use HasFactory;

    protected $table = 'pkks_periods';

    protected $guarded = ['id'];

    protected $casts = [
        'assessment_date' => 'date',
        'is_active' => 'boolean',
        'archived_at' => 'datetime',
    ];

    /**
     * Relasi ke 12 Indikator PKKS
     */
    public function indicators(): HasMany
    {
        return $this->hasMany(PkksIndicator::class)->orderBy('indicator_number');
    }

    /**
     * Relasi ke Catatan & Rekomendasi Asesor
     */
    public function assessorReviews(): HasMany
    {
        return $this->hasMany(PkksAssessorReview::class, 'pkks_period_id')->latest();
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived' || !empty($this->archived_at);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->is_active;
    }

    /**
     * Jadikan periode ini sebagai arsip resmi (Terkunci)
     */
    public function archive(): void
    {
        $this->update([
            'status' => 'archived',
            'is_active' => false,
            'archived_at' => now(),
        ]);
    }

    /**
     * Jadikan periode ini sebagai periode aktif
     */
    public function activate(): void
    {
        DB::transaction(function () {
            // Nonaktifkan periode lain
            static::where('id', '!=', $this->id)->update([
                'is_active' => false,
                'status' => 'archived',
                'archived_at' => now(),
            ]);

            $this->update([
                'status' => 'active',
                'is_active' => true,
                'archived_at' => null,
            ]);
        });
    }

    /**
     * Kloning struktur 12 indikator resmi ke tahun ajaran baru
     */
    public function cloneToNewYear(string $newYear, ?string $assessorName = null, ?string $pin = null): self
    {
        return DB::transaction(function () use ($newYear, $assessorName, $pin) {
            // 1. Arsipkan periode saat ini
            $this->archive();

            // 2. Buat periode baru
            $newPeriod = static::create([
                'academic_year' => $newYear,
                'theme' => $this->theme ?? 'Optimalisasi Kinerja dan Kepemimpinan Pembelajaran Berkelanjutan Berbasis Kurikulum Merdeka Menuju Generasi Emas',
                'assessment_date' => null,
                'assessor_name' => $assessorName ?? $this->assessor_name,
                'assessor_pin' => $pin ?? $this->assessor_pin ?? '1972',
                'is_active' => true,
                'status' => 'active',
                'archived_at' => null,
            ]);

            // 3. Salin struktur 12 indikator baku ke periode baru
            $currentIndicators = $this->indicators()->get();

            foreach ($currentIndicators as $ind) {
                PkksIndicator::create([
                    'pkks_period_id' => $newPeriod->id,
                    'indicator_number' => $ind->indicator_number,
                    'competence' => $ind->competence,
                    'title' => $ind->title,
                    'description' => $ind->description,
                    'best_practice_notes' => $ind->best_practice_notes,
                    'status' => 'Ada',
                    'sort_order' => $ind->sort_order,
                ]);
            }

            return $newPeriod;
        });
    }
}
