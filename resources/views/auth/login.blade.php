@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="bg-white p-6 rounded shadow-lg w-96">
    <h2 class="text-2xl font-bold mb-4">تسجيل الدخول</h2>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <input type="email" name="email" placeholder="البريد الإلكتروني" class="border p-2 w-full mb-4">
        <input type="password" name="password" placeholder="كلمة المرور" class="border p-2 w-full mb-4">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full">دخول</button>
    </form>
</div>
@endsection
