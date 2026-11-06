<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SchoolHoliday extends Model
{
    protected $table = 'school_holidays';

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Cek apakah sebuah tanggal tertentu merupakan hari libur
     */
    public static function isHoliday(string|Carbon $date): ?self
    {
        $dateStr = is_string($date) ? $date : $date->toDateString();

        return self::where('is_active', true)
            ->whereDate('start_date', '<=', $dateStr)
            ->whereDate('end_date', '>=', $dateStr)
            ->first();
    }

    /**
     * Hitung total hari libur aktif di luar hari Minggu pada rentang tanggal tertentu
     */
    public static function countHolidaysBetween(Carbon $start, Carbon $end): int
    {
        $holidays = self::where('is_active', true)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start->toDateString(), $end->toDateString()])
                  ->orWhereBetween('end_date', [$start->toDateString(), $end->toDateString()])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->where('start_date', '<=', $start->toDateString())
                         ->where('end_date', '>=', $end->toDateString());
                  });
            })
            ->get();

        if ($holidays->isEmpty()) {
            return 0;
        }

        $holidayDates = [];
        foreach ($holidays as $h) {
            $curr = Carbon::parse($h->start_date);
            $last = Carbon::parse($h->end_date);

            while ($curr <= $last) {
                if ($curr >= $start && $curr <= $end) {
                    // Hanya hitung jika bukan hari Minggu (karena hari Minggu sudah otomatis dipotong)
                    if ($curr->dayOfWeek !== Carbon::SUNDAY) {
                        $holidayDates[$curr->toDateString()] = true;
                    }
                }
                $curr->addDay();
            }
        }

        return count($holidayDates);
    }
}
