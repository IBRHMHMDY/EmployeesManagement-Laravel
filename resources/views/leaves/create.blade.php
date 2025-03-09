@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">تقديم طلب إجازة</h2>
    <form method="POST" action="{{ route('leaves.store') }}">
        @csrf
        <label class="block mb-2">الموظف:</label>
        <select name="employee_id" class="border p-2 rounded w-full">
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
            @endforeach
        </select>

        <label class="block mt-4">نوع الإجازة:</label>
        <input type="text" name="leave_type" class="border p-2 rounded w-full" required>

        <label class="block mt-4">بداية الإجازة:</label>
        <input type="date" name="start_date" class="border p-2 rounded w-full" required>

        <label class="block mt-4">نهاية الإجازة:</label>
        <input type="date" name="end_date" class="border p-2 rounded w-full" required>

        <label class="block mt-4">سبب الإجازة:</label>
        <textarea name="reason" class="border p-2 rounded w-full"></textarea>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded mt-4">إرسال الطلب</button>
    </form>
</div>
@endsection
