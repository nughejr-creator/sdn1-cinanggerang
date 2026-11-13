<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAttendance extends Model
{
    protected $table = 'teacher_attendances';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function getFotoBuktiUrlAttribute(): ?string
    {
        if (!empty($this->foto_bukti)) {
            try {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($this->foto_bukti);
            } catch (\Throwable $e) {
                return asset('storage/' . $this->foto_bukti);
            }
        }
        return $this->teacher?->foto_url;
    }

    public function getFotoPulangUrlAttribute(): ?string
    {
        if (!empty($this->foto_pulang)) {
            try {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($this->foto_pulang);
            } catch (\Throwable $e) {
                return asset('storage/' . $this->foto_pulang);
            }
        }
        return $this->teacher?->foto_url;
    }

    public function getDutyProofUrlAttribute(): ?string
    {
        if (!empty($this->duty_proof_file)) {
            try {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($this->duty_proof_file);
            } catch (\Throwable $e) {
                return asset('storage/' . $this->duty_proof_file);
            }
        }
        return null;
    }
}
