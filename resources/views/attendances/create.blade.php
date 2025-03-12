@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">إضافة سجل حضور جديد</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('attendances.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">الموظف:</label>
            <select name="employee_id" class="w-full p-2 border border-gray-300 rounded">
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
            @error('employee_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">التاريخ:</label>
            <input type="date" name="date" class="w-full p-2 border border-gray-300 rounded" required>
            @error('date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">وقت الحضور:</label>
            <input type="time" name="check_in" class="w-full p-2 border border-gray-300 rounded" required>
            @error('check_in')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">وقت الانصراف:</label>
            <input type="time" name="check_out" class="w-full p-2 border border-gray-300 rounded">
            @error('check_out')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">حفظ</button>
        <a href="{{ route('attendances.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">إلغاء</a>
    </form>
</div>
@endsection
