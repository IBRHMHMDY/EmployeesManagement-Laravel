@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-3xl font-bold mb-6 text-gray-700">إضافة مستخدم جديد</h2>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block">الاسم</label>
            <input type="text" name="name" class="border p-2 w-full rounded" required>
        </div>
        <div class="mb-4">
            <label class="block">البريد الإلكتروني</label>
            <input type="email" name="email" class="border p-2 w-full rounded" required>
        </div>
        <div class="mb-4">
            <label class="block">كلمة المرور</label>
            <input type="password" name="password" class="border p-2 w-full rounded" required>
        </div>
        <div class="mb-4">
            <label class="block">الدور</label>
            <select name="role" class="border p-2 w-full rounded">
                <option value="employee">موظف</option>
                <option value="admin">مسؤول</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded">حفظ</button>
    </form>
</div>
@endsection
