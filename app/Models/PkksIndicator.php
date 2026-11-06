<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PkksIndicator extends Model
{
    use HasFactory;

    protected $table = 'pkks_indicators';

    protected $guarded = ['id'];

    public function period(): BelongsTo
    {
        return $this->belongsTo(PkksPeriod::class, 'pkks_period_id');
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(PkksEvidence::class)->orderBy('sort_order');
    }
}
