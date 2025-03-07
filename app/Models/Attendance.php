<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'date', 'check_in', 'check_out', 'late_minutes'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
