@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">إدارة الإجازات</h2>

    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('leaves.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">طلب إجازة</a>
        <form method="GET" action="{{ route('leaves.index') }}" class="flex items-center gap-4">
            <input type="text" name="employee_name" placeholder="ابحث باسم الموظف..." class="border p-2 rounded" value="{{ request('employee_name') }}">
            <input type="date" name="date" class="border p-2 rounded" value="{{ request('date') }}">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">🔍 بحث</button>
        </form>
    </div>

    <table class="w-full border-collapse border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">الموظف</th>
                <th class="border p-2">نوع الإجازة</th>
                <th class="border p-2">بداية الإجازة</th>
                <th class="border p-2">نهاية الإجازة</th>
                <th class="border p-2">الحالة</th>
                <th class="border p-2">الإجراء</th>
            </tr>
        </thead>
        <tbody>
            @foreach($leaves as $leave)
                <tr>
                    <td class="border p-2">{{ $leave->employee->name }}</td>
                    <td class="border p-2">{{ $leave->leave_type }}</td>
                    <td class="border p-2">{{ $leave->start_date }}</td>
                    <td class="border p-2">{{ $leave->end_date }}</td>
                    <td class="border p-2">{{ $leave->status }}</td>
                    <td class="border p-2 flex gap-4 items-center justify-center">
                        <a href="{{ route('leaves.edit', $leave->id) }}" class="text-blue-500">تعديل</a>
                        <form method="POST" action="{{ route('leaves.destroy', $leave->id) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $leaves->links() }}
    </div>
</div>
@endsection
