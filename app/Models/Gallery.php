<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'foto' => 'array',
    ];
}
