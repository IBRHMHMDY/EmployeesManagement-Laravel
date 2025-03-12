<?php

namespace App\Http\Controllers;

use App\Exports\AttendancesExport;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Shift;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AttendancesController extends Controller
{
    // عرض كشف الحضور والانصراف
    public function index(Request $request)
    {
        $query = Attendance::query();

        // البحث حسب اسم الموظف
        if ($request->filled('employee_name')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->employee_name . '%');
            });
        }

        // الفلترة حسب التاريخ
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $attendances = $query->with('employee.department')->latest()->paginate(10);

        return view('attendances.index', compact('attendances'));
    }
    // تسجيل الحضور والانصراف بعد حساب الغيابات والخصومات والمكافآت
    public function checkAttendance($employee_id, $time_in, $time_out)
    {
        $employee = Employee::find($employee_id);
        $shift = EmployeeShift::where('employee_id', $employee_id)
            ->where('shift_date', now()->toDateString())
            ->first();

        if (!$shift) {
            return response()->json(['message' => 'لا يوجد شيفت لهذا اليوم'], 400);
        }

        // حساب وقت التأخير
        $shift_start = strtotime($shift->shift->start_time);
        $actual_time_in = strtotime($time_in);
        $late_minutes = max(0, ($actual_time_in - $shift_start) / 60);

        // حساب وقت العمل الإضافي
        $shift_end = strtotime($shift->shift->end_time);
        $actual_time_out = strtotime($time_out);
        $overtime_minutes = max(0, ($actual_time_out - $shift_end) / 60);
        $overtime_hours = floor($overtime_minutes / 60);

        // تحديد الخصومات
        $absence_days = 0;
        if ($late_minutes >= 45) {
            $absence_days = 1; // تأخير أكثر من 45 دقيقة = خصم يوم
        } elseif ($late_minutes >= 25) {
            $absence_days = 0.5; // تأخير أكثر من 25 دقيقة = خصم نصف يوم
        }

        // تسجيل الحضور في قاعدة البيانات
        Attendance::create([
            'employee_id' => $employee_id,
            'shift_id' => $employee->shift_id, // تحديد الوردية تلقائيًا من الموظف
            'date' => now()->toDateString(),
            'time_in' => $time_in,
            'time_out' => $time_out,
            'late_minutes' => $late_minutes,
            'overtime_hours' => $overtime_hours,
            'absence_days' => $absence_days,
        ]);

        return response()->json(['message' => 'تم تسجيل الحضور بنجاح'], 200);
    }
    // حساب الغياب بدون إذن لمدة 3 أيام
    public function checkAbsences()
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            $absences = Attendance::where('employee_id', $employee->id)
                                ->where('date', '>=', now()->subDays(3)->toDateString())
                                ->whereNull('time_in') // لم يسجل حضور
                                ->count();

            if ($absences >= 3) {
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => now()->toDateString(),
                    'absence_days' => 3, // خصم 3 أيام
                ]);
            }
        }
    }


    // عرض صفحة تسجيل الحضور
    public function showCheckInPage()
    {
        $employees = Employee::all();
        $shifts = Shift::where('start_time', '<=', now()->format('H:i:s'))
            ->where('end_time', '>=', now()->format('H:i:s'))
            ->first();
        return view('attendances.check-in', compact('employees', 'shifts'));
    }

    // تسجيل الحضور
    public function checkIn(Request $request)
    {
        $employee = auth()->user(); // جلب الموظف الحالي
        $shift = $employee->shift; // جلب الوردية الخاصة بالموظف

        if (!$shift) {
            return redirect()->back()->with('error', 'لم يتم تعيين وردية لك، يرجى التواصل مع الإدارة.');
        }

        $currentTime = now()->format('H:i');
        $shiftStart = Carbon::parse($shift->start_time)->format('H:i');

        // حساب التأخير بالدقائق
        $lateMinutes = Carbon::parse($currentTime)->diffInMinutes(Carbon::parse($shiftStart), false);
        $lateMinutes = $lateMinutes > 0 ? $lateMinutes : 0;

        // تحديد الحالة بناءً على التأخير
        if ($lateMinutes >= 45) {
            $status = 'تأخير يوم كامل';
        } elseif ($lateMinutes >= 25) {
            $status = 'تأخير نصف يوم';
        } else {
            $status = 'حاضر';
        }

        // إنشاء سجل الحضور
        Attendance::create([
            'employee_id' => $employee->id,
            'shift_id' => $shift->id,
            'date' => now()->toDateString(),
            'check_in' => now()->format('h:i A'),
            'late_minutes' => $lateMinutes,
            'status' => $status,
        ]);

        return redirect()->route('attendances.index')->with('success', 'تم تسجيل الحضور بنجاح.');
    }


    // عرض صفحة تسجيل الانصراف
    public function showCheckOutPage()
    {
        $attendances = Attendance::whereNull('check_out')->with('employee')->get();
        $shifts = Shift::where('start_time', '<=', now()->format('H:i:s'))
            ->where('end_time', '>=', now()->format('H:i:s'))
            ->first();
        return view('attendances.check-out', compact('attendances', 'shifts'));
    }

    // تسجيل الانصراف
    public function checkOut(Request $request)
{
    $employee = auth()->user();
    $attendance = Attendance::where('employee_id', $employee->id)
        ->where('date', now()->toDateString())
        ->first();

    if (!$attendance) {
        return redirect()->back()->with('error', 'يجب تسجيل الحضور أولاً قبل تسجيل الانصراف.');
    }

    $currentTime = now()->format('h:i A');
    $shiftEnd = Carbon::parse($attendance->shift->end_time)->format('h:i A');

    // حساب ساعات العمل
    $workingHours = Carbon::parse($attendance->check_in)->diffInHours(Carbon::parse($currentTime));

    // حساب الوقت الإضافي
    $overtimeMinutes = Carbon::parse($currentTime)->diffInMinutes(Carbon::parse($shiftEnd), false);
    $overtimeMinutes = $overtimeMinutes > 0 ? $overtimeMinutes : 0;

    // تحديث سجل الحضور
    $attendance->update([
        'check_out' => now()->format('h:i A'),
        'working_hours' => $workingHours,
        'overtime_minutes' => $overtimeMinutes,
    ]);

    return redirect()->route('attendances.index')->with('success', 'تم تسجيل الانصراف بنجاح.');
}

    // تعديل الحضور والانصراف
    public function edit(Attendance $attendance)
    {
        $employees = Employee::all();
        return view('attendances.edit', compact('attendance','employees'));
    }

    // تحديث بيانات الحضور والانصراف
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'check_in' => 'required',
            'check_out' => 'nullable',
            'late_minutes' => 'required|integer',
            'working_hours' => 'required|integer',
            'overtime_minutes' => 'required|integer',
        ]);

        $attendance->update($request->all());

        return redirect()->route('attendances.index')->with('success', 'تم تحديث البيانات بنجاح');
    }

    // حذف سجل الحضور والانصراف
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendances.index')->with('success', 'تم حذف السجل بنجاح');
    }

    public function report(Request $request)
    {
        $query = Attendance::with('employee.department');

        if ($request->has('date')) {
            $query->whereDate('date', $request->date);
        }


        $attendances = $query->get();

        return view('attendances.report', compact('attendances'));
    }

    public function exportExcel()
    {
        return Excel::download(new AttendancesExport, 'attendance_report.xlsx');
    }

    public function exportPdf()
    {
        $attendances = Attendance::with('employee.department')->get();

        $pdf = Pdf::loadView('attendances.report', compact('attendances'))
                ->setPaper('A4', 'landscape');

        return $pdf->download('تقرير_الحضور_والانصراف.pdf');
    }
}
