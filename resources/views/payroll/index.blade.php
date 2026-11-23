@extends('layouts.master')

@section('title', 'Payroll List')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Payroll List</h4>
        <a href="{{ route('payroll.create') }}" class="btn btn-primary">Generate Payroll</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('payroll.index') }}" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="month" class="form-select">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                        {{ date('F', mktime(0,0,0,$m,1)) }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-auto">
            <input type="number" name="year" value="{{ $year }}" class="form-control">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-secondary">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Designation</th>
                    <th>Basic</th>
                    <th>Allowance</th>
                    <th>Deduction</th>
                    <th>Net Salary</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payrolls as $p)
                <tr>
                    <td>{{ $loop->iteration + ($payrolls->currentPage() - 1) * $payrolls->perPage() }}</td>
                    <td>{{ $p->employee->name ?? '-' }}</td>
                    <td>{{ $p->employee->department->department_name ?? '-' }}</td>
                    <td>{{ $p->employee->designation->designation_name ?? '-' }}</td>
                    <td>{{ number_format($p->basic_salary, 2) }}</td>
                    <td>{{ number_format($p->total_allowance, 2) }}</td>
                    <td>{{ number_format($p->total_deduction, 2) }}</td>
                    <td><strong>{{ number_format($p->net_salary, 2) }}</strong></td>
                    <td>
                        @if($p->status == 'paid')
                            <span class="badge bg-success">Paid</span>
                        @elseif($p->status == 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('payroll.show', $p) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('payroll.edit', $p) }}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="{{ route('payroll.payslip', $p) }}" target="_blank" class="btn btn-sm btn-dark">Payslip</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center">No payroll found for this month.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $payrolls->withQueryString()->links() }}
</div>
@endsection