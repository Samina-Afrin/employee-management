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

// Payroll
Route::resource('payroll', PayrollController::class);
Route::get('payroll/month/{month}', [PayrollController::class, 'monthDetail'])->name('payroll.month');
Route::get('payroll/{id}/payslip', [PayrollController::class, 'downloadPayslip'])->name('payroll.payslip');
Route::get('payroll/sheet/{month}', [PayrollController::class, 'downloadSheet'])->name('payroll.sheet');