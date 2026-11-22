@extends('layouts.master')

@section('title', 'Payroll')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Payroll Summary</h4>
        <a href="{{ route('payroll.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Generate Payroll
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="month" name="month" class="form-control" value="{{ request('month') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Month</th>
                    <th class="text-center">Employees</th>
                    <th class="text-end">Total Gross (৳)</th>
                    <th class="text-end">Total Net (৳)</th>
                    <th class="text-center">Paid</th>
                    <th class="text-center">Pending</th>
                    <th width="260">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($summaries as $row)
                @php $monthStr = \Carbon\Carbon::parse($row->salary_month)->format('Y-m'); @endphp
                <tr>
                    <td><strong>{{ \Carbon\Carbon::parse($row->salary_month)->format('F Y') }}</strong></td>
                    <td class="text-center">{{ $row->total }}</td>
                    <td class="text-end">{{ number_format($row->total_gross, 2) }}</td>
                    <td class="text-end">{{ number_format($row->total_net, 2) }}</td>
                    <td class="text-center"><span class="badge bg-success">{{ $row->paid_count }}</span></td>
                    <td class="text-center"><span class="badge bg-warning text-dark">{{ $row->pending_count }}</span></td>
                    <td>
                        <a href="{{ route('payroll.month', $monthStr) }}" class="btn btn-sm btn-info">View Sheet</a>
                        <a href="{{ route('payroll.sheet', $monthStr) }}" class="btn btn-sm btn-danger">PDF Sheet</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No payroll records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $summaries->withQueryString()->links() }}
</div>
@endsection