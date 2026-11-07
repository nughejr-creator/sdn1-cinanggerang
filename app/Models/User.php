<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'teacher_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (!$this->is_active) {
            return false;
        }

        return in_array($this->role, ['Admin', 'Kepala Sekolah', 'Guru', 'Asisten Admin']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    public function isKepalaSekolah(): bool
    {
        return $this->role === 'Kepala Sekolah';
    }

    public function isGuru(): bool
    {
        return $this->role === 'Guru';
    }

    public function canManageUsers(): bool
    {
        return $this->is_active && $this->role === 'Admin';
    }

    public function canManageAttendance(): bool
    {
        return $this->is_active && in_array($this->role, ['Admin', 'Kepala Sekolah', 'Asisten Admin']);
    }

    public function canManageSchoolSettings(): bool
    {
        return $this->is_active && in_array($this->role, ['Admin', 'Kepala Sekolah']);
    }
}
