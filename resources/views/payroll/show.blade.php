@extends('layouts.master')

@section('title', 'Payslip')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
        <h4>Payslip — {{ \Carbon\Carbon::parse($payroll->salary_month)->format('F Y') }}</h4>
        <div>
            <a href="{{ route('payroll.payslip', $payroll->id) }}" class="btn btn-danger">
                <i class="bi bi-file-pdf"></i> Download PDF
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Print
            </button>
            <a href="{{ route('payroll.edit', $payroll) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <!-- Payslip -->
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4">
                <h3 class="mb-1">EMPLOYEE MANAGEMENT SYSTEM</h3>
                <p class="mb-0">Payslip — {{ \Carbon\Carbon::parse($payroll->salary_month)->format('F Y') }}</p>
            </div>

            <table class="table table-sm">
                <tr>
                    <td><strong>Employee Name:</strong> {{ $payroll->employee->name ?? '—' }}</td>
                    <td><strong>Employee Code:</strong> {{ $payroll->employee->employee_code ?? '—' }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Department:</strong> {{ $payroll->employee->department->department_name ?? '—' }}</td>
                </tr>
            </table>

            <div class="alert alert-info">
                <div class="row text-center">
                    <div class="col">
                        <strong>Basic Salary (Monthly)</strong><br>
                        ৳ {{ number_format($payroll->per_day_salary * 30, 2) }}
                    </div>
                    <div class="col"><strong>Working Days</strong><br>{{ $payroll->working_days }}</div>
                    <div class="col"><strong>Present</strong><br><span class="text-success">{{ $payroll->present_days }}</span></div>
                    <div class="col"><strong>Absent</strong><br><span class="text-danger">{{ $payroll->absent_days }}</span></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-success">Earnings</h5>
                    <table class="table table-bordered table-sm">
                        <tr><th>Basic ({{ $payroll->present_days }} × {{ number_format($payroll->per_day_salary, 2) }})</th><td class="text-end">{{ number_format($payroll->basic_salary, 2) }}</td></tr>
                        <tr><th>House Rent (50%)</th><td class="text-end">{{ number_format($payroll->house_rent, 2) }}</td></tr>
                        <tr><th>Medical (10%)</th><td class="text-end">{{ number_format($payroll->medical_allowance, 2) }}</td></tr>
                        <tr><th>Transport (5%)</th><td class="text-end">{{ number_format($payroll->transport_allowance, 2) }}</td></tr>
                        <tr><th>Other</th><td class="text-end">{{ number_format($payroll->other_allowance, 2) }}</td></tr>
                        <tr class="table-secondary"><th>Gross Salary</th><th class="text-end">{{ number_format($payroll->gross_salary, 2) }}</th></tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="text-danger">Deductions</h5>
                    <table class="table table-bordered table-sm">
                        <tr><th>Tax ({{ $payroll->tax_percent }}%)</th><td class="text-end">{{ number_format($payroll->tax_deduction, 2) }}</td></tr>
                        <tr><th>Absence ({{ $payroll->absent_days }} × {{ number_format($payroll->per_day_salary, 2) }})</th><td class="text-end">{{ number_format($payroll->absence_deduction, 2) }}</td></tr>
                        <tr><th>Loan</th><td class="text-end">{{ number_format($payroll->loan_deduction, 2) }}</td></tr>
                        <tr class="table-secondary"><th>Total Deductions</th>
                            <th class="text-end">{{ number_format($payroll->tax_deduction + $payroll->absence_deduction + $payroll->loan_deduction, 2) }}</th></tr>
                    </table>
                </div>
            </div>

            <div class="alert alert-primary text-center">
                <h4 class="mb-1">Net Salary: ৳ {{ number_format($payroll->net_salary, 2) }}</h4>
                <p class="mb-0"><em>{{ $payroll->net_salary_words }}</em></p>
            </div>

            <table class="table table-bordered table-sm mt-3">
                <tr><th width="200">Payment Status</th>
                    <td>
                        @if($payroll->payment_status == 2)
                            <span class="badge bg-success">Paid</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                </tr>
                <tr><th>Payment Date</th><td>{{ $payroll->payment_date?->format('d M Y') ?? '—' }}</td></tr>
            </table>

            <div class="row mt-5">
                <div class="col text-center">
                    <hr><small>Employee Signature</small>
                </div>
                <div class="col text-center">
                    <hr><small>HR Manager</small>
                </div>
                <div class="col text-center">
                    <hr><small>Authorized Signature</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection