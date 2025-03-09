@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">إدارة الإجازات الرسمية</h2>
    <a href="{{ route('holidays.create') }}" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">إضافة إجازة رسمية</a>

    <table class="w-full border-collapse border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">اسم الإجازة</th>
                <th class="border p-2">التاريخ</th>
                <th class="border p-2">النوع</th>
                <th class="border p-2">الإجراء</th>
            </tr>
        </thead>
        <tbody>
            @foreach($holidays as $holiday)
                <tr>
                    <td class="border p-2">{{ $holiday->name }}</td>
                    <td class="border p-2">{{ $holiday->date }}</td>
                    <td class="border p-2">
                        @if ($holiday->type == 'official')
                            <span class="text-green-500 font-bold">رسمية</span>
                        @else
                            <span class="text-blue-500 font-bold">أسبوعية</span>
                        @endif
                    </td>
                    <td class="border p-2">
                        <a href="{{ route('holidays.edit', $holiday->id) }}" class="text-blue-500">تعديل</a> |
                        <form method="POST" action="{{ route('holidays.destroy', $holiday->id) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
