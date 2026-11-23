<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year  ?? now()->year;

        $payrolls = Payroll::with('employee.department', 'employee.designation')
            ->where('month', $month)
            ->where('year', $year)
            ->latest()
            ->paginate(20);

        return view('payroll.index', compact('payrolls', 'month', 'year'));
    }

    public function create()
    {
        $employees = Employee::where('status', 1)
            ->with('department', 'designation')
            ->get();

        return view('payroll.create', compact('employees'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year'  => 'required|integer|digits:4',
            'default_allowances.*.title'  => 'nullable|string|max:255',
            'default_allowances.*.amount' => 'nullable|numeric|min:0',
            'default_deductions.*.title'  => 'nullable|string|max:255',
            'default_deductions.*.amount' => 'nullable|numeric|min:0',
        ]);

        $employees = Employee::where('status', 1)->get();
        $count = 0;

        $defaultAllowances = collect($request->default_allowances ?? [])
            ->filter(fn($a) => !empty($a['title']) && isset($a['amount']) && $a['amount'] !== '');
        $defaultDeductions = collect($request->default_deductions ?? [])
            ->filter(fn($d) => !empty($d['title']) && isset($d['amount']) && $d['amount'] !== '');

        foreach ($employees as $employee) {
            $exists = Payroll::where('employee_id', $employee->id)
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->exists();

            if ($exists) continue;

            $basic = $employee->monthly_salary ?? 0;

            $allowanceSum = $defaultAllowances->sum(fn($a) => (float) $a['amount']);
            $deductionSum = $defaultDeductions->sum(fn($d) => (float) $d['amount']);

            $gross = $basic + $allowanceSum ?? 0;
            $net   = $gross - $deductionSum ?? 0;

            $payroll = Payroll::create([
                'employee_id'     => $employee->id,
                'month'           => $request->month,
                'year'            => $request->year,
                'basic_salary'    => $basic,
                'total_allowance' => $allowanceSum,
                'total_deduction' => $deductionSum,
                'gross_salary'    => $gross,
                'net_salary'      => $net,
                'status'          => 'pending',
            ]);

            foreach ($defaultAllowances as $a) {
                $payroll->details()->create([
                    'type'   => 'allowance',
                    'title'  => $a['title'],
                    'amount' => $a['amount'],
                ]);
            }
            foreach ($defaultDeductions as $d) {
                $payroll->details()->create([
                    'type'   => 'deduction',
                    'title'  => $d['title'],
                    'amount' => $d['amount'],
                ]);
            }

            $count++;
        }

        return redirect()->route('payroll.index', [
            'month' => $request->month,
            'year'  => $request->year,
        ])->with('success', "$count payroll(s) generated successfully.");
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('employee.department', 'employee.designation', 'details');
        return view('payroll.show', compact('payroll'));
    }

    public function edit(Payroll $payroll)
    {
        $payroll->load('details', 'employee');
        return view('payroll.edit', compact('payroll'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'allowances.*.title'  => 'nullable|string|max:255',
            'allowances.*.amount' => 'nullable|numeric|min:0',
            'deductions.*.title'  => 'nullable|string|max:255',
            'deductions.*.amount' => 'nullable|numeric|min:0',
        ]);

        $payroll->details()->delete();

        $totalAllowance = 0;
        $totalDeduction = 0;

        foreach ($request->allowances ?? [] as $a) {
            if (empty($a['title']) || !isset($a['amount']) || $a['amount'] === '') continue;
            $payroll->details()->create([
                'type'   => 'allowance',
                'title'  => $a['title'],
                'amount' => $a['amount'],
            ]);
            $totalAllowance += (float) $a['amount'];
        }

        foreach ($request->deductions ?? [] as $d) {
            if (empty($d['title']) || !isset($d['amount']) || $d['amount'] === '') continue;
            $payroll->details()->create([
                'type'   => 'deduction',
                'title'  => $d['title'],
                'amount' => $d['amount'],
            ]);
            $totalDeduction += (float) $d['amount'];
        }

        $gross = $payroll->basic_salary + $totalAllowance;
        $net   = $gross - $totalDeduction;

        $payroll->update([
            'total_allowance' => $totalAllowance,
            'total_deduction' => $totalDeduction,
            'gross_salary'    => $gross,
            'net_salary'      => $net,
        ]);

        return redirect()->route('payroll.show', $payroll)
            ->with('success', 'Payroll updated successfully.');
    }

    public function markPaid(Payroll $payroll)
    {
        $payroll->update([
            'status'       => 'paid',
            'payment_date' => now(),
        ]);

        return back()->with('success', 'Payroll marked as paid.');
    }

    public function payslip(Payroll $payroll)
    {
        $payroll->load('employee.department', 'employee.designation', 'details');
        return view('payroll.payslip', compact('payroll'));
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return back()->with('success', 'Payroll deleted.');
    }
}