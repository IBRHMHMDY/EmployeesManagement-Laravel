<aside class="w-64 bg-white shadow-md h-screen p-4">
    <ul>
        <li class="mb-4"><a href="{{ route('dashboard') }}" class="block p-2 bg-blue-500 text-white rounded">الرئيسية</a></li>
        <li class="mb-4"><a href="{{ route('employees.index') }}" class="block p-2 hover:bg-gray-200 rounded">الموظفين</a></li>
        <li class="mb-4"><a href="{{ route('departments.index') }}" class="block p-2 hover:bg-gray-200 rounded">الأقسام</a></li>
        <li class="mb-4"><a href="{{ route('attendances.index') }}" class="block p-2 hover:bg-gray-200 rounded">كشف الحضور والإنصراف</a></li>
        <li class="mb-4"><a href="{{ route('salaries.index') }}" class="block p-2 hover:bg-gray-200 rounded">كشف المرتبات</a></li>
        <li class="mb-4"><a href="{{ route('leaves.index') }}" class="block p-2 hover:bg-gray-200 rounded">كشف الاجازات</a></li>
        <li class="mb-4"><a href="{{ route('holidays.index') }}" class="block p-2 hover:bg-gray-200 rounded">قائمة الأجازات الرسمية</a></li>
        <li class="mb-4"><a href="{{ route('settings.index') }}" class="block p-2 hover:bg-gray-200 rounded">الإعدادات</a></li>
    </ul>
</aside>
