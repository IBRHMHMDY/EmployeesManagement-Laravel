<?php
namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendancesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Attendance::with('employee.department')->get()->map(function ($attendance) {
            return [
                'الموظف'      => $attendance->employee->name,
                'القسم'       => $attendance->employee->department->name ?? 'غير محدد',
                'التاريخ'     => $attendance->date,
                'وقت الحضور'  => $attendance->check_in,
                'وقت الانصراف' => $attendance->check_out ?? '---',
                'التأخير (دقائق)' => $attendance->late_minutes,
                'عدد الساعات' => $attendance->working_hours,
                'الإضافي (دقائق)' => $attendance->overtime_minutes,
                'الحالة' => $attendance->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'الموظف',
            'القسم',
            'التاريخ',
            'وقت الحضور',
            'وقت الانصراف',
            'التأخير (دقائق)',
            'عدد الساعات',
            'الإضافي (دقائق)',
            'الحالة',
        ];
    }
}

