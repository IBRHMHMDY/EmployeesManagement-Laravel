<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delay extends Model
{
    use HasFactory;

    protected $fillable = ['attendance_id', 'late_minutes', 'status'];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
