<?php

namespace App\Services;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TeacherAccountGenerator
{
    /**
     * Generate akun login Filament untuk seluruh data guru secara otomatis
     *
     * @param string $defaultPassword Password default untuk akun guru baru
     * @return array Rekapitulasi hasil generate
     */
    public static function generateAll(string $defaultPassword = 'guru123456'): array
    {
        $teachers = Teacher::all();
        $created = 0;
        $updated = 0;
        $accounts = [];

        foreach ($teachers as $teacher) {
            $fullName = $teacher->nama . (!empty($teacher->gelar) ? ', ' . $teacher->gelar : '');
            $email = !empty($teacher->email) 
                ? strtolower(trim($teacher->email)) 
                : strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $teacher->nama)) . '@sdncinanggerang1.sch.id';

            // Penentuan Hak Akses / Role
            $role = 'Guru';
            if (str_contains(strtolower($teacher->jabatan ?? ''), 'kepala sekolah') || str_contains(strtolower($teacher->nama), 'aep yayat')) {
                $role = 'Kepala Sekolah';
            } elseif (str_contains(strtolower($teacher->nama), 'agi hamdani')) {
                $role = 'Admin'; // Admin GTK / IT Pengembang
            }

            // Cek apakah user sudah terdaftar berdasarkan email atau teacher_id
            $user = User::where('email', $email)
                ->orWhere('teacher_id', $teacher->id)
                ->first();

            if ($user) {
                $user->update([
                    'name' => $fullName,
                    'teacher_id' => $teacher->id,
                    'is_active' => true,
                    // Pertahankan role jika sudah Admin
                    'role' => $user->role === 'Admin' ? 'Admin' : $role,
                ]);
                $updated++;
                $accounts[] = [
                    'nama' => $fullName,
                    'email' => $user->email,
                    'role' => $user->role,
                    'jabatan' => $teacher->jabatan,
                    'status' => 'Telah Ditautkan',
                ];
            } else {
                User::create([
                    'name' => $fullName,
                    'email' => $email,
                    'password' => Hash::make($defaultPassword),
                    'role' => $role,
                    'teacher_id' => $teacher->id,
                    'is_active' => true,
                ]);
                $created++;
                $accounts[] = [
                    'nama' => $fullName,
                    'email' => $email,
                    'role' => $role,
                    'jabatan' => $teacher->jabatan,
                    'status' => 'Akun Baru Dibuat',
                ];
            }
        }

        return [
            'created' => $created,
            'updated' => $updated,
            'total' => $teachers->count(),
            'default_password' => $defaultPassword,
            'accounts' => $accounts,
        ];
    }
}
