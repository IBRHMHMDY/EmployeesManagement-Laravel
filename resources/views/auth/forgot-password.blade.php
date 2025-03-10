@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center">نسيت كلمة المرور</h2>

    @if (session('status'))
        <div class="bg-green-100 text-green-700 p-3 rounded mt-3">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('password.request') }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium">البريد الإلكتروني:</label>
            <input type="email" name="email" class="border p-2 w-full rounded" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full">
            إرسال رابط إعادة تعيين كلمة المرور
        </button>
    </form>
</div>
@endsection
