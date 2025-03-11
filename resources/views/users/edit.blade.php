@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-3xl font-bold mb-6 text-gray-700">تعديل بيانات المستخدم</h2>
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block">الاسم</label>
            <input type="text" name="name" value="{{ $user->name }}"  class="border p-2 w-full rounded" required>
        </div>
        <div class="mb-4">
            <label class="block">البريد الإلكتروني</label>
            <input type="email" name="email" value="{{ $user->email }}"  class="border p-2 w-full rounded" required>
        </div>
        <div class="mb-4">
            <label class="block">الدور</label>
            <select name="role" class="border p-2 w-full rounded">
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>مستخدم</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>مسؤول</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded">تحديث</button>
    </form>
</div>
@endsection
