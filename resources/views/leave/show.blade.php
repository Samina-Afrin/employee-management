@extends('layouts.master')

@section('title', 'Leave Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Leave Details</h4>
        <a href="{{ route('leave.index') }}" class="btn btn-secondary">Back</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <tr><th width="250">ID</th><td>{{ $leave->id }}</td></tr>
        <tr>
            <th>Employee</th>
            <td>{{ $leave->employee->employee_code ?? '—' }} - {{ $leave->employee->name ?? '—' }}</td>
        </tr>
        <tr><th>Leave Type</th><td>{{ $leave->leave_type_label }}</td></tr>
        <tr><th>Leave Year</th><td>{{ $leave->leave_year }}</td></tr>
        <tr><th>Yearly Limit</th><td>{{ $leave->yearly_limit_days }} days</td></tr>
        <tr><th>From</th><td>{{ $leave->start_date?->format('d M Y') }}</td></tr>
        <tr><th>To</th><td>{{ $leave->end_date?->format('d M Y') }}</td></tr>
        <tr><th>Total Days</th><td>{{ $leave->total_days }}</td></tr>
        <tr>
            <th>Status</th>
            <td>
                @switch($leave->status)
                    @case(1) <span class="badge bg-warning text-dark">Pending</span> @break
                    @case(2) <span class="badge bg-success">Approved</span> @break
                    @case(3) <span class="badge bg-danger">Rejected</span> @break
                @endswitch
            </td>
        </tr>
        <tr><th>Reason</th><td>{{ $leave->reason }}</td></tr>
        <tr><th>Approved By</th><td>{{ $leave->approver->name ?? '—' }}</td></tr>
        <tr><th>Decision Note</th><td>{{ $leave->decision_note }}</td></tr>
        <tr><th>Remaining Balance</th><td>{{ $leave->remaining_balance ?? '—' }}</td></tr>
        <tr><th>Created At</th><td>{{ $leave->created_at?->format('d M Y, h:i A') }}</td></tr>
    </table>

    @if($leave->status == 1)
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('leave.approve', $leave) }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="decision_note" class="form-control"
                               placeholder="Approval note (optional)">
                        <button class="btn btn-success">Approve</button>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <form action="{{ route('leave.reject', $leave) }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="decision_note" class="form-control"
                               placeholder="Rejection reason (optional)">
                        <button class="btn btn-danger">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <a href="{{ route('leave.edit', $leave) }}" class="btn btn-warning mt-3">Edit</a>
</div>
@endsection