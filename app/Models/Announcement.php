<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
        'tampil_di_beranda' => 'boolean',
    ];

    public function getFileLampiranUrlAttribute(): ?string
    {
        if (!empty($this->file_lampiran)) {
            try {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($this->file_lampiran);
            } catch (\Throwable $e) {
                return asset('storage/' . $this->file_lampiran);
            }
        }
        return null;
    }

    public function getDetailUrlAttribute(): string
    {
        return route('pengumuman.detail', $this->id);
    }
}
