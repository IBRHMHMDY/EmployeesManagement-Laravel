<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id','leave_type', 'start_date', 'end_date', 'status', 'reason'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getEffectiveLeaveDays()
    {
        $totalDays = Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)) + 1;

        // استبعاد الإجازات الرسمية والأسبوعية
        $holidays = OfficialHoliday::whereBetween('date', [$this->start_date, $this->end_date])->count();

        // حساب عدد الإجازات الأسبوعية داخل الفترة
        $weeklyHolidays = OfficialHoliday::where('type', 'weekly')->pluck('date')->map(function ($day) {
            return Carbon::parse($this->start_date)->diffInDaysFiltered(fn($date) => $date->isSameDay(Carbon::parse($day)), Carbon::parse($this->end_date));
        })->sum();

        return $totalDays - ($holidays + $weeklyHolidays);
    }

}
