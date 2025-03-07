@extends('layouts.app')

@section('title', 'إضافة قسم جديد')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-4">إضافة قسم جديد</h1>

    <form action="{{ route('departments.store') }}" method="POST">
        @csrf
        <label class="block mb-2">اسم القسم:</label>
        <input type="text" name="name" class="border p-2 w-full mb-4" required>

        <button type="submit" class="p-2 bg-green-500 text-white rounded">حفظ</button>
    </form>
</div>
@endsection
