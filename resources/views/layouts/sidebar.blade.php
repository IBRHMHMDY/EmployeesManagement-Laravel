<aside class="bg-gray-800 text-white w-64 min-h-screen p-4">
    <ul>
        <li class="mb-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:bg-gray-700 p-2 rounded">
                <span class="material-icons">home</span> الرئيسية
            </a>
        </li>
        <li class="mb-4">
            <a href="{{ route('employees.index') }}" class="flex items-center gap-2 hover:bg-gray-700 p-2 rounded">
                <span class="material-icons">person</span> إدارة الموظفين
            </a>
        </li>
        <li class="mb-4">
            <a href="{{ route('departments.index') }}" class="flex items-center gap-2 hover:bg-gray-700 p-2 rounded">
                <span class="material-icons">business</span> الأقسام
            </a>
        </li>
        <li class="mb-4">
            <a href="{{ route('attendances.index') }}" class="flex items-center gap-2 hover:bg-gray-700 p-2 rounded">
                <span class="material-icons">schedule</span> الحضور والانصراف
            </a>
        </li>
        <li class="mb-4">
            <a href="{{ route('salaries.index') }}" class="flex items-center gap-2 hover:bg-gray-700 p-2 rounded">
                <span class="material-icons">money</span> كشف المرتبات
            </a>
        </li>
        <li class="mb-4">
            <a href="{{ route('leaves.index') }}" class="flex items-center gap-2 hover:bg-gray-700 p-2 rounded">
                <span class="material-icons">today</span> الإجازات
            </a>
        </li>
        <li class="mb-4">
            <a href="{{ route('holidays.index') }}" class="flex items-center gap-2 hover:bg-gray-700 p-2 rounded">
                <span class="material-icons">event</span>الاجازات الرسمية
            </a>
        </li>
        <li class="mb-4">
            <a href="{{ route('deductions.index') }}" class="flex items-center gap-2 hover:bg-gray-700 p-2 rounded">
                <span class="material-icons">money_off</span> الخصومات
            </a>
        </li>
    </ul>
</aside>






{{-- <aside class="w-64 bg-white shadow-md h-screen p-4">
    <ul>
        <li class="mb-4"><a href="{{ route('dashboard') }}" class="block p-2 bg-blue-500 text-white rounded">الرئيسية</a></li>
        <li class="mb-4"><a href="{{ route('departments.index') }}" class="block p-2 hover:bg-gray-200 rounded">الأقسام</a></li>
        <li class="mb-4"><a href="{{ route('employees.index') }}" class="block p-2 hover:bg-gray-200 rounded">الموظفين</a></li>
        <li class="mb-4"><a href="{{ route('attendances.index') }}" class="block p-2 hover:bg-gray-200 rounded">كشف الحضور والإنصراف</a></li>
        <li class="mb-4"><a href="{{ route('salaries.index') }}" class="block p-2 hover:bg-gray-200 rounded">كشف المرتبات</a></li>
        <li class="mb-4"><a href="{{ route('leaves.index') }}" class="block p-2 hover:bg-gray-200 rounded">كشف الاجازات</a></li>
        <li class="mb-4"><a href="{{ route('holidays.index') }}" class="block p-2 hover:bg-gray-200 rounded">قائمة الأجازات الرسمية</a></li>
        <li class="mb-4"><a href="{{ route('deductions.index') }}" class="block p-2 hover:bg-gray-200 rounded">إدارة الخصومات</a></li>
        <li class="mb-4"><a href="{{ route('settings.index') }}" class="block p-2 hover:bg-gray-200 rounded">الإعدادات</a></li>
    </ul>
</aside> --}}
