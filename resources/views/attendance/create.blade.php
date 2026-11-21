@extends('layouts.master')

@section('title', 'Bulk Attendance')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Bulk Attendance</h4>
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Back</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <label for="date" class="form-label">Attendance date</label>
            <input type="date" id="date" name="date" class="form-control" value="{{ $date }}">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary">Load Date</button>
        </div>
    </form>

    <form action="{{ route('attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="attendance_date" value="{{ $date }}">

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th width="130">Check In</th>
                        <th width="130">Check Out</th>
                        <th width="170">Status *</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $i => $employee)
                        @php
                            $record = $existing->get($employee->id);
                            $status = old("attendance.{$employee->id}.status", $record->status ?? '');
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $employee->employee_code }} - {{ $employee->name }}</td>
                            <td>
                                <input type="time"
                                       name="attendance[{{ $employee->id }}][check_in]"
                                       class="form-control form-control-sm"
                                       value="{{ old("attendance.{$employee->id}.check_in", $record->check_in ?? '') }}">
                            </td>
                            <td>
                                <input type="time"
                                       name="attendance[{{ $employee->id }}][check_out]"
                                       class="form-control form-control-sm"
                                       value="{{ old("attendance.{$employee->id}.check_out", $record->check_out ?? '') }}">
                            </td>
                            <td>
                                <select name="attendance[{{ $employee->id }}][status]"
                                        class="form-select form-select-sm" required>
                                    <option value="">Select status</option>
                                    <option value="1" @selected($status == 1)>Present</option>
                                    <option value="2" @selected($status == 2)>Late</option>
                                    <option value="3" @selected($status == 3)>Absent</option>
                                    <option value="4" @selected($status == 4)>Leave</option>
                                    <option value="5" @selected($status == 5)>Holiday</option>
                                </select>
                            </td>
                            <td>
                                <input type="text"
                                       name="attendance[{{ $employee->id }}][remarks]"
                                       class="form-control form-control-sm"
                                       value="{{ old("attendance.{$employee->id}.remarks", $record->remarks ?? '') }}">
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No employees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($employees->isNotEmpty())
            <button class="btn btn-success">Save Attendance</button>
        @endif
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
