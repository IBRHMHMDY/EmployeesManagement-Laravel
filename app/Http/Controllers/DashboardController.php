<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Deduction;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Salary;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'totalEmployees' => Employee::count(),
            'totalDepartments' => Department::count(),
            'todayAttendances' => Attendance::whereDate('check_in', now()->toDateString())->count(),
            'approvedLeavesToday' => Leave::whereDate('start_date', now()->toDateString())->where('status', 'approved')->count(),
            'totalSalaries' => Salary::sum('net_salary'),
            'totalLeaves' => Leave::count(),
            'total_late_minutes' => Attendance::whereDate('check_in', now()->toDateString())->where('check_in', '>', now()->setHour(8)->setMinute(30))->sum('late_minutes'),
            'total_absences' => Attendance::whereDate('check_in', now()->toDateString())->whereNull('check_out')->count(),
            'total_overtime' => Attendance::whereDate('check_in', now()->toDateString())->where('check_out', '>', now()->setHour(17)->setMinute(30))->sum('overtime_minutes'),
            'total_deductions' => Deduction::sum('amount'),
            'notifications' => $this->getNotifications(),
        ]);
    }

    private function getNotifications()
    {
        $notifications = [];

        if (Attendance::whereDate('check_in', now()->toDateString())->count() < Employee::count()) {
            $notifications[] = 'هناك موظفون لم يسجلوا الحضور اليوم.';
        }

        if (Deduction::whereDate('created_at', now()->toDateString())->exists()) {
            $notifications[] = 'تم تسجيل خصومات جديدة اليوم.';
        }

        if (Leave::where('status', 'pending')->exists()) {
            $notifications[] = 'هناك إجازات تحتاج إلى الموافقة.';
        }

        return $notifications;
    }

}
