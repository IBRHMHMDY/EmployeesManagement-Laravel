<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير الحضور والانصراف</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* لضمان دعم العربية في PDF */
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2 style="text-align: center;">تقرير الحضور والانصراف</h2>

    <table>
        <thead>
            <tr>
                <th>الموظف</th>
                <th>القسم</th>
                <th>التاريخ</th>
                <th>وقت الحضور</th>
                <th>وقت الانصراف</th>
                <th>التأخير (دقائق)</th>
                <th>عدد الساعات</th>
                <th>الإضافي (دقائق)</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->employee->name }}</td>
                    <td>{{ $attendance->employee->department->name ?? 'غير محدد' }}</td>
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->check_in }}</td>
                    <td>{{ $attendance->check_out ?? '---' }}</td>
                    <td>{{ $attendance->late_minutes }}</td>
                    <td>{{ $attendance->working_hours }}</td>
                    <td>{{ $attendance->overtime_minutes }}</td>
                    <td>{{ $attendance->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
