@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">إعدادات النظام</h2>

    @if(session('success'))
        <div class="bg-green-500 text-white p-2 mb-4">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}">
        @csrf
        @foreach($settings as $setting)
            <div class="mb-4">
                <label class="block font-semibold">{{ $setting->label }}</label>
                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}"
                       class="w-full p-2 border rounded">
            </div>
        @endforeach
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">حفظ التغييرات</button>
    </form>
</div>
@endsection
