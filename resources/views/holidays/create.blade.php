@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">إضافة إجازة جديدة</h2>
    <form action="{{ route('holidays.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700">اسم الإجازة</label>
            <input type="text" name="name" class="border p-2 rounded w-full" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">تاريخ الإجازة</label>
            <input type="date" name="date" class="border p-2 rounded w-full" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">نوع الإجازة</label>
            <select name="type" class="border p-2 rounded w-full">
                <option value="official">رسمية</option>
                <option value="weekly">أسبوعية</option>
            </select>
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">إضافة</button>
        <a href="{{ route('holidays.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">إلغاء</a>
    </form>
</div>
@endsection
