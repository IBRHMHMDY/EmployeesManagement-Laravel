<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    public function index(Request $request) {
        $query = Employee::with('department');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('job_title', 'like', '%' . $request->search . '%')
                  ->orWhere('hiring_date', 'like', '%' . $request->search . '%');
        }

        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }

        $employees = $query->paginate(10);

        return view('employees.index', compact('employees'));
    }


    public function create() {
        $departments = Department::all();
        $shifts = Shift::all();
        return view('employees.create', compact('departments','shifts'));
    }


    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable',
            'job_title' => 'required',
            'basic_salary' => 'required|numeric',
            'department_id' => 'required|exists:departments,id',
            'hiring_date' => 'required|date',
            'status' => 'required|in:active,inactive',
            'shift_id' => 'required|exists:shifts,id'
        ]);

        // حفظ البيانات بشكل آمن
        Employee::create($request->only([
            'name', 'email', 'phone', 'job_title', 'basic_salary', 'department_id', 'hiring_date', 'status', 'shift_id'
        ]));


        return redirect()->route('employees.index')->with('success', 'تم إضافة الموظف بنجاح');
    }

    public function show(Employee $employee) {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee) {
        $departments = Department::all();
        $shifts = Shift::all();
        return view('employees.edit', compact('employee', 'departments', 'shifts'));
    }

    public function update(Request $request, Employee $employee) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable',
            'job_title' => 'required',
            'basic_salary' => 'required|numeric',
            'department_id' => 'required|exists:departments,id',
            'hiring_date' => 'required|date',
            'status' => 'required|in:active,inactive',
            'shift_id' => 'required|exists:shifts,id'
        ]);

        // تحديث البيانات بشكل آمن
        $employee->update($request->only([
            'name', 'email', 'phone', 'job_title', 'basic_salary', 'department_id', 'hiring_date', 'status', 'shift_id'
        ]));

        return redirect()->route('employees.index')->with('success', 'تم تحديث بيانات الموظف بنجاح.');
    }

    public function destroy(Employee $employee) {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'تم حذف الموظف بنجاح');
    }

}
