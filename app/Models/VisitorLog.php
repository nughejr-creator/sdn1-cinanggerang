<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    protected $table = 'visitor_logs';

    protected $guarded = ['id'];

    protected $casts = [
        'visited_date' => 'date',
        'last_activity_at' => 'datetime',
        'hits' => 'integer',
    ];
}
