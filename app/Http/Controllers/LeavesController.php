<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Http\Request;

class LeavesController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::with('employee');

        // فلترة حسب الموظف أو التاريخ
        if ($request->has('employee_name')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->employee_name . '%');
            });
        }

        if ($request->has('date')) {
            $query->whereDate('start_date', '=', $request->date);
        }

        $leaves = $query->paginate(10);
        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        Leave::create($request->all());

        return redirect()->route('leaves.index')->with('success', 'تم تقديم طلب الإجازة بنجاح');
    }

    public function edit(Leave $leave)
    {
        $employees = Employee::all();
        return view('leaves.edit', compact('leave', 'employees'));
    }

    public function updateStatus(Request $request, Leave $leave)
    {
        $request->validate(['status' => 'required|in:قيد الإنتظار,تم الموافقه,تم الرفض']);
        $leave->update(['status' => $request->status]);
        return back()->with('success', 'تم تحديث حالة الإجازة بنجاح');
    }
    public function update(Request $request, Leave $leave)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:قيد الإنتظار,تم الموافقة,تم الرفض',
        ]);

        $leave->update($request->all());

        return redirect()->route('leaves.index')->with('success', 'تم تحديث بيانات الإجازة بنجاح');
    }

    public function destroy(Leave $leave)
    {
        $leave->delete();
        return back()->with('success', 'تم حذف الإجازة بنجاح');
    }
    
}
