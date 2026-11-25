<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeePortalController extends Controller
{
    /**
     * Employee Dashboard
     */
    public function dashboard()
    {
        $employee = $this->getEmployee();

        if (!$employee) {
            return redirect()->route('home')->with('error', 'No employee profile linked to your account.');
        }

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Attendance summary (current month)
        $monthlyAttendances = Attendance::where('employee_id', $employee->id)
            ->whereMonth('attendance_date', $currentMonth)
            ->whereYear('attendance_date', $currentYear)
            ->get();

        $presentDays = $monthlyAttendances->whereIn('status', [1, 2])->count();
        $absentDays = $monthlyAttendances->where('status', 3)->count();
        $leaveDays = $monthlyAttendances->where('status', 4)->count();

        // Leave summary
        $pendingLeaves = Leave::where('employee_id', $employee->id)->where('status', 1)->count();
        $approvedLeaves = Leave::where('employee_id', $employee->id)->where('status', 2)->count();

        // Latest payroll
        $latestPayroll = Payroll::where('employee_id', $employee->id)
            ->latest('salary_month')
            ->first();

        // Active notices
        $notices = Notice::active()
            ->latest('notice_date')
            ->take(5)
            ->get();

        return view('portal.dashboard', compact(
            'employee',
            'presentDays',
            'absentDays',
            'leaveDays',
            'pendingLeaves',
            'approvedLeaves',
            'latestPayroll',
            'notices'
        ));
    }

    /**
     * My Attendance
     */
    public function myAttendance(Request $request)
    {
        $employee = $this->getEmployee();

        if (!$employee) {
            return redirect()->route('home')->with('error', 'No employee profile found.');
        }

        $month = $request->month ?? date('Y-m');

        $attendances = Attendance::where('employee_id', $employee->id)
            ->where('attendance_date', 'like', $month . '%')
            ->orderByDesc('attendance_date')
            ->get();

        $summary = [
            'present' => $attendances->whereIn('status', [1, 2])->count(),
            'absent'  => $attendances->where('status', 3)->count(),
            'leave'   => $attendances->where('status', 4)->count(),
        ];

        return view('portal.attendance', compact('employee', 'attendances', 'month', 'summary'));
    }

    /**
     * My Leaves
     */
    public function myLeaves()
    {
        $employee = $this->getEmployee();

        if (!$employee) {
            return redirect()->route('home')->with('error', 'No employee profile found.');
        }

        $leaves = Leave::where('employee_id', $employee->id)
            ->latest()
            ->paginate(15);

        return view('portal.leaves', compact('employee', 'leaves'));
    }

    /**
     * My Payslips
     */
    public function myPayslips()
    {
        $employee = $this->getEmployee();

        if (!$employee) {
            return redirect()->route('home')->with('error', 'No employee profile found.');
        }

        $payrolls = Payroll::where('employee_id', $employee->id)
            ->latest('salary_month')
            ->paginate(15);

        return view('portal.payslips', compact('employee', 'payrolls'));
    }

    /**
     * View Notice
     */
    public function notices()
    {
        $employee = $this->getEmployee();

        $notices = Notice::active()
            ->latest('notice_date')
            ->paginate(10);

        return view('portal.notices', compact('employee', 'notices'));
    }

    /**
     * Get employee linked to current user
     */
    private function getEmployee()
    {
        return Employee::where('user_id', Auth::id())->first();
    }
}