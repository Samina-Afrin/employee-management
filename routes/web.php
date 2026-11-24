
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

Auth::routes();

// Dashboard Routes
Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/home', [DashboardController::class, 'index'])
        ->name('home');
});

// Employee Routes
Route::resource('employee', EmployeeController::class);

// Department Routes
Route::resource('department', DepartmentController::class);

// Designation Routes
Route::resource('designation', DesignationController::class);

// Attendance Routes
Route::resource('attendance', AttendanceController::class);

// Leave Routes
Route::resource('leave', LeaveController::class);

Route::post('leave/{leave}/approve', [LeaveController::class, 'approve'])
    ->name('leave.approve');

Route::post('leave/{leave}/reject', [LeaveController::class, 'reject'])
    ->name('leave.reject');


// Payroll Routes
Route::prefix('payroll')->name('payroll.')->group(function () {

    Route::get('/', [PayrollController::class, 'index'])->name('index');

    Route::get('/create', [PayrollController::class, 'create'])->name('create');

    Route::post('/generate', [PayrollController::class, 'generate'])->name('generate');

    Route::get('/{payroll}/payslip', [PayrollController::class, 'payslip'])
        ->name('payslip');

    Route::get('/{payroll}', [PayrollController::class, 'show'])->name('show');

    Route::get('/{payroll}/edit', [PayrollController::class, 'edit'])->name('edit');

    Route::put('/{payroll}', [PayrollController::class, 'update'])->name('update');

    Route::patch('/{payroll}/paid', [PayrollController::class, 'markPaid'])
        ->name('paid');

    Route::delete('/{payroll}', [PayrollController::class, 'destroy'])
        ->name('destroy');
});


// Loan Routes
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


// Recruitment Routes
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
