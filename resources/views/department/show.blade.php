@extends('layouts.master')

@section('title', 'Department Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Department Details</h4>
        <a href="{{ route('department.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <table class="table table-bordered">
        <tr><th width="250">ID</th><td>{{ $department->id }}</td></tr>
        <tr><th>Department Name</th><td>{{ $department->department_name }}</td></tr>
        <tr><th>Department Code</th><td>{{ $department->department_code }}</td></tr>
        <tr><th>Description</th><td>{{ $department->description }}</td></tr>
        <tr>
            <th>Status</th>
            <td>
                @if($department->status == 1)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </td>
        </tr>
        <tr><th>Created At</th><td>{{ $department->created_at?->format('d M Y, h:i A') }}</td></tr>
        <tr><th>Updated At</th><td>{{ $department->updated_at?->format('d M Y, h:i A') }}</td></tr>
    </table>

    <a href="{{ route('department.edit', $department) }}" class="btn btn-warning">Edit</a>
</div>
@endsection