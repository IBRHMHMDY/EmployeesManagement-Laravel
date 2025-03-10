@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-3xl font-bold mb-6 text-gray-700">إضافة موظف جديد</h2>
    @if ($errors->any())
        <div class="bg-red-500 text-white p-2 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="bg-white shadow-lg rounded-lg p-6">
        <form action="{{ route('employees.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block">الاسم:</label>
                <input type="text" name="name" class="border p-2 w-full rounded">
            </div>

            <div class="mb-4">
                <label class="block">البريد الإلكتروني:</label>
                <input type="email" name="email" class="border p-2 w-full rounded">
            </div>

            <div class="mb-4">
                <label class="block">رقم الهاتف:</label>
                <input type="text" name="phone" class="border p-2 w-full rounded">
            </div>

            <div class="mb-4">
                <label class="block">المسمى الوظيفي:</label>
                <input type="text" name="job_title" class="border p-2 w-full rounded">
            </div>

            <div class="mb-4">
                <label class="block">تاريخ التوظيف:</label>
                <input type="date" name="hiring_date" value="{{ request('date, ') }}" class="border p-2 w-full rounded">
            </div>

            <div class="mb-4">
                <label class="block">الراتب الأساسي:</label>
                <input type="number" name="basic_salary" class="border p-2 w-full rounded">
            </div>

            <div class="mb-4">
                <label class="block">القسم:</label>
                <select name="department_id" class="border p-2 w-full rounded">
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block">حالة الموظف:</label>
                <select name="status" class="border p-2 w-full rounded">
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded">إضافة</button>
        </form>
    </div>
</div>
@endsection
