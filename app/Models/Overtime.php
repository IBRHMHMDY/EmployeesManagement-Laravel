<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;

    protected $fillable = ['attendance_id', 'overtime_minutes', 'overtime_hours'];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
