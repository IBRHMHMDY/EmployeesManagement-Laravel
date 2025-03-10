@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">إضافة خصم جديد</h2>

    <form method="POST" action="{{ route('deductions.store') }}">
        @csrf

        <label class="block mb-2">الموظف</label>
        <select name="employee_id" class="border p-2 rounded w-full">
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
            @endforeach
        </select>

        <label class="block mt-4">سبب الخصم</label>
        <input type="text" name="reason" class="border p-2 rounded w-full" required>

        <label class="block mt-4">المبلغ</label>
        <input type="number" name="amount" class="border p-2 rounded w-full" required step="0.01">

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">حفظ</button>
    </form>
</div>
@endsection
