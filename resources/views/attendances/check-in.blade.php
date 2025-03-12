@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">تسجيل الحضور</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('attendances.check-in') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="employee_id" class="block text-gray-700 font-bold">اختر الموظف:</label>
            <select name="employee_id" id="employee_id" class="w-full border p-2 rounded">
                <option value="">اختر موظف...</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }} - {{ $employee->department->name ?? 'غير محدد' }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="shift_id" class="block text-gray-700 font-bold">اختر الوردية:</label>
            <select name="shift_id" id="shift_id" class="w-full border p-2 rounded">
                <option value="">اختر وردية...</option>
                @foreach($shifts as $shift)
                    <option value="{{ $shift->id }}">{{ $employee->shift->name ?? 'غير محدد'}}</option>
                @endforeach
            </select>
        </div>


        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">تسجيل الحضور</button>
    </form>
</div>
@endsection
