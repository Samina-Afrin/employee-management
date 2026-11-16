@extends('layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Employee Details</h3>
    <a href="{{ route('employee.index') }}" class="btn btn-secondary">Back</a>
</div>

<table class="table table-bordered">
    <tr><th width="250">Employee Code</th><td>{{ $employee->employee_code }}</td></tr>
    <tr><th>Name</th><td>{{ $employee->name }}</td></tr>
    <tr><th>Phone</th><td>{{ $employee->phone }}</td></tr>
    <tr><th>NID Number</th><td>{{ $employee->nid_number }}</td></tr>
    <tr><th>Gender</th><td>{{ $employee->gender == 1 ? 'Male' : 'Female' }}</td></tr>
    <tr><th>Date of Birth</th><td>{{ $employee->date_of_birth?->format('d M Y') }}</td></tr>
    <tr><th>Address</th><td>{{ $employee->address }}</td></tr>
    <tr><th>Joining Date</th><td>{{ $employee->joining_date?->format('d M Y') }}</td></tr>
    <tr>
        <th>Job Type</th>
        <td>
            @if($employee->job_type == 1) Full Time
            @elseif($employee->job_type == 2) Part Time
            @else Contract
            @endif
        </td>
    </tr>
    <tr>
        <th>Status</th>
        <td>
            @if($employee->status == 1) <span class="badge bg-success">Active</span>
            @elseif($employee->status == 2) <span class="badge bg-warning text-dark">Inactive</span>
            @else <span class="badge bg-secondary">Resigned</span>
            @endif
        </td>
    </tr>
    <tr>
        <th>Photo</th>
        <td>
            @if($employee->photo)
                <img src="{{ asset('storage/'.$employee->photo) }}" width="120" class="rounded">
            @else
                <em>No photo</em>
            @endif
        </td>
    </tr>
</table>

<a href="{{ route('employee.edit', $employee) }}" class="btn btn-warning">Edit</a>
@endsection