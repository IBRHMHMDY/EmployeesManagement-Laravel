<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'basic_salary', 'overtime_hours', 'overtime_amount', 'deductions', 'net_salary'];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
