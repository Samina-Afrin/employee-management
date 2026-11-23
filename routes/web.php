<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [\App\Http\Controllers\DashboardController::class, 'index'])->name('home');
});

Route::resource('employee', EmployeeController::class);
Route::resource('department', DepartmentController::class);
Route::resource('designation', DesignationController::class);
Route::resource('attendance', AttendanceController::class);
Route::resource('leave', LeaveController::class);
Route::post('leave/{leave}/approve', [LeaveController::class, 'approve'])->name('leave.approve');
Route::post('leave/{leave}/reject', [LeaveController::class, 'reject'])->name('leave.reject');

// Payroll Routes
Route::prefix('payroll')->name('payroll.')->group(function () {
    Route::get('/', [PayrollController::class, 'index'])->name('index');
    Route::get('/create', [PayrollController::class, 'create'])->name('create');
    Route::post('/generate', [PayrollController::class, 'generate'])->name('generate');
    Route::get('/{payroll}/payslip', [PayrollController::class, 'payslip'])->name('payslip');  // ⬅ age
    Route::get('/{payroll}', [PayrollController::class, 'show'])->name('show');
    Route::get('/{payroll}/edit', [PayrollController::class, 'edit'])->name('edit');
    Route::put('/{payroll}', [PayrollController::class, 'update'])->name('update');
    Route::patch('/{payroll}/paid', [PayrollController::class, 'markPaid'])->name('paid');
    Route::delete('/{payroll}', [PayrollController::class, 'destroy'])->name('destroy');
});