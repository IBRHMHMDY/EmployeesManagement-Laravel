@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-xl font-bold mb-4">إدارة الخصومات</h2>
    <a href="{{ route('deductions.create') }}" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">
        + إضافة خصم جديد
    </a>

    <table class="w-full border-collapse border mt-4">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">الموظف</th>
                <th class="border p-2">المبلغ</th>
                <th class="border p-2">سبب الخصم</th>
                <th class="border p-2">الإجراء</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deductions as $deduction)
                <tr>
                    <td class="border p-2">{{ $deduction->employee->name }}</td>
                    <td class="border p-2">{{ $deduction->amount }} ج.م</td>
                    <td class="border p-2">{{ $deduction->reason }}</td>
                    <td class="border p-2">
                        <a href="{{ route('deductions.edit', $deduction->id) }}" class="text-blue-500">تعديل</a> |
                        <form method="POST" action="{{ route('deductions.destroy', $deduction->id) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $deductions->links() }}
    </div>
</div>
@endsection
