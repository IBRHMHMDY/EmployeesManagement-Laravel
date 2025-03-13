<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Http\Request;

class DeductionsController extends Controller
{
    public function index()
    {
        $deductions = Deduction::with('employee')->latest()->paginate(10);
        return view('deductions.index', compact('deductions'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('deductions.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'reason' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        Deduction::create($request->all());
        $this->updateNetSalary($request->employee_id);
        return redirect()->route('deductions.index')->with('success', 'تم إضافة الخصم بنجاح.');
    }

    private function updateNetSalary($employee_id)
    {
        $employee = Employee::with('deductions')->findOrFail($employee_id);

        // حساب صافي الراتب
        $basic_salary = $employee->basic_salary;
        $total_deductions = $employee->deductions->sum('amount');
        $net_salary = $basic_salary - $total_deductions;

        // تحديث جدول المرتبات (payrolls)
        Salary::update(
            ['employee_id' => $employee_id], // البحث عن المرتب الحالي أو إنشاؤه
            ['net_salary' => $net_salary], // تحديث صافي الراتب
            ['total_deductions' => $total_deductions], // تحديث إجمالي الخصومات
            ['month' => now()->format('F Y')], // تحديث الشهر الحالي
        );
    }


    public function edit(Deduction $deduction)
    {
        $employees = Employee::all();
        return view('deductions.edit', compact('deduction', 'employees'));
    }

    public function update(Request $request, Deduction $deduction)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'reason' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $deduction->update($request->all());

        return redirect()->route('deductions.index')->with('success', 'تم تحديث الخصم بنجاح.');
    }

    public function destroy(Request $request, Deduction $deduction)
    {
        $this->updateNetSalary($request->employee_id);
        $deduction->delete();
        return redirect()->route('deductions.index')->with('success', 'تم حذف الخصم بنجاح.');
    }

    private function cancelDeduction($employee_id)
    {
        $employee = Employee::with('deductions')->findOrFail($employee_id);

        // حساب صافي الراتب
        $basic_salary = $employee->basic_salary;
        $total_deductions = $employee->deductions->sum('amount');
        $net_salary = $basic_salary + $total_deductions;

        // تحديث جدول المرتبات (payrolls)
        Salary::updateOrCreate(
            ['employee_id' => $employee_id], // البحث عن المرتب الحالي أو إنشاؤه
            ['net_salary' => $net_salary], // تحديث صافي الراتب
            ['total_deductions' => $total_deductions], // تحديث إجمالي الخصومات
        );
    }
}
