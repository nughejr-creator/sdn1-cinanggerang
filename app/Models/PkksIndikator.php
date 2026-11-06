<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PkksIndikator extends Model
{
    protected $table = 'pkks_indikators';

    protected $guarded = ['id'];

    public function komponen(): BelongsTo
    {
        return $this->belongsTo(PkksKomponen::class, 'pkks_komponen_id');
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(PkksEvidence::class);
    }
}
