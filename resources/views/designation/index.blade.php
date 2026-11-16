@extends('layouts.master')

@section('title', 'Designations')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Designation List</h4>
        <a href="{{ route('designation.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Add Designation
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control"
                   placeholder="Search designation"
                   value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="department_id" class="form-select">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" @selected(request('department_id')==$dept->id)>
                        {{ $dept->department_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="1" @selected(request('status')=='1')>Active</option>
                <option value="0" @selected(request('status')==='0')>Inactive</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Designation</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th width="220">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($designations as $d)
                <tr>
                    <td>{{ $d->id }}</td>
                    <td>{{ $d->designation_name }}</td>
                    <td>{{ $d->designation_code }}</td>
                    <td>{{ $d->description }}</td>
                    <td>
                        @if($d->status == 1)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('designation.show', $d) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('designation.edit', $d) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('designation.destroy', $d) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this designation?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No designations found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $designations->withQueryString()->links() }}
</div>
@endsection