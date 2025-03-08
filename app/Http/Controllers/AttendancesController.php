<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendancesController extends Controller
{
    // عرض صفحة الحضور والانصراف مع جميع الموظفين
    public function index(Request $request)
    {
        $employees = Employee::with('department')->get();
        $attendances = Attendance::with('employee')->whereDate('date', Carbon::today())->get();
        return view('attendances.index', compact('employees', 'attendances'));
    }

    // البحث عن الموظف وعرض النتائج مباشرة
    public function search(Request $request)
    {
        $search = $request->input('query');
        $attendances = Attendance::with('employee')
            ->whereHas('employee', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            })
            ->latest()
            ->get();

        return response()->json($attendances);
    }

    public function checkIn(Request $request,Employee $employee)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $now = Carbon::now();
        $officialStart = Carbon::createFromTime(9, 0, 0); // وقت الدوام الرسمي
        $lateMinutes = max(0, $now->diffInMinutes($officialStart, false));

        $attendance = Attendance::updateOrCreate(
            ['employee_id' => $employee->id, 'date' => $now->toDateString()],
            ['check_in' => $now->toTimeString(), 'late_minutes' => $lateMinutes]
        );

        return redirect()->back()->with('success', 'تم تسجيل الحضور بنجاح');
    }

    public function checkOut(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);

        $employee = Employee::findOrFail($request->employee_id);
        $now = Carbon::now();
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $now->toDateString())
            ->firstOrFail();

        $checkInTime = Carbon::parse($attendance->check_in);
        $workingMinutes = $checkInTime->diffInMinutes($now);
        $overtimeMinutes = max(0, $workingMinutes - 480); // أي وقت يزيد عن 8 ساعات يعتبر إضافي

        $attendance->update([
            'check_out' => $now->toTimeString(),
            'working_hours' => round($workingMinutes / 60, 2),
            'overtime_minutes' => $overtimeMinutes,
        ]);

        return redirect()->back()->with('success', 'تم تسجيل الانصراف بنجاح');
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'employee_id' => 'required|exists:employees,id',
    //     ]);

    //     $employee = Employee::with('department')->findOrFail($request->employee_id);
    //     $now = Carbon::now();
    //     $lateMinutes = $this->calculateLateMinutes($now);

    //     $attendance = Attendance::create([
    //         'employee_id' => $employee->id,
    //         'date' => $now->toDateString(),
    //         'check_in' => $now->toTimeString(),
    //         'late_minutes' => $lateMinutes,
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'employee' => [
    //             'name' => $employee->name,
    //             'department' => $employee->department->name ?? 'غير محدد',
    //         ],
    //         'date' => $attendance->date,
    //         'check_in' => $attendance->check_in,
    //         'late_minutes' => $attendance->late_minutes,
    //     ]);
    // }



    // public function store(Request $request){

        // $request->validate([
        //     'employee_id' => 'required|exists:employees,id',
        //     'date' => 'required|date',
        //     'check_in' => 'required',
        //     'check_out' => 'nullable',
        // ]);

        // $checkIn = Carbon::parse($request->check_in);
        // $officialStart = Carbon::createFromTime(9, 0, 0);
        // $lateMinutes = max(0, $checkIn->diffInMinutes($officialStart));

        // Attendance::create([
        //     'employee_id' => $request->employee_id,
        //     'date' => $request->date,
        //     'check_in' => $request->check_in,
        //     'check_out' => $request->check_out,
        //     'late_minutes' => $lateMinutes,
        // ]);

        // return redirect()->route('attendances.index')->with('success', 'تم تسجيل الحضور بنجاح.');
    // }

    // public function edit(Attendance $attendance)
    // {
    //     $employees = Employee::all();
    //     return view('attendances.edit', compact('attendance', 'employees'));
    // }

    // public function update(Request $request, Attendance $attendance)
    // {
    //     $request->validate([
    //         'employee_id' => 'required|exists:employees,id',
    //         'date' => 'required|date',
    //         'check_in' => 'required',
    //         'check_out' => 'nullable',
    //     ]);

    //     $checkIn = Carbon::parse($request->check_in);
    //     $officialStart = Carbon::createFromTime(9, 0, 0);
    //     $lateMinutes = max(0, $checkIn->diffInMinutes($officialStart));

    //     $attendance->update([
    //         'employee_id' => $request->employee_id,
    //         'date' => $request->date,
    //         'check_in' => $request->check_in,
    //         'check_out' => $request->check_out,
    //         'late_minutes' => $lateMinutes,
    //     ]);

    //     return redirect()->route('attendances.index')->with('success', 'تم تحديث بيانات الحضور بنجاح.');
    // }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendances.index')->with('success', 'تم حذف بيانات الحضور بنجاح.');
    }
}
