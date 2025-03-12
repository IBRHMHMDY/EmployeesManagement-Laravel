<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    // use HasFactory;

    protected $fillable = ['employee_id', 'shift_id', 'date', 'check_in', 'check_out', 'late_minutes', 'status'];

    protected $casts = [
        'check_in' => 'string',  // أو 'integer', 'boolean', 'datetime'
        'check_out' => 'string',
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
