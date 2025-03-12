@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-3xl font-bold mb-6 text-gray-700">تعديل بيانات الموظف</h2>
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
        <form action="{{ route('employees.update', $employee->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block">الاسم:</label>
                <input type="text" name="name" value="{{ $employee->name }}" class="border p-2 w-full rounded">
            </div>

            <div class="mb-4">
                <label class="block">البريد الإلكتروني:</label>
                <input type="email" name="email" value="{{ $employee->email }}" class="border p-2 w-full rounded">
            </div>
            <div class="mb-4">
                <label class="block">رقم الهاتف:</label>
                <input type="text" name="phone" value="{{ $employee->phone }}" class="border p-2 w-full rounded">
            </div>
            <div class="mb-4">
                <label class="block">المسمى الوظيفي:</label>
                <input type="text" name="job_title" value="{{ $employee->job_title }}" class="border p-2 w-full rounded">
            </div>
            <div class="mb-4">
                <label class="block">الراتب الأساسي:</label>
                <input type="number" name="basic_salary" value="{{ $employee->basic_salary }}" class="border p-2 w-full rounded">
            </div>
            <div class="mb-4">
                <label class="block">تاريخ التوظيف:</label>
                <input type="date" name="hiring_date" value="{{ $employee->hiring_date }}" class="border p-2 w-full rounded">
            </div>
            <div class="mb-4">
                <label class="block">القسم:</label>
                <select name="department_id" class="border p-2 w-full rounded">
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ $employee->department_id == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                        @endforeach
                    </select>
            </div>
            <div class="mb-4">
                <label class="block">الوردية:</label>
                <select name="shift_id" class="border p-2 w-full rounded">
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}"
                            {{ $employee->shift_id == $shift->id ? 'selected' : '' }}>
                            {{ $shift->name }}
                        </option>
                        @endforeach
                    </select>
            </div>
            <div class="mb-4">
                <label class="block">حالة الموظف:</label>
                <select name="status" class="border p-2 w-full rounded">
                    <option value="active" {{ $employee->status == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ $employee->status == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded">حفظ التعديلات</button>
        </form>
    </div>
</div>
@endsection
