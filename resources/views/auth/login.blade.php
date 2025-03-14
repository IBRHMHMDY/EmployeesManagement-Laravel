@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
    <div class="flex flex-col mx-auto w-full justify-center items-center bg-white p-6 rounded shadow-lg max-w-[400px]">
        <h2 class="font-extrabold text-4xl text-center mb-10">نظام إدارة الموظفين</h2>
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h2 class="text-2xl font-bold mb-4">تسجيل الدخول</h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="البريد الإلكتروني" class="border p-2 w-full mb-4">
            <input type="password" name="password" placeholder="كلمة المرور" class="border p-2 w-full mb-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full">دخول</button>
        </form>
    </div>
@endsection
