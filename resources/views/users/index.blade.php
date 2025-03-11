@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow-lg rounded-lg">

    <h2 class="text-2xl font-bold mb-4">إدارة المستخدمين</h2>
    <div class="mb-4">
        <a href="{{ route('users.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">إضافة مستخدم</a>
    </div>

    <table class="w-full border-collapse border border-gray-30">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">الاسم</th>
                <th class="border p-2">البريد الإلكتروني</th>
                <th class="border p-2">الدور</th>
                <th class="border p-2">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="border p-2">{{ $user->name }}</td>
                <td class="border p-2">{{ $user->email }}</td>
                <td class="border p-2 {{ $user->role == 'admin' ? 'text-red-500 font-bold' : '' }} ">{{ $user->role }}</td>
                <td class="border p-2 flex gap-4">
                    <a href="{{ route('users.edit', $user) }}" class="text-blue-600">تعديل</a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="text-red-600">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
