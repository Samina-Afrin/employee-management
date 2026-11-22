<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::select(
                'salary_month',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(gross_salary) as total_gross'),
                DB::raw('SUM(net_salary) as total_net'),
                DB::raw('SUM(CASE WHEN payment_status=2 THEN 1 ELSE 0 END) as paid_count'),
                DB::raw('SUM(CASE WHEN payment_status=1 THEN 1 ELSE 0 END) as pending_count')
            )
            ->groupBy('salary_month')
            ->orderByDesc('salary_month');

        if ($request->filled('month')) {
            $query->whereDate('salary_month', $request->month . '-01');
        }

        $summaries = $query->paginate(12);
        return view('payroll.index', compact('summaries'));
    }

    public function create(Request $request)
    {
        $month = $request->month ?? date('Y-m');
        $employees = Employee::orderBy('name')->get();
        $monthDate = $month . '-01';

        $existing = Payroll::whereDate('salary_month', $monthDate)
            ->get()
            ->keyBy('employee_id');

        return view('payroll.create', compact('employees', 'month', 'existing'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'salary_month' => 'required|date_format:Y-m',
            'payroll' => 'required|array',
            'payroll.*.per_day_salary' => 'required|numeric|min:0',
            'payroll.*.working_days' => 'required|integer|min:1',
        ]);

        $month = $request->salary_month;
        $monthDate = $month . '-01';
        $monthCarbon = Carbon::parse($monthDate);

        $saved = 0;
        DB::beginTransaction();
        try {
            foreach ($request->payroll as $employeeId => $row) {
                $attendances = Attendance::where('employee_id', $employeeId)
                    ->whereMonth('attendance_date', $monthCarbon->month)
                    ->whereYear('attendance_date', $monthCarbon->year)
                    ->get();

                // শুধু Absent (status=3) — Leave (status=4) বাদ
                $absentDays = $attendances->where('status', 3)->count();

                // Present (1) + Late (2) + Leave (4) — Leave নিলে কাটবে না
                $presentDays = $attendances->whereIn('status', [1, 2, 4])->count();

                $perDay = (float) ($row['per_day_salary'] ?? 0);
                $workingDays = (int) ($row['working_days'] ?? 30);

                // Attendance ডাটা না থাকলে full month ধরি
                if ($attendances->count() == 0) {
                    $presentDays = $workingDays - $absentDays;
                }

                $basic = $perDay * $presentDays;
                $house = $basic * 0.50;
                $medical = $basic * 0.10;
                $transport = $basic * 0.05;
                $other = (float) ($row['other_allowance'] ?? 0);

                $gross = $basic + $house + $medical + $transport + $other;

                $taxPercent = (float) ($row['tax_percent'] ?? 0);
                $taxDeduction = $gross * ($taxPercent / 100);

                // শুধু Absent-এর জন্য deduction
                $absenceDeduction = $perDay * $absentDays;
                $loanDeduction = (float) ($row['loan_deduction'] ?? 0);

                $net = $gross - $taxDeduction - $absenceDeduction - $loanDeduction;
                $net = $net < 0 ? 0 : $net;

                Payroll::updateOrCreate(
                    [
                        'employee_id' => $employeeId,
                        'salary_month' => $monthDate,
                    ],
                    [
                        'per_day_salary'      => $perDay,
                        'working_days'        => $workingDays,
                        'present_days'        => $presentDays,   // Present + Leave
                        'absent_days'         => $absentDays,    // শুধু Absent
                        'basic_salary'        => $basic,
                        'house_rent'          => $house,
                        'medical_allowance'   => $medical,
                        'transport_allowance' => $transport,
                        'other_allowance'     => $other,
                        'gross_salary'        => $gross,
                        'tax_percent'         => $taxPercent,
                        'tax_deduction'       => $taxDeduction,
                        'absence_deduction'   => $absenceDeduction,
                        'loan_deduction'      => $loanDeduction,
                        'net_salary'          => $net,
                        'payment_status'      => 1,
                    ]
                );
                $saved++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
        }

        return redirect()->route('payroll.index')
            ->with('success', "Payroll generated for {$saved} employees for {$month}.");
    }

    public function show($id)
    {
        $payroll = Payroll::with('employee.department')->findOrFail($id);
        return view('payroll.show', compact('payroll'));
    }

    public function downloadPayslip($id)
    {
        $payroll = Payroll::with('employee.department')->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payroll.payslip-pdf', compact('payroll'))
            ->setPaper('A4', 'portrait');

        $filename = 'payslip_' . ($payroll->employee->employee_code ?? 'emp') . '_' . \Carbon\Carbon::parse($payroll->salary_month)->format('Y_m') . '.pdf';

        return $pdf->download($filename);
    }

    public function downloadSheet($month)
    {
        $monthDate = $month . '-01';
        $records = Payroll::with('employee.department')
            ->whereDate('salary_month', $monthDate)
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payroll.sheet-pdf', compact('records', 'month'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('payroll_sheet_' . $month . '.pdf');
    }

    public function edit($id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);
        return view('payroll.edit', compact('payroll'));
    }

    public function update(Request $request, $id)
    {
        $payroll = Payroll::findOrFail($id);

        $data = $request->validate([
            'per_day_salary'      => 'required|numeric|min:0',
            'working_days'        => 'required|integer|min:1',
            'tax_percent'         => 'nullable|numeric|min:0|max:100',
            'loan_deduction'      => 'nullable|numeric|min:0',
            'other_allowance'     => 'nullable|numeric|min:0',
            'payment_status'      => 'required|in:1,2',
            'payment_date'        => 'nullable|date',
        ]);

        $perDay = (float) $data['per_day_salary'];
        $workingDays = (int) $data['working_days'];
        $absentDays = $payroll->absent_days;
        $presentDays = $workingDays - $absentDays;  // Leave বাদ, শুধু Absent

        $basic = $perDay * $presentDays;
        $house = $basic * 0.50;
        $medical = $basic * 0.10;
        $transport = $basic * 0.05;
        $other = $data['other_allowance'] ?? 0;

        $gross = $basic + $house + $medical + $transport + $other;
        $taxDeduction = $gross * (($data['tax_percent'] ?? 0) / 100);
        $absenceDeduction = $perDay * $absentDays;
        $loanDeduction = $data['loan_deduction'] ?? 0;

        $net = $gross - $taxDeduction - $absenceDeduction - $loanDeduction;
        $net = $net < 0 ? 0 : $net;

        $payroll->update([
            'per_day_salary'      => $perDay,
            'working_days'        => $workingDays,
            'present_days'        => $presentDays,
            'basic_salary'        => $basic,
            'house_rent'          => $house,
            'medical_allowance'   => $medical,
            'transport_allowance' => $transport,
            'other_allowance'     => $other,
            'gross_salary'        => $gross,
            'tax_percent'         => $data['tax_percent'] ?? 0,
            'tax_deduction'       => $taxDeduction,
            'absence_deduction'   => $absenceDeduction,
            'loan_deduction'      => $loanDeduction,
            'net_salary'          => $net,
            'payment_status'      => $data['payment_status'],
            'payment_date'        => $data['payment_date'] ?? null,
        ]);

        return redirect()->route('payroll.index')
            ->with('success', 'Payroll updated successfully.');
    }

    public function destroy($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();
        return redirect()->route('payroll.index')
            ->with('success', 'Payroll deleted successfully.');
    }

    public function monthDetail($month)
    {
        $monthDate = $month . '-01';
        $records = Payroll::with('employee.department')
            ->whereDate('salary_month', $monthDate)
            ->get();

        return view('payroll.month-detail', compact('records', 'month'));
    }
}