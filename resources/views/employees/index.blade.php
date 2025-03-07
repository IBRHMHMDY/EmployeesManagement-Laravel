@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-2xl font-bold mb-4">إدارة الموظفين</h2>

    <div class="mb-4 flex justify-between">
        <form method="GET" action="{{ route('employees.index') }}" class="flex space-x-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن موظف..."
                class="border p-2 rounded">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">بحث</button>
        </form>
        <a href="{{ route('employees.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">+ إضافة موظف</a>
    </div>

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">الاسم</th>
                <th class="border p-2">البريد الإلكتروني</th>
                <th class="border p-2">المسمى الوظيفي</th>
                <th class="border p-2">القسم</th>
                <th class="border p-2">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td class="border p-2">{{ $employee->name }}</td>
                    <td class="border p-2">{{ $employee->email }}</td>
                    <td class="border p-2">{{ $employee->job_title }}</td>
                    <td class="border p-2">{{ $employee->department->name ?? 'غير محدد' }}</td>
                    <td class="border p-2">
                        <a href="{{ route('employees.show', $employee) }}" class="text-green-500">عرض</a> |
                        <a href="{{ route('employees.edit', $employee) }}" class="text-blue-500">تعديل</a> |
                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $employees->appends(request()->query())->links() }}
    </div>
</div>
@endsection
