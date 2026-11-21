@extends('layouts.master')

@section('title', 'Edit Attendance')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Edit Attendance — {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h4>
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

    <form action="{{ route('attendance.update', $records->first()->id ?? 0) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th width="130">Check In</th>
                        <th width="130">Check Out</th>
                        <th width="150">Status *</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $i => $att)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $att->employee->employee_code ?? '—' }} - {{ $att->employee->name ?? '—' }}</td>
                            <td>
                                <input type="time"
                                       name="attendance[{{ $att->id }}][check_in]"
                                       class="form-control form-control-sm"
                                       value="{{ old("attendance.{$att->id}.check_in", $att->check_in) }}">
                            </td>
                            <td>
                                <input type="time"
                                       name="attendance[{{ $att->id }}][check_out]"
                                       class="form-control form-control-sm"
                                       value="{{ old("attendance.{$att->id}.check_out", $att->check_out) }}">
                            </td>
                            <td>
                                <select name="attendance[{{ $att->id }}][status]"
                                        class="form-select form-select-sm" required>
                                    @php $cur = old("attendance.{$att->id}.status", $att->status); @endphp
                                    <option value="1" @selected($cur==1)>Present</option>
                                    <option value="2" @selected($cur==2)>Late</option>
                                    <option value="3" @selected($cur==3)>Absent</option>
                                    <option value="4" @selected($cur==4)>Leave</option>
                                    <option value="5" @selected($cur==5)>Holiday</option>
                                </select>
                            </td>
                            <td>
                                <input type="text"
                                       name="attendance[{{ $att->id }}][remarks]"
                                       class="form-control form-control-sm"
                                       value="{{ old("attendance.{$att->id}.remarks", $att->remarks) }}">
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No records.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <button class="btn btn-primary">Update All</button>
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection