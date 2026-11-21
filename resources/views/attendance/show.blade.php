@extends('layouts.master')

@section('title', 'Attendance Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Attendance Details — {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h4>
        <div>
            <a href="{{ route('attendance.edit', $records->first()->id ?? 0) }}" class="btn btn-warning">Edit All</a>
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Summary -->
    <div class="row mb-3">
        <div class="col"><div class="card text-center"><div class="card-body">
            <h6>Total</h6><h4>{{ $summary['total'] }}</h4>
        </div></div></div>
        <div class="col"><div class="card text-center bg-success text-white"><div class="card-body">
            <h6>Present</h6><h4>{{ $summary['present'] }}</h4>
        </div></div></div>
        <div class="col"><div class="card text-center bg-warning text-dark"><div class="card-body">
            <h6>Late</h6><h4>{{ $summary['late'] }}</h4>
        </div></div></div>
        <div class="col"><div class="card text-center bg-danger text-white"><div class="card-body">
            <h6>Absent</h6><h4>{{ $summary['absent'] }}</h4>
        </div></div></div>
        <div class="col"><div class="card text-center bg-info text-dark"><div class="card-body">
            <h6>Leave</h6><h4>{{ $summary['leave'] }}</h4>
        </div></div></div>
        <div class="col"><div class="card text-center bg-secondary text-white"><div class="card-body">
            <h6>Holiday</h6><h4>{{ $summary['holiday'] }}</h4>
        </div></div></div>
    </div>

    <!-- Detail table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Employee</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th width="200">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $i => $att)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $att->employee->employee_code ?? '—' }} - {{ $att->employee->name ?? '—' }}</td>
                    <td>{{ $att->check_in }}</td>
                    <td>{{ $att->check_out }}</td>
                    <td>
                        @switch($att->status)
                            @case(1) <span class="badge bg-success">Present</span> @break
                            @case(2) <span class="badge bg-warning text-dark">Late</span> @break
                            @case(3) <span class="badge bg-danger">Absent</span> @break
                            @case(4) <span class="badge bg-info text-dark">Leave</span> @break
                            @case(5) <span class="badge bg-secondary">Holiday</span> @break
                        @endswitch
                    </td>
                    <td>{{ $att->remarks }}</td>
                    <td>
                        <a href="{{ route('attendance.edit', $att->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('attendance.destroy', $att->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this record?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No records on this date.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection