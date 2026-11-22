<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Payroll;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Main counts
        $totalEmployees = Employee::count();
        $totalDepartments = Department::count();
        $totalDesignations = Designation::count();
        $totalLeaves = Leave::count();
        $totalPayrolls = Payroll::count();

        // Today's attendance
        $todayPresent = Attendance::whereDate('attendance_date', $today)
            ->whereIn('status', [1, 2])->count();
        $todayAbsent = Attendance::whereDate('attendance_date', $today)
            ->where('status', 3)->count();
        $todayLeave = Attendance::whereDate('attendance_date', $today)
            ->where('status', 4)->count();

        // Pending leaves
        $pendingLeaves = Leave::where('status', 1)->count();

        // Current month payroll
        $monthlyPayroll = Payroll::whereMonth('salary_month', $currentMonth)
            ->whereYear('salary_month', $currentYear)
            ->sum('net_salary');

        // Department-wise employees
        $departmentWise = Department::withCount('employees')
            ->orderByDesc('employees_count')
            ->take(5)
            ->get();

        // Recent data
        $recentEmployees = Employee::latest()->take(5)->get();
        $recentLeaves = Leave::with('employee')->latest()->take(5)->get();
        $recentPayrolls = Payroll::with('employee')->latest()->take(5)->get();

        // Time zone data
        $now = Carbon::now();
        $timezone = config('app.timezone', 'UTC');
        $utcTime = Carbon::now('UTC');
        $dhakaTime = Carbon::now('Asia/Dhaka');

        return view('dashboard', compact(
            'totalEmployees',
            'totalDepartments',
            'totalDesignations',
            'totalLeaves',
            'totalPayrolls',
            'todayPresent',
            'todayAbsent',
            'todayLeave',
            'pendingLeaves',
            'monthlyPayroll',
            'departmentWise',
            'recentEmployees',
            'recentLeaves',
            'recentPayrolls',
            'now',
            'timezone',
            'utcTime',
            'dhakaTime'
        ));
    }
}