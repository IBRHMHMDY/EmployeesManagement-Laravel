@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">تسجيل الحضور والانصراف</h2>

    {{-- 🔍 اختيار الموظف وتسجيل الحضور --}}
    <form method="POST" action="{{ route('attendances.checkin') }}">
        @csrf
        <div class="flex items-center mb-6">
            <select name="employee_id" class="w-full border p-2 rounded">
                <option value="">اختر موظف...</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">
                        {{ $employee->name }} - {{ $employee->department->name ?? 'غير محدد' }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="ml-4 bg-green-500 text-white px-4 py-2 rounded">
                تسجيل الحضور
            </button>
        </div>
    </form>

    {{-- 📋 جدول الحضور --}}
    <table class="w-full border-collapse border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">الموظف</th>
                <th class="border p-2">القسم</th>
                <th class="border p-2">التاريخ</th>
                <th class="border p-2">وقت الحضور</th>
                <th class="border p-2">وقت الانصراف</th>
                <th class="border p-2">التأخير</th>
                <th class="border p-2">عدد الساعات</th>
                <th class="border p-2">الإضافي</th>
                <th class="border p-2">الإجراء</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
                <tr>
                    <td class="border p-2">{{ $attendance->employee->name }}</td>
                    <td class="border p-2">{{ $attendance->employee->department->name ?? 'غير محدد' }}</td>
                    <td class="border p-2">{{ $attendance->date }}</td>
                    <td class="border p-2">{{ $attendance->check_in }}</td>
                    <td class="border p-2">{{ $attendance->check_out ?? '---' }}</td>
                    <td class="border p-2">{{ $attendance->late_minutes }} دقيقة</td>
                    <td class="border p-2">{{ $attendance->working_hours }}</td>
                    <td class="border p-2">{{ $attendance->overtime_minutes }} دقيقة</td>
                    <td class="border p-2">
                        @if(!$attendance->check_out)
                            <form method="POST" action="{{ route('attendances.checkout') }}">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $attendance->employee_id }}">
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">
                                    تسجيل الانصراف
                                </button>
                            </form>
                        @else
                            مسجل
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
