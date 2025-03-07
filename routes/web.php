<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\EmployeesController;

// تسجيل مستخدم جديد
Route::post('/register',[AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// عرض لوحة التحكم
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
// حماية المسارات باستخدام Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // مسار مخصص للمدير فقط
    Route::get('/admin-only', function () {
        return response()->json(['message' => 'مرحبًا أيها المدير']);
    })->middleware('role:admin');

    // مسار مخصص للموظفين فقط
    Route::get('/employee-only', function () {
        return response()->json(['message' => 'مرحبًا أيها الموظف']);
    })->middleware('role:employee');
});

Route::resource('departments', DepartmentsController::class);
Route::resource('employees', EmployeesController::class);
Route::resource('salaries', SalariesController::class);
Route::resource('attendances', AttendancesController::class);
