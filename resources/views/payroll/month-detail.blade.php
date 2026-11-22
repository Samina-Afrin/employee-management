@extends('layouts.master')

@section('title', 'Payroll Detail')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Payroll — {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</h4>
        <div>
            <a href="{{ route('payroll.sheet', $month) }}" class="btn btn-danger">
                <i class="bi bi-file-pdf"></i> Download Final Sheet PDF
            </a>
            <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th class="text-end">Per Day</th>
                    <th class="text-center">Present</th>
                    <th class="text-center">Absent</th>
                    <th class="text-end">Gross</th>
                    <th class="text-end">Deductions</th>
                    <th class="text-end">Net</th>
                    <th>Status</th>
                    <th width="180">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->employee->employee_code ?? '—' }} - {{ $p->employee->name ?? '—' }}</td>
                    <td class="text-end">{{ number_format($p->per_day_salary, 2) }}</td>
                    <td class="text-center">{{ $p->present_days }}</td>
                    <td class="text-center">{{ $p->absent_days }}</td>
                    <td class="text-end">{{ number_format($p->gross_salary, 2) }}</td>
                    <td class="text-end">{{ number_format($p->tax_deduction + $p->absence_deduction + $p->loan_deduction, 2) }}</td>
                    <td class="text-end"><strong>{{ number_format($p->net_salary, 2) }}</strong></td>
                    <td>
                        @if($p->payment_status == 2)
                            <span class="badge bg-success">Paid</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('payroll.show', $p) }}" class="btn btn-sm btn-info">Payslip</a>
                        <a href="{{ route('payroll.payslip', $p->id) }}" class="btn btn-sm btn-danger">PDF</a>
                        <a href="{{ route('payroll.edit', $p) }}" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center">No payroll records for this month.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection