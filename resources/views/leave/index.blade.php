@extends('layouts.master')

@section('title', 'Leave Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Leave List</h4>
        <a href="{{ route('leave.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Apply Leave
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
            <input type="text" name="search" class="form-control"
                   placeholder="Search employee name / code"
                   value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="leave_type" class="form-select">
                <option value="">All Types</option>
                <option value="1" @selected(request('leave_type')=='1')>Casual</option>
                <option value="2" @selected(request('leave_type')=='2')>Sick</option>
                <option value="3" @selected(request('leave_type')=='3')>Annual</option>
                <option value="4" @selected(request('leave_type')=='4')>Festival</option>
                <option value="5" @selected(request('leave_type')=='5')>Maternity</option>
                <option value="6" @selected(request('leave_type')=='6')>Paternity</option>
                <option value="7" @selected(request('leave_type')=='7')>Unpaid</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="1" @selected(request('status')=='1')>Pending</option>
                <option value="2" @selected(request('status')=='2')>Approved</option>
                <option value="3" @selected(request('status')=='3')>Rejected</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="year" class="form-control"
                   placeholder="Year (e.g. 2026)"
                   value="{{ request('year') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('leave.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Type</th>
                    <th>From → To</th>
                    <th>Total Days</th>
                    <th>Year</th>
                    <th>Status</th>
                    <th width="260">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $i => $lv)
                <tr>
                    <td>{{ $leaves->firstItem() + $i }}</td>
                    <td>{{ $lv->employee->employee_code ?? '—' }} - {{ $lv->employee->name ?? '—' }}</td>
                    <td>{{ $lv->leave_type_label }}</td>
                    <td>
                        {{ $lv->start_date?->format('d M Y') }} →
                        {{ $lv->end_date?->format('d M Y') }}
                    </td>
                    <td>{{ $lv->total_days }}</td>
                    <td>{{ $lv->leave_year }}</td>
                    <td>
                        @switch($lv->status)
                            @case(1) <span class="badge bg-warning text-dark">Pending</span> @break
                            @case(2) <span class="badge bg-success">Approved</span> @break
                            @case(3) <span class="badge bg-danger">Rejected</span> @break
                        @endswitch
                    </td>
                    <td>
                        <a href="{{ route('leave.show', $lv) }}" class="btn btn-sm btn-info">View</a>

                        @if($lv->status == 1)
                            <form action="{{ route('leave.approve', $lv) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success" onclick="return confirm('Approve?')">Approve</button>
                            </form>
                            <form action="{{ route('leave.reject', $lv) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Reject?')">Reject</button>
                            </form>
                        @endif

                        <a href="{{ route('leave.edit', $lv) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('leave.destroy', $lv) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this leave?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Del</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center">No leave records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $leaves->withQueryString()->links() }}
</div>
@endsection