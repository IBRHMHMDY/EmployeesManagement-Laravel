<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $query = Salary::query();

        // البحث حسب الشهر
        if ($request->has('month') && !empty($request->month)) {
            $query->where('month', $request->month);
        }

        // البحث حسب اسم الموظف
        if ($request->has('employee_name') && !empty($request->employee_name)) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->employee_name . '%');
            });
        }

        $salaries = $query->paginate(10);

        return view('salaries.index', compact('salaries'));

    }
    // حساب الراتب وفقًا للإضافي والخصومات
    public function calculateSalary($employee_id)
    {
        $employee = Employee::find($employee_id);

        // إجمالي الغياب والتأخير
        $total_absence = Attendance::where('employee_id', $employee_id)
                                ->sum('absence_days');

        // عدد الساعات الإضافية
        $total_overtime = Attendance::where('employee_id', $employee_id)
                                    ->sum('overtime_hours');

        // حساب سعر الساعة
        $basic_salary = $employee->basic_salary;
        $hourly_rate = $basic_salary / 160; // (160 = عدد ساعات العمل الشهرية)

        // حساب الخصومات
        $deduction = $total_absence * ($basic_salary / 30); // خصم على أساس يومي

        // حساب المكافآت (سعر الساعة الإضافية = 1.5x)
        $overtime_bonus = $total_overtime * $hourly_rate * 1.5;

        // حساب الراتب النهائي
        $final_salary = $basic_salary - $deduction + $overtime_bonus;

        return response()->json([
            'employee_id' => $employee_id,
            'basic_salary' => $basic_salary,
            'deduction' => $deduction,
            'overtime_bonus' => $overtime_bonus,
            'final_salary' => $final_salary,
        ]);
    }



    public function create()
    {
        $employees = Employee::all();
        return view('salaries.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|date_format:Y-m',
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        // حساب عدد أيام العمل في الشهر
        $daysInMonth = Carbon::parse($request->month)->daysInMonth;

        // حساب ساعات العمل الفعلية للموظف
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereMonth('date', Carbon::parse($request->month)->month)
            ->whereYear('date', Carbon::parse($request->month)->year)
            ->get();

        $totalLatePenalty = $attendances->sum('late_penalty'); // مجموع أيام الخصم بسبب التأخير
        $totalOvertimeHours = 0; // عدد الساعات الإضافية

        foreach ($attendances as $attendance) {
            if ($attendance->check_out) {
                $workHours = Carbon::parse($attendance->check_in)->diffInHours(Carbon::parse($attendance->check_out));
                if ($workHours > 8) {
                    $totalOvertimeHours += $workHours - 8;
                }
            }
        }

        $overtimePay = $totalOvertimeHours * $employee->hourly_rate * 1.5;
        $deductions = $totalLatePenalty * ($employee->basic_salary / $daysInMonth);
        $netSalary = $employee->basic_salary + $overtimePay - $deductions;

        $salary = new Salary();
        $salary->employee_id = $employee->id;
        $salary->month = $request->month;
        $salary->basic_salary = $employee->basic_salary;
        $salary->overtime_hours = $totalOvertimeHours;
        $salary->overtime_pay = $overtimePay;
        $salary->deductions = $deductions;
        $salary->net_salary = $netSalary;
        $salary->save();

        return redirect()->route('salaries.index')->with('success', 'تم حساب المرتب بنجاح');
    }

    public function destroy(Salary $salary)
    {
        $salary->delete();
        return redirect()->route('salaries.index')->with('success', 'تم حذف السجل بنجاح');
    }

}
