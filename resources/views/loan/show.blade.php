@extends('layouts.master')

@section('title', 'Loan Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Loan Details</h4>
        <a href="{{ route('loan.index') }}" class="btn btn-secondary">Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <tr><th width="250">Employee</th><td>{{ $loan->employee->name ?? '-' }} ({{ $loan->employee->employee_code ?? '-' }})</td></tr>
        <tr><th>Loan Type</th><td>
            @if($loan->loan_type === 'long_term')
                <span class="badge bg-primary">Long Term</span>
            @else
                <span class="badge bg-info">Short Term</span>
            @endif
        </td></tr>
        <tr><th>Amount</th><td>৳ {{ number_format($loan->amount, 2) }}</td></tr>
        <tr><th>Loan Date</th><td>{{ $loan->loan_date->format('d M Y') }}</td></tr>
        <tr><th>Purpose</th><td>{{ $loan->purpose ?? '-' }}</td></tr>

        @if($loan->loan_type === 'long_term')
            <tr><th>Lender Name</th><td>{{ $loan->lender_name ?? '-' }}</td></tr>
            <tr><th>Total Installments</th><td>{{ $loan->total_installments ?? '-' }}</td></tr>
            <tr><th>EMI Amount</th><td>৳ {{ number_format($loan->emi_amount, 2) }}</td></tr>
        @else
            <tr><th>Month</th><td>{{ date('F', mktime(0,0,0,$loan->month,1)) }} {{ $loan->year }}</td></tr>
        @endif

        <tr><th>Status</th><td>
            @if($loan->status === 'approved')
                <span class="badge bg-success">Approved</span>
            @elseif($loan->status === 'rejected')
                <span class="badge bg-danger">Rejected</span>
            @elseif($loan->status === 'paid')
                <span class="badge bg-dark">Paid</span>
            @else
                <span class="badge bg-warning text-dark">Pending</span>
            @endif
        </td></tr>
        <tr><th>Remarks</th><td>{{ $loan->remarks ?? '-' }}</td></tr>
    </table>

    <div class="mt-3">
        @if($loan->status === 'pending')
            <form method="POST" action="{{ route('loan.approve', $loan) }}" class="d-inline">
                @csrf @method('PATCH')
                <button class="btn btn-success">Approve</button>
            </form>
            <form method="POST" action="{{ route('loan.reject', $loan) }}" class="d-inline">
                @csrf @method('PATCH')
                <button class="btn btn-danger">Reject</button>
            </form>
        @endif

        @if($loan->status === 'approved')
            <form method="POST" action="{{ route('loan.paid', $loan) }}" class="d-inline">
                @csrf @method('PATCH')
                <button class="btn btn-dark">Mark as Paid</button>
            </form>
        @endif

        <a href="{{ route('loan.edit', $loan) }}" class="btn btn-warning">Edit</a>

        <form method="POST" action="{{ route('loan.destroy', $loan) }}" class="d-inline float-end">
            @csrf @method('DELETE')
            <button class="btn btn-danger" onclick="return confirm('Delete this loan?')">Delete</button>
        </form>
    </div>
</div>
@endsection