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
            // 'recentActions' => ActivityLog::latest()->limit(5)->get(),
            'notifications' => $this->getNotifications(),
            'chartLabels' => Employee::pluck('name')->toArray(),
            'chartData' => Employee::withSum('attendance', 'working_hours')->get()->map(fn($e) => $e->attendances_sum_working_hours ?? 0)->toArray(),
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
