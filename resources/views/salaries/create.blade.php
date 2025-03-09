@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">إضافة مرتب جديد</h2>

    <form action="{{ route('salaries.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="employee_id" class="block text-gray-700">اسم الموظف</label>
            <select name="employee_id" class="w-full border p-2 rounded" required>
                <option value="">اختر الموظف</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="month" class="block text-gray-700">الشهر</label>
            <input type="month" name="month" class="w-full border p-2 rounded" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">حفظ</button>
        <a href="{{ route('salaries.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">رجوع</a>
    </form>
</div>
@endsection
