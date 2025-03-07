<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
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
        return view('employees.create', compact('departments'));
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
            'status' => 'required|in:active,inactive'
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')->with('success', 'تم إضافة الموظف بنجاح');
    }

    public function show(Employee $employee) {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee) {
        $departments = Department::all();
        return view('employees.edit', compact('employee', 'departments'));
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
            'status' => 'required|in:active,inactive'
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')->with('success', 'تم تحديث بيانات الموظف');
    }



    public function destroy(Employee $employee) {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'تم حذف الموظف بنجاح');
    }

}
