<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PkksAssessorReview extends Model
{
    use HasFactory;

    protected $table = 'pkks_assessor_reviews';

    protected $fillable = [
        'pkks_period_id',
        'assessor_name',
        'assessor_agency',
        'recommendation_status',
        'performance_review',
        'follow_up_recommendation',
        'ip_address',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(PkksPeriod::class, 'pkks_period_id');
    }
}
