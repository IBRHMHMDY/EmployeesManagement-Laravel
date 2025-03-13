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
use Laravel\Prompts\Output\ConsoleOutput;
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
    // public function checkAttendance($employee_id, $time_in, $time_out)
    // {
    //     $employee = Employee::find($employee_id);
    //     $shift = $employee->shifts()->where('day', now()->format('l'))->first();

    //     if (!$shift) {
    //         return response()->json(['message' => 'لا يوجد شيفت لهذا اليوم'], 400);
    //     }

    //     // حساب وقت التأخير
    //     $shift_start = strtotime($shift->shift->start_time);
    //     $actual_time_in = strtotime($time_in);
    //     $late_minutes = max(0, ($actual_time_in - $shift_start) / 60);

    //     // حساب وقت العمل الإضافي
    //     $shift_end = strtotime($shift->shift->end_time);
    //     $actual_time_out = strtotime($time_out);
    //     $overtime_minutes = max(0, ($actual_time_out - $shift_end) / 60);
    //     $overtime_hours = floor($overtime_minutes / 60);

    //     // تحديد الخصومات
    //     $absence_days = 0;
    //     if ($late_minutes >= 45) {
    //         $absence_days = 1; // تأخير أكثر من 45 دقيقة = خصم يوم
    //     } elseif ($late_minutes >= 25) {
    //         $absence_days = 0.5; // تأخير أكثر من 25 دقيقة = خصم نصف يوم
    //     }

    //     // تسجيل الحضور في قاعدة البيانات
    //     Attendance::create([
    //         'employee_id' => $employee_id,
    //         'shift_id' => $employee->shift_id, // تحديد الوردية تلقائيًا من الموظف
    //         'date' => now()->toDateString(),
    //         'time_in' => $time_in,
    //         'time_out' => $time_out,
    //         'late_minutes' => $late_minutes,
    //         'overtime_hours' => $overtime_hours,
    //         'absence_days' => $absence_days,
    //     ]);

    //     return response()->json(['message' => 'تم تسجيل الحضور بنجاح'], 200);
    // }
    // حساب الغياب بدون إذن لمدة 3 أيام
    // public function checkAbsences()
    // {
    //     $employees = Employee::all();

    //     foreach ($employees as $employee) {
    //         $absences = Attendance::where('employee_id', $employee->id)
    //                             ->where('date', '>=', now()->subDays(3)->toDateString())
    //                             ->whereNull('time_in') // لم يسجل حضور
    //                             ->count();

    //         if ($absences >= 3) {
    //             Attendance::create([
    //                 'employee_id' => $employee->id,
    //                 'date' => now()->toDateString(),
    //                 'absence_days' => 3, // خصم 3 أيام
    //             ]);
    //         }
    //     }
    // }

    // عرض صفحة تسجيل الحضور
    public function showCheckInPage()
    {
        $employees = Employee::all();
        // $shifts = Shift::all();

        return view('attendances.check-in', compact('employees'));
    }

    // تسجيل الحضور
    public function checkIn(Request $request)
    {
        // البحث عن الموظف بناءً على الـ ID القادم من الطلب
        $employee = Employee::find($request->employee_id);

        if (!$employee) {
            return redirect()->back()->with('error', 'لم يتم العثور على الموظف.');
        }

        $shift = $employee->shift;

        if (!$shift) {
            return redirect()->back()->with('error', 'لم يتم تعيين وردية لك، يرجى التواصل مع الإدارة.');
        }

        // جلب الوقت الحالي وزمن بداية الوردية
        $currentTime = Carbon::now();
        $shiftStart = Carbon::parse($shift->start_time);

        // حساب التأخير فقط إذا كان الموظف متأخرًا
        // حساب التأخير
        $lateMinutes = max(0, Carbon::parse(now())->diffInMinutes(Carbon::parse($shift->start_time), false));

        // تحديد حالة التأخير
        $status = ($lateMinutes >= 45) ? 'تأخير يوم كامل' : (($lateMinutes >= 25) ? 'تأخير نصف يوم' : 'حاضر');

        // إنشاء سجل الحضور
        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            'shift_id' => $shift->id,
            'date' => now()->toDateString(),
            'check_in' => now()->format('h:i A'),
        ]);

        // تسجيل التأخير إذا كان الموظف متأخراً
        if ($lateMinutes > 0) {
            Delay::create([
                'attendance_id' => $attendance->id,
                'late_minutes' => $lateMinutes,
                'status' => $status,
            ]);
        }

        return redirect()->route('attendances.index')->with('success', 'تم تسجيل الحضور بنجاح.');
    }

    // عرض صفحة تسجيل الانصراف
    public function showCheckOutPage()
    {
        $attendances = Attendance::whereNull('check_out')->with('employee')->get();
        $shifts = Shift::where('start_time', '<=', now()->format('h:i A'))
            ->where('end_time', '>=', now()->format('h:i A'))
            ->first();
        return view('attendances.check-out', compact('attendances', 'shifts'));
    }

    // تسجيل الانصراف
    public function checkOut(Request $request)
    {
        // جلب الموظف من الطلب
        $employee = Employee::find($request->employee_id);

        if (!$employee) {
            return redirect()->back()->with('error', 'لم يتم العثور على الموظف.');
        }

        // جلب سجل الحضور الخاص بهذا الموظف لهذا اليوم
        $attendance = Attendance::where('employee_id', $employee->id)
        ->where('date', now()->toDateString())
        ->whereNull('check_out') // يجب أن يكون الحضور غير مسجل للانصراف مسبقًا
        ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'لم يتم العثور على سجل الحضور لهذا اليوم.');
        }

        // جلب بيانات الوردية
        $shift = $employee->shift;

        if (!$shift) {
            return redirect()->back()->with('error', 'لم يتم تعيين وردية لهذا الموظف.');
        }

        // جلب وقت الحضور والانصراف الحالي
        $checkInTime = Carbon::parse($attendance->check_in);
        $checkOutTime = now()->format('h:i A'); // وقت تسجيل الانصراف

        // التأكد من أن القيم ليست فارغة قبل الحساب
        if (!$checkInTime || !$checkOutTime) {
            return redirect()->back()->with('error', 'بيانات الحضور غير صحيحة.');
        }

        // حساب ساعات العمل الفعلية
        $workingMinutes = $checkInTime->diffInMinutes($checkOutTime);
        $workingHours = floor($workingMinutes / 60); // عدد الساعات الكاملة
        $remainingMinutes = $workingMinutes % 60; // الدقائق المتبقية
        $totalWorkingHours = $workingHours + ($remainingMinutes / 60);
        // dd($totalWorkingHours);
        // حساب الساعات الإضافية
        $shiftStart = Carbon::parse($employee->shift->start_time);
        $shiftEnd = Carbon::parse($employee->shift->end_time);
        $shiftDurationMinutes = $shiftStart->diffInMinutes($shiftEnd);
        $shiftDurationHours = round($shiftDurationMinutes / 60, 2);
        $overtimeMinutes = max(0, $workingMinutes - $shiftDurationMinutes);
        $overtimeHours = floor($overtimeMinutes / 60);
        $overtimeRemainingMinutes = $overtimeMinutes % 60;
        $totalOvertimeHours = $overtimeHours + ($overtimeRemainingMinutes / 60);

        // تحديث سجل الحضور بالانصراف
        $attendance->update([
            'check_out' => $checkOutTime, // تنسيق 12 ساعة
            'working_hours' => $totalWorkingHours,
            'overtime_minutes' => $overtimeMinutes,
            'overtime_hours' => $totalOvertimeHours, // إضافة الساعات الإضافية كحقل جديد
        ]);

        if ($attendance->wasChanged()) {
            return redirect()->route('attendances.index')->with('success', 'تم تسجيل الانصراف بنجاح.');
        } else {
            return redirect()->back()->with('error', 'لم يتم تحديث سجل الحضور.');
        }
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
        $today = today();

        if ($request->has('date')) {
            $query->whereDate('date', $today);
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
