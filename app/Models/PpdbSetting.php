<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbSetting extends Model
{
    protected $table = 'ppdb_settings';

    protected $guarded = ['id'];

    protected $casts = [
        'is_ppdb_open' => 'boolean',
    ];

    /**
     * Dapatkan instance pengaturan tunggal dengan nilai default
     */
    public static function getSetting(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'is_ppdb_open' => false,
                'academic_year' => '2026/2027',
                'closed_message' => 'Mohon maaf, pendaftaran peserta didik baru belum dibuka atau periode pendaftaran telah berakhir.',
                'whatsapp_contact' => '081321021585',
            ]
        );
    }
}
