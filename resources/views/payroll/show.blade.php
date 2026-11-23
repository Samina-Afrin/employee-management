@extends('layouts.master')

@section('title', 'Payroll Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Payroll Details</h4>
        <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <table class="table table-bordered mb-0">
                <tr><th style="width:200px;">Employee</th><td>{{ $payroll->employee->name ?? '-' }}</td></tr>
                <tr><th>Employee Code</th><td>{{ $payroll->employee->employee_code ?? '-' }}</td></tr>
                <tr><th>Department</th><td>{{ $payroll->employee->department->department_name ?? '-' }}</td></tr>
                <tr><th>Designation</th><td>{{ $payroll->employee->designation->designation_name ?? '-' }}</td></tr>
                <tr><th>Month</th><td>{{ date('F', mktime(0,0,0,$payroll->month,1)) }} {{ $payroll->year }}</td></tr>
                <tr><th>Basic Salary</th><td>{{ number_format($payroll->basic_salary, 2) }}</td></tr>
                <tr><th>Total Allowance</th><td>{{ number_format($payroll->total_allowance, 2) }}</td></tr>
                <tr><th>Total Deduction</th><td>{{ number_format($payroll->total_deduction, 2) }}</td></tr>
                <tr><th>Gross Salary</th><td>{{ number_format($payroll->gross_salary, 2) }}</td></tr>
                <tr><th>Net Salary</th><td><strong class="text-success">{{ number_format($payroll->net_salary, 2) }}</strong></td></tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($payroll->status == 'paid')
                            <span class="badge bg-success">Paid</span>
                        @elseif($payroll->status == 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                </tr>
                <tr><th>Payment Date</th><td>{{ $payroll->payment_date ? $payroll->payment_date->format('d M Y') : '-' }}</td></tr>
            </table>
        </div>
    </div>

    <h5>Allowances & Deductions</h5>
    <table class="table table-sm table-bordered">
        <thead class="table-secondary">
            <tr><th>Type</th><th>Title</th><th>Amount</th></tr>
        </thead>
        <tbody>
            @forelse($payroll->details as $d)
            <tr>
                <td>
                    @if($d->type == 'allowance')
                        <span class="badge bg-success">Allowance</span>
                    @else
                        <span class="badge bg-danger">Deduction</span>
                    @endif
                </td>
                <td>{{ $d->title }}</td>
                <td>{{ number_format($d->amount, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">No details added.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        @if($payroll->status !== 'paid')
            <form method="POST" action="{{ route('payroll.paid', $payroll) }}" class="d-inline">
                @csrf @method('PATCH')
                <button class="btn btn-success" onclick="return confirm('Mark this payroll as paid?')">Mark as Paid</button>
            </form>
        @endif

        <a href="{{ route('payroll.edit', $payroll) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('payroll.payslip', $payroll) }}" target="_blank" class="btn btn-dark">🖨️ Payslip</a>

        <form method="POST" action="{{ route('payroll.destroy', $payroll) }}" class="d-inline float-end">
            @csrf @method('DELETE')
            <button class="btn btn-danger" onclick="return confirm('Delete this payroll?')">Delete</button>
        </form>
    </div>
</div>
@endsection