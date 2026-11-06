<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PkksKomponen extends Model
{
    protected $table = 'pkks_komponens';

    protected $guarded = ['id'];

    public function indikators(): HasMany
    {
        return $this->hasMany(PkksIndikator::class)->orderBy('nomor');
    }
}
