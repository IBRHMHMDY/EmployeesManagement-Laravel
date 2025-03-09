<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendancesController extends Controller
{
    // عرض كشف الحضور والانصراف
    public function index()
    {
        $attendances = Attendance::with('employee')->orderBy('date', 'desc')->get();
        return view('attendances.index', compact('attendances'));
    }

    // عرض صفحة تسجيل الحضور
    public function showCheckInPage()
    {
        $employees = Employee::all();
        return view('attendances.check-in', compact('employees'));
    }

    // تسجيل الحضور
    public function checkIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $currentTime = Carbon::now();

        // استرجاع إعدادات أوقات العمل
        $settings = Setting::first();
        if (!empty($settings->work_start_time)) {
            $workStartTime = Carbon::createFromFormat('H:i', $settings->work_start_time);
        } else {
            // تعيين وقت افتراضي إذا كانت القيمة غير صالحة
            $workStartTime = Carbon::createFromFormat('H:i', '09:00');
        }

        $lateMinutes = max(0, $workStartTime->diffInMinutes($currentTime, false));

        // تحديد الحالة بناءً على التأخير
        if ($lateMinutes >= $settings->late_full_day) {
            $status = 'تأخير يوم كامل';
        } elseif ($lateMinutes >= $settings->late_half_day) {
            $status = 'تأخير نصف يوم';
        } else {
            $status = 'حاضر';
        }

        // حفظ بيانات الحضور
        Attendance::create([
            'employee_id' => $employee->id,
            'date' => Carbon::today(),
            'check_in' => $currentTime->format('h:i A'),
            'check_out' => null, // تأكد من تمرير NULL وليس تركه فارغًا
            'late_minutes' => $lateMinutes,
            'status' => $status
        ]);

        return redirect()->route('attendances.index')->with('success', 'تم تسجيل الحضور بنجاح');
    }

    // عرض صفحة تسجيل الانصراف
    public function showCheckOutPage()
    {
        $attendances = Attendance::whereNull('check_out')->with('employee')->get();
        return view('attendances.check-out', compact('attendances'));
    }

    // تسجيل الانصراف
    public function checkOut(Request $request)
    {
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
        ]);

        $attendance = Attendance::findOrFail($request->attendance_id);
        $checkOutTime = Carbon::now();

        // حساب عدد ساعات العمل
        $checkInTime = Carbon::parse($attendance->check_in);
        $workingHours = $checkInTime->diffInHours($checkOutTime);

        // حساب الإضافي (بعد 8 ساعات عمل)
        $overtime = max(0, $workingHours - 8);

        // تحديث بيانات الحضور بالانصراف
        $attendance->update([
            'check_out' => $checkOutTime->format('h:i A'),
            'working_hours' => $workingHours,
            'overtime_minutes' => $overtime * 60,
        ]);

        return redirect()->route('attendances.index')->with('success', 'تم تسجيل الانصراف بنجاح');
    }

    // تعديل الحضور والانصراف
    public function edit(Attendance $attendance)
    {
        return view('attendances.edit', compact('attendance'));
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
}
