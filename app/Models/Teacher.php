<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model
{
    protected $guarded = ['id'];

    public function studentClass(): HasOne
    {
        return $this->hasOne(StudentClass::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(TeacherAttendance::class);
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (!empty($this->foto)) {
            try {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($this->foto);
            } catch (\Throwable $e) {
                return asset('storage/' . $this->foto);
            }
        }
        return null;
    }
}
