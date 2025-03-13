@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">تسجيل الانصراف</h2>
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border-collapse border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">الموظف</th>
                <th class="border p-2">القسم</th>
                <th class="border p-2">وقت الحضور</th>
                <th class="border p-2">إجراء</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
                <tr>
                    <td class="border p-2">{{ $attendance->employee->name }}</td>
                    <td class="border p-2">{{ $attendance->employee->department->name ?? 'غير محدد' }}</td>
                    <td class="border p-2">{{ $attendance->check_in }}</td>
                    <td class="border p-2">
                        <form method="POST" action="{{ route('attendances.check-out') }}">
                            @csrf
                            <input type="hidden" name="employee_id" value="{{ $attendance->employee->id ?? '' }}">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">تسجيل الانصراف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
