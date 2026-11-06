<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeadmasterHistory extends Model
{
    use HasFactory;

    protected $table = 'headmaster_histories';

    protected $guarded = ['id'];

    public function headmaster(): BelongsTo
    {
        return $this->belongsTo(HeadmasterProfile::class, 'headmaster_profile_id');
    }
}
