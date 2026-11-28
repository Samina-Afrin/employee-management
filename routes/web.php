<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\CompanyAssetController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PerformanceController;

Auth::routes();

// ==================== DASHBOARD ====================
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
});

// ==================== HR MODULES ====================
Route::resource('employee', EmployeeController::class);
Route::resource('department', DepartmentController::class);
Route::resource('designation', DesignationController::class);
Route::resource('attendance', AttendanceController::class);

// ==================== LEAVE ====================
Route::resource('leave', LeaveController::class);

Route::post('leave/{leave}/approve', [LeaveController::class, 'approve'])
    ->name('leave.approve');

Route::post('leave/{leave}/reject', [LeaveController::class, 'reject'])
    ->name('leave.reject');

// ==================== PAYROLL ====================
Route::prefix('payroll')->name('payroll.')->group(function () {

    Route::get('/', [PayrollController::class, 'index'])
        ->name('index');

    Route::get('/create', [PayrollController::class, 'create'])
        ->name('create');

    // Generate Payroll
    Route::post('/generate', [PayrollController::class, 'generate'])
        ->name('generate');

    Route::post('/', [PayrollController::class, 'store'])
        ->name('store');

    Route::get('/month/{month}', [PayrollController::class, 'monthDetail'])
        ->name('month');

    Route::get('/sheet/{month}', [PayrollController::class, 'downloadSheet'])
        ->name('sheet');

    Route::get('/{id}/payslip', [PayrollController::class, 'downloadPayslip'])
        ->name('payslip');

    Route::get('/{payroll}', [PayrollController::class, 'show'])
        ->name('show');

    Route::get('/{payroll}/edit', [PayrollController::class, 'edit'])
        ->name('edit');

    Route::put('/{payroll}', [PayrollController::class, 'update'])
        ->name('update');

    Route::patch('/{payroll}/paid', [PayrollController::class, 'markPaid'])
        ->name('paid');

    Route::delete('/{payroll}', [PayrollController::class, 'destroy'])
        ->name('destroy');
});

// ==================== LOAN ====================
Route::prefix('loan')->name('loan.')->group(function () {

    Route::get('/', [LoanController::class, 'index'])->name('index');
    Route::get('/create', [LoanController::class, 'create'])->name('create');
    Route::post('/', [LoanController::class, 'store'])->name('store');
    Route::get('/{loan}', [LoanController::class, 'show'])->name('show');
    Route::get('/{loan}/edit', [LoanController::class, 'edit'])->name('edit');
    Route::put('/{loan}', [LoanController::class, 'update'])->name('update');

    Route::patch('/{loan}/approve', [LoanController::class, 'approve'])
        ->name('approve');

    Route::patch('/{loan}/reject', [LoanController::class, 'reject'])
        ->name('reject');

    Route::patch('/{loan}/paid', [LoanController::class, 'markPaid'])
        ->name('paid');

    Route::delete('/{loan}', [LoanController::class, 'destroy'])
        ->name('destroy');
});

// ==================== RECRUITMENT ====================
Route::middleware(['auth'])->group(function () {

    Route::get('/recruitment', [RecruitmentController::class, 'index'])
        ->name('recruitment.index');

    Route::get('/recruitment/create', [RecruitmentController::class, 'create'])
        ->name('recruitment.create');

    Route::post('/recruitment', [RecruitmentController::class, 'store'])
        ->name('recruitment.store');

    Route::get('/recruitment/{recruitment}/edit', [RecruitmentController::class, 'edit'])
        ->name('recruitment.edit');

    Route::put('/recruitment/{recruitment}', [RecruitmentController::class, 'update'])
        ->name('recruitment.update');

    Route::delete('/recruitment/{recruitment}', [RecruitmentController::class, 'destroy'])
        ->name('recruitment.destroy');

    Route::get('/recruitment/{recruitment}/applicants', [RecruitmentController::class, 'applicants'])
        ->name('recruitment.applicants');

    Route::post('/recruitment/{recruitment}/apply', [RecruitmentController::class, 'apply'])
        ->name('recruitment.apply');

    Route::patch('/recruitment/{recruitment}/stage', [RecruitmentController::class, 'updateStage'])
        ->name('recruitment.stage');

    Route::post('/recruitment/{recruitment}/convert', [RecruitmentController::class, 'convertToEmployee'])
        ->name('recruitment.convert');
});

// ==================== COMPANY ASSETS ====================
Route::middleware(['auth'])->group(function () {
    Route::resource('company-assets', CompanyAssetController::class);
});

// ==================== TRAINING & PROMOTION ====================
Route::middleware(['auth'])->group(function () {
    Route::resource('trainings', TrainingController::class);
    Route::resource('promotions', PromotionController::class);
});

// ==================== NOTICE ====================
Route::resource('notice', NoticeController::class);

// ==================== PERFORMANCE ====================
Route::middleware(['auth'])->prefix('performance')->name('performance.')->group(function () {

    // Goals List
    Route::get('/goals/list', [PerformanceController::class, 'goalsIndex'])
        ->name('goals.index');

    // Reviews
    Route::get('/', [PerformanceController::class, 'index'])
        ->name('index');

    Route::get('/create', [PerformanceController::class, 'create'])
        ->name('create');

    Route::post('/', [PerformanceController::class, 'store'])
        ->name('store');

    Route::get('/{performance}', [PerformanceController::class, 'show'])
        ->name('show');

    Route::post('/{performance}/submit', [PerformanceController::class, 'submit'])
        ->name('submit');

    Route::post('/{performance}/approve', [PerformanceController::class, 'approve'])
        ->name('approve');

    Route::post('/{performance}/rating', [PerformanceController::class, 'updateRating'])
        ->name('rating');

    // Goals CRUD
    Route::post('/{performance}/goals', [PerformanceController::class, 'storeGoal'])
        ->name('goals.store');

    Route::patch('/goals/{goal}', [PerformanceController::class, 'updateGoal'])
        ->name('goals.update');

    Route::delete('/goals/{goal}', [PerformanceController::class, 'destroyGoal'])
        ->name('goals.destroy');
});