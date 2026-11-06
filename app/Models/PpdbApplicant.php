<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbApplicant extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
}
