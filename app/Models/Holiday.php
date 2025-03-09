<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['name', 'date', 'type'];

    public static function isHoliday($date)
    {
        return self::where('date', $date)->orWhere(function ($query) use ($date) {
            $query->where('type', 'weekly')
                ->whereRaw('WEEKDAY(?) = WEEKDAY(date)', [$date]);
        })->exists();
    }

}
