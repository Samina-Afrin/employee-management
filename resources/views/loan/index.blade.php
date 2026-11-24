@extends('layouts.master')

@section('title', 'Loan Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Loan Management</h4>
        <a href="{{ route('loan.create') }}" class="btn btn-primary">+ Add Loan</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Total Long Term Loan</p>
                    <h4 class="mb-0 text-primary">৳ {{ number_format($totalLongTerm, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Total Short Term Loan</p>
                    <h4 class="mb-0 text-info">৳ {{ number_format($totalShortTerm, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Pending Approvals</p>
                    <h4 class="mb-0 text-warning">{{ $pendingCount }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('loan.index') }}" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search employee...">
        </div>
        <div class="col-md-2">
            <select name="loan_type" class="form-select">
                <option value="">All Types</option>
                <option value="long_term" @selected(request('loan_type')=='long_term')>Long Term</option>
                <option value="short_term" @selected(request('loan_type')=='short_term')>Short Term</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="pending" @selected(request('status')=='pending')>Pending</option>
                <option value="approved" @selected(request('status')=='approved')>Approved</option>
                <option value="rejected" @selected(request('status')=='rejected')>Rejected</option>
                <option value="paid" @selected(request('status')=='paid')>Paid</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-secondary">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Lender / Month</th>
                    <th>EMI / Purpose</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $i => $loan)
                <tr>
                    <td>{{ $loans->firstItem() + $i }}</td>
                    <td>{{ $loan->employee->name ?? '-' }}</td>
                    <td>
                        @if($loan->loan_type === 'long_term')
                            <span class="badge bg-primary">Long Term</span>
                        @else
                            <span class="badge bg-info">Short Term</span>
                        @endif
                    </td>
                    <td>৳ {{ number_format($loan->amount, 2) }}</td>
                    <td>{{ $loan->loan_date->format('d M Y') }}</td>
                    <td>
                        @if($loan->loan_type === 'long_term')
                            {{ $loan->lender_name ?? '-' }}
                        @else
                            {{ date('F', mktime(0,0,0,$loan->month,1)) }} {{ $loan->year }}
                        @endif
                    </td>
                    <td>
                        @if($loan->loan_type === 'long_term')
                            ৳ {{ number_format($loan->emi_amount, 2) }} × {{ $loan->total_installments }}
                        @else
                            {{ $loan->purpose ?? '-' }}
                        @endif
                    </td>
                    <td>
                        @if($loan->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($loan->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @elseif($loan->status === 'paid')
                            <span class="badge bg-dark">Paid</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('loan.show', $loan) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('loan.edit', $loan) }}" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center">No loans found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $loans->withQueryString()->links() }}
</div>
@endsection