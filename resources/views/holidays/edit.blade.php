@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">تعديل الإجازة</h2>
    <form action="{{ route('holidays.update', $holiday->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700">اسم الإجازة</label>
            <input type="text" name="name" class="border p-2 rounded w-full" value="{{ $holiday->name }}" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">تاريخ الإجازة</label>
            <input type="date" name="date" class="border p-2 rounded w-full" value="{{ $holiday->date }}" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">نوع الإجازة</label>
            <select name="type" class="border p-2 rounded w-full">
                <option value="official" {{ $holiday->type == 'official' ? 'selected' : '' }}>رسمية</option>
                <option value="weekly" {{ $holiday->type == 'weekly' ? 'selected' : '' }}>أسبوعية</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">تحديث</button>
        <a href="{{ route('holidays.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">إلغاء</a>
    </form>
</div>
@endsection
