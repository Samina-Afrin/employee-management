<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/home', [HomeController::class, 'index'])->name('home');
});


Route::resource('employee', EmployeeController::class);


Route::resource('department', DepartmentController::class);


Route::resource('designation', DesignationController::class);