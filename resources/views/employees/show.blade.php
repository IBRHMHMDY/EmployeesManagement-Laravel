@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-3xl font-bold mb-6 text-gray-700">تفاصيل الموظف</h2>

    <div class="bg-white shadow-lg rounded-lg p-6 max-w-2xl ">
        <div class="flex items-center space-x-4 mb-6 gap-4">
            <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center text-2xl font-bold">
                {{ strtoupper(substr($employee->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-2xl font-semibold text-gray-800">{{ $employee->name }}</h3>
                <p class="text-gray-500">{{ $employee->job_title }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-gray-700">
            <p><strong> البريد الإلكتروني:</strong> {{ $employee->email }}</p>
            <p><strong> الهاتف:</strong> {{ $employee->phone }}</p>
            <p><strong> القسم:</strong> {{ $employee->department->name ?? 'غير محدد' }}</p>
            <p><strong> الراتب الأساسي:</strong> {{ number_format($employee->basic_salary, 2) }} ج.م</p>
            <p><strong> تاريخ التوظيف:</strong> {{ $employee->hiring_date }}</p>
            <p><strong> الحالة:</strong>
                <span class="px-3 py-1 rounded text-white text-sm font-semibold
                {{ $employee->status == 'active' ? 'bg-green-500' : 'bg-red-500' }}">
                    {{ $employee->status == 'active' ? 'نشط 🟢' : 'غير نشط 🔴' }}
                </span>
            </p>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('employees.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded shadow">⬅️ رجوع</a>
            <div class="space-x-2">
                <a href="{{ route('employees.edit', $employee->id) }}" class="bg-blue-500 text-white px-6 py-2 rounded shadow">✏️ تعديل</a>
                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded shadow" onclick="return confirm('هل أنت متأكد من حذف هذا الموظف؟')">🗑️ حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
