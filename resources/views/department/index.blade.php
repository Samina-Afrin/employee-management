@extends('layouts.master')

@section('title', 'Departments')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Department List</h4>
        <a href="{{ route('department.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Add Department
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control"
                   placeholder="Search department name / code"
                   value="{{ request('search') }}">
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
                    <th>Department Name</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th width="220">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $dept)
                <tr>
                    <td>{{ $dept->id }}</td>
                    <td>{{ $dept->department_name }}</td>
                    <td>{{ $dept->department_code }}</td>
                    <td>{{ $dept->description }}</td>
                    <td>
                        @if($dept->status == 1)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('department.show', $dept) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('department.edit', $dept) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('department.destroy', $dept) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this department?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No departments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $departments->withQueryString()->links() }}
</div>
@endsection