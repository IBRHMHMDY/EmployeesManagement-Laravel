<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeductionsController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\AttendancesController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeavesController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShiftController;

Route::middleware('guest')->group(function() {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

Route::resource('users', UserController::class);



Route::resource('departments', DepartmentsController::class);
Route::resource('employees', EmployeesController::class);
Route::resource('shifts', ShiftController::class);
Route::resource('salaries', SalaryController::class);

// عرض صفحة الحضور والانصراف
Route::prefix('attendances')->group(function () {
    Route::get('/', [AttendancesController::class, 'index'])->name('attendances.index'); // عرض كشف الحضور والانصراف
    Route::get('/check-in', [AttendancesController::class, 'showCheckInPage'])->name('attendances.check-in-page'); // صفحة تسجيل الحضور
    Route::post('/check-in', [AttendancesController::class, 'checkIn'])->name('attendances.check-in'); // تنفيذ تسجيل الحضور
    Route::get('/check-out', [AttendancesController::class, 'showCheckOutPage'])->name('attendances.check-out-page'); // صفحة تسجيل الانصراف
    Route::post('/check-out', [AttendancesController::class, 'checkOut'])->name('attendances.check-out'); // تنفيذ تسجيل الانصراف
    Route::get('/{attendance}/edit', [AttendancesController::class, 'edit'])->name('attendances.edit'); // تعديل
    Route::put('/{attendance}', [AttendancesController::class, 'update'])->name('attendances.update'); // حفظ التعديلات
    Route::delete('/{attendance}', [AttendancesController::class, 'destroy'])->name('attendances.destroy'); // حذف
    Route::get('/attendances/report', [AttendancesController::class, 'report'])->name('attendances.report');
    Route::get('/attendances/export-excel', [AttendancesController::class, 'exportExcel'])->name('attendances.exportExcel');
    Route::get('/attendances/export-pdf', [AttendancesController::class, 'exportPDF'])->name('attendances.exportPDF');
});

Route::resource('salaries', SalaryController::class);
// عرض صفحة الإجازات
Route::get('/leaves', [LeavesController::class, 'index'])->name('leaves.index');
Route::get('/leaves/create', [LeavesController::class, 'create'])->name('leaves.create');
Route::post('/leaves', [LeavesController::class, 'store'])->name('leaves.store');
Route::get('leaves/{leave}/edit', [LeavesController::class, 'edit'])->name('leaves.edit');
Route::put('leaves/{leave}', [LeavesController::class, 'update'])->name('leaves.update');
Route::patch('leaves/{leave}/update-status', [LeavesController::class, 'updateStatus'])->name('leaves.updateStatus');
Route::delete('leaves/{leave}', [LeavesController::class, 'destroy'])->name('leaves.destroy');
// عرض صفحة إدارة الأجازات الرسمية
Route::resource('holidays', HolidayController::class);
// عرض صفحة الاستقطاعات
Route::resource('deductions', DeductionsController::class);
// عرض صفحة الإعدادات
Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
