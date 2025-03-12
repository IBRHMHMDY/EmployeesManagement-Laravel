@extends('layouts.app')

@section('content')
<div class="max-w-6xl md:max-w-5xl mx-auto bg-white p-6 shadow-lg rounded-lg">
    <h2 class="text-3xl font-bold mb-6 text-gray-700">لوحة التحكم</h2>
    <!-- الإحصائيات -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-blue-500 text-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold">إجمالي الموظفين</h3>
            <p class="text-2xl">{{ $totalEmployees }}</p>
        </div>
        <div class="bg-green-500 text-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold">عدد الأقسام</h3>
            <p class="text-2xl">{{ $totalDepartments }}</p>
        </div>
        <div class="bg-yellow-500 text-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold">الحضور اليوم</h3>
            <p class="text-2xl">{{ $todayAttendances }}</p>
        </div>
        <div class="bg-red-500 text-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold">الإجازات المعتمدة اليوم</h3>
            <p class="text-2xl">{{ $approvedLeavesToday }}</p>
        </div>
        <div class="bg-purple-500 text-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold">إجمالي المرتبات المستحقة</h3>
            <p class="text-2xl">{{ number_format($totalSalaries, 2) }} ج.م</p>
        </div>
        <div class="bg-red-100 p-4 rounded">
            <h3 class="text-xl font-bold text-red-600">إجمالي الغيابات</h3>
            <p>{{ $total_absences }} يوم</p>
        </div>
        <div class="bg-yellow-100 p-4 rounded">
            <h3 class="text-xl font-bold text-yellow-600">إجمالي التأخير</h3>
            <p>{{ $total_late_minutes }} دقيقة</p>
        </div>
        <div class="bg-green-100 p-4 rounded">
            <h3 class="text-xl font-bold text-green-600">إجمالي الإضافي</h3>
            <p>{{ $total_overtime }} ساعة</p>
        </div>
        <div class="bg-blue-100 p-4 rounded">
            <h3 class="text-xl font-bold text-blue-600">إجمالي الخصومات</h3>
            <p>{{ $total_deductions }} جنيه</p>
        </div>
    </div>

    <!-- الرسم البياني -->
    {{-- <div class="mt-8">
        <h3 class="text-xl font-bold mb-4">ساعات العمل والإضافي</h3>
        <canvas id="workHoursChart"></canvas>
    </div> --}}

    <!-- جدول العمليات الأخيرة -->
    {{-- <div class="mt-8">
        <h3 class="text-xl font-bold mb-4">آخر العمليات</h3>
        <table class="w-full border-collapse border mt-4">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">العملية</th>
                    <th class="border p-2">المستخدم</th>
                    <th class="border p-2">التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentActions as $action)
                    <tr>
                        <td class="border p-2">{{ $action->description }}</td>
                        <td class="border p-2">{{ $action->user->name }}</td>
                        <td class="border p-2">{{ $action->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div> --}}

    <!-- إشعارات سريعة -->
    <div class="mt-8">
        <h3 class="text-xl font-bold mb-4">الإشعارات</h3>
        <ul class="list-disc pl-6">
            @foreach($notifications as $notification)
                <li class="text-red-500 font-semibold">{{ $notification }}</li>
            @endforeach
        </ul>
    </div>
</div>
{{--
<!-- سكريبت الرسم البياني -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('workHoursChart').getContext('2d');
    var workHoursChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'ساعات العمل',
                data: @json($chartData),
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        }
    });
</script> --}}
@endsection
