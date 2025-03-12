@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">كشف الحضور والانصراف</h2>
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ route('attendances.check-in-page') }}" class="bg-green-500 text-white px-4 py-2 rounded">تسجيل الحضور</a>
            <a href="{{ route('attendances.check-out-page') }}" class="bg-blue-500 text-white px-4 py-2 rounded">تسجيل الانصراف</a>

        </div>
        <div>
            <form action="{{ route('attendances.report') }}" method="GET" class="mb-4 flex items-center gap-4">
                <input type="date" name="date" value="{{ request('date', now()->toDateString()) }}" class="border p-2 rounded">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">عرض التقرير</button>
            </form>
        </div>
    </div>
    <div >
        <form method="GET" action="{{ route('attendances.index') }}" class="mt-4 mx-auto w-full ">
            <input type="text" name="employee_name" placeholder="ابحث باسم الموظف..." class="border p-2 rounded w-2/4"
                value="{{ request('employee_name') }}">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                بحث
            </button>
        </form>
    </div>
    <table class="w-full border-collapse border mt-4">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">الموظف</th>
                <th class="border p-2">التاريخ</th>
                <th class="border p-2">الوردية</th>
                <th class="border p-2">وقت الحضور</th>
                <th class="border p-2">وقت الانصراف</th>
                <th class="border p-2">التأخير</th>
                <th class="border p-2">ساعات العمل</th>
                <th class="border p-2">الإضافي</th>
                <th class="border p-2">الحالة</th>
                <th class="border p-2">الإجراء</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
                <tr>
                    <td class="border p-2">{{ $attendance->employee->name }}</td>
                    <td class="border p-2">{{ $attendance->date }}</td>
                    <td class="border px-4 py-2">{{ $attendance->shift->name ?? 'غير محدد' }}</td>
                    <td class="border p-2">{{ $attendance->check_in }}</td>
                    <td class="border p-2">{{ $attendance->check_out ?? '---' }}</td>
                    <td class="border p-2">{{ $attendance->late_minutes }} دقيقة</td>
                    <td class="border p-2">{{ $attendance->working_hours }}</td>
                    <td class="border p-2">{{ $attendance->overtime_minutes }} دقيقة</td>
                    <td class="border p-2">
                        @if ($attendance->status == 'حاضر')
                            <span class="text-green-500 font-bold">{{ $attendance->status }}</span>
                        @elseif ($attendance->status == 'تأخير نصف يوم')
                            <span class="text-yellow-500 font-bold">{{ $attendance->status }}</span>
                        @elseif ($attendance->status == 'تأخير يوم كامل')
                            <span class="text-red-500 font-bold">{{ $attendance->status }}</span>
                        @else
                            <span class="text-gray-500 font-bold">غير مسجل</span>
                        @endif
                    </td>
                    <td class="border p-2">
                        <a href="{{ route('attendances.edit', $attendance->id) }}" class="text-blue-500">تعديل</a> |
                        <form method="POST" action="{{ route('attendances.destroy', $attendance->id) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $attendances->links() }}
    </div>
    <div class="flex space-x-2 mb-4">
        <a href="{{ route('attendances.exportExcel') }}" class="bg-green-500 text-white px-4 py-2 rounded">
            تصدير إلى Excel
        </a>
        <a href="{{ route('attendances.exportPDF') }}" class="bg-red-500 text-white px-4 py-2 rounded">
            تصدير إلى PDF
        </a>
        <!-- زر الطباعة -->
        <button class="print-btn" onclick="window.print()"
            style="display: block; margin: 10px auto; padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
        🖨️ طباعة التقرير
        </button>
    </div>
</div>
@endsection
