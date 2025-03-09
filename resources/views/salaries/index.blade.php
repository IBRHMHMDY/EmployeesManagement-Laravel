@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">كشف المرتبات</h2>

    <div class="flex justify-between items-center mb-4">
        <a href="{{ route('salaries.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">إضافة مرتب جديد</a>
        <form method="GET" action="{{ route('salaries.index') }}" class="flex items-center gap-4">
            <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}" class="border p-2 rounded">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">عرض المرتبات</button>
        </form>
    </div>

    <table class="w-full border-collapse border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">الموظف</th>
                <th class="border p-2">الشهر</th>
                <th class="border p-2">الراتب الأساسي</th>
                <th class="border p-2">الإضافي (ساعات)</th>
                <th class="border p-2">إجمالي الإضافي</th>
                <th class="border p-2">الخصومات</th>
                <th class="border p-2">صافي المرتب</th>
                <th class="border p-2">الإجراء</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salaries as $salary)
            <tr>
                <td class="border p-2">{{ $salary->employee->name }}</td>
                <td class="border p-2">{{ $salary->month }}</td>
                <td class="border p-2">{{ number_format($salary->basic_salary, 2) }}</td>
                <td class="border p-2">{{ $salary->overtime_hours }}</td>
                <td class="border p-2">{{ number_format($salary->overtime_pay, 2) }}</td>
                <td class="border p-2">{{ number_format($salary->deductions, 2) }}</td>
                <td class="border p-2">{{ number_format($salary->net_salary, 2) }}</td>
                <td class="border p-2">
                    <form action="{{ route('salaries.destroy', $salary->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">🗑 حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $salaries->links() }}
    </div>

    <div class="flex space-x-2 mt-4">
        {{-- <a href="{{ route('salaries.exportExcel') }}" class="bg-green-500 text-white px-4 py-2 rounded">📊 تصدير إلى Excel</a>
        <a href="{{ route('salaries.exportPDF') }}" class="bg-red-500 text-white px-4 py-2 rounded">📄 تصدير إلى PDF</a> --}}
        <button class="print-btn" onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded">🖨️ طباعة التقرير</button>
    </div>
</div>
@endsection
