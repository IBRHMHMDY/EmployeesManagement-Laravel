@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">كشف الحضور والانصراف</h2>

    <a href="{{ route('attendances.check-in-page') }}" class="bg-green-500 text-white px-4 py-2 rounded">تسجيل الحضور</a>
    <a href="{{ route('attendances.check-out-page') }}" class="bg-blue-500 text-white px-4 py-2 rounded">تسجيل الانصراف</a>

    <table class="w-full border-collapse border mt-4">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">الموظف</th>
                <th class="border p-2">التاريخ</th>
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
                    <td class="border p-2">{{ $attendance->check_in }}</td>
                    <td class="border p-2">{{ $attendance->check_out ?? '---' }}</td>
                    <td class="border p-2">{{ $attendance->late_minutes }} دقيقة</td>
                    <td class="border p-2">{{ $attendance->working_hours }}</td>
                    <td class="border p-2">{{ $attendance->overtime_minutes }} دقيقة</td>
                    <td class="border p-2">{{ $attendance->status }}</td>
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
</div>
@endsection
