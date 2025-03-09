@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">تعديل بيانات الإجازة</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('leaves.update', $leave->id) }}">
        @csrf
        @method('PUT')

        <!-- اختيار الموظف -->
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">الموظف</label>
            <select name="employee_id" class="border p-2 rounded w-full">
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" {{ $leave->employee_id == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- نوع الإجازة -->
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">نوع الإجازة</label>
            <input type="text" name="leave_type" class="border p-2 rounded w-full" value="{{ $leave->leave_type }}">
        </div>

        <!-- تاريخ البداية -->
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">بداية الإجازة</label>
            <input type="date" name="start_date" class="border p-2 rounded w-full" value="{{ $leave->start_date }}">
        </div>

        <!-- تاريخ النهاية -->
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">نهاية الإجازة</label>
            <input type="date" name="end_date" class="border p-2 rounded w-full" value="{{ $leave->end_date }}">
        </div>

        <!-- حالة الإجازة -->
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">الحالة</label>
            <select name="status" class="border p-2 rounded w-full">
                <option value="قيد الإنتظار" {{ $leave->status == 'قيد الإنتظار' ? 'selected' : '' }}>قيد الانتظار</option>
                <option value="تم الموافقة" {{ $leave->status == 'تم الموافقة' ? 'selected' : '' }}>موافقة</option>
                <option value="تم الرفض" {{ $leave->status == 'تم الرفض' ? 'selected' : '' }}>مرفوضة</option>
            </select>
        </div>

        <!-- زر الحفظ -->
        <div class="flex justify-between items-center">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">💾 حفظ التعديلات</button>
            <a href="{{ route('leaves.index') }}" class="text-gray-700">🔙 الرجوع</a>
        </div>
    </form>
</div>
@endsection
