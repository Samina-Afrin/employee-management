@extends('layouts.master')

@section('content')
<h3 class="mb-3">Employee List</h3>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control"
               placeholder="Search name / code / NID / phone"
               value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="1" @selected(request('status')=='1')>Active</option>
            <option value="2" @selected(request('status')=='2')>Inactive</option>
            <option value="3" @selected(request('status')=='3')>Resigned</option>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary">Filter</button>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('employee.create') }}" class="btn btn-success">+ Add Employee</a>
    </div>
</form>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Phone</th>
            <th>NID</th>
            <th>Job Type</th>
            <th>Status</th>
            <th width="200">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($employees as $emp)
        <tr>
            <td>{{ $emp->employee_code }}</td>
            <td>{{ $emp->name }}</td>
            <td>{{ $emp->phone }}</td>
            <td>{{ $emp->nid_number }}</td>
            <td>
                @if($emp->job_type == 1) Full Time
                @elseif($emp->job_type == 2) Part Time
                @else Contract
                @endif
            </td>
            <td>
                @if($emp->status == 1) <span class="badge bg-success">Active</span>
                @elseif($emp->status == 2) <span class="badge bg-warning text-dark">Inactive</span>
                @else <span class="badge bg-secondary">Resigned</span>
                @endif
            </td>
            <td>
                <a href="{{ route('employee.show', $emp) }}" class="btn btn-sm btn-info">View</a>
                <a href="{{ route('employee.edit', $emp) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('employee.destroy', $emp) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Delete this employee?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center">No employees found.</td></tr>
        @endforelse
    </tbody>
</table>

{{ $employees->withQueryString()->links() }}
@endsection