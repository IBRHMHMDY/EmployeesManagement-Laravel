<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "email",
        "phone",
        "job_title",
        "basic_salary",
        "department_id",
        "hiring_date",
        "status",
        "shift_id",
      ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
    public function salary()
    {
        return $this->hasOne(Salary::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function deductions()
    {
        return $this->hasMany(Deduction::class);
    }

    public function getNetSalaryAttribute()
    {
        $basic_salary = $this->basic_salary;
        $total_deductions = $this->deductions->sum('amount');

        return $basic_salary - $total_deductions;
    }
}
