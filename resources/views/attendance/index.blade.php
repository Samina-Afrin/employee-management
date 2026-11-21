@extends('layouts.master')

@section('title', 'Attendance')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Attendance Summary (Daily)</h4>
        <a href="{{ route('attendance.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Bulk Attendance
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
            <input type="date" name="date" class="form-control"
                   value="{{ request('date') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Filter by Date</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Present</th>
                    <th class="text-center">Late</th>
                    <th class="text-center">Absent</th>
                    <th class="text-center">Leave</th>
                    <th class="text-center">Holiday</th>
                    <th width="200">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($summaries as $row)
                @php
                    
                    $firstId = \App\Models\Attendance::whereDate('attendance_date', $row->attendance_date)->value('id');
                @endphp
                <tr>
                    <td><strong>{{ \Carbon\Carbon::parse($row->attendance_date)->format('d M Y') }}</strong></td>
                    <td class="text-center">{{ $row->total }}</td>
                    <td class="text-center"><span class="badge bg-success">{{ $row->present_count }}</span></td>
                    <td class="text-center"><span class="badge bg-warning text-dark">{{ $row->late_count }}</span></td>
                    <td class="text-center"><span class="badge bg-danger">{{ $row->absent_count }}</span></td>
                    <td class="text-center"><span class="badge bg-info text-dark">{{ $row->leave_count }}</span></td>
                    <td class="text-center"><span class="badge bg-secondary">{{ $row->holiday_count }}</span></td>
                    <td>
                        <a href="{{ route('attendance.show', $firstId) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('attendance.edit', $firstId) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('attendance.destroy', $firstId) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete ALL attendance for this date?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete All</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center">No attendance records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $summaries->withQueryString()->links() }}
</div>
@endsection